<?php

namespace App\Http\Controllers;

use App\Models\Ormas;
use App\Models\JenisOrmas;
use Illuminate\Http\Request;

class OrmasController extends Controller
{
    /**
     * Halaman daftar ORMAS (publik).
     */
    public function index(Request $request)
    {
        // ===== VALIDASI INPUT FILTER =====
        $validated = $request->validate([
            'search'       => 'nullable|string|max:100',
            'bentuk'       => 'nullable|string|max:100',
            'bidang'       => 'nullable|string|max:255',
            'kecamatan'    => 'nullable|integer|exists:kecamatan,id',
            'kelurahan'    => 'nullable|integer|exists:kelurahan,id',
            'status_aktif' => 'nullable|in:0,1', // Validasi filter status
        ]);

        $search       = $validated['search'] ?? null;
        $bentuk       = $validated['bentuk'] ?? null;
        $bidang       = $validated['bidang'] ?? null;
        $kecamatan    = $validated['kecamatan'] ?? null;
        $kelurahan    = $validated['kelurahan'] ?? null;
        $status_aktif = $validated['status_aktif'] ?? null;

        // ===== QUERY (Hanya Saring Status Disetujui, Tampilkan Semua Aktif/Nonaktif) =====
        $query = Ormas::with(['jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'pengurus'])
            ->where('status', 'disetujui'); 

        if ($search) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->where('nama', 'like', '%' . $search . '%');
        }

        if ($kecamatan) {
            $query->where('kecamatan_id', $kecamatan);
        }

        if ($kelurahan) {
            $query->where('kelurahan_id', $kelurahan);
        }

        if ($bentuk) {
            $query->whereHas('jenisOrmas', function ($q) use ($bentuk) {
                $q->where('nama', $bentuk);
            });
        }

        if ($bidang) {
            $query->whereHas('bidangKegiatan', function ($q) use ($bidang) {
                $q->where('nama', $bidang);
            });
        }

        if ($status_aktif !== null && $status_aktif !== '') {
            $query->where('is_active', $status_aktif);
        }

        $ormas = $query->orderBy('nama')->paginate(10);

        $ormas->appends([
            'search'       => $search,
            'bentuk'       => $bentuk,
            'bidang'       => $bidang,
            'kecamatan'    => $kecamatan,
            'kelurahan'    => $kelurahan,
            'status_aktif' => $status_aktif,
        ]);

        $jenisOrmas = JenisOrmas::withCount('ormas')->get();

        return view('ormas', compact('ormas', 'jenisOrmas', 'search', 'bentuk', 'bidang', 'kecamatan', 'kelurahan', 'status_aktif'));
    }

    /**
     * Detail ORMAS via AJAX (PUBLIK).
     */
    public function detail($id)
    {
        if (!is_numeric($id) || (int) $id < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $ormas = Ormas::with([
            'jenisOrmas',
            'bidangKegiatan',
            'kecamatan',
            'kelurahan',
            'pengurus',
        ])
            ->where('status', 'disetujui')
            ->find($id);

        if (!$ormas) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $safeData = [
            'id'                     => $ormas->id,
            'nama'                   => $ormas->nama,
            'singkatan'              => $ormas->singkatan,
            'nomor_registrasi'       => $ormas->nomor_registrasi,
            'tahun_daftar'           => $ormas->tahun_daftar,
            'jenis_ormas'            => $ormas->jenisOrmas ? ['nama' => $ormas->jenisOrmas->nama] : null,
            'bidang_kegiatan'        => $ormas->bidangKegiatan ? ['nama' => $ormas->bidangKegiatan->nama] : null,
            'alamat_kesekretariatan' => $ormas->alamat_kesekretariatan,
            'kecamatan'              => $ormas->kecamatan ? ['nama' => $ormas->kecamatan->nama] : null,
            'kelurahan'              => $ormas->kelurahan ? ['nama' => $ormas->kelurahan->nama] : null,
            'email'                  => $ormas->email,
            'no_telepon'             => $ormas->no_telepon,
            'website'                => $ormas->website,
            'latitude'               => $ormas->latitude,
            'longitude'              => $ormas->longitude,
            'jumlah_anggota'         => $ormas->jumlah_anggota,
            'is_active'              => (bool) $ormas->is_active,
            'pelaporan'              => $ormas->pelaporan,
            'pengurus'               => $ormas->pengurus->map(function ($p) {
                return [
                    'nama'    => $p->nama,
                    'jabatan' => $p->jabatan ?? null,
                    'alamat'  => $p->alamat,
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data'    => $safeData,
        ]);
    }
}