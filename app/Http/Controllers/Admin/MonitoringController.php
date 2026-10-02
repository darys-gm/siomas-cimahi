<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ormas;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Dokumen hampir habis masa berlaku (contoh: 30 hari)
        $dokumenHampirKadaluarsa = Ormas::where('status', 'disetujui')
            ->whereDate('verified_at', '<=', now()->addDays(30))
            ->with(['user', 'kecamatan'])
            ->get();

        // ORMAS belum lengkap (data tidak lengkap)
        $ormasBelumLengkap = Ormas::whereIn('status', ['draft', 'revisi'])
            ->with(['user', 'jenisOrmas'])
            ->get()
            ->filter(function($ormas) {
                return !$ormas->isComplete();
            });

        // ORMAS belum diverifikasi
        $ormasBelumDiverifikasi = Ormas::where('status', 'menunggu_verifikasi')
            ->with(['user', 'kecamatan'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.monitoring.index', compact(
            'dokumenHampirKadaluarsa',
            'ormasBelumLengkap',
            'ormasBelumDiverifikasi'
        ));
    }
}