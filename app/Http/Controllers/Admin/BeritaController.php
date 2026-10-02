<?php
// app/Http/Controllers/Admin/BeritaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class BeritaController extends Controller
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const MAX_SIZE_KB = 5120;

    public function index()
    {
        $beritas = Berita::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul'        => 'required|string|max:255',
                'isi'          => 'required|string',
                'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'is_published' => 'nullable|boolean',
            ]);

            // Upload gambar
            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $gambarPath = $this->uploadAndConvertImage($request->file('gambar'));
                if (!$gambarPath) {
                    return back()->with('error', 'File gambar tidak valid.')->withInput();
                }
            }

            // ============================================================
            // 🔒 EXPLICIT ASSIGN
            // ============================================================
            $berita = new Berita();
            $berita->judul        = $validated['judul'];
            $berita->isi          = $validated['isi'];
            $berita->gambar       = $gambarPath;
            $berita->user_id      = Auth::id();
            $berita->is_published = $request->has('is_published');
            $berita->published_at = $berita->is_published ? now() : null;
            $berita->slug         = $this->generateUniqueSlug($validated['judul']);
            $berita->save();

            return redirect()->route('admin.berita.index')
                ->with('success', 'Berita berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store berita error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal menambahkan berita. Silakan coba lagi.')->withInput();
        }
    }

    public function edit($id)
    {
        if (!is_numeric($id) || (int) $id < 1) abort(404);
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        try {
            $berita = Berita::findOrFail($id);

            $validated = $request->validate([
                'judul'        => 'required|string|max:255',
                'isi'          => 'required|string',
                'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'is_published' => 'nullable|boolean',
            ]);

            // Upload gambar baru
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama (basename)
                if ($berita->gambar) {
                    $safeOld = basename($berita->gambar);
                    Storage::disk('public')->delete('berita/' . $safeOld);
                }

                $gambarPath = $this->uploadAndConvertImage($request->file('gambar'));
                if (!$gambarPath) {
                    return back()->with('error', 'File gambar tidak valid.')->withInput();
                }
                $berita->gambar = $gambarPath;
            }

            // 🔒 Explicit assign
            $berita->judul = $validated['judul'];
            $berita->isi   = $validated['isi'];

            // Update slug hanya kalau judul berubah
            if ($berita->judul !== $validated['judul']) {
                $berita->slug = $this->generateUniqueSlug($validated['judul'], $berita->id);
            }

            $wasPublished = $berita->is_published;
            $berita->is_published = $request->has('is_published');

            // Set published_at hanya kalau baru dipublish
            if ($berita->is_published && !$wasPublished) {
                $berita->published_at = now();
            } elseif (!$berita->is_published) {
                $berita->published_at = null;
            }

            $berita->save();

            return redirect()->route('admin.berita.index')
                ->with('success', 'Berita berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update berita error', [
                'message'  => $e->getMessage(),
                'berita_id' => $id,
            ]);

            return back()->with('error', 'Gagal mengupdate berita. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $berita = Berita::findOrFail($id);

            if ($berita->gambar) {
                $safeFilename = basename($berita->gambar);
                Storage::disk('public')->delete('berita/' . $safeFilename);
            }

            $berita->delete();

            return redirect()->route('admin.berita.index')
                ->with('success', 'Berita berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete berita error', [
                'message'  => $e->getMessage(),
                'berita_id' => $id,
            ]);

            return back()->with('error', 'Gagal menghapus berita. Silakan coba lagi.');
        }
    }

    public function togglePublish($id)
    {
        try {
            $berita = Berita::findOrFail($id);
            $berita->is_published = !$berita->is_published;

            if ($berita->is_published && !$berita->published_at) {
                $berita->published_at = now();
            } elseif (!$berita->is_published) {
                $berita->published_at = null;
            }

            $berita->save();

            $status = $berita->is_published ? 'dipublikasikan' : 'disembunyikan';
            return back()->with('success', "Berita berhasil {$status}.");

        } catch (\Exception $e) {
            Log::error('Toggle publish error', [
                'message'  => $e->getMessage(),
                'berita_id' => $id,
            ]);

            return back()->with('error', 'Gagal mengubah status publikasi.');
        }
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $judul, ?int $exceptId = null): string
    {
        $baseSlug = Str::slug($judul);
        $slug     = $baseSlug;
        $counter  = 1;

        while (true) {
            $query = Berita::where('slug', $slug);
            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Upload + konversi gambar ke WebP
     */
    private function uploadAndConvertImage($file): ?string
    {
        // 1. Validasi ekstensi
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            Log::warning('Berita upload rejected: invalid extension', ['ext' => $extension]);
            return null;
        }

        // 2. Validasi MIME
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_MIMES, true)) {
            Log::warning('Berita upload rejected: invalid MIME', ['mime' => $mimeType]);
            return null;
        }

        // 3. Validasi ukuran
        if ($file->getSize() > self::MAX_SIZE_KB * 1024) {
            return null;
        }

        try {
            $filename = time() . '_' . Str::random(16) . '.webp';
            $path     = 'berita/' . $filename;

            if (!file_exists(storage_path('app/public/berita'))) {
                mkdir(storage_path('app/public/berita'), 0755, true);
            }

            $image = Image::make($file);

            $maxWidth  = 1200;
            $maxHeight = 1200;

            if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                $image->resize($maxWidth, $maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $image->encode('webp', 80);
            $image->save(storage_path('app/public/' . $path));

            return $path;

        } catch (\Exception $e) {
            Log::error('WebP conversion failed', ['message' => $e->getMessage()]);

            // Fallback: simpan sebagai JPG
            try {
                $filename = time() . '_' . Str::random(16) . '.jpg';
                $path     = 'berita/' . $filename;

                $image = Image::make($file);

                $maxWidth  = 1200;
                $maxHeight = 1200;

                if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                    $image->resize($maxWidth, $maxHeight, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                $image->encode('jpg', 85);
                $image->save(storage_path('app/public/' . $path));

                return $path;

            } catch (\Exception $e2) {
                Log::error('JPG fallback failed', ['message' => $e2->getMessage()]);

                // Ultimate fallback: simpan sebagai file ASLI tapi dengan ekstensi whitelist
                // JANGAN pakai getClientOriginalExtension() mentah
                $safeExtension = in_array($extension, self::ALLOWED_EXTENSIONS, true) ? $extension : 'jpg';
                $filename      = time() . '_' . Str::random(16) . '.' . $safeExtension;
                $file->storeAs('berita', $filename, 'public');

                return 'berita/' . $filename;
            }
        }
    }
}