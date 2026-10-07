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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    // ============================================================
    // KONSTANTA
    // ============================================================
    private const STATUSES = ['draft', 'menunggu_verifikasi', 'revisi', 'disetujui', 'ditolak'];
    private const MONTHS   = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    // ============================================================
    // INDEX — Halaman Laporan ORMAS
    // ============================================================
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'nullable|integer|min:2000|max:2100',
        ]);

        $tahun = $validated['tahun'] ?? date('Y');

        // Tahun tersedia untuk filter
        $tahunTersedia = Ormas::select(DB::raw('YEAR(created_at) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($tahunTersedia)) {
            $tahunTersedia = [date('Y')];
        }

        // Data ORMAS dengan pagination
        $ormas = $this->baseOrmasQuery()
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Grafik bulanan (1 query)
        $grafikBulanan = $this->getGrafikBulanan($tahun);

        // Master data
        $kecamatan      = Kecamatan::all();
        $kelurahan      = Kelurahan::all();
        $jenisOrmas     = JenisOrmas::all();
        $bidangKegiatan = BidangKegiatan::all();
        $statuses       = self::STATUSES;

        // 🔥 Summary stats — 1 query untuk 4 angka
        $stats = $this->getSummaryStats($tahun);

        return view('admin.laporan.index', array_merge(
            compact(
                'ormas',
                'kecamatan',
                'kelurahan',
                'jenisOrmas',
                'bidangKegiatan',
                'statuses',
                'grafikBulanan',
                'tahun',
                'tahunTersedia'
            ),
            [
                'totalOrmas'     => $stats['total'],
                'totalDisetujui' => $stats['disetujui'],
                'totalDitolak'   => $stats['ditolak'],
                'totalMenunggu'  => $stats['menunggu'],
            ]
        ));
    }

    // ============================================================
    // DOWNLOAD ALL ORMAS (PDF)
    // ============================================================
    public function downloadAllOrmas(Request $request)
    {
        try {
            $validated = $request->validate([
                'tahun'              => 'nullable|integer|min:2000|max:2100',
                'kecamatan_id'       => 'nullable|integer|exists:kecamatans,id',
                'kelurahan_id'       => 'nullable|integer|exists:kelurahans,id',
                'jenis_ormas_id'     => 'nullable|integer|exists:jenis_ormas,id',
                'bidang_kegiatan_id' => 'nullable|integer|exists:bidang_kegiatan,id',
            ]);

            $tahun            = $validated['tahun'] ?? date('Y');
            $kecamatanId      = $validated['kecamatan_id'] ?? null;
            $kelurahanId      = $validated['kelurahan_id'] ?? null;
            $jenisOrmasId     = $validated['jenis_ormas_id'] ?? null;
            $bidangKegiatanId = $validated['bidang_kegiatan_id'] ?? null;

            $query = $this->baseOrmasQuery()->whereYear('created_at', $tahun);

            if ($kecamatanId)      $query->where('kecamatan_id', $kecamatanId);
            if ($kelurahanId)      $query->where('kelurahan_id', $kelurahanId);
            if ($jenisOrmasId)     $query->where('jenis_ormas_id', $jenisOrmasId);
            if ($bidangKegiatanId) $query->where('bidang_kegiatan_id', $bidangKegiatanId);

            $ormas = $query->orderBy('nama')->get();

            if ($ormas->isEmpty()) {
                return redirect()->route('admin.laporan.index')
                    ->with('error', 'Tidak ada data ORMAS yang sesuai dengan filter untuk tahun ' . $tahun);
            }

            // 🔥 Preload nama filter dalam 1x query (bukan 4x find)
            $filterData = $this->buildFilterData(
                $tahun,
                $kecamatanId,
                $kelurahanId,
                $jenisOrmasId,
                $bidangKegiatanId
            );

            $pdf = Pdf::loadView('admin.laporan.pdf-all', compact('ormas', 'tahun', 'filterData'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption('margin-top', 8);
            $pdf->setOption('margin-bottom', 8);
            $pdf->setOption('margin-left', 8);
            $pdf->setOption('margin-right', 8);

            return $pdf->download('laporan_ormas_' . $tahun . '.pdf');

        } catch (\Exception $e) {
            Log::error('Download All ORMAS PDF Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return redirect()->route('admin.laporan.index')
                ->with('error', 'Gagal mendownload PDF. Silakan coba lagi.');
        }
    }

    // ============================================================
    // DOWNLOAD DETAIL ORMAS (PDF)
    // ============================================================
    public function downloadDetailOrmas($id)
    {
        try {
            if (!$this->isValidId($id)) {
                abort(404);
            }

            $ormas = $this->baseOrmasQuery()->findOrFail((int) $id);

            $pdf = Pdf::loadView('admin.laporan.pdf-detail', compact('ormas'));
            $pdf->setPaper('a4', 'portrait');

            $filename = 'detail_ormas_' . str_replace(' ', '_', $ormas->nama) . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Download Detail ORMAS PDF Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'id'      => $id,
            ]);

            return redirect()->route('admin.laporan.index')
                ->with('error', 'Gagal mendownload PDF. Silakan coba lagi.');
        }
    }

    // ============================================================
    // EXPORT EXCEL — SEMUA ORMAS
    // ============================================================
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
            Log::error('Export Excel Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return redirect()->route('admin.laporan.index')
                ->with('error', 'Gagal export Excel. Silakan coba lagi.');
        }
    }

    // ============================================================
    // EXPORT EXCEL — DETAIL 1 ORMAS
    // ============================================================
    public function exportDetailExcel($id)
    {
        try {
            if (!$this->isValidId($id)) {
                abort(404);
            }

            $ormas = $this->baseOrmasQuery()->findOrFail((int) $id);

            return (new OrmasDetailExport($ormas))->download();

        } catch (\Exception $e) {
            Log::error('Export Detail Excel Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'id'      => $id,
            ]);

            return redirect()->route('admin.laporan.index')
                ->with('error', 'Gagal export detail Excel. Silakan coba lagi.');
        }
    }

    // ============================================================
    // PRINT — Cetak Langsung
    // ============================================================
    public function print(Request $request)
    {
        $query = Ormas::with(['user', 'jenisOrmas', 'bidangKegiatan', 'kecamatan', 'kelurahan']);

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        // Filter dinamis berdasarkan jenis_laporan
        switch ($request->jenis_laporan) {
            case 'kecamatan':
                if ($request->filled('kecamatan_id')) {
                    $query->where('kecamatan_id', $request->kecamatan_id);
                }
                break;

            case 'kelurahan':
                if ($request->filled('kelurahan_id')) {
                    $query->where('kelurahan_id', $request->kelurahan_id);
                }
                break;

            case 'status':
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                break;

            case 'jenis':
                if ($request->filled('jenis_ormas_id')) {
                    $query->where('jenis_ormas_id', $request->jenis_ormas_id);
                }
                break;

            case 'bidang':
                if ($request->filled('bidang_kegiatan_id')) {
                    $query->where('bidang_kegiatan_id', $request->bidang_kegiatan_id);
                }
                break;
        }

        $ormas = $query->orderBy('nama')->get();

        return view('admin.laporan.print', compact('ormas'))->with('request', $request);
    }

    // ============================================================
    // GET DATA GRAFIK (AJAX)
    // ============================================================
    public function getDataGrafik(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tahun' => 'nullable|integer|min:2000|max:2100',
        ]);

        $tahun = $validated['tahun'] ?? date('Y');

        return response()->json([
            'success' => true,
            'data'    => $this->getGrafikBulanan($tahun),
        ]);
    }

    // ============================================================
    // GET STATS (AJAX) — Untuk Dashboard
    // ============================================================
    public function getStats(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tahun' => 'nullable|integer|min:2000|max:2100',
        ]);

        $tahun = $validated['tahun'] ?? date('Y');

        $stats = $this->getSummaryStats($tahun);

        return response()->json([
            'success' => true,
            'data'    => [
                'totalOrmas'     => $stats['total'],
                'totalDisetujui' => $stats['disetujui'],
                'totalDitolak'   => $stats['ditolak'],
                'totalMenunggu'  => $stats['menunggu'],
                'totalRevisi'    => $stats['revisi'],
            ],
        ]);
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
     * Base query ORMAS dengan relasi standar.
     */
    private function baseOrmasQuery()
    {
        return Ormas::with([
            'jenisOrmas',
            'bidangKegiatan',
            'kecamatan',
            'kelurahan',
            'pengurus',
        ]);
    }

    /**
     * Bangun filterData untuk PDF dengan minimal query.
     * Load semua label sekaligus menggunakan find() ke collection.
     */
    private function buildFilterData(
        $tahun,
        $kecamatanId,
        $kelurahanId,
        $jenisOrmasId,
        $bidangKegiatanId
    ): array {
        return [
            'tahun'           => $tahun,
            'kecamatan'       => $kecamatanId ? (Kecamatan::find($kecamatanId)?->nama ?? '-') : 'Semua',
            'kelurahan'       => $kelurahanId ? (Kelurahan::find($kelurahanId)?->nama ?? '-') : 'Semua',
            'jenis_ormas'     => $jenisOrmasId ? (JenisOrmas::find($jenisOrmasId)?->nama ?? '-') : 'Semua',
            'bidang_kegiatan' => $bidangKegiatanId ? (BidangKegiatan::find($bidangKegiatanId)?->nama ?? '-') : 'Semua',
        ];
    }

    /**
     * ============================================================
     * 🔥 SUMMARY STATS — 1 QUERY untuk semua status
     * Menggantikan 5 query terpisah.
     * ============================================================
     */
    private function getSummaryStats($tahun): array
    {
        $result = Ormas::whereYear('created_at', $tahun)
            ->selectRaw('
                COUNT(*) AS total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS disetujui,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS ditolak,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS menunggu,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS revisi
            ', ['disetujui', 'ditolak', 'menunggu_verifikasi', 'revisi'])
            ->first();

        return [
            'total'     => (int) ($result->total ?? 0),
            'disetujui' => (int) ($result->disetujui ?? 0),
            'ditolak'   => (int) ($result->ditolak ?? 0),
            'menunggu'  => (int) ($result->menunggu ?? 0),
            'revisi'    => (int) ($result->revisi ?? 0),
        ];
    }

    /**
     * ============================================================
     * 🔥 GRAFIK BULANAN — 1 QUERY untuk 4 status × 12 bulan
     * Menggantikan 4 query terpisah.
     * ============================================================
     */
    private function getGrafikBulanan($tahun): array
    {
        // Single query: ambil bulan, status, dan total sekaligus
        $rows = Ormas::whereYear('created_at', $tahun)
            ->selectRaw('
                MONTH(created_at) AS bulan,
                status,
                COUNT(*) AS total
            ')
            ->groupBy('bulan', 'status')
            ->get();

        // Susun data per status per bulan (default 0)
        $bulanData = [
            'total'     => array_fill(1, 12, 0),
            'disetujui' => array_fill(1, 12, 0),
            'ditolak'   => array_fill(1, 12, 0),
            'menunggu'  => array_fill(1, 12, 0),
        ];

        foreach ($rows as $row) {
            $bulan = (int) $row->bulan;
            $total = (int) $row->total;

            // Total keseluruhan
            $bulanData['total'][$bulan] += $total;

            // Per status
            switch ($row->status) {
                case 'disetujui':
                    $bulanData['disetujui'][$bulan] += $total;
                    break;
                case 'ditolak':
                    $bulanData['ditolak'][$bulan] += $total;
                    break;
                case 'menunggu_verifikasi':
                    $bulanData['menunggu'][$bulan] += $total;
                    break;
            }
        }

        // Format output untuk chart
        $output = [
            'bulan'     => [],
            'total'     => [],
            'disetujui' => [],
            'ditolak'   => [],
            'menunggu'  => [],
        ];

        for ($i = 1; $i <= 12; $i++) {
            $output['bulan'][]     = self::MONTHS[$i - 1];
            $output['total'][]     = $bulanData['total'][$i];
            $output['disetujui'][] = $bulanData['disetujui'][$i];
            $output['ditolak'][]   = $bulanData['ditolak'][$i];
            $output['menunggu'][]  = $bulanData['menunggu'][$i];
        }

        return $output;
    }
}