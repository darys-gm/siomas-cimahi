<!DOCTYPE html>
<html>
<head>
    <title>Detail ORMAS - {{ $ormas->nama }}</title>
    <style>
        /* ============================================ */
        /* MARGIN KERTAS: ATAS 3cm, KIRI 3cm, BAWAH 2cm, KANAN 2cm */
        /* ============================================ */
        @page {
            margin-top: 3cm;
            margin-bottom: 2cm;
            margin-left: 3cm;
            margin-right: 2cm;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11px;
            color: #1a202c;
            background: #ffffff;
        }
        
        /* ===== HEADER ===== */
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
        .header .info {
            font-size: 10px;
            color: #555555;
            margin-top: 4px;
        }
        .header .info span {
            margin: 0 6px;
        }
        .header .info .status-text {
            font-weight: 600;
            color: #000000;
        }

        /* ===== SECTION ===== */
        .section {
            margin-bottom: 15px;
        }

        /* ============================================ */
        /* GARIS PEMISAH - TIDAK MENEMPEL KE TEPI KERTAS */
        /* margin kiri-kanan 20px agar tidak kena pinggiran */
        /* ============================================ */
        .section-header {
            font-size: 12px;
            font-weight: 700;
            color: #000000;
            padding-bottom: 4px;
            margin-bottom: 6px;
            margin-left: 20px;
            margin-right: 20px;
            border-bottom: 2px solid #000000;
        }

        /* ============================================ */
        /* ISI DATA - MARGIN SAMA DENGAN HEADER         */
        /* Agar sejajar dengan judul section di atasnya */
        /* ============================================ */
        .section-body {
            padding: 4px 0;
            margin-left: 20px;
            margin-right: 20px;
        }
        .section-body .row {
            display: flex;
            padding: 3px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .section-body .row:last-child {
            border-bottom: none;
        }
        .section-body .label {
            width: 160px;
            font-weight: 500;
            color: #555555;
            flex-shrink: 0;
            font-size: 10px;
        }
        .section-body .value {
            flex: 1;
            color: #1a202c;
            font-weight: 500;
            font-size: 10px;
        }
        .section-body .value .empty {
            color: #999999;
            font-weight: 400;
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
            padding: 3px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .anggota-item .label-anggota {
            width: 160px;
            font-weight: 500;
            color: #555555;
            flex-shrink: 0;
            font-size: 10px;
        }
        .anggota-item .jumlah {
            flex: 1;
            color: #1a202c;
            font-weight: 500;
            font-size: 10px;
        }

        /* ============================================ */
        /* DATA PENGURUS - TANPA TABEL/BORDER           */
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
            font-size: 10px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 3px;
        }
        .pengurus-item .pengurus-field {
            font-size: 9px;
            padding: 1px 0;
            display: flex;
        }
        .pengurus-item .pengurus-field .lbl {
            width: 70px;
            color: #555555;
            flex-shrink: 0;
            font-size: 9px;
        }
        .pengurus-item .pengurus-field .val {
            flex: 1;
            font-weight: 500;
            color: #1a202c;
            font-size: 9px;
        }

        /* ===== KOORDINAT ===== */
        .coord-text {
            font-size: 10px;
            color: #1a202c;
            font-weight: 500;
        }
        .coord-text .lbl {
            color: #555555;
            font-weight: 500;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 18px;
            text-align: center;
            border-top: 1px solid #cccccc;
            padding-top: 8px;
            font-size: 8px;
            color: #888888;
            margin-left: 20px;
            margin-right: 20px;
        }
        .footer p {
            margin: 1px 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .pengurus-grid {
                grid-template-columns: 1fr 1fr;
            }
            .anggota-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 480px) {
            .pengurus-grid {
                grid-template-columns: 1fr;
            }
            .anggota-grid {
                grid-template-columns: 1fr;
            }
            .section-body .row {
                flex-direction: column;
                padding: 4px 0;
            }
            .section-body .label {
                width: 100%;
                margin-bottom: 2px;
            }
            .anggota-item {
                flex-direction: column;
                padding: 4px 0;
            }
            .anggota-item .label-anggota {
                width: 100%;
                margin-bottom: 2px;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>DETAIL ORGANISASI MASYARAKAT</h1>
        <p class="sub-title">KOTA CIMAHI</p>
        <div class="info">
            <span>Tanggal Cetak: <strong>{{ now()->format('d F Y H:i') }}</strong></span>
            <span>|</span>
            <span>Status: <span class="status-text">{{ $ormas->status_text ?? $ormas->status }}</span></span>
        </div>
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
                <span class="label">Tanggal Daftar :</span>
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
                    <p style="color: #999999; font-style: italic; font-size: 10px;">Belum ada data pengurus</p>
                </div>
            @endif
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Dicetak dari <strong>SIOMAS</strong> - Sistem Informasi Organisasi Masyarakat Kota Cimahi</p>
        <p>Dicetak oleh: {{ Auth::user()->name ?? 'Admin' }} | {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>