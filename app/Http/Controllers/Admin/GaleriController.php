<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GaleriController extends Controller
{
    // ============================================================
    // KONSTANTA
    // ============================================================
    private const STORAGE_PATH     = 'galeri';
    private const MAX_IMAGE_WIDTH  = 1200;
    private const MAX_IMAGE_HEIGHT = 1200;
    private const WEBP_QUALITY     = 80;
    private const JPEG_QUALITY     = 85;

    // ============================================================
    // INDEX — Daftar Galeri dengan Filter Kategori
    // ============================================================
    public function index(Request $request)
    {
        $query = Galeri::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $galeris = $query->paginate(12)->withQueryString();

        return view('admin.galeri.index', compact('galeris'));
    }

    // ============================================================
    // CREATE — Form Tambah Galeri
    // ============================================================
    public function create()
    {
        $kategoriList = Galeri::listKategori();

        return view('admin.galeri.create', compact('kategoriList'));
    }

    // ============================================================
    // STORE — Simpan Galeri Baru
    // ============================================================
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul'     => 'required|string|max:255',
                'kategori'  => 'required|in:ormas,bakesbangpol',
                'deskripsi' => 'nullable|string|max:5000',
                'gambar'    => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'is_active' => 'nullable|boolean',
            ], [
                'kategori.required' => 'Kategori wajib dipilih.',
                'kategori.in'       => 'Kategori tidak valid.',
            ]);

            // 🔒 Upload + konversi gambar
            $path = $this->uploadAndConvertImage($request->file('gambar'));

            if (!$path) {
                return back()
                    ->with('error', 'File gambar tidak valid.')
                    ->withInput();
            }

            // 🔒 Explicit assign (bukan mass assignment)
            $galeri = new Galeri();
            $galeri->judul     = $validated['judul'];
            $galeri->kategori  = $validated['kategori'];
            $galeri->deskripsi = $validated['deskripsi'] ?? null;
            $galeri->gambar    = $path;
            $galeri->user_id   = (int) Auth::id();
            $galeri->is_active = $request->has('is_active');
            $galeri->save();

            $this->logAktivitas(
                $request,
                'Tambah Galeri',
                "Menambahkan galeri '{$galeri->judul}' kategori {$galeri->kategori}"
            );

            return redirect()
                ->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store galeri error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()
                ->with('error', 'Gagal menambahkan galeri. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // EDIT — Form Edit Galeri
    // ============================================================
    public function edit($id)
    {
        if (!$this->isValidId($id)) {
            abort(404);
        }

        $galeri       = Galeri::findOrFail((int) $id);
        $kategoriList = Galeri::listKategori();

        return view('admin.galeri.edit', compact('galeri', 'kategoriList'));
    }

    // ============================================================
    // UPDATE — Update Galeri
    // ============================================================
    public function update(Request $request, $id)
    {
        try {
            $galeri = Galeri::findOrFail($id);

            $validated = $request->validate([
                'judul'     => 'required|string|max:255',
                'kategori'  => 'required|in:ormas,bakesbangpol',
                'deskripsi' => 'nullable|string|max:5000',
                'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'is_active' => 'nullable|boolean',
            ], [
                'kategori.required' => 'Kategori wajib dipilih.',
                'kategori.in'       => 'Kategori tidak valid.',
            ]);

            // Upload gambar baru (kalau ada)
            if ($request->hasFile('gambar')) {
                // 🔒 Hapus gambar lama dengan basename (anti path traversal)
                $this->deleteImageFile($galeri->gambar);

                $path = $this->uploadAndConvertImage($request->file('gambar'));

                if (!$path) {
                    return back()
                        ->with('error', 'File gambar tidak valid.')
                        ->withInput();
                }

                $galeri->gambar = $path;
            }

            // 🔒 Explicit assign
            $galeri->judul     = $validated['judul'];
            $galeri->kategori  = $validated['kategori'];
            $galeri->deskripsi = $validated['deskripsi'] ?? null;
            $galeri->is_active = $request->has('is_active');
            $galeri->save();

            $this->logAktivitas(
                $request,
                'Update Galeri',
                "Mengupdate galeri '{$galeri->judul}'"
            );

            return redirect()
                ->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil diupdate.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update galeri error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'id'      => $id,
            ]);

            return back()
                ->with('error', 'Gagal mengupdate galeri. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // DESTROY — Hapus Galeri
    // ============================================================
    public function destroy($id)
    {
        try {
            $galeri = Galeri::findOrFail($id);
            $judul  = $galeri->judul;

            // 🔒 Hapus file gambar dengan basename
            $this->deleteImageFile($galeri->gambar);

            $galeri->delete();

            $this->logAktivitas(
                request(),
                'Hapus Galeri',
                "Menghapus galeri '{$judul}'"
            );

            // Response JSON untuk AJAX, redirect untuk form
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Galeri berhasil dihapus.',
                ]);
            }

            return redirect()
                ->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Delete galeri error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'id'      => $id,
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus galeri. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus galeri. Silakan coba lagi.');
        }
    }

    // ============================================================
    // TOGGLE ACTIVE
    // ============================================================
    public function toggleActive($id)
    {
        try {
            $galeri = Galeri::findOrFail($id);

            $galeri->is_active = !$galeri->is_active;
            $galeri->save();

            $status = $galeri->is_active ? 'diaktifkan' : 'dinonaktifkan';

            $this->logAktivitas(
                request(),
                'Toggle Galeri',
                "Galeri '{$galeri->judul}' {$status}"
            );

            return back()->with('success', "Galeri berhasil {$status}.");

        } catch (\Exception $e) {
            Log::error('Toggle galeri error', [
                'message' => $e->getMessage(),
                'id'      => $id,
            ]);

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
     * Helper log aktivitas — supaya tidak duplikasi.
     */
    private function logAktivitas(Request $request, string $aktivitas, string $deskripsi): void
    {
        \App\Models\LogAktivitas::create([
            'user_id'    => (int) Auth::id(),
            'aktivitas'  => $aktivitas,
            'deskripsi'  => $deskripsi,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Hapus file gambar dari storage (basename untuk keamanan).
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
     * 🔒 SAFE FALLBACK — Simpan file asli dengan whitelist ekstensi
     * ============================================================
     * JANGAN pakai getClientOriginalExtension() mentah-mentah!
     * Wajib whitelist & rename dengan hash.
     */
    private function saveFallbackImage($file, ?string $extension = null): ?string
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $extension = $extension ?? strtolower($file->getClientOriginalExtension());

        // Jika ekstensi tidak di whitelist → paksa jadi 'jpg'
        if (!in_array($extension, $allowedExtensions, true)) {
            $extension = 'jpg';
        }

        $filename = time() . '_' . Str::random(16) . '.' . $extension;
        $path     = self::STORAGE_PATH . '/' . $filename;

        try {
            $file->storeAs(self::STORAGE_PATH, $filename, 'public');
            return $path;
        } catch (\Exception $e) {
            Log::error('Fallback image save failed', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * ============================================================
     * 🔒 UPLOAD & CONVERT IMAGE
     * - Verifikasi MIME
     * - Resize jika terlalu besar
     * - Convert ke WebP (fallback JPG)
     * - Fallback save original (whitelist)
     * ============================================================
     */
    private function uploadAndConvertImage($file): ?string
    {
        // Kalau GD tidak tersedia, langsung fallback
        if (!extension_loaded('gd')) {
            Log::warning('GD extension not loaded, saving file as original');
            return $this->saveFallbackImage($file);
        }

        // Generate nama file unik
        $baseFilename = time() . '_' . Str::random(16);
        $tempPath     = storage_path('app/public/temp_' . $baseFilename);

        try {
            // Pastikan direktori galeri ada (permission 0755, bukan 0777)
            $galeriDir = storage_path('app/public/' . self::STORAGE_PATH);
            if (!file_exists($galeriDir)) {
                mkdir($galeriDir, 0755, true);
            }

            // Simpan file sementara
            $file->move(storage_path('app/public'), 'temp_' . $baseFilename);

            if (!file_exists($tempPath)) {
                throw new \Exception('File temporary tidak ditemukan.');
            }

            // Dapatkan info gambar
            $imageInfo = getimagesize($tempPath);
            if ($imageInfo === false) {
                @unlink($tempPath);
                throw new \Exception('File bukan gambar yang valid.');
            }

            $mimeType      = $imageInfo['mime'];
            $image         = null;
            $isTransparent = false;

            // Load gambar berdasarkan MIME
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = function_exists('imagecreatefromjpeg')
                        ? imagecreatefromjpeg($tempPath)
                        : throw new \Exception('GD JPEG support not available');
                    break;

                case 'image/png':
                    if (!function_exists('imagecreatefrompng')) {
                        throw new \Exception('GD PNG support not available');
                    }
                    $image         = imagecreatefrompng($tempPath);
                    $isTransparent = true;
                    if ($image) {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                    }
                    break;

                case 'image/gif':
                    $image = function_exists('imagecreatefromgif')
                        ? imagecreatefromgif($tempPath)
                        : throw new \Exception('GD GIF support not available');
                    $isTransparent = true;
                    break;

                case 'image/webp':
                    if (!function_exists('imagecreatefromwebp')) {
                        // WebP tidak didukung → fallback JPG
                        $newPath = self::STORAGE_PATH . '/' . $baseFilename . '.jpg';
                        copy($tempPath, storage_path('app/public/' . $newPath));
                        @unlink($tempPath);
                        return $newPath;
                    }
                    $image = imagecreatefromwebp($tempPath);
                    break;

                default:
                    // Tipe tidak dikenal → fallback save original
                    @unlink($tempPath);
                    return $this->saveFallbackImage($file);
            }

            if (!$image) {
                @unlink($tempPath);
                throw new \Exception('Gagal memproses gambar.');
            }

            // Resize jika terlalu besar
            $image = $this->resizeImageIfNeeded($image, $isTransparent);

            // Simpan gambar (WebP atau JPG)
            $outputPath = $this->saveProcessedImage($image, $baseFilename, $mimeType);

            // Cleanup
            imagedestroy($image);
            @unlink($tempPath);

            return $outputPath;

        } catch (\Exception $e) {
            Log::error('Galeri image upload failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            // Cleanup temp file jika masih ada
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }

            // Fallback: simpan file asli dengan whitelist ekstensi
            return $this->saveFallbackImage($file);
        }
    }

    /**
     * Resize gambar jika dimensinya melebihi max.
     */
    private function resizeImageIfNeeded($image, bool $isTransparent)
    {
        $width  = imagesx($image);
        $height = imagesy($image);

        if ($width <= self::MAX_IMAGE_WIDTH && $height <= self::MAX_IMAGE_HEIGHT) {
            return $image;
        }

        $ratio     = min(self::MAX_IMAGE_WIDTH / $width, self::MAX_IMAGE_HEIGHT / $height);
        $newWidth  = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($isTransparent) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resizedImage;
    }

    /**
     * Simpan gambar yang sudah diproses (WebP atau JPG).
     */
    private function saveProcessedImage($image, string $baseFilename, string $mimeType): string
    {
        $webpSupported = function_exists('imagewebp');

        // Coba WebP dulu (kecuali GIF — karena GIF tidak support WebP animated)
        if ($webpSupported && $mimeType !== 'image/gif') {
            $outputPath     = self::STORAGE_PATH . '/' . $baseFilename . '.webp';
            $fullOutputPath = storage_path('app/public/' . $outputPath);
            $success        = imagewebp($image, $fullOutputPath, self::WEBP_QUALITY);

            if ($success) {
                return $outputPath;
            }
        }

        // Fallback ke JPG
        $outputPath     = self::STORAGE_PATH . '/' . $baseFilename . '.jpg';
        $fullOutputPath = storage_path('app/public/' . $outputPath);
        $success        = imagejpeg($image, $fullOutputPath, self::JPEG_QUALITY);

        if (!$success) {
            throw new \Exception('Gagal menyimpan gambar.');
        }

        return $outputPath;
    }
}