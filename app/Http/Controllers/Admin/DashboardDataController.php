<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ormas;
use App\Models\LogAktivitas;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardDataController extends Controller
{
    public function getStats()
    {
        $totalOrmas = Ormas::count();
        $ormasAktif = Ormas::where('status', 'disetujui')->count();
        $menungguVerifikasi = Ormas::where('status', 'menunggu_verifikasi')->count();
        $ditolak = Ormas::where('status', 'ditolak')->count();

        $ormasPerKecamatan = Ormas::select('kecamatan.nama', DB::raw('count(*) as total'))
            ->join('kecamatan', 'ormas.kecamatan_id', '=', 'kecamatan.id')
            ->groupBy('kecamatan.nama')
            ->get();

        $grafikStatus = Ormas::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $pengajuanBaru = Ormas::where('status', 'menunggu_verifikasi')
            ->with(['user', 'jenisOrmas'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'status' => $item->status,
                    'status_text' => $item->status_text,
                    'status_badge' => $item->status_badge,
                    'created_at' => $item->created_at->diffForHumans(),
                    'created_at_raw' => $item->created_at->toDateTimeString()
                ];
            });

        $logs = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_name' => $item->user->name ?? 'User',
                    'user_initial' => substr($item->user->name ?? 'U', 0, 1),
                    'aktivitas' => $item->aktivitas,
                    'deskripsi' => $item->deskripsi,
                    'created_at' => $item->created_at->diffForHumans(),
                    'created_at_raw' => $item->created_at->toDateTimeString(),
                    'badge_color' => $this->getBadgeColor($item->aktivitas)
                ];
            });

        $totalBerita = Berita::count();
        $beritaPublished = Berita::where('is_published', true)->count();
        $beritaDraft = Berita::where('is_published', false)->count();
        $totalUsers = User::where('role', 'user')->count();
        $ormasTerverifikasi = Ormas::where('status', 'disetujui')->count();

        // Preview berita terbaru
        $previewBeritas = Berita::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'excerpt' => $item->excerpt,
                    'is_published' => $item->is_published,
                    'created_at' => $item->created_at->format('d/m/Y'),
                    'status_text' => $item->is_published ? 'Published' : 'Draft',
                    'status_badge' => $item->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'totalOrmas' => $totalOrmas,
                'ormasAktif' => $ormasAktif,
                'menungguVerifikasi' => $menungguVerifikasi,
                'ditolak' => $ditolak,
                'ormasPerKecamatan' => $ormasPerKecamatan,
                'grafikStatus' => $grafikStatus,
                'pengajuanBaru' => $pengajuanBaru,
                'logs' => $logs,
                'totalBerita' => $totalBerita,
                'beritaPublished' => $beritaPublished,
                'beritaDraft' => $beritaDraft,
                'totalUsers' => $totalUsers,
                'ormasTerverifikasi' => $ormasTerverifikasi,
                'previewBeritas' => $previewBeritas,
                'lastUpdate' => now()->toDateTimeString(),
                'lastUpdateHuman' => now()->diffForHumans()
            ]
        ]);
    }

    private function getBadgeColor($aktivitas)
    {
        $colors = [
            'Login' => 'bg-green-100',
            'Logout' => 'bg-red-100',
            'Verifikasi' => 'bg-blue-100',
            'Edit Profil ORMAS' => 'bg-yellow-100',
            'Ubah Password' => 'bg-purple-100',
            'Tambah Berita' => 'bg-indigo-100',
            'Update Berita' => 'bg-indigo-100',
            'Hapus Berita' => 'bg-red-100',
            'Manajemen User' => 'bg-pink-100',
        ];
        return $colors[$aktivitas] ?? 'bg-gray-100';
    }
}