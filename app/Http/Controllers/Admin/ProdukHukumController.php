<?php
// app/Http/Controllers/Admin/ProdukHukumController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdukHukum;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProdukHukumController extends Controller
{
    private const MAX_SIZE_KB = 10240; // 10MB

    public function index()
    {
        $produkHukums = ProdukHukum::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.produk-hukum.index', compact('produkHukums'));
    }

    public function create()
    {
        return view('admin.produk-hukum.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul'      => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:5000',
                'file_pdf'   => 'required|file|mimes:pdf|max:10240',
                'is_active'  => 'nullable|boolean',
            ]);

            // ============================================================
            // 🔒 VERIFIKASI MIME PDF DENGAN finfo (bukan hanya mimes:pdf)
            // ============================================================
            if ($request->hasFile('file_pdf')) {
                $file = $request->file('file_pdf');

                // Double check MIME
                $finfo    = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file->getRealPath());
                finfo_close($finfo);

                if ($mimeType !== 'application/pdf') {
                    return back()->with('error', 'File yang diunggah bukan PDF yang valid.')->withInput();
                }

                // Rename file dengan hash
                $fileName = time() . '_' . Str::slug($validated['judul']) . '_' . Str::random(8) . '.pdf';
                $fileName = substr($fileName, 0, 200); // batasi panjang

                $path = $file->storeAs('produk_hukum', $fileName, 'public');
            } else {
                return back()->with('error', 'File PDF wajib diunggah.')->withInput();
            }

            // ============================================================
            // 🔒 EXPLICIT ASSIGN
            // ============================================================
            $produkHukum = new ProdukHukum();
            $produkHukum->judul       = $validated['judul'];
            $produkHukum->keterangan  = $validated['keterangan'] ?? null;
            $produkHukum->file_pdf    = $fileName;
            $produkHukum->user_id     = Auth::id();  // <-- Dari Auth, bukan dari request
            $produkHukum->is_active   = $request->has('is_active');
            $produkHukum->save();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Tambah Produk Hukum',
                'deskripsi'  => "Menambahkan produk hukum {$produkHukum->judul}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.produk-hukum.index')
                ->with('success', 'Produk Hukum berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store produk hukum error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal menambahkan produk hukum. Silakan coba lagi.')->withInput();
        }
    }

    public function edit($id)
    {
        if (!is_numeric($id) || (int) $id < 1) abort(404);
        $produkHukum = ProdukHukum::findOrFail($id);
        return view('admin.produk-hukum.edit', compact('produkHukum'));
    }

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

            // Kalau ada file baru
            if ($request->hasFile('file_pdf')) {
                $file = $request->file('file_pdf');

                // Double check MIME
                $finfo    = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file->getRealPath());
                finfo_close($finfo);

                if ($mimeType !== 'application/pdf') {
                    return back()->with('error', 'File yang diunggah bukan PDF yang valid.')->withInput();
                }

                // Hapus file lama dengan basename (anti path traversal)
                if ($produkHukum->file_pdf) {
                    $safeOld = basename($produkHukum->file_pdf);
                    Storage::disk('public')->delete('produk_hukum/' . $safeOld);
                }

                $fileName = time() . '_' . Str::slug($validated['judul']) . '_' . Str::random(8) . '.pdf';
                $fileName = substr($fileName, 0, 200);

                $file->storeAs('produk_hukum', $fileName, 'public');
                $produkHukum->file_pdf = $fileName;
            }

            // 🔒 Explicit assign
            $produkHukum->judul      = $validated['judul'];
            $produkHukum->keterangan = $validated['keterangan'] ?? null;
            $produkHukum->is_active  = $request->has('is_active');
            $produkHukum->save();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Update Produk Hukum',
                'deskripsi'  => "Mengupdate produk hukum {$produkHukum->judul}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.produk-hukum.index')
                ->with('success', 'Produk Hukum berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update produk hukum error', [
                'message'   => $e->getMessage(),
                'produk_id' => $id,
            ]);

            return back()->with('error', 'Gagal mengupdate produk hukum. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $produkHukum = ProdukHukum::findOrFail($id);

            if ($produkHukum->file_pdf) {
                $safeFilename = basename($produkHukum->file_pdf);
                Storage::disk('public')->delete('produk_hukum/' . $safeFilename);
            }

            $judul = $produkHukum->judul;
            $produkHukum->delete();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Hapus Produk Hukum',
                'deskripsi'  => "Menghapus produk hukum {$judul}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->route('admin.produk-hukum.index')
                ->with('success', 'Produk Hukum berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete produk hukum error', [
                'message'   => $e->getMessage(),
                'produk_id' => $id,
            ]);

            return back()->with('error', 'Gagal menghapus produk hukum. Silakan coba lagi.');
        }
    }

    public function toggleActive($id)
    {
        try {
            $produkHukum = ProdukHukum::findOrFail($id);
            $produkHukum->is_active = !$produkHukum->is_active;
            $produkHukum->save();

            $status = $produkHukum->is_active ? 'diaktifkan' : 'dinonaktifkan';

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Toggle Produk Hukum',
                'deskripsi'  => "Produk Hukum {$produkHukum->judul} {$status}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', "Produk Hukum berhasil {$status}.");

        } catch (\Exception $e) {
            Log::error('Toggle produk hukum error', [
                'message'   => $e->getMessage(),
                'produk_id' => $id,
            ]);

            return back()->with('error', 'Gagal mengubah status. Silakan coba lagi.');
        }
    }
}