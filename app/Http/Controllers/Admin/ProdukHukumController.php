<?php
// app/Http/Controllers/Admin/ProdukHukumController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdukHukum;
use App\Models\LogAktivitas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProdukHukumController extends Controller
{
    // ============================================================
    // KONSTANTA
    // ============================================================
    private const STORAGE_PATH    = 'produk_hukum';
    private const MAX_SIZE_KB     = 10240; // 10 MB
    private const MAX_TITLE_SLUG  = 100;   // Panjang max slug judul
    private const ALLOWED_MIME    = 'application/pdf';

    // ============================================================
    // INDEX — Daftar Produk Hukum
    // ============================================================
    public function index()
    {
        $produkHukums = ProdukHukum::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.produk-hukum.index', compact('produkHukums'));
    }

    // ============================================================
    // CREATE — Form Tambah Produk Hukum
    // ============================================================
    public function create()
    {
        return view('admin.produk-hukum.create');
    }

    // ============================================================
    // STORE — Simpan Produk Hukum Baru
    // ============================================================
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul'      => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:5000',
                'file_pdf'   => 'required|file|mimes:pdf|max:10240',
                'is_active'  => 'nullable|boolean',
            ]);

            // 🔒 Verifikasi MIME ganda
            $file = $request->file('file_pdf');

            if (!$this->isValidPdfMime($file)) {
                return back()
                    ->with('error', 'File yang diunggah bukan PDF yang valid.')
                    ->withInput();
            }

            // Generate nama file unik
            $fileName = $this->generatePdfFilename($validated['judul']);

            // Simpan file ke storage
            $file->storeAs(self::STORAGE_PATH, $fileName, 'public');

            // 🔒 Explicit assign
            $produkHukum = new ProdukHukum();
            $produkHukum->judul      = $validated['judul'];
            $produkHukum->keterangan = $validated['keterangan'] ?? null;
            $produkHukum->file_pdf   = $fileName;
            $produkHukum->user_id    = (int) Auth::id();
            $produkHukum->is_active  = $request->has('is_active');
            $produkHukum->save();

            $this->logAktivitas(
                $request,
                'Tambah Produk Hukum',
                "Menambahkan produk hukum {$produkHukum->judul}"
            );

            return redirect()
                ->route('admin.produk-hukum.index')
                ->with('success', 'Produk Hukum berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store produk hukum error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()
                ->with('error', 'Gagal menambahkan produk hukum. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // EDIT — Form Edit Produk Hukum
    // ============================================================
    public function edit($id)
    {
        if (!$this->isValidId($id)) {
            abort(404);
        }

        $produkHukum = ProdukHukum::findOrFail((int) $id);

        return view('admin.produk-hukum.edit', compact('produkHukum'));
    }

    // ============================================================
    // UPDATE — Update Produk Hukum
    // ============================================================
    public function update(Request $request, $id)
    {
        try {
            $produkHukum = ProdukHukum::findOrFail($id);

            $validated = $request->validate([
                'judul'      => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:5000',
                'file_pdf'   => 'nullable|file|mimes:pdf|max:10240',
                'is_active'  => 'nullable|boolean',
            ]);

            // Ganti file PDF (kalau ada)
            if ($request->hasFile('file_pdf')) {
                $file = $request->file('file_pdf');

                // 🔒 Verifikasi MIME ganda
                if (!$this->isValidPdfMime($file)) {
                    return back()
                        ->with('error', 'File yang diunggah bukan PDF yang valid.')
                        ->withInput();
                }

                // Hapus file lama dengan basename (anti path traversal)
                $this->deletePdfFile($produkHukum->file_pdf);

                // Generate nama file unik
                $fileName = $this->generatePdfFilename($validated['judul']);

                // Simpan file baru
                $file->storeAs(self::STORAGE_PATH, $fileName, 'public');

                $produkHukum->file_pdf = $fileName;
            }

            // 🔒 Explicit assign
            $produkHukum->judul      = $validated['judul'];
            $produkHukum->keterangan = $validated['keterangan'] ?? null;
            $produkHukum->is_active  = $request->has('is_active');
            $produkHukum->save();

            $this->logAktivitas(
                $request,
                'Update Produk Hukum',
                "Mengupdate produk hukum {$produkHukum->judul}"
            );

            return redirect()
                ->route('admin.produk-hukum.index')
                ->with('success', 'Produk Hukum berhasil diupdate.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update produk hukum error', [
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'produk_id' => $id,
            ]);

            return back()
                ->with('error', 'Gagal mengupdate produk hukum. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // DESTROY — Hapus Produk Hukum
    // ============================================================
    public function destroy($id)
    {
        try {
            $produkHukum = ProdukHukum::findOrFail($id);

            // Hapus file PDF dari storage
            $this->deletePdfFile($produkHukum->file_pdf);

            $judul = $produkHukum->judul;
            $produkHukum->delete();

            $this->logAktivitas(
                request(),
                'Hapus Produk Hukum',
                "Menghapus produk hukum {$judul}"
            );

            return redirect()
                ->route('admin.produk-hukum.index')
                ->with('success', 'Produk Hukum berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete produk hukum error', [
                'message'   => $e->getMessage(),
                'produk_id' => $id,
            ]);

            return back()->with('error', 'Gagal menghapus produk hukum. Silakan coba lagi.');
        }
    }

    // ============================================================
    // TOGGLE ACTIVE — Aktif/Nonaktif Produk Hukum
    // ============================================================
    /**
     * Menangani 2 skenario:
     * 1. Request AJAX/fetch → kembalikan JSON
     * 2. Request form biasa → redirect dengan flash message
     */
    public function toggleActive($id)
    {
        try {
            $produkHukum = ProdukHukum::findOrFail($id);

            $produkHukum->is_active = !$produkHukum->is_active;
            $produkHukum->save();

            $status = $produkHukum->is_active ? 'diaktifkan' : 'dinonaktifkan';

            $this->logAktivitas(
                request(),
                'Toggle Produk Hukum',
                "Produk Hukum {$produkHukum->judul} {$status}"
            );

            // Response JSON untuk AJAX, redirect untuk form
            if (request()->wantsJson()) {
                return response()->json([
                    'success'   => true,
                    'is_active' => $produkHukum->is_active,
                    'message'   => "Produk Hukum berhasil {$status}.",
                ]);
            }

            return back()->with('success', "Produk Hukum berhasil {$status}.");

        } catch (\Exception $e) {
            Log::error('Toggle produk hukum error', [
                'message'   => $e->getMessage(),
                'produk_id' => $id,
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah status. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Gagal mengubah status. Silakan coba lagi.');
        }
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Validasi format ID (angka positif).
     */
    private function isValidId($id): bool
    {
        return is_numeric($id) && (int) $id > 0;
    }

    /**
     * Helper log aktivitas.
     */
    private function logAktivitas(Request $request, string $aktivitas, string $deskripsi): void
    {
        LogAktivitas::create([
            'user_id'    => (int) Auth::id(),
            'aktivitas'  => $aktivitas,
            'deskripsi'  => $deskripsi,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * 🔒 Verifikasi MIME PDF dengan finfo (bukan hanya mimes:pdf).
     * Return true jika file benar-benar PDF.
     */
    private function isValidPdfMime($file): bool
    {
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        return $mimeType === self::ALLOWED_MIME;
    }

    /**
     * Generate nama file PDF unik dan aman.
     * - Menggunakan time + slug + random
     * - Potong slug (bukan seluruh filename) agar tidak hilang ekstensi
     * - Fallback ke timestamp jika judul karakter spesial
     */
    private function generatePdfFilename(string $judul): string
    {
        $timestamp = time();
        $random    = Str::random(8);

        // Slug judul (maksimal 100 karakter)
        $slug = Str::slug($judul);
        if (empty($slug)) {
            $slug = 'dokumen';
        }
        $slug = substr($slug, 0, self::MAX_TITLE_SLUG);

        return "{$timestamp}_{$slug}_{$random}.pdf";
    }

    /**
     * 🔒 Hapus file PDF dari storage (basename anti path traversal).
     */
    private function deletePdfFile(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $safeFilename = basename($filename);
        Storage::disk('public')->delete(self::STORAGE_PATH . '/' . $safeFilename);
    }
}