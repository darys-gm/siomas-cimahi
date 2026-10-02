<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ormas;
use App\Models\LogAktivitas;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik ORMAS
        $totalOrmas = Ormas::count();
        $ormasAktif = Ormas::where('status', 'disetujui')->count();
        $menungguVerifikasi = Ormas::where('status', 'menunggu_verifikasi')->count();
        $ditolak = Ormas::where('status', 'ditolak')->count();

        // ORMAS per Kecamatan
        $ormasPerKecamatan = Ormas::select('kecamatan.nama', DB::raw('count(*) as total'))
            ->join('kecamatan', 'ormas.kecamatan_id', '=', 'kecamatan.id')
            ->groupBy('kecamatan.nama')
            ->get();

        // Grafik Status
        $grafikStatus = Ormas::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Pengajuan Baru
        $pengajuanBaru = Ormas::where('status', 'menunggu_verifikasi')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Log Aktivitas
        $logs = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrmas',
            'ormasAktif',
            'menungguVerifikasi',
            'ditolak',
            'ormasPerKecamatan',
            'grafikStatus',
            'pengajuanBaru',
            'logs'
        ));
    }
}