<!DOCTYPE html>
<html>
<head>
    <title>Laporan Semua Data ORMAS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10px;
            padding: 15px;
            color: #1a202c;
            background: #ffffff;
            width: 100%;
            max-width: 100%;
        }
        
        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000000;
        }
        .header h1 {
            font-size: 18px;
            font-weight: 700;
            color: #000000;
            margin: 0;
            letter-spacing: 1px;
        }
        .header .sub-title {
            font-size: 12px;
            font-weight: 600;
            color: #000000;
            margin: 2px 0 0 0;
            letter-spacing: 1px;
        }
        .header .info {
            font-size: 9px;
            color: #555555;
            margin-top: 4px;
        }
        .header .info span {
            margin: 0 6px;
        }

        /* ===== INFO BOX ===== */
        .info-box {
            background: #f5f5f5;
            padding: 6px 12px;
            border-radius: 3px;
            margin-bottom: 10px;
            border: 1px solid #dddddd;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 9px;
        }
        .info-box .item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .info-box .label {
            color: #555555;
        }
        .info-box .value {
            font-weight: 700;
            color: #000000;
        }

        /* ===== FILTER INFO ===== */
        .filter-info {
            background: #f9f9f9;
            padding: 5px 10px;
            border-radius: 3px;
            margin-bottom: 10px;
            border-left: 3px solid #000000;
            font-size: 9px;
            color: #333333;
        }
        .filter-info strong {
            color: #000000;
        }
        .filter-info .label-filter {
            color: #666666;
        }

        /* ===== TABEL ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8px;
            page-break-inside: auto;
            table-layout: fixed;
        }
        table thead th {
            background: #000000;
            color: white;
            padding: 4px 4px;
            text-align: left;
            border: 1px solid #000000;
            font-weight: 600;
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        table tbody td {
            padding: 3px 4px;
            border: 1px solid #cccccc;
            vertical-align: middle;
            font-size: 7px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #eeeeee;
        }

        /* ===== LEBAR KOLOM ===== */
        table thead th:nth-child(1) { width: 3%; }  /* No */
        table thead th:nth-child(2) { width: 16%; } /* Nama ORMAS */
        table thead th:nth-child(3) { width: 12%; } /* No AHU/SKT */
        table thead th:nth-child(4) { width: 10%; } /* Nama Ketua */
        table thead th:nth-child(5) { width: 24%; } /* Alamat Lengkap */
        table thead th:nth-child(6) { width: 15%; } /* Bidang Kegiatan */
        table thead th:nth-child(7) { width: 10%; } /* Kecamatan */
        table thead th:nth-child(8) { width: 10%; } /* Kelurahan */

        /* ===== TEXT WRAP ===== */
        .wrap-text {
            display: block;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            line-height: 1.2;
        }

        /* ===== ALAMAT KOSONG ===== */
        .alamat-kosong {
            color: #999999;
            font-style: italic;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 15px;
            text-align: center;
            border-top: 1px solid #cccccc;
            padding-top: 8px;
            font-size: 8px;
            color: #888888;
        }
        .footer p {
            margin: 1px 0;
        }
        
        /* ===== PRINT ===== */
        @media print {
            body { padding: 10px; }
            .no-print { display: none; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            
            table tbody td { font-size: 6px; padding: 2px 3px; }
            table thead th { font-size: 6px; padding: 3px 3px; }
            .wrap-text { font-size: 6px; }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>LAPORAN DATA ORGANISASI MASYARAKAT</h1>
        <p class="sub-title">KOTA CIMAHI</p>
        <div class="info">
            <span>Tahun: <strong>{{ $tahun }}</strong></span>
            <span>|</span>
            <span>Tanggal Cetak: <strong>{{ now()->format('d F Y H:i') }}</strong></span>
            <span>|</span>
            <span>Total Data: <strong>{{ $ormas->count() }}</strong></span>
        </div>
    </div>

    <!-- FILTER INFO -->
    <div class="filter-info">
        <span class="label-filter">Filter:</span>
        <strong>Kecamatan</strong> {{ $filterData['kecamatan'] ?? 'Semua' }} &nbsp;|&nbsp;
        <strong>Kelurahan</strong> {{ $filterData['kelurahan'] ?? 'Semua' }} &nbsp;|&nbsp;
        <strong>Bentuk Ormas</strong> {{ $filterData['jenis_ormas'] ?? 'Semua' }} &nbsp;|&nbsp;
        <strong>Bidang Kegiatan</strong> {{ $filterData['bidang_kegiatan'] ?? 'Semua' }}
    </div>

    <!-- INFO BOX -->
    <div class="info-box">
        <span class="item"><span class="label">Total:</span> <span class="value">{{ $ormas->count() }}</span></span>
        <span class="item"><span class="label">Disetujui:</span> <span class="value">{{ $ormas->where('status', 'disetujui')->count() }}</span></span>
        <span class="item"><span class="label">Ditolak:</span> <span class="value">{{ $ormas->where('status', 'ditolak')->count() }}</span></span>
        <span class="item"><span class="label">Menunggu:</span> <span class="value">{{ $ormas->where('status', 'menunggu_verifikasi')->count() }}</span></span>
        <span class="item"><span class="label">Revisi:</span> <span class="value">{{ $ormas->where('status', 'revisi')->count() }}</span></span>
    </div>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">No</th>
                <th>Nama ORMAS</th>
                <th>No AHU/SKT</th>
                <th>Nama Ketua</th>
                <th>Alamat Lengkap</th>
                <th>Bidang Kegiatan</th>
                <th>Kecamatan</th>
                <th>Kelurahan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ormas as $item)
            <tr>
                <td style="text-align: center; font-weight: 600; color: #555555;">{{ $loop->iteration }}</td>
                <td>
                    <span class="wrap-text">
                        {{ $item->nama }}
                    </span>
                </td>
                <td>
                    <span class="wrap-text">
                        {{ $item->nomor_registrasi ?? '-' }}
                    </span>
                </td>
                <td>
                    <span class="wrap-text">
                        @php
                            // Cari ketua dari relasi pengurus dengan jabatan 'Ketua'
                            $ketua = $item->pengurus->where('jabatan', 'Ketua')->first();
                            // Jika tidak ada, ambil pengurus pertama
                            if (!$ketua) {
                                $ketua = $item->pengurus->first();
                            }
                        @endphp
                        {{ $ketua->nama ?? '-' }}
                    </span>
                </td>
                <td>
                    <span class="wrap-text">
                        @php
                            // Cek berbagai kemungkinan field alamat
                            $alamat = '';
                            
                            // Coba field alamat_sekretariat
                            if (isset($item->alamat_sekretariat) && !empty($item->alamat_sekretariat)) {
                                $alamat = $item->alamat_sekretariat;
                            }
                            // Coba field alamat_kesekretariatan
                            elseif (isset($item->alamat_kesekretariatan) && !empty($item->alamat_kesekretariatan)) {
                                $alamat = $item->alamat_kesekretariatan;
                            }
                            // Coba field alamat
                            elseif (isset($item->alamat) && !empty($item->alamat)) {
                                $alamat = $item->alamat;
                            }
                            // Jika semua kosong, buat dari kelurahan dan kecamatan
                            else {
                                $kelurahan = $item->kelurahan->nama ?? '';
                                $kecamatan = $item->kecamatan->nama ?? '';
                                if (!empty($kelurahan) && !empty($kecamatan)) {
                                    $alamat = 'Kec. ' . $kecamatan . ', Kel. ' . $kelurahan . ', Kota Cimahi';
                                } else {
                                    $alamat = '-';
                                }
                            }
                        @endphp
                        {{ $alamat }}
                    </span>
                </td>
                <td>
                    <span class="wrap-text">
                        {{ $item->bidangKegiatan->nama ?? '-' }}
                    </span>
                </td>
                <td>
                    <span class="wrap-text">
                        {{ $item->kecamatan->nama ?? '-' }}
                    </span>
                </td>
                <td>
                    <span class="wrap-text">
                        {{ $item->kelurahan->nama ?? '-' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 15px; color: #999999;">
                    Tidak ada data ORMAS untuk tahun {{ $tahun }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <p>Dicetak dari <strong>SIOMAS</strong> - Sistem Informasi Organisasi Masyarakat Kota Cimahi</p>
        <p>Dicetak oleh: {{ Auth::user()->name ?? 'Admin' }} | {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>