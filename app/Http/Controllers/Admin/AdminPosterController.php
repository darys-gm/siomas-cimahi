<?php
// app/Http/Controllers/Admin/AdminPosterController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poster;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPosterController extends Controller
{
    /**
     * Ekstensi yang diizinkan untuk upload poster
     */
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const MAX_SIZE_KB = 2048;

    public function index()
    {
        $posters = Poster::orderBy('urutan', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.poster.index', compact('posters'));
    }

    public function create()
    {
        return view('admin.poster.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul'      => 'required|string|max:255',
                'gambar'     => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'link'       => 'nullable|url|max:255',
                'is_active'  => 'nullable|boolean',
                'urutan'     => 'nullable|integer|min:0|max:100000',
            ]);

            // ============================================================
            // 🔒 UPLOAD DENGAN VERIFIKASI GANDA (EXTENSION + MIME)
            // ============================================================
            if ($request->hasFile('gambar')) {
                $path = $this->safeUploadImage($request->file('gambar'));
                if (!$path) {
                    return back()->with('error', 'File gambar tidak valid atau tidak dapat diproses.')->withInput();
                }
            } else {
                return back()->with('error', 'File gambar wajib diunggah.')->withInput();
            }

            // ============================================================
            // 🔒 EXPLICIT ASSIGN — JANGAN MASS ASSIGNMENT
            // ============================================================
            $poster = new Poster();
            $poster->judul    = $validated['judul'];
            $poster->gambar   = $path;
            $poster->link     = $validated['link'] ?? null;
            $poster->urutan   = $validated['urutan'] ?? 0;
            $poster->is_active = $request->has('is_active');
            $poster->save();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Tambah Poster',
                'deskripsi'  => "Menambahkan poster '{$poster->judul}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.poster.index')
                ->with('success', 'Poster berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store poster error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal menambahkan poster. Silakan coba lagi.')->withInput();
        }
    }

    public function edit($id)
    {
        if (!is_numeric($id) || (int) $id < 1) abort(404);
        $poster = Poster::findOrFail($id);
        return view('admin.poster.edit', compact('poster'));
    }

    public function update(Request $request, $id)
    {
        try {
            $poster = Poster::findOrFail($id);

            $validated = $request->validate([
                'judul'      => 'required|string|max:255',
                'gambar'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'link'       => 'nullable|url|max:255',
                'is_active'  => 'nullable|boolean',
                'urutan'     => 'nullable|integer|min:0|max:100000',
            ]);

            // Upload gambar baru (kalau ada)
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama dengan basename (anti path traversal)
                if ($poster->gambar) {
                    $safeOld = basename($poster->gambar);
                    Storage::disk('public')->delete('posters/' . $safeOld);
                }

                $path = $this->safeUploadImage($request->file('gambar'));
                if (!$path) {
                    return back()->with('error', 'File gambar tidak valid.')->withInput();
                }
                $poster->gambar = $path;
            }

            // 🔒 Explicit assign
            $poster->judul     = $validated['judul'];
            $poster->link      = $validated['link'] ?? null;
            $poster->urutan    = $validated['urutan'] ?? 0;
            $poster->is_active = $request->has('is_active');
            $poster->save();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Update Poster',
                'deskripsi'  => "Mengupdate poster '{$poster->judul}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.poster.index')
                ->with('success', 'Poster berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update poster error', [
                'message'  => $e->getMessage(),
                'poster_id' => $id,
            ]);

            return back()->with('error', 'Gagal mengupdate poster. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * ============================================================
     * HAPUS POSTER
     * ============================================================
     * Menangani 2 skenario:
     * 1. Request AJAX/fetch → kembalikan JSON
     * 2. Request form biasa → redirect dengan flash message
     * ============================================================
     */
    public function destroy($id)
    {
        try {
            $poster = Poster::findOrFail($id);
            $judul  = $poster->judul;

            if ($poster->gambar) {
                $safeFilename = basename($poster->gambar);
                Storage::disk('public')->delete('posters/' . $safeFilename);
            }

            $poster->delete();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Hapus Poster',
                'deskripsi'  => "Menghapus poster '{$judul}'",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // ============================================================
            // 🔒 DETEKSI RESPONSE: JSON vs REDIRECT
            // Kalau request via AJAX/fetch → kembalikan JSON
            // Kalau request via form biasa → redirect
            // ============================================================
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Poster berhasil dihapus.',
                ]);
            }

            return redirect()->route('admin.poster.index')
                ->with('success', 'Poster berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete poster error', [
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'poster_id' => $id,
            ]);

            // Kalau request via AJAX → kembalikan JSON error
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus poster. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus poster. Silakan coba lagi.');
        }
    }

    public function toggleActive($id)
    {
        try {
            $poster = Poster::findOrFail($id);
            $poster->is_active = !$poster->is_active;
            $poster->save();

            $status = $poster->is_active ? 'diaktifkan' : 'dinonaktifkan';

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Toggle Poster',
                'deskripsi'  => "Poster '{$poster->judul}' {$status}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return response()->json([
                'success'   => true,
                'is_active' => $poster->is_active,
                'message'   => "Poster berhasil {$status}",
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle poster error', [
                'message'  => $e->getMessage(),
                'poster_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status. Silakan coba lagi.',
            ], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'orders'              => 'required|array|max:200',
                'orders.*.id'         => 'required|integer|exists:posters,id',
                'orders.*.urutan'     => 'required|integer|min:0|max:100000',
            ]);

            DB::transaction(function () use ($validated) {
                foreach ($validated['orders'] as $order) {
                    Poster::where('id', (int) $order['id'])
                        ->update(['urutan' => (int) $order['urutan']]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Urutan poster berhasil diupdate.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data urutan tidak valid.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Update poster order error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate urutan. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * ============================================================
     * 🔒 SAFE UPLOAD IMAGE — Verifikasi ekstensi + MIME + rename
     * ============================================================
     */
    private function safeUploadImage($file): ?string
    {
        // 1. Cek ekstensi
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            Log::warning('Poster upload rejected: invalid extension', ['ext' => $extension]);
            return null;
        }

        // 2. Cek MIME dengan finfo (bukan dari client)
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_MIMES, true)) {
            Log::warning('Poster upload rejected: invalid MIME', ['mime' => $mimeType]);
            return null;
        }

        // 3. Cek ukuran
        if ($file->getSize() > self::MAX_SIZE_KB * 1024) {
            return null;
        }

        // 4. Rename file dengan hash (anti path traversal & collision)
        $filename = time() . '_' . Str::random(16) . '.' . $extension;
        $path     = $file->storeAs('posters', $filename, 'public');

        return $path;
    }
}