<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\OrmasExport;
use App\Exports\OrmasDetailExport;
use App\Models\Ormas;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisOrmas;
use App\Models\BidangKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan ORMAS
     */
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        
        $tahunTersedia = Ormas::select(DB::raw('YEAR(created_at) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($tahunTersedia)) {
            $tahunTersedia = [date('Y')];
        }

        $ormas = Ormas::with(['jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'pengurus'])
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $grafikBulanan = $this->getGrafikBulanan($tahun);

        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $jenisOrmas = JenisOrmas::all();
        $bidangKegiatan = BidangKegiatan::all();
        $statuses = ['draft', 'menunggu_verifikasi', 'revisi', 'disetujui', 'ditolak'];

        $totalOrmas = Ormas::whereYear('created_at', $tahun)->count();
        $totalDisetujui = Ormas::whereYear('created_at', $tahun)->where('status', 'disetujui')->count();
        $totalDitolak = Ormas::whereYear('created_at', $tahun)->where('status', 'ditolak')->count();
        $totalMenunggu = Ormas::whereYear('created_at', $tahun)->where('status', 'menunggu_verifikasi')->count();

        return view('admin.laporan.index', compact(
            'ormas', 
            'kecamatan', 
            'kelurahan', 
            'jenisOrmas',
            'bidangKegiatan',
            'statuses',
            'grafikBulanan',
            'tahun',
            'tahunTersedia',
            'totalOrmas',
            'totalDisetujui',
            'totalDitolak',
            'totalMenunggu'
        ));
    }

    /**
     * Mendapatkan data grafik per bulan
     */
    private function getGrafikBulanan($tahun)
    {
        $data = [];
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        $ormasPerBulan = Ormas::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', $tahun)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan')
            ->toArray();

        $disetujuiPerBulan = Ormas::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', $tahun)
            ->where('status', 'disetujui')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan')
            ->toArray();

        $ditolakPerBulan = Ormas::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', $tahun)
            ->where('status', 'ditolak')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan')
            ->toArray();

        $menungguPerBulan = Ormas::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', $tahun)
            ->where('status', 'menunggu_verifikasi')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan')
            ->toArray();

        for ($i = 1; $i <= 12; $i++) {
            $data['bulan'][] = $bulan[$i - 1];
            $data['total'][] = $ormasPerBulan[$i] ?? 0;
            $data['disetujui'][] = $disetujuiPerBulan[$i] ?? 0;
            $data['ditolak'][] = $ditolakPerBulan[$i] ?? 0;
            $data['menunggu'][] = $menungguPerBulan[$i] ?? 0;
        }

        return $data;
    }

    /**
     * Download semua data ORMAS dalam format PDF dengan filter
     */
    public function downloadAllOrmas(Request $request)
    {
        try {
            $tahun = $request->tahun ?? date('Y');
            $kecamatanId = $request->kecamatan_id;
            $kelurahanId = $request->kelurahan_id;
            $jenisOrmasId = $request->jenis_ormas_id;
            $bidangKegiatanId = $request->bidang_kegiatan_id;
            
            $query = Ormas::with(['jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan', 'pengurus'])
                ->whereYear('created_at', $tahun);

            if ($kecamatanId) {
                $query->where('kecamatan_id', $kecamatanId);
            }
            if ($kelurahanId) {
                $query->where('kelurahan_id', $kelurahanId);
            }
            if ($jenisOrmasId) {
                $query->where('jenis_ormas_id', $jenisOrmasId);
            }
            if ($bidangKegiatanId) {
                $query->where('bidang_kegiatan_id', $bidangKegiatanId);
            }

            $ormas = $query->orderBy('nama')->get();

            if ($ormas->isEmpty()) {
                return redirect()->route('admin.laporan.index')
                    ->with('error', 'Tidak ada data ORMAS yang sesuai dengan filter untuk tahun ' . $tahun);
            }

            $filterData = [
                'tahun' => $tahun,
                'kecamatan' => $kecamatanId ? Kecamatan::find($kecamatanId)->nama ?? '-' : 'Semua',
                'kelurahan' => $kelurahanId ? Kelurahan::find($kelurahanId)->nama ?? '-' : 'Semua',
                'jenis_ormas' => $jenisOrmasId ? JenisOrmas::find($jenisOrmasId)->nama ?? '-' : 'Semua',
                'bidang_kegiatan' => $bidangKegiatanId ? BidangKegiatan::find($bidangKegiatanId)->nama ?? '-' : 'Semua',
            ];

            $pdf = Pdf::loadView('admin.laporan.pdf-all', compact('ormas', 'tahun', 'filterData'));
            
            $pdf->setPaper('a4', 'landscape');
            
            $pdf->setOption('margin-top', 8);
            $pdf->setOption('margin-bottom', 8);
            $pdf->setOption('margin-left', 8);
            $pdf->setOption('margin-right', 8);
            
            $filename = 'laporan_ormas_' . $tahun . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('Download All ORMAS PDF Error: ' . $e->getMessage());
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Gagal mendownload PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download detail ORMAS dalam format PDF
     */
    public function downloadDetailOrmas($id)
    {
        try {
            $ormas = Ormas::with([
                'jenisOrmas', 
                'bidangKegiatan', 
                'kecamatan', 
                'kelurahan',
                'pengurus'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('admin.laporan.pdf-detail', compact('ormas'));
            
            $pdf->setPaper('a4', 'portrait');
            
            $filename = 'detail_ormas_' . str_replace(' ', '_', $ormas->nama) . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('Download Detail ORMAS PDF Error: ' . $e->getMessage());
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Gagal mendownload PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export data ORMAS ke Excel (SEMUA ORMAS)
     * Logika ada di App\Exports\OrmasExport
     */
    public function exportExcel(Request $request)
    {
        try {
            return (new OrmasExport(
                $request->tahun ?? date('Y'),
                $request->kecamatan_id,
                $request->kelurahan_id,
                $request->jenis_ormas_id,
                $request->bidang_kegiatan_id
            ))->download();

        } catch (\Exception $e) {
            \Log::error('Export Excel Error: ' . $e->getMessage());
            \Log::error('Export Excel Trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export detail ORMAS ke Excel (1 ORMAS)
     * Logika ada di App\Exports\OrmasDetailExport
     */
    public function exportDetailExcel($id)
    {
        try {
            $ormas = Ormas::with([
                'jenisOrmas', 
                'bidangKegiatan', 
                'kecamatan', 
                'kelurahan',
                'pengurus'
            ])->findOrFail($id);

            return (new OrmasDetailExport($ormas))->download();

        } catch (\Exception $e) {
            \Log::error('Export Detail Excel Error: ' . $e->getMessage());
            \Log::error('Export Detail Excel Trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Gagal export detail Excel: ' . $e->getMessage());
        }
    }

    /**
     * Print laporan (untuk cetak langsung)
     */
    public function print(Request $request)
    {
        $query = Ormas::with(['user', 'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan']);

        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }

        if ($request->jenis_laporan == 'kecamatan' && $request->kecamatan_id) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->jenis_laporan == 'kelurahan' && $request->kelurahan_id) {
            $query->where('kelurahan_id', $request->kelurahan_id);
        }

        if ($request->jenis_laporan == 'status' && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->jenis_laporan == 'jenis' && $request->jenis_ormas_id) {
            $query->where('jenis_ormas_id', $request->jenis_ormas_id);
        }

        if ($request->jenis_laporan == 'bidang' && $request->bidang_kegiatan_id) {
            $query->where('bidang_kegiatan_id', $request->bidang_kegiatan_id);
        }

        $ormas = $query->orderBy('nama')->get();

        return view('admin.laporan.print', compact('ormas'))->with('request', $request);
    }

    /**
     * Mendapatkan data grafik via AJAX
     */
    public function getDataGrafik(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $data = $this->getGrafikBulanan($tahun);
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Mendapatkan statistik untuk dashboard
     */
    public function getStats(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        
        $totalOrmas = Ormas::whereYear('created_at', $tahun)->count();
        $totalDisetujui = Ormas::whereYear('created_at', $tahun)->where('status', 'disetujui')->count();
        $totalDitolak = Ormas::whereYear('created_at', $tahun)->where('status', 'ditolak')->count();
        $totalMenunggu = Ormas::whereYear('created_at', $tahun)->where('status', 'menunggu_verifikasi')->count();
        $totalRevisi = Ormas::whereYear('created_at', $tahun)->where('status', 'revisi')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'totalOrmas' => $totalOrmas,
                'totalDisetujui' => $totalDisetujui,
                'totalDitolak' => $totalDitolak,
                'totalMenunggu' => $totalMenunggu,
                'totalRevisi' => $totalRevisi,
            ]
        ]);
    }
}