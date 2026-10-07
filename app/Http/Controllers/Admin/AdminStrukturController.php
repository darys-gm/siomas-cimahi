<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use App\Models\LogAktivitas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminStrukturController extends Controller
{
    // ============================================================
    // KONSTANTA
    // ============================================================
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const ALLOWED_MIMES      = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_SIZE_KB        = 5120; // 5 MB
    private const STORAGE_PATH       = 'struktur';

    // ============================================================
    // INDEX — Halaman Manajemen Struktur
    // ============================================================
    public function index()
    {
        $struktur = StrukturOrganisasi::getActive();

        return view('admin.struktur.index', compact('struktur'));
    }

    // ============================================================
    // STORE — Upload gambar struktur baru
    // Kalau sudah ada, gambar lama otomatis dihapus.
    // ============================================================
    public function store(Request $request)
    {
        try {
            // 🔒 Validasi dasar (dilengkapi verifikasi ganda di helper)
            $request->validate([
                'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            if (!$request->hasFile('gambar')) {
                return back()->with('error', 'File gambar wajib diunggah.');
            }

            // 🔒 Upload dengan verifikasi ganda (ekstensi + MIME + size)
            $path = $this->safeUploadImage($request->file('gambar'));

            if (!$path) {
                return back()->with('error', 'File gambar tidak valid. Gunakan JPG, PNG, atau WEBP.');
            }

            // Hapus gambar lama (kalau ada) — hanya 1 gambar aktif
            $existing = StrukturOrganisasi::getActive();
            if ($existing) {
                $this->deleteImageFile($existing->gambar);
                $existing->delete();
            }

            // Simpan record baru ke database
            $struktur = new StrukturOrganisasi();
            $struktur->gambar = $path;
            $struktur->save();

            $this->logAktivitas(
                $request,
                'Upload Struktur Organisasi',
                'Mengupload gambar struktur organisasi baru'
            );

            return redirect()
                ->route('admin.struktur.index')
                ->with('success', 'Gambar struktur organisasi berhasil diupload.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store struktur error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal mengupload gambar. Silakan coba lagi.');
        }
    }

    // ============================================================
    // DESTROY — Hapus gambar struktur
    // ============================================================
    /**
     * Menangani 2 skenario:
     * 1. Request AJAX/fetch → kembalikan JSON
     * 2. Request form biasa → redirect dengan flash message
     */
    public function destroy($id)
    {
        try {
            if (!$this->isValidId($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID tidak valid.',
                ], 400);
            }

            $struktur = StrukturOrganisasi::find((int) $id);

            if (!$struktur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            // Hapus file gambar dari storage
            $this->deleteImageFile($struktur->gambar);

            // Hapus record dari database
            $struktur->delete();

            $this->logAktivitas(
                request(),
                'Hapus Struktur Organisasi',
                'Menghapus gambar struktur organisasi'
            );

            // Response JSON untuk AJAX, redirect untuk form
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gambar struktur organisasi berhasil dihapus.',
                ]);
            }

            return redirect()
                ->route('admin.struktur.index')
                ->with('success', 'Gambar struktur organisasi berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete struktur error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'id'      => $id,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus gambar. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus gambar. Silakan coba lagi.');
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
     * Helper log aktivitas — supaya tidak duplikasi di setiap method.
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
        Storage::disk('public')->delete(self::STORAGE_PATH . '/' . $safeFilename);
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
            Log::warning('Struktur upload rejected: invalid extension', [
                'ext' => $extension,
                'ip'  => request()->ip(),
            ]);
            return null;
        }

        // 2. Cek MIME dengan finfo (bukan dari client)
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_MIMES, true)) {
            Log::warning('Struktur upload rejected: invalid MIME', [
                'mime' => $mimeType,
                'ip'   => request()->ip(),
            ]);
            return null;
        }

        // 3. Cek ukuran
        if ($file->getSize() > self::MAX_SIZE_KB * 1024) {
            Log::warning('Struktur upload rejected: file too large', [
                'size' => $file->getSize(),
                'ip'   => request()->ip(),
            ]);
            return null;
        }

        // 4. Rename file dengan hash (anti path traversal & collision)
        $filename = time() . '_' . Str::random(16) . '.' . $extension;

        return $file->storeAs(self::STORAGE_PATH, $filename, 'public');
    }
}