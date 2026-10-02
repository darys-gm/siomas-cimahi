<?php

namespace App\Http\Controllers;

use App\Models\ProdukHukum;
use Illuminate\Http\Request;

class ProdukHukumController extends Controller
{
    /**
     * Menampilkan daftar semua produk hukum yang aktif
     */
    public function index()
    {
        $produkHukums = ProdukHukum::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('produk-hukum', compact('produkHukums'));
    }

    /**
     * Menampilkan detail produk hukum dengan PDF viewer
     * 
     * 🔒 Fix: filter is_active = true untuk mencegah IDOR
     */
    public function show($id)
    {
        // 🔒 Validasi ID
        if (!is_numeric($id) || (int) $id < 1) {
            abort(404);
        }

        // 🔒 CRITICAL FIX: hanya tampilkan yang is_active = true
        $produkHukum = ProdukHukum::where('id', (int) $id)
            ->where('is_active', true)
            ->first();

        if (!$produkHukum) {
            abort(404);
        }

        $produkHukumLainnya = ProdukHukum::where('is_active', true)
            ->where('id', '!=', $produkHukum->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 🔒 Cek file dengan path yang sudah di-basename
        $safeFilename = basename($produkHukum->file_pdf);
        $filePath     = storage_path('app/public/produk_hukum/' . $safeFilename);
        $fileExists   = file_exists($filePath);

        return view('produk-hukum-detail', compact('produkHukum', 'produkHukumLainnya', 'fileExists'));
    }

    /**
     * Menampilkan file PDF langsung di browser
     * 
     * 🔒 Fix: filter is_active + path traversal protection + header injection protection
     */
    public function viewPdf($id)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            abort(404);
        }

        // 🔒 CRITICAL FIX: hanya tampilkan yang is_active = true
        $produkHukum = ProdukHukum::where('id', (int) $id)
            ->where('is_active', true)
            ->first();

        if (!$produkHukum) {
            abort(404);
        }

        // 🔒 Fix path traversal: ambil hanya nama file-nya, jangan path relatif
        $safeFilename = basename($produkHukum->file_pdf);
        $filePath     = storage_path('app/public/produk_hukum/' . $safeFilename);

        if (!file_exists($filePath) || !is_readable($filePath)) {
            abort(404);
        }

        // 🔒 Fix header injection: sanitize filename
        // Hilangkan karakter yang bisa dipakai untuk header injection
        $safeDownloadName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $produkHukum->judul);
        $safeDownloadName = mb_substr($safeDownloadName, 0, 100) . '.pdf';

        // 🔒 Force MIME type as PDF
        return response()->file($filePath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $safeDownloadName . '"',
            'X-Content-Type-Options' => 'nosniff',  // 🔒 cegah MIME sniffing
        ]);
    }
}