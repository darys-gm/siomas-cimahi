<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Poster;
use App\Models\Agenda;
use App\Models\Setting;
use App\Services\VisitorStatisticService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * @var VisitorStatisticService
     */
    protected VisitorStatisticService $visitorService;

    /**
     * Constructor - inject VisitorStatisticService
     */
    public function __construct(VisitorStatisticService $visitorService)
    {
        $this->visitorService = $visitorService;
    }

    /**
     * Halaman Beranda
     */
    public function index(Request $request)
    {
        // ============================================================
        // 1. AMBIL DATA UNTUK SECTION GALERI (PER KATEGORI)
        // ============================================================
        // Galeri Bakesbangpol (default tampil di halaman user)
        $galerisBakesbangpol = Galeri::where('is_active', true)
            ->where('kategori', 'bakesbangpol')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Galeri Ormas
        $galerisOrmas = Galeri::where('is_active', true)
            ->where('kategori', 'ormas')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Untuk kompatibilitas (jika ada bagian lain yang pakai $galeris)
        $galeris = $galerisBakesbangpol;

        // ============================================================
        // 2. AMBIL DATA UNTUK SECTION BERITA
        // ============================================================
        $beritas = Berita::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        // ============================================================
        // 3. AMBIL DATA UNTUK SECTION POSTER
        // ============================================================
        $posters = Poster::active()->ordered()->get();

        // ============================================================
        // 4. AMBIL RUNNING TEXT
        // ============================================================
        $runningText = Setting::get(
            'running_text',
            'Selamat datang di SIOMAS Kota Cimahi - Sistem Informasi Organisasi Masyarakat'
        );
        $runningSpeed = Setting::get('running_speed', 20);

        // ============================================================
        // 5. AMBIL DATA AGENDA (untuk Kalender & List Agenda)
        // ============================================================
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // Agenda hari ini
        $todayAgendas = Agenda::where('is_active', 1)
            ->whereDate('tanggal', $today)
            ->get();

        // Fallback: jika tidak ada agenda hari ini, tampilkan semua agenda aktif
        if ($todayAgendas->count() == 0) {
            $todayAgendas = Agenda::where('is_active', 1)
                ->orderBy('tanggal', 'asc')
                ->get();
        }

        // Semua agenda aktif (untuk rendering kalender di JS)
        $allAgendas = Agenda::where('is_active', 1)
            ->orderBy('tanggal', 'asc')
            ->get();

        // ============================================================
        // 6. AMBIL STATISTIK PENGUNJUNG
        // ============================================================
        $visitorStats = $this->visitorService->getAllStats();

        // ============================================================
        // 7. KIRIM SEMUA DATA KE VIEW
        // ============================================================
        return view('home', compact(
            'galeris',
            'galerisBakesbangpol',
            'galerisOrmas',
            'beritas',
            'posters',
            'runningText',
            'runningSpeed',
            'allAgendas',
            'todayAgendas',
            'visitorStats'
        ));
    }
}