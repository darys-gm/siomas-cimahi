<?php
// app/Http/Controllers/Admin/AdminPosterController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poster;
use App\Models\LogAktivitas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminPosterController extends Controller
{
    /**
     * Ekstensi yang diizinkan untuk upload poster
     */
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const ALLOWED_MIMES      = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const MAX_SIZE_KB        = 2048;

    // ============================================================
    // INDEX — Daftar Poster
    // ============================================================
    public function index()
    {
        $posters = Poster::orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.poster.index', compact('posters'));
    }

    // ============================================================
    // CREATE — Form Tambah Poster
    // ============================================================
    public function create()
    {
        return view('admin.poster.create');
    }

    // ============================================================
    // STORE — Simpan Poster Baru
    // ============================================================
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul'     => 'required|string|max:255',
                'gambar'    => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'link'      => 'nullable|url|max:255',
                'is_active' => 'nullable|boolean',
                'urutan'    => 'nullable|integer|min:0|max:100000',
            ]);

            // 🔒 Upload gambar dengan verifikasi ganda
            $path = $this->safeUploadImage($request->file('gambar'));

            if (!$path) {
                return back()
                    ->with('error', 'File gambar tidak valid atau tidak dapat diproses.')
                    ->withInput();
            }

            // 🔒 Explicit assign (bukan mass assignment)
            $poster = new Poster();
            $poster->judul     = $validated['judul'];
            $poster->gambar    = $path;
            $poster->link      = $validated['link'] ?? null;
            $poster->urutan    = $validated['urutan'] ?? 0;
            $poster->is_active = $request->has('is_active');
            $poster->save();

            $this->logAktivitas($request, 'Tambah Poster', "Menambahkan poster '{$poster->judul}'");

            return redirect()
                ->route('admin.poster.index')
                ->with('success', 'Poster berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store poster error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()
                ->with('error', 'Gagal menambahkan poster. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // EDIT — Form Edit Poster
    // ============================================================
    public function edit($id)
    {
        if (!$this->isValidId($id)) {
            abort(404);
        }

        $poster = Poster::findOrFail((int) $id);

        return view('admin.poster.edit', compact('poster'));
    }

    // ============================================================
    // UPDATE — Update Poster
    // ============================================================
    public function update(Request $request, $id)
    {
        try {
            $poster = Poster::findOrFail($id);

            $validated = $request->validate([
                'judul'     => 'required|string|max:255',
                'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'link'      => 'nullable|url|max:255',
                'is_active' => 'nullable|boolean',
                'urutan'    => 'nullable|integer|min:0|max:100000',
            ]);

            // Upload gambar baru (kalau ada)
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama (basename anti path traversal)
                $this->deleteImageFile($poster->gambar);

                $path = $this->safeUploadImage($request->file('gambar'));

                if (!$path) {
                    return back()
                        ->with('error', 'File gambar tidak valid.')
                        ->withInput();
                }

                $poster->gambar = $path;
            }

            // 🔒 Explicit assign
            $poster->judul     = $validated['judul'];
            $poster->link      = $validated['link'] ?? null;
            $poster->urutan    = $validated['urutan'] ?? 0;
            $poster->is_active = $request->has('is_active');
            $poster->save();

            $this->logAktivitas($request, 'Update Poster', "Mengupdate poster '{$poster->judul}'");

            return redirect()
                ->route('admin.poster.index')
                ->with('success', 'Poster berhasil diupdate.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update poster error', [
                'message'   => $e->getMessage(),
                'poster_id' => $id,
            ]);

            return back()
                ->with('error', 'Gagal mengupdate poster. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // DESTROY — Hapus Poster
    // ============================================================
    /**
     * Menangani 2 skenario:
     * 1. Request AJAX/fetch → kembalikan JSON
     * 2. Request form biasa → redirect dengan flash message
     */
    public function destroy($id)
    {
        try {
            $poster = Poster::findOrFail($id);
            $judul  = $poster->judul;

            // Hapus file gambar
            $this->deleteImageFile($poster->gambar);

            $poster->delete();

            $this->logAktivitas(request(), 'Hapus Poster', "Menghapus poster '{$judul}'");

            // Response JSON untuk AJAX, redirect untuk form
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Poster berhasil dihapus.',
                ]);
            }

            return redirect()
                ->route('admin.poster.index')
                ->with('success', 'Poster berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete poster error', [
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'poster_id' => $id,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus poster. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus poster. Silakan coba lagi.');
        }
    }

    // ============================================================
    // TOGGLE ACTIVE
    // ============================================================
    public function toggleActive($id): JsonResponse
    {
        try {
            $poster = Poster::findOrFail($id);

            $poster->is_active = !$poster->is_active;
            $poster->save();

            $status = $poster->is_active ? 'diaktifkan' : 'dinonaktifkan';

            $this->logAktivitas(
                request(),
                'Toggle Poster',
                "Poster '{$poster->judul}' {$status}"
            );

            return response()->json([
                'success'   => true,
                'is_active' => $poster->is_active,
                'message'   => "Poster berhasil {$status}",
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle poster error', [
                'message'   => $e->getMessage(),
                'poster_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status. Silakan coba lagi.',
            ], 500);
        }
    }

    // ============================================================
    // UPDATE ORDER
    // ============================================================
    public function updateOrder(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'orders'          => 'required|array|max:200',
                'orders.*.id'     => 'required|integer|exists:posters,id',
                'orders.*.urutan' => 'required|integer|min:0|max:100000',
            ]);

            // Update massal dalam 1 transaction
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

        } catch (ValidationException $e) {
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
     * Helper log aktivitas — supaya tidak duplikasi.
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
     * Hapus file gambar dari storage (dengan basename untuk keamanan).
     */
    private function deleteImageFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $safeFilename = basename($path);
        Storage::disk('public')->delete('posters/' . $safeFilename);
    }

    /**
     * ============================================================
     * 🔒 SAFE UPLOAD IMAGE
     * Verifikasi: ekstensi + MIME + ukuran + rename
     * ============================================================
     */
    private function safeUploadImage($file): ?string
    {
        // 1. Cek ekstensi
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            Log::warning('Poster upload rejected: invalid extension', [
                'ext'    => $extension,
                'ip'     => request()->ip(),
            ]);
            return null;
        }

        // 2. Cek MIME dengan finfo (bukan dari client)
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_MIMES, true)) {
            Log::warning('Poster upload rejected: invalid MIME', [
                'mime' => $mimeType,
                'ip'   => request()->ip(),
            ]);
            return null;
        }

        // 3. Cek ukuran
        if ($file->getSize() > self::MAX_SIZE_KB * 1024) {
            Log::warning('Poster upload rejected: file too large', [
                'size' => $file->getSize(),
                'ip'   => request()->ip(),
            ]);
            return null;
        }

        // 4. Rename file dengan hash (anti path traversal & collision)
        $filename = time() . '_' . Str::random(16) . '.' . $extension;

        return $file->storeAs('posters', $filename, 'public');
    }
}