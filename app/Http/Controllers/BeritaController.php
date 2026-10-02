<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Menampilkan semua berita yang sudah dipublikasikan
     */
    public function index()
    {
        $beritaTerbaru = Berita::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();

        // 🔒 Fix: kalau tidak ada berita terbaru, langsung return view kosong
        if (!$beritaTerbaru) {
            return view('berita-semua', [
                'beritaTerbaru'  => null,
                'beritaLainnya'  => Berita::where('is_published', true)
                    ->orderBy('published_at', 'desc')
                    ->paginate(9),
            ]);
        }

        $beritaLainnya = Berita::where('is_published', true)
            ->where('id', '!=', $beritaTerbaru->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('berita-semua', compact('beritaTerbaru', 'beritaLainnya'));
    }

    /**
     * Menampilkan detail berita berdasarkan slug
     */
    public function show($slug)
    {
        // 🔒 Validasi format slug
        if (!is_string($slug) || !preg_match('/^[A-Za-z0-9\-_]+$/', $slug)) {
            abort(404);
        }

        $berita = Berita::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        // 🔒 Pesan generik — jangan bocorkan apakah slug ada atau tidak
        if (!$berita) {
            abort(404);
        }

        $beritaTerbaru = Berita::where('is_published', true)
            ->where('id', '!=', $berita->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('berita-detail', compact('berita', 'beritaTerbaru'));
    }
}