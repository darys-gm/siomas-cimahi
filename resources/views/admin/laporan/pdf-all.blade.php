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

        /* ============================================ */
        /* KOP SURAT DENGAN GAMBAR (JPG BASE64)         */
        /* ============================================ */
        .kop-surat {
            width: 100%;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border: none !important; /* Memastikan div pembungkus tidak ada border */
            margin-right: 20px;
        }
        
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important; /* Menghilangkan border pada tabel */
        }
        
        .kop-table td {
            vertical-align: middle;
            border: none !important; /* Menghilangkan border abu-abu pada sel tabel */
        }

        .kop-logo {
            width: 15%;
            text-align: left;
        }
        
        .kop-logo img {
            height: 80px; 
            width: auto;
            display: block;
            margin-left: 110px;
        }

        .kop-teks {
            width: 85%;
            text-align: center;
        }

        .kop-surat .pemkot {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 2px;
            margin-right: 90px;
        }
        .kop-surat .dinas {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 5px;
            margin-right: 90px;
        }
        .kop-surat .alamat {
            font-size: 11px;
            line-height: 1.3;
            margin-right: 90px;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            margin-right: 30px;
            border-bottom: 2px solid #000000; /* Garis pemisah di bawah judul laporan tetap ada */
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

        /* ===== FILTER INFO ===== */
        .filter-info {
            background: #f9f9f9;
            padding: 5px 10px;
            border-radius: 3px;
            margin-bottom: 10px;
            margin-right: 30px;
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

        /* ===== TABEL UTAMA ===== */
        table.data-table {
            width: 97.3%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8px;
            page-break-inside: auto;
            table-layout: fixed;
        }
        table.data-table thead th {
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
        table.data-table tbody td {
            padding: 3px 4px;
            border: 1px solid #cccccc;
            vertical-align: middle;
            font-size: 7px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table.data-table tbody tr:hover {
            background-color: #eeeeee;
        }

        /* ===== LEBAR KOLOM ===== */
        table.data-table thead th:nth-child(1) { width: 3%; }  /* No */
        table.data-table thead th:nth-child(2) { width: 16%; } /* Nama ORMAS */
        table.data-table thead th:nth-child(3) { width: 12%; } /* No AHU/SKT */
        table.data-table thead th:nth-child(4) { width: 10%; } /* Nama Ketua */
        table.data-table thead th:nth-child(5) { width: 24%; } /* Alamat Lengkap */
        table.data-table thead th:nth-child(6) { width: 15%; } /* Bidang Kegiatan */
        table.data-table thead th:nth-child(7) { width: 8%; } /* Kecamatan */
        table.data-table thead th:nth-child(8) { width: 8%; } /* Kelurahan */

        /* ===== TEXT WRAP ===== */
        .wrap-text {
            display: block;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            line-height: 1.2;
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
            table.data-table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            
            table.data-table tbody td { font-size: 6px; padding: 2px 3px; }
            table.data-table thead th { font-size: 6px; padding: 3px 3px; }
            .wrap-text { font-size: 6px; }
        }
    </style>
</head>
<body>
    <!-- ============================================ -->
    <!-- KOP SURAT RESMI (TANPA GARIS BORDER)         -->
    <!-- ============================================ -->
    <div class="kop-surat">
        <table class="kop-table">
            <tr>
                <!-- Kolom Kiri: Logo -->
                <td class="kop-logo">
                    @php
                        // Pastikan path ini sesuai dengan lokasi dan ekstensi file JPG Anda
                        $path = public_path('images/lg-link-3.jpg');
                        
                        if (file_exists($path)) {
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        } else {
                            $base64 = ''; 
                        }
                    @endphp

                    @if($base64)
                        <img src="{{ $base64 }}" alt="Logo Cimahi">
                    @endif
                </td>
                <!-- Kolom Kanan: Teks -->
                <td class="kop-teks">
                    <div class="pemkot">PEMERINTAH KOTA CIMAHI</div>
                    <div class="dinas">BADAN KESATUAN BANGSA DAN POLITIK</div>
                    <div class="alamat">
                        Jalan Rd. Demang Hardjakusumah Blok Jati Cihanjuang, Cimahi, Jawa Barat 40513<br>
                        Telepon (022) 6654274, Faximile (022) 6654274<br>
                        Laman www.cimahikota.go.id, Pos-el bakesbangpol@cimahikota.go.id
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- HEADER LAPORAN -->
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

    <!-- TABEL UTAMA -->
    <table class="data-table">
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
                            // Ambil ketua dari relasi pengurus (jika ada), jika tidak ambil pengurus pertama
                            $ketua = $item->pengurus->firstWhere('jabatan', 'Ketua') ?? $item->pengurus->first();
                        @endphp
                        {{ $ketua->nama ?? '-' }}
                    </span>
                </td>
                <td>
                    <span class="wrap-text">
                        @php
                            // Gunakan alamat_kesekretariatan, jika kosong gabungkan kecamatan & kelurahan
                            $alamat = $item->alamat_kesekretariatan;
                            if (empty($alamat)) {
                                $kel = $item->kelurahan->nama ?? '';
                                $kec = $item->kecamatan->nama ?? '';
                                if ($kel && $kec) {
                                    $alamat = 'Kel. ' . $kel . ', Kec. ' . $kec;
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
    </div>
</body>
</html>