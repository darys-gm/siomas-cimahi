<?php

namespace App\Exports;

use App\Models\Ormas;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OrmasDetailExport
{
    protected $ormas;

    public function __construct(Ormas $ormas)
    {
        $this->ormas = $ormas;
    }

    /**
     * Proses export detail dan langsung download
     */
    public function download()
    {
        $ormas = $this->ormas;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Detail ORMAS');

        // ============================================
        // JUDUL
        // ============================================
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'DETAIL DATA ORGANISASI MASYARAKAT');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1a56db']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // ============================================
        // INFORMASI UMUM
        // ============================================
        $sheet->setCellValue('A2', 'INFORMASI UMUM');
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e5e7eb']],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        $pelaporanLabels = [
            'sudah' => 'Sudah',
            'belum' => 'Belum',
            'tidak_ada' => 'Tidak Ada'
        ];
        $pelaporanValue = $ormas->pelaporan ?? 'belum';

        $koordinat = ($ormas->latitude && $ormas->longitude) 
            ? $ormas->latitude . ', ' . $ormas->longitude 
            : '-';

        // Kolom yang DIHAPUS: Tahun Berdiri, SK Pengakuan, Akta Pendirian, Website
        $informasi = [
            ['Nama ORMAS', $ormas->nama, 'Status', $ormas->is_active ? 'Aktif' : 'Nonaktif'],
            ['Singkatan', $ormas->singkatan ?? '-', 'Status Pelaporan', $pelaporanLabels[$pelaporanValue] ?? 'Belum'],
            ['No AHU/SKT', $ormas->nomor_registrasi ?? '-', 'Tanggal Daftar', $ormas->created_at->format('d/m/Y H:i')],
            ['Bentuk ORMAS', $ormas->jenisOrmas->nama ?? '-', 'No Telepon', $ormas->no_telepon ?? '-'],
            ['Bidang Kegiatan', $ormas->bidangKegiatan->nama ?? '-', 'Email', $ormas->email ?? '-'],
            ['Kecamatan', $ormas->kecamatan->nama ?? '-', '', ''],
            ['Kelurahan', $ormas->kelurahan->nama ?? '-', '', ''],
            ['Alamat Lengkap', $ormas->alamat_kesekretariatan ?? '-', '', ''],
            ['Titik Koordinat', $koordinat, '', ''],
        ];

        $row = 3;
        foreach ($informasi as $data) {
            $sheet->setCellValue('A' . $row, $data[0]);
            $sheet->setCellValue('B' . $row, $data[1]);
            $sheet->setCellValue('C' . $row, $data[2]);
            $sheet->setCellValue('D' . $row, $data[3]);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->getStyle('C' . $row)->getFont()->setBold(true);
            $row++;
        }

        $row++; // Spasi

        // ============================================
        // DATA KEANGGOTAAN
        // ============================================
        $sheet->setCellValue('A' . $row, 'DATA KEANGGOTAAN');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e5e7eb']],
        ]);
        $row++;

        $keanggotaan = [
            ['Jumlah Total Anggota', $ormas->jumlah_anggota ?? 0, 'Jumlah Anggota Perempuan', $ormas->jumlah_anggota_perempuan ?? 0],
            ['Anggota Perempuan (16-30)', $ormas->anggota_perempuan_rentang_16_30 ?? 0, 'Jumlah Anggota Laki-laki', $ormas->jumlah_anggota_laki_laki ?? 0],
            ['Anggota Laki-laki (16-30)', $ormas->anggota_laki_laki_rentang_16_30 ?? 0, '', ''],
        ];

        foreach ($keanggotaan as $data) {
            $sheet->setCellValue('A' . $row, $data[0]);
            $sheet->setCellValue('B' . $row, $data[1]);
            $sheet->setCellValue('C' . $row, $data[2]);
            $sheet->setCellValue('D' . $row, $data[3]);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->getStyle('C' . $row)->getFont()->setBold(true);
            $row++;
        }

        $row++; // Spasi

        // ============================================
        // DATA PENGURUS
        // ============================================
        $sheet->setCellValue('A' . $row, 'DATA PENGURUS');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e5e7eb']],
        ]);
        $row++;

        // Header pengurus
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Nama Lengkap');
        $sheet->setCellValue('C' . $row, 'Alamat Lengkap');
        $sheet->setCellValue('D' . $row, 'No Telepon');
        $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e2e8f0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;

        $no = 1;
        foreach ($ormas->pengurus as $pengurus) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $pengurus->nama ?? '-');
            $sheet->setCellValue('C' . $row, $pengurus->alamat ?? '-');
            $sheet->setCellValue('D' . $row, $pengurus->no_hp ?? '-');
            $row++;
        }

        // ============================================
        // STYLING AKHIR
        // ============================================
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:D' . $highestRow)->getAlignment()->setWrapText(true);

        $sheet->getStyle('A2:D' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // ============================================
        // CREATE WRITER & DOWNLOAD
        // ============================================
        $writer = new Xlsx($spreadsheet);
        $filename = 'detail_' . str_replace(' ', '_', $ormas->nama) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}