<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $query = Galeri::with('user')->orderBy('created_at', 'desc');

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $galeris = $query->paginate(12)->withQueryString();

        return view('admin.galeri.index', compact('galeris'));
    }

    public function create()
    {
        $kategoriList = Galeri::listKategori();
        return view('admin.galeri.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:ormas,bakesbangpol',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|max:5120', // Max 5MB
            'is_active' => 'boolean'
        ], [
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $this->uploadAndConvertImage($request->file('gambar'));
            $validated['gambar'] = $path;
        }

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Galeri::create($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $galeri = Galeri::findOrFail($id);
        $kategoriList = Galeri::listKategori();
        return view('admin.galeri.edit', compact('galeri', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:ormas,bakesbangpol',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:5120',
            'is_active' => 'boolean'
        ], [
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($galeri->gambar) {
                Storage::disk('public')->delete($galeri->gambar);
            }
            $path = $this->uploadAndConvertImage($request->file('gambar'));
            $validated['gambar'] = $path;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $galeri->update($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil diupdate.');
    }

    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);
        if ($galeri->gambar) {
            Storage::disk('public')->delete($galeri->gambar);
        }
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->is_active = !$galeri->is_active;
        $galeri->save();

        $status = $galeri->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Galeri berhasil {$status}.");
    }

    /**
     * Upload dan konversi gambar - dengan pengecekan GD extension
     * (Sama persis dengan yang di BeritaController)
     */
    private function uploadAndConvertImage($file)
    {
        try {
            // Cek apakah GD extension tersedia
            if (!extension_loaded('gd')) {
                \Log::warning('GD extension not loaded, saving file as original');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . Str::random(10) . '.' . $extension;
                $path = 'galeri/' . $filename;
                $file->storeAs('galeri', $filename, 'public');
                return $path;
            }

            // Generate nama file unik
            $timestamp = time();
            $random = Str::random(10);
            $filename = $timestamp . '_' . $random;
            $tempPath = storage_path('app/public/temp_' . $filename);

            // Pastikan direktori galeri ada
            $galeriDir = storage_path('app/public/galeri');
            if (!file_exists($galeriDir)) {
                mkdir($galeriDir, 0777, true);
            }

            // Simpan file sementara
            $file->move(storage_path('app/public'), 'temp_' . $filename);

            // Cek apakah file temporary ada
            if (!file_exists($tempPath)) {
                throw new \Exception('File temporary tidak ditemukan.');
            }

            // Dapatkan informasi gambar
            $imageInfo = getimagesize($tempPath);
            if ($imageInfo === false) {
                @unlink($tempPath);
                throw new \Exception('File bukan gambar yang valid.');
            }

            $mimeType = $imageInfo['mime'];
            $image = null;
            $isTransparent = false;

            // Load gambar berdasarkan tipe dengan pengecekan fungsi
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    if (function_exists('imagecreatefromjpeg')) {
                        $image = imagecreatefromjpeg($tempPath);
                    } else {
                        throw new \Exception('GD JPEG support not available');
                    }
                    break;
                case 'image/png':
                    if (function_exists('imagecreatefrompng')) {
                        $image = imagecreatefrompng($tempPath);
                        $isTransparent = true;
                        if ($image) {
                            imagepalettetotruecolor($image);
                            imagealphablending($image, true);
                            imagesavealpha($image, true);
                        }
                    } else {
                        throw new \Exception('GD PNG support not available');
                    }
                    break;
                case 'image/gif':
                    if (function_exists('imagecreatefromgif')) {
                        $image = imagecreatefromgif($tempPath);
                        $isTransparent = true;
                    } else {
                        throw new \Exception('GD GIF support not available');
                    }
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $image = imagecreatefromwebp($tempPath);
                    } else {
                        // WebP tidak didukung, simpan sebagai JPG
                        $newPath = 'galeri/' . $filename . '.jpg';
                        copy($tempPath, storage_path('app/public/' . $newPath));
                        @unlink($tempPath);
                        return $newPath;
                    }
                    break;
                default:
                    $extension = $file->getClientOriginalExtension();
                    $newPath = 'galeri/' . $filename . '.' . $extension;
                    copy($tempPath, storage_path('app/public/' . $newPath));
                    @unlink($tempPath);
                    return $newPath;
            }

            if (!$image) {
                @unlink($tempPath);
                throw new \Exception('Gagal memproses gambar.');
            }

            // Resize jika gambar terlalu besar
            $width = imagesx($image);
            $height = imagesy($image);
            $maxWidth = 1200;
            $maxHeight = 1200;

            if ($width > $maxWidth || $height > $maxHeight) {
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = round($width * $ratio);
                $newHeight = round($height * $ratio);
                
                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                
                if ($isTransparent) {
                    imagealphablending($resizedImage, false);
                    imagesavealpha($resizedImage, true);
                    $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                    imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
                }
                
                imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resizedImage;
            }

            // Cek apakah WebP didukung
            $webpSupported = function_exists('imagewebp');

            if ($webpSupported && $mimeType !== 'image/gif') {
                // Simpan sebagai WebP
                $outputPath = 'galeri/' . $filename . '.webp';
                $fullOutputPath = storage_path('app/public/' . $outputPath);
                $success = imagewebp($image, $fullOutputPath, 80);
                
                if (!$success) {
                    // Fallback ke JPG
                    $outputPath = 'galeri/' . $filename . '.jpg';
                    $fullOutputPath = storage_path('app/public/' . $outputPath);
                    $success = imagejpeg($image, $fullOutputPath, 85);
                }
            } else {
                // Simpan sebagai JPG
                $outputPath = 'galeri/' . $filename . '.jpg';
                $fullOutputPath = storage_path('app/public/' . $outputPath);
                $success = imagejpeg($image, $fullOutputPath, 85);
            }
            
            // Hapus resource dan file temporary
            imagedestroy($image);
            @unlink($tempPath);

            if (!$success) {
                throw new \Exception('Gagal menyimpan gambar.');
            }

            return $outputPath;

        } catch (\Exception $e) {
            \Log::error('Galeri image upload failed: ' . $e->getMessage());
            
            // Fallback: simpan file asli
            try {
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . Str::random(10) . '.' . $extension;
                $path = 'galeri/' . $filename;
                $file->storeAs('galeri', $filename, 'public');
                return $path;
            } catch (\Exception $e2) {
                \Log::error('Galeri fallback failed: ' . $e2->getMessage());
                throw $e;
            }
        }
    }
}