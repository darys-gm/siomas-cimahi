@extends('layouts.home')

@section('title', 'Persyaratan Pendaftaran ORMAS yang Tidak Berbadan Hukum - SIOMAS Kota Cimahi')

@section('content')
<div class="relative min-h-screen py-8 sm:py-12 mt-8">
    <!-- Background -->
    <div class="absolute inset-0 w-full h-full">
        <img src="{{ asset('images/bg-pola.png') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-white/70 via-white/80 to-white/90"></div>
    </div>

    <div class="container mx-auto px-3 sm:px-4 relative z-10 max-w-5xl">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-2xl shadow-lg p-6 sm:p-8 mb-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-2xl sm:text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold leading-tight">
                        Persyaratan Pendaftaran ORMAS yang Tidak Berbadan Hukum
                    </h1>
                    <p class="text-white/80 text-xs sm:text-sm mt-1">
                        Panduan lengkap pendaftaran Ormas tidak berbadan hukum di Kota Cimahi
                    </p>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/50 p-5 sm:p-8 space-y-6">

            <!-- Info Dasar -->
            <section>
                <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-r-lg">
                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed text-justify">
                        Ormas yang tidak berbadan hukum resmi disetujui pendaftarannya setelah memperoleh <strong>Surat Keterangan Terdaftar (SKT)</strong> dari Kementerian Dalam Negeri. Berikut rincian dokumen dan tahapan pendaftaran untuk Ormas tidak berbadan hukum dengan mengacu pada <strong>Pasal 11 Peraturan Menteri Dalam Negeri Nomor 57 Tahun 2017</strong>.
                    </p>
                </div>
            </section>

            <!-- Apa Itu Ormas Tidak Berbadan Hukum -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-red-600"></i>
                    Apa Itu Ormas Tidak Berbadan Hukum?
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed text-justify">
                    Organisasi Kemasyarakatan (Ormas) tidak berbadan hukum adalah perkumpulan yang dibentuk oleh masyarakat yang diakui keberadaannya secara administratif melalui <strong>Surat Keterangan Terdaftar (SKT)</strong> yang dikeluarkan oleh Kementerian Dalam Negeri tanpa menempuh pengesahan badan hukum di Kementerian Hukum dan HAM.
                </p>
            </section>

            <!-- Syarat Pendaftaran -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-clipboard-check text-red-600"></i>
                    Syarat Pendaftaran Ormas Tidak Berbadan Hukum
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed mb-3">
                    Sebelum mengajukan permohonan, lengkapi dan siapkanlah terlebih dahulu dokumen seperti terlampir berikut ini:
                </p>
                <ol class="space-y-3">
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">1</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Akta pendirian dari notaris</strong> yang memuat AD atau AD dan ART, mencakup: nama dan lambang; tempat kedudukan; asas, tujuan, dan fungsi; kepengurusan; hak dan kewajiban anggota; pengelolaan keuangan; mekanisme penyelesaian sengketa dan pengawasan internal; serta pembubaran organisasi.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">2</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Program kerja</strong> organisasi.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">3</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Susunan pengurus</strong> (Ketua, Sekretaris, dan Bendahara), terdiri atas:
                            <ul class="mt-2 ml-4 space-y-1 list-disc list-inside text-gray-600">
                                <li>Surat Keputusan (SK) Kepengurusan lengkap sesuai AD/ART</li>
                                <li>Biodata pengurus</li>
                                <li>Pas foto berwarna ukuran 4×6 terbaru</li>
                                <li>Foto kopi e-KTP ketua, sekretaris, dan bendahara (atau sebutan lain) yang berstatus Warga Negara Indonesia</li>
                            </ul>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">4</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Surat keterangan domisili sekretariat</strong> dari lurah setempat, dengan lampiran:
                            <ul class="mt-2 ml-4 space-y-1 list-disc list-inside text-gray-600">
                                <li>Bukti kepemilikan/pernyataan (surat perjanjian kontrak/izin pakai)</li>
                                <li>Foto kantor atau sekretariat tampak depan yang memuat papan nama</li>
                            </ul>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">5</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Nomor Pokok Wajib Pajak (NPWP)</strong> atas nama Ormas.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">6</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Surat pernyataan tidak dalam sengketa kepengurusan</strong> atau tidak dalam perkara di pengadilan.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">7</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Surat pernyataan kesanggupan melaporkan kegiatan</strong>.
                        </div>
                    </li>
                </ol>
            </section>

            <!-- Lampiran Tambahan -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-paperclip text-red-600"></i>
                    Lampiran Tambahan
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed mb-3">
                    Selain itu, lampirkan pula dokumen berikut bersama permohonan ajuan:
                </p>
                <ol class="space-y-3">
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">1</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Formulir isian data Ormas</strong>.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">2</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Surat pernyataan tidak berafiliasi</strong>, serta pernyataan bahwa nama, lambang, bendera, tanda gambar, simbol, atribut, dan cap/stempel yang Anda gunakan belum menjadi hak paten dan/atau hak cipta pihak lain dan bukan milik Pemerintah.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">3</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Rekomendasi dari kementerian</strong> yang membidangi urusan agama, bagi Ormas dengan kekhususan bidang keagamaan.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">4</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Rekomendasi dari kementerian dan/atau perangkat daerah</strong> yang membidangi urusan kebudayaan, bagi Ormas dengan kekhususan bidang kepercayaan kepada Tuhan Yang Maha Esa.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">5</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Surat pernyataan kesediaan</strong> dari pejabat negara, pejabat pemerintahan, dan/atau tokoh masyarakat yang namanya tercantum dalam kepengurusan Ormas.
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">6</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <strong>Dokumen pendukung lainnya</strong>
                        </div>
                    </li>
                </ol>
            </section>

            <!-- Masa Berlaku -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-red-600"></i>
                    Masa Berlaku dan Pelaporan
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed text-justify">
                    SKT berlaku selama <strong>5 (lima) tahun</strong> sejak tanggal penerbitan dan apabila tidak ada perubahan. Setelah masa berlaku habis, dapat diperpanjang lagi dengan prosedur serupa. Selama masa berlaku tersebut, pengurus secara berkala menyampaikan laporan perkembangan dan kegiatan organisasi kepada pemerintah daerah. Dengan demikian, keberadaan Ormas tetap tercatat resmi sesuai <strong>Permendagri Nomor 57 Tahun 2017</strong>.
                </p>
            </section>

            <!-- FAQ -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-question-circle text-red-600"></i>
                    Pertanyaan yang Sering Diajukan (FAQ)
                </h2>
                <div class="space-y-3">
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apa itu Ormas tidak berbadan hukum?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Ormas tidak berbadan hukum adalah organisasi kemasyarakatan yang diakui keberadaannya setelah melengkapi persyaratan pendaftaran ormas dan terdaftar memiliki SKT (Surat Keterangan Terdaftar) yang dikeluarkan oleh Kementerian Dalam Negeri, yang pendaftaran untuk memperoleh SKT tanpa menempuh pengesahan badan hukum di Kementerian Hukum dan HAM.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apa saja syarat pendaftaran Ormas tidak berbadan hukum?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Dokumen utama yang diperlukan meliputi akta pendirian dari notaris yang memuat AD atau AD dan ART, program kerja, susunan pengurus (SK kepengurusan, biodata, pas foto berwarna 4×6, serta fotokopi e-KTP pengurus (ketua, sekretaris, dan bendahara)), surat keterangan domisili sekretariat dari lurah, NPWP atas nama Ormas, surat pernyataan tidak dalam sengketa kepengurusan, surat pernyataan kesanggupan melaporkan kegiatan dan dokumen pendukung lainnya yang diperlukan.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apa dasar hukum pendaftaran Ormas tidak berbadan hukum?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Pendaftaran mengacu pada Pasal 11 Peraturan Menteri Dalam Negeri (Permendagri) Nomor 57 Tahun 2017 tentang Pendaftaran dan Pengelolaan Sistem Informasi Organisasi Kemasyarakatan.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Siapa yang menerbitkan SKT bagi Ormas tidak berbadan hukum?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">SKT diterbitkan oleh Kementerian Dalam Negeri. Ormas tidak berbadan hukum dinyatakan resmi terdaftar setelah memperoleh SKT tersebut.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Berapa lama masa berlaku SKT dan apakah bisa diperpanjang?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">SKT berlaku selama 5 (lima) tahun sejak tanggal penerbitan dan dapat diperpanjang dengan prosedur serupa. Selama masa berlaku, pengurus wajib menyampaikan laporan perkembangan dan kegiatan organisasi secara berkala kepada pemerintah daerah.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apakah Ormas tidak berbadan hukum wajib memiliki NPWP?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Ya. Nomor Pokok Wajib Pajak (NPWP) atas nama Ormas termasuk salah satu dokumen yang wajib dilampirkan saat mengajukan permohonan pendaftaran.</p>
                    </details>
                </div>
            </section>

            <!-- Info Kontak -->
            <section class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-100 rounded-xl p-5">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-headset text-red-600"></i>
                    Informasi dan Konsultasi
                </h2>
                <p class="text-sm sm:text-base text-gray-700 mb-3">
                    Untuk konsultasi atau informasi lebih lanjut mengenai pendaftaran Ormas tidak berbadan hukum, silakan menghubungi:
                </p>
                <div class="bg-white rounded-lg p-4 space-y-2">
                    <p class="text-sm text-gray-700 flex items-start gap-2">
                        <i class="fas fa-building text-red-600 mt-1"></i>
                        <span><strong>Bakesbangpol Kota Cimahi</strong><br>Komplek Perkantoran Pemerintah Kota Cimahi, Gedung C Lantai 1<br>Jl. Rd Demang Hardjakusumah Nomor 3 Cihanjuang, Cibabat, Kecamatan Cimahi Utara</span>
                    </p>
                    <p class="text-sm text-gray-700 flex items-center gap-2">
                        <i class="fas fa-phone text-red-600"></i>
                        <span>(022) 6654274</span>
                    </p>
                    <p class="text-sm text-gray-700 flex items-center gap-2">
                        <i class="fas fa-envelope text-red-600"></i>
                        <span>bakesbangpol@cimahikota.go.id</span>
                    </p>
                </div>
            </section>

            <!-- Link ke Halaman Lain -->
            <section class="text-center pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Ingin mendirikan Ormas dengan status badan hukum penuh?</p>
                <a href="{{ route('persyaratan-berbadan-hukum') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
                    <i class="fas fa-arrow-right"></i>
                    Pelajari Persyaratan Ormas Berbadan Hukum
                </a>
            </section>
        </div>
    </div>
</div>
@endsection