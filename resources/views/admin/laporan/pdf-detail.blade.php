<!DOCTYPE html>
<html>
<head>
    <title>Detail ORMAS - {{ $ormas->nama }}</title>
    <style>
        /* ============================================ */
        /* MARGIN KERTAS */
        /* ============================================ */
        @page {
            margin-top: 2cm; 
            margin-bottom: 2cm;
            margin-left: 2.5cm;
            margin-right: 2cm;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11px;
            color: #1a202c;
            background: #ffffff;
        }

        /* ============================================ */
        /* KOP SURAT DENGAN GAMBAR (JPG BASE64)         */
        /* ============================================ */
        .kop-surat {
            width: 100%;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000; /* Garis tunggal tebal */
        }
        
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 15%; /* Lebar kolom logo tetap */
            text-align: left;
        }
        
        .kop-logo img {
            height: 80px; 
            width: auto;
            display: block;
            margin-left: 110px;
        }

        .kop-teks {
            width: 85%; /* Sisa lebar untuk teks */
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

        /* ===== TANGGAL DI BAWAH KOP ===== */
        .tanggal-cetak {
            text-align: right;
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #000;
            margin-right: 130px;
        }

        /* ===== HEADER LAPORAN ===== */
        .header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: 700;
            color: #000000;
            margin: 0;
        }
        .header .sub-title {
            font-size: 12px;
            font-weight: 600;
            color: #000000;
            margin: 2px 0 0 0;
            letter-spacing: 1px;
        }

        /* ===== SECTION ===== */
        .section {
            margin-bottom: 10px;
            margin-left: 50px;
            margin-right: 50px;
        }

        .section-header {
            font-size: 12px;
            font-weight: 700;
            color: #000000;
            padding-bottom: 4px;
            margin-bottom: 6px;
            margin-left: 0; /* Disesuaikan agar pas */
            margin-right: 0;
            border-bottom: 2px solid #000000;
        }

        .section-body {
            padding: 4px 0;
            margin-left: 0;
            margin-right: 0;
        }
        .section-body .row {
            display: flex;
            padding: 4px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .section-body .row:last-child {
            border-bottom: none;
        }
        .section-body .label {
            width: 170px;
            font-weight: 500;
            color: #555555;
            flex-shrink: 0;
            font-size: 11px;
        }
        .section-body .value {
            flex: 1;
            color: #1a202c;
            font-weight: 500;
            font-size: 11px;
        }

        /* ===== DATA KEANGGOTAAN ===== */
        .anggota-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px 20px;
            margin-top: 4px;
        }
        .anggota-item {
            display: flex;
            padding: 4px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .anggota-item .label-anggota {
            width: 170px;
            font-weight: 500;
            color: #555555;
            flex-shrink: 0;
            font-size: 11px;
        }
        .anggota-item .jumlah {
            flex: 1;
            color: #1a202c;
            font-weight: 500;
            font-size: 11px;
        }

        /* ============================================ */
        /* DATA PENGURUS                                */
        /* ============================================ */
        .pengurus-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px 20px;
            margin-top: 6px;
        }
        .pengurus-item {
            padding: 4px 0;
            border-bottom: 1px dashed #e0e0e0;
        }
        .pengurus-item .pengurus-nomor {
            font-size: 11px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 3px;
        }
        .pengurus-item .pengurus-field {
            font-size: 10px;
            padding: 1px 0;
            display: flex;
        }
        .pengurus-item .pengurus-field .lbl {
            width: 70px;
            color: #555555;
            flex-shrink: 0;
            font-size: 10px;
        }
        .pengurus-item .pengurus-field .val {
            flex: 1;
            font-weight: 500;
            color: #1a202c;
            font-size: 10px;
        }

        /* ===== KOORDINAT ===== */
        .coord-text {
            font-size: 11px;
            color: #1a202c;
            font-weight: 500;
        }
        .coord-text .lbl {
            color: #555555;
            font-weight: 500;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #cccccc;
            padding-top: 8px;
            font-size: 9px;
            color: #888888;
        }
        .footer p {
            margin: 1px 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .pengurus-grid { grid-template-columns: 1fr 1fr; }
            .anggota-grid { grid-template-columns: 1fr; }
            .kop-surat .dinas { font-size: 16px; }
            .kop-surat .pemkot { font-size: 14px; }
        }
        @media (max-width: 480px) {
            .pengurus-grid { grid-template-columns: 1fr; }
            .anggota-grid { grid-template-columns: 1fr; }
            .section-body .row { flex-direction: column; padding: 4px 0; }
            .section-body .label { width: 100%; margin-bottom: 2px; }
            .anggota-item { flex-direction: column; padding: 4px 0; }
            .anggota-item .label-anggota { width: 100%; margin-bottom: 2px; }
        }
    </style>
</head>
<body>
    <!-- ============================================ -->
    <!-- KOP SURAT RESMI (MENGGUNAKAN TABEL)          -->
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

    <!-- TANGGAL DI BAWAH KOP -->
    <div class="tanggal-cetak">
        Cimahi,&nbsp;&nbsp;&nbsp;&nbsp; {{ now()->format('d F Y') }}
    </div>

    <!-- HEADER LAPORAN -->
    <div class="header">
        <h1>DETAIL ORGANISASI KEMASYARAKATAN</h1>
        <p class="sub-title">KOTA CIMAHI</p>
    </div>

    <!-- DATA ORMAS -->
    <div class="section">
        <div class="section-header">DATA ORMAS :</div>
        <div class="section-body">
            <div class="row">
                <span class="label">Nama ORMAS :</span>
                <span class="value"><strong>{{ $ormas->nama }}</strong></span>
            </div>
            <div class="row">
                <span class="label">Singkatan :</span>
                <span class="value">{{ $ormas->singkatan ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">No AHU/SKT :</span>
                <span class="value">{{ $ormas->nomor_registrasi ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Tahun Daftar :</span>
                <span class="value">{{ $ormas->tahun_daftar ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Bentuk Ormas :</span>
                <span class="value">{{ $ormas->jenisOrmas->nama ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Bidang Kegiatan :</span>
                <span class="value">{{ $ormas->bidangKegiatan->nama ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Status Aktif :</span>
                <span class="value">{{ $ormas->is_active ? 'Aktif' : 'Nonaktif' }}</span>
            </div>
            <div class="row">
                <span class="label">Status Pelaporan :</span>
                <span class="value">
                    @php
                        $pelaporanLabels = ['sudah' => 'Sudah', 'belum' => 'Belum', 'tidak_ada' => 'Tidak Ada'];
                        $pelaporanValue = $ormas->pelaporan ?? 'belum';
                    @endphp
                    {{ $pelaporanLabels[$pelaporanValue] ?? 'Belum' }}
                </span>
            </div>
            <div class="row">
                <span class="label">Tanggal Daftar (Sistem) :</span>
                <span class="value">{{ $ormas->created_at->format('d F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- KONTAK & ALAMAT -->
    <div class="section">
        <div class="section-header">KONTAK & ALAMAT</div>
        <div class="section-body">
            <div class="row">
                <span class="label">Alamat :</span>
                <span class="value">{{ $ormas->alamat_kesekretariatan ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Kecamatan :</span>
                <span class="value">{{ $ormas->kecamatan->nama ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Kelurahan :</span>
                <span class="value">{{ $ormas->kelurahan->nama ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">No Telepon :</span>
                <span class="value">{{ $ormas->no_telepon ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Email :</span>
                <span class="value">{{ $ormas->email ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Koordinat :</span>
                <span class="value">
                    @if($ormas->latitude && $ormas->longitude)
                        <span class="coord-text">
                            Lat: {{ $ormas->latitude }}, Lng: {{ $ormas->longitude }}
                        </span>
                    @else
                        -
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- DATA KEANGGOTAAN -->
    <div class="section">
        <div class="section-header">DATA KEANGGOTAAN</div>
        <div class="section-body">
            <div class="anggota-grid">
                <div class="anggota-item">
                    <span class="label-anggota">Total Anggota</span>
                    <span class="jumlah">: {{ $ormas->jumlah_anggota ?? 0 }}</span>
                </div>
                <div class="anggota-item">
                    <span class="label-anggota">Anggota Perempuan</span>
                    <span class="jumlah">: {{ $ormas->jumlah_anggota_perempuan ?? 0 }}</span>
                </div>
                <div class="anggota-item">
                    <span class="label-anggota">Perempuan (16-30)</span>
                    <span class="jumlah">: {{ $ormas->anggota_perempuan_rentang_16_30 ?? 0 }}</span>
                </div>
                <div class="anggota-item">
                    <span class="label-anggota">Anggota Laki-laki</span>
                    <span class="jumlah">: {{ $ormas->jumlah_anggota_laki_laki ?? 0 }}</span>
                </div>
                <div class="anggota-item">
                    <span class="label-anggota">Laki-laki (16-30)</span>
                    <span class="jumlah">: {{ $ormas->anggota_laki_laki_rentang_16_30 ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- DATA PENGURUS -->
    <div class="section">
        <div class="section-header">DATA PENGURUS</div>
        <div class="section-body">
            @php
                $pengurusList = $ormas->pengurus ?? collect();
                if ($pengurusList->isEmpty() && isset($ormas->pengurus) && $ormas->pengurus instanceof \Illuminate\Support\Collection) {
                    $pengurusList = $ormas->pengurus;
                }
            @endphp

            @if($pengurusList->count() > 0)
                <div class="pengurus-grid">
                    @foreach($pengurusList as $index => $pengurus)
                        <div class="pengurus-item">
                            <div class="pengurus-nomor">{{ $index + 1 }}. Pengurus</div>
                            <div class="pengurus-field">
                                <span class="lbl">Nama :</span>
                                <span class="val">{{ $pengurus->nama ?? '-' }}</span>
                            </div>
                            <div class="pengurus-field">
                                <span class="lbl">Alamat :</span>
                                <span class="val">{{ $pengurus->alamat ?? '-' }}</span>
                            </div>
                            <div class="pengurus-field">
                                <span class="lbl">No Telepon :</span>
                                <span class="val">{{ $pengurus->no_hp ?? '-' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 15px 0;">
                    <p style="color: #999999; font-style: italic; font-size: 11px;">Belum ada data pengurus</p>
                </div>
            @endif
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Dicetak dari <strong>SIOMAS</strong> - Sistem Informasi Organisasi Masyarakat Kota Cimahi</p>
    </div>
</body>
</html>