<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminStrukturController extends Controller
{
    /**
     * Ekstensi yang diizinkan
     */
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_SIZE_KB = 5120; // 5 MB

    /**
     * Halaman Manajemen Struktur
     */
    public function index()
    {
        $struktur = StrukturOrganisasi::getActive();
        return view('admin.struktur.index', compact('struktur'));
    }

    /**
     * Upload gambar struktur baru.
     * Kalau sudah ada, gambar lama otomatis dihapus.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            if (!$request->hasFile('gambar')) {
                return back()->with('error', 'File gambar wajib diunggah.');
            }

            // 🔒 Validasi ganda: ekstensi + MIME
            $file = $request->file('gambar');

            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
                return back()->with('error', 'Format file tidak diizinkan. Gunakan JPG, PNG, atau WEBP.');
            }

            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file->getRealPath());
            finfo_close($finfo);

            if (!in_array($mimeType, self::ALLOWED_MIMES, true)) {
                return back()->with('error', 'File yang diunggah bukan gambar yang valid.');
            }

            // Hapus gambar lama (kalau ada)
            $existing = StrukturOrganisasi::getActive();
            if ($existing && $existing->gambar) {
                $safeOld = basename($existing->gambar);
                Storage::disk('public')->delete('struktur/' . $safeOld);
                $existing->delete();
            }

            // Upload gambar baru
            $filename = time() . '_' . Str::random(16) . '.' . $extension;
            $path     = $file->storeAs('struktur', $filename, 'public');

            // Simpan ke database
            $struktur = new StrukturOrganisasi();
            $struktur->gambar = $path;
            $struktur->save();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Upload Struktur Organisasi',
                'deskripsi'  => 'Mengupload gambar struktur organisasi baru',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.struktur.index')
                ->with('success', 'Gambar struktur organisasi berhasil diupload.');

        } catch (\Illuminate\Validation\ValidationException $e) {
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

    /**
     * Hapus gambar struktur.
     */
    public function destroy($id)
    {
        try {
            if (!is_numeric($id) || (int) $id < 1) {
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

            // Hapus file
            if ($struktur->gambar) {
                $safeFilename = basename($struktur->gambar);
                Storage::disk('public')->delete('struktur/' . $safeFilename);
            }

            $struktur->delete();

            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'aktivitas'  => 'Hapus Struktur Organisasi',
                'deskripsi'  => 'Menghapus gambar struktur organisasi',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Return JSON kalau request AJAX
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gambar struktur organisasi berhasil dihapus.',
                ]);
            }

            return redirect()->route('admin.struktur.index')
                ->with('success', 'Gambar struktur organisasi berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete struktur error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus gambar. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus gambar. Silakan coba lagi.');
        }
    }
}