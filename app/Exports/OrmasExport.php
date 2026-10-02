<?php

namespace App\Exports;

use App\Models\Ormas;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OrmasExport
{
    protected $tahun;
    protected $kecamatanId;
    protected $kelurahanId;
    protected $jenisOrmasId;
    protected $bidangKegiatanId;

    public function __construct($tahun, $kecamatanId = null, $kelurahanId = null, $jenisOrmasId = null, $bidangKegiatanId = null)
    {
        $this->tahun = $tahun;
        $this->kecamatanId = $kecamatanId;
        $this->kelurahanId = $kelurahanId;
        $this->jenisOrmasId = $jenisOrmasId;
        $this->bidangKegiatanId = $bidangKegiatanId;
    }

    /**
     * Ambil data ORMAS sesuai filter
     */
    protected function getQuery()
    {
        $query = Ormas::with([
            'jenisOrmas',
            'bidangKegiatan',
            'kecamatan',
            'kelurahan',
            'pengurus'
        ])->whereYear('created_at', $this->tahun);

        if ($this->kecamatanId) {
            $query->where('kecamatan_id', $this->kecamatanId);
        }
        if ($this->kelurahanId) {
            $query->where('kelurahan_id', $this->kelurahanId);
        }
        if ($this->jenisOrmasId) {
            $query->where('jenis_ormas_id', $this->jenisOrmasId);
        }
        if ($this->bidangKegiatanId) {
            $query->where('bidang_kegiatan_id', $this->bidangKegiatanId);
        }

        return $query->orderBy('nama')->get();
    }

    /**
     * Proses export dan langsung download
     */
    public function download()
    {
        $ormas = $this->getQuery();

        if ($ormas->isEmpty()) {
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Tidak ada data ORMAS yang sesuai dengan filter untuk tahun ' . $this->tahun);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data ORMAS ' . $this->tahun);

        // Sheet 1: Data ORMAS
        $this->buildDataSheet($sheet, $ormas);

        // Sheet 2: Statistik
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $statSheet = $spreadsheet->getActiveSheet();
        $statSheet->setTitle('Statistik ORMAS');

        $this->buildStatistikSheet($statSheet);

        // Kembali ke sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        // Output
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_ormas_' . $this->tahun . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Build Sheet 1: Data ORMAS
     */
    protected function buildDataSheet($sheet, $ormas)
    {
        $columnWidths = [
            'A' => 6, 'B' => 45, 'C' => 20, 'D' => 30, 'E' => 30, 'F' => 40, 'G' => 20,
            'H' => 30, 'I' => 40, 'J' => 20, 'K' => 30, 'L' => 40, 'M' => 20, 'N' => 20,
            'O' => 35, 'P' => 40, 'Q' => 20, 'R' => 20, 'S' => 35, 'T' => 20, 'U' => 35,
            'V' => 20, 'W' => 25, 'X' => 25, 'Y' => 25, 'Z' => 25, 'AA' => 15, 'AB' => 20, 'AC' => 25
        ];

        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $headers = [
            'NO', 'NAMA ORMAS', 'SINGKATAN', 'NO AHU/SKT',
            'NAMA KETUA', 'ALAMAT KETUA', 'NO TELEPON KETUA',
            'NAMA SEKRETARIS', 'ALAMAT SEKRETARIS', 'NO TELEPON SEKRETARIS',
            'NAMA BENDAHARA', 'ALAMAT BENDAHARA', 'NO TELEPON BENDAHARA',
            'BENTUK ORMAS', 'BIDANG KEGIATAN', 'ALAMAT KESEKRETARIATAN',
            'KECAMATAN', 'KELURAHAN', 'TITIK KOORDINAT', 'NO TELEPON', 'EMAIL',
            'JUMLAH TOTAL ANGGOTA', 'JUMLAH ANGGOTA PEREMPUAN', 'ANGGOTA PEREMPUAN (16-30)',
            'JUMLAH ANGGOTA LAKI-LAKI', 'ANGGOTA LAKI-LAKI (16-30)',
            'STATUS AKTIF', 'STATUS PELAPORAN', 'TANGGAL PENDAFTARAN'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1a56db'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:' . $col . '1')->applyFromArray($headerStyle);

        $pelaporanLabels = [
            'sudah' => 'Sudah',
            'belum' => 'Belum',
            'tidak_ada' => 'Tidak Ada'
        ];

        $row = 2;
        $no = 0;
        foreach ($ormas as $orma) {
            $no++;
            
            $pengurusList = $orma->pengurus;
            $ketua = $pengurusList->count() > 0 ? $pengurusList->get(0) : null;
            $sekretaris = $pengurusList->count() > 1 ? $pengurusList->get(1) : null;
            $bendahara = $pengurusList->count() > 2 ? $pengurusList->get(2) : null;

            $pelaporanValue = $orma->pelaporan ?? 'belum';
            $koordinat = ($orma->latitude && $orma->longitude) 
                ? $orma->latitude . ', ' . $orma->longitude 
                : '-';

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $orma->nama);
            $sheet->setCellValue('C' . $row, $orma->singkatan ?? '-');
            $sheet->setCellValue('D' . $row, $orma->nomor_registrasi ?? '-');
            $sheet->setCellValue('E' . $row, $ketua->nama ?? '-');
            $sheet->setCellValue('F' . $row, $ketua->alamat ?? '-');
            $sheet->setCellValue('G' . $row, $ketua->no_hp ?? '-');
            $sheet->setCellValue('H' . $row, $sekretaris->nama ?? '-');
            $sheet->setCellValue('I' . $row, $sekretaris->alamat ?? '-');
            $sheet->setCellValue('J' . $row, $sekretaris->no_hp ?? '-');
            $sheet->setCellValue('K' . $row, $bendahara->nama ?? '-');
            $sheet->setCellValue('L' . $row, $bendahara->alamat ?? '-');
            $sheet->setCellValue('M' . $row, $bendahara->no_hp ?? '-');
            $sheet->setCellValue('N' . $row, $orma->jenisOrmas->nama ?? '-');
            $sheet->setCellValue('O' . $row, $orma->bidangKegiatan->nama ?? '-');
            $sheet->setCellValue('P' . $row, $orma->alamat_kesekretariatan ?? '-');
            $sheet->setCellValue('Q' . $row, $orma->kecamatan->nama ?? '-');
            $sheet->setCellValue('R' . $row, $orma->kelurahan->nama ?? '-');
            $sheet->setCellValue('S' . $row, $koordinat);
            $sheet->setCellValue('T' . $row, $orma->no_telepon ?? '-');
            $sheet->setCellValue('U' . $row, $orma->email ?? '-');
            $sheet->setCellValue('V' . $row, $orma->jumlah_anggota ?? 0);
            $sheet->setCellValue('W' . $row, $orma->jumlah_anggota_perempuan ?? 0);
            $sheet->setCellValue('X' . $row, $orma->anggota_perempuan_rentang_16_30 ?? 0);
            $sheet->setCellValue('Y' . $row, $orma->jumlah_anggota_laki_laki ?? 0);
            $sheet->setCellValue('Z' . $row, $orma->anggota_laki_laki_rentang_16_30 ?? 0);
            $sheet->setCellValue('AA' . $row, $orma->is_active ? 'Aktif' : 'Nonaktif');
            $sheet->setCellValue('AB' . $row, $pelaporanLabels[$pelaporanValue] ?? 'Belum');
            $sheet->setCellValue('AC' . $row, $orma->created_at->format('d/m/Y H:i'));

            $sheet->getStyle('A' . $row . ':AC' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A' . $row . ':AC' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $row++;
        }

        $highestColumn = 'AC';
        $highestRow = $row - 1;
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->freezePane('A2');
    }

    /**
     * Build Sheet 2: Statistik ORMAS
     */
    protected function buildStatistikSheet($statSheet)
    {
        // Judul
        $statSheet->mergeCells('A1:C1');
        $statSheet->setCellValue('A1', 'STATISTIK ORGANISASI MASYARAKAT KOTA CIMAHI');
        $statSheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1a56db']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $statSheet->getRowDimension(1)->setRowHeight(35);

        $statSheet->mergeCells('A2:C2');
        $statSheet->setCellValue('A2', 'Tahun: ' . $this->tahun);
        $statSheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $statSheet->getRowDimension(2)->setRowHeight(25);

        $statRow = 4;

        // 1. Statistik per Kecamatan
        $kecamatanStats = Ormas::select('kecamatan_id', DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $this->tahun)
            ->groupBy('kecamatan_id')
            ->with('kecamatan')
            ->get();

        $statRow = $this->buildStatSection(
            $statSheet, $statRow,
            'STATISTIK PER KECAMATAN', 'KECAMATAN',
            $kecamatanStats,
            function($stat) { return $stat->kecamatan->nama ?? '-'; }
        );

        // 2. Statistik per Kelurahan
        $kelurahanStats = Ormas::select('kelurahan_id', DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $this->tahun)
            ->groupBy('kelurahan_id')
            ->with('kelurahan')
            ->get();

        $statRow = $this->buildStatSection(
            $statSheet, $statRow,
            'STATISTIK PER KELURAHAN', 'KELURAHAN',
            $kelurahanStats,
            function($stat) { return $stat->kelurahan->nama ?? '-'; }
        );

        // 3. Statistik per Bentuk Ormas
        $bentukStats = Ormas::select('jenis_ormas_id', DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $this->tahun)
            ->groupBy('jenis_ormas_id')
            ->with('jenisOrmas')
            ->get();

        $statRow = $this->buildStatSection(
            $statSheet, $statRow,
            'STATISTIK PER BENTUK ORMAS', 'BENTUK ORMAS',
            $bentukStats,
            function($stat) { return $stat->jenisOrmas->nama ?? '-'; }
        );

        // 4. Statistik per Bidang Kegiatan
        $bidangStats = Ormas::select('bidang_kegiatan_id', DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $this->tahun)
            ->groupBy('bidang_kegiatan_id')
            ->with('bidangKegiatan')
            ->get();

        $statRow = $this->buildStatSection(
            $statSheet, $statRow,
            'STATISTIK PER BIDANG KEGIATAN', 'BIDANG KEGIATAN',
            $bidangStats,
            function($stat) { return $stat->bidangKegiatan->nama ?? '-'; }
        );

        // 5. Statistik per Status
        $statusLabels = [
            'draft' => 'Draft',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'revisi' => 'Revisi',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak'
        ];

        $statusStats = Ormas::select('status', DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $this->tahun)
            ->groupBy('status')
            ->get();

        $statRow = $this->buildStatSection(
            $statSheet, $statRow,
            'STATISTIK PER STATUS', 'STATUS',
            $statusStats,
            function($stat) use ($statusLabels) { return $statusLabels[$stat->status] ?? $stat->status; }
        );

        // 6. Statistik per Status Pelaporan
        $pelaporanLabels = [
            'sudah' => 'Sudah',
            'belum' => 'Belum',
            'tidak_ada' => 'Tidak Ada'
        ];

        $pelaporanStats = Ormas::select('pelaporan', DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $this->tahun)
            ->groupBy('pelaporan')
            ->get();

        $statRow = $this->buildStatSection(
            $statSheet, $statRow,
            'STATISTIK PER STATUS PELAPORAN', 'STATUS PELAPORAN',
            $pelaporanStats,
            function($stat) use ($pelaporanLabels) { return $pelaporanLabels[$stat->pelaporan] ?? $stat->pelaporan; }
        );

        // Auto size columns
        foreach (range('A', 'C') as $col) {
            $statSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $statSheet->getStyle('A1:C' . $statRow)->getAlignment()->setWrapText(true);
        $statSheet->getStyle('A1:C' . $statRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    }

    /**
     * Helper: Build satu section statistik (header + rows + total)
     */
    protected function buildStatSection($statSheet, $statRow, $title, $kolomLabel, $stats, $labelCallback)
    {
        // Header section
        $statSheet->setCellValue('A' . $statRow, $title);
        $statSheet->mergeCells('A' . $statRow . ':C' . $statRow);
        $statSheet->getStyle('A' . $statRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $statSheet->getRowDimension($statRow)->setRowHeight(25);
        $statRow++;

        // Header kolom
        $statSheet->setCellValue('A' . $statRow, $kolomLabel);
        $statSheet->setCellValue('B' . $statRow, 'JUMLAH ORMAS');
        $statSheet->setCellValue('C' . $statRow, 'PERSENTASE');
        $statSheet->getStyle('A' . $statRow . ':C' . $statRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e2e8f0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $statRow++;

        // Data
        $total = $stats->sum('total');
        foreach ($stats as $stat) {
            $persentase = $total > 0 ? round(($stat->total / $total) * 100, 2) : 0;
            $statSheet->setCellValue('A' . $statRow, $labelCallback($stat));
            $statSheet->setCellValue('B' . $statRow, $stat->total);
            $statSheet->setCellValue('C' . $statRow, $persentase . '%');
            $statRow++;
        }

        // Total
        $statSheet->setCellValue('A' . $statRow, 'TOTAL');
        $statSheet->setCellValue('B' . $statRow, $total);
        $statSheet->setCellValue('C' . $statRow, '100%');
        $statSheet->getStyle('A' . $statRow . ':C' . $statRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fef3c7']],
        ]);
        $statRow += 2;

        return $statRow;
    }
}