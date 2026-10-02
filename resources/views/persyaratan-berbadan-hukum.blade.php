@extends('layouts.home')

@section('title', 'Persyaratan Pendirian ORMAS Berbadan Hukum - SIOMAS Kota Cimahi')

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
                    <i class="fas fa-balance-scale text-2xl sm:text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold leading-tight">
                        Persyaratan Pendirian ORMAS Berbadan Hukum
                    </h1>
                    <p class="text-white/80 text-xs sm:text-sm mt-1">
                        Panduan lengkap pendirian Organisasi Kemasyarakatan berbadan hukum di Kota Cimahi
                    </p>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/50 p-5 sm:p-8 space-y-6">

            <!-- Pengertian Ormas -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-red-600"></i>
                    Apa itu Organisasi Kemasyarakatan atau Ormas?
                </h2>
                <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-r-lg">
                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed text-justify">
                        Organisasi Kemasyarakatan yang selanjutnya disebut Ormas adalah organisasi yang didirikan dan dibentuk oleh masyarakat secara sukarela berdasarkan kesamaan aspirasi, kehendak, kebutuhan, kepentingan, kegiatan, dan tujuan untuk berpartisipasi dalam pembangunan demi tercapainya tujuan Negara Kesatuan Republik Indonesia yang berdasarkan Pancasila.
                    </p>
                </div>
            </section>

            <!-- Dasar Hukum -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-gavel text-red-600"></i>
                    Dasar Hukum
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed text-justify">
                    Pendirian Ormas berbadan hukum mengacu pada <strong>Undang-Undang Nomor 17 Tahun 2013</strong> tentang Organisasi Kemasyarakatan beserta turunan peraturan pelaksanaannya, yaitu <strong>Peraturan Pemerintah Nomor 58 Tahun 2016</strong>. Pemerintah melalui Kementerian Hukum dan Hak Asasi Manusia mengesahkan status badan hukumnya sebagai suatu organisasi kemasyarakatan.
                </p>
            </section>

            <!-- Apa Itu Ormas Berbadan Hukum -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-red-600"></i>
                    Apa Itu Ormas Berbadan Hukum?
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed text-justify">
                    Ormas berbadan hukum adalah organisasi kemasyarakatan yang didirikan oleh masyarakat secara sukarela dan telah mendapatkan pengakuan serta pengesahan hukum penuh dari negara melalui Kementerian Hukum dan Hak Asasi Manusia (Kemenkumham). Status badan hukum ini membuat Ormas tersebut resmi menjadi subjek hukum mandiri, sehingga hak, kewajiban, dan kekayaan organisasi terpisah secara jelas dari kekayaan pribadi para pendiri atau pengurusnya.
                </p>
                <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-600 rounded-r-lg">
                    <p class="text-sm text-gray-700">
                        <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                        Secara regulasi di Indonesia, Ormas berbadan hukum dapat berbentuk <strong>Yayasan</strong> atau <strong>Perkumpulan</strong>.
                    </p>
                </div>
            </section>

            <!-- Syarat Pendirian -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-clipboard-check text-red-600"></i>
                    Syarat Pendirian Ormas Berbadan Hukum
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed mb-3">
                    Sebelum mengajukan permohonan, siapkan seluruh dokumen berikut secara lengkap:
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

            <!-- Proses Pengesahan -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-file-signature text-red-600"></i>
                    Proses Pengesahan Badan Hukum
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed text-justify">
                    Proses pengesahan badan hukum untuk Organisasi Kemasyarakatan (baik berbentuk Perkumpulan maupun Yayasan) saat ini dilakukan sepenuhnya secara elektronik melalui <strong>Sistem Administrasi Badan Hukum (SABH)</strong> di situs resmi <a href="https://ahu.go.id/" target="_blank" class="text-red-600 hover:text-red-800 underline font-medium">Ditjen AHU Online Kemenkumham <i class="fas fa-external-link-alt text-xs"></i></a>.
                </p>
                <div class="mt-3 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg">
                    <p class="text-sm text-gray-700">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        <strong>Penting:</strong> Permohonan ke sistem AHU Online ini <strong>tidak dilakukan secara mandiri</strong> oleh pendiri, melainkan <strong>wajib diajukan oleh Notaris</strong> yang telah diberikan kuasa.
                    </p>
                </div>
            </section>

            <!-- Kewajiban Pelaporan -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-tasks text-red-600"></i>
                    Kewajiban Pelaporan ke Pemerintah Daerah
                </h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed mb-3">
                    Setelah memperoleh pengesahan badan hukum, pengurus Ormas wajib melaporkan keberadaan kepengurusannya kepada pemerintah daerah setempat. Lampirkan dokumen berikut saat melapor ke <strong>Bakesbangpol Kota Cimahi</strong>:
                </p>
                <ul class="space-y-2">
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">1</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <span>Surat pencatatan pelaporan keberadaan kepada Wali Kota Cimahi melalui Kepala Bakesbangpol Kota Cimahi</span>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">2</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <span>Surat Keputusan Pengesahan Status Badan Hukum (AHU)</span>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">3</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <span>SK Susunan kepengurusan di daerah</span>
                        </div>
                    </li>
                   <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">4</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <span>Pas Foto dan KTP pengurus (KSB)</span>
                        </div>
                    </li>
                   <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">5</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <span>Surat keterangan domisili sekretariat dari kelurahan</span>
                        </div>
                    </li>
                   <li class="flex gap-3">
                        <span class="flex-shrink-0 w-7 h-7 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xs">6</span>
                        <div class="text-sm sm:text-base text-gray-700">
                            <span>Dokumen pendukung lainnya</span>
                        </div>
                    </li>

                </ul>
            </section>

            <!-- FAQ -->
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-question-circle text-red-600"></i>
                    Pertanyaan yang Sering Diajukan (FAQ)
                </h2>
                <div class="space-y-3">
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apa itu Ormas berbadan hukum?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Ormas berbadan hukum adalah organisasi kemasyarakatan yang didirikan oleh masyarakat secara sukarela dan telah mendapatkan pengakuan serta pengesahan hukum penuh dari negara melalui Kementerian Hukum dan Hak Asasi Manusia (Kemenkumham). Status badan hukum ini membuat Ormas tersebut resmi menjadi subjek hukum mandiri, sehingga hak, kewajiban, dan kekayaan organisasi terpisah secara jelas dari kekayaan pribadi para pendiri atau pengurusnya.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apa saja syarat pendirian Ormas berbadan hukum?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Untuk mendapatkan status ini, pengurus Ormas harus melalui proses notaris untuk membuat <strong>Akta Pendirian</strong> dan <strong>Anggaran Dasar/Anggaran Rumah Tangga (AD/ART)</strong>, mengajukan nama organisasi di platform Ditjen AHU Online, serta memiliki NPWP organisasi dan surat keterangan domisili serta dokumen lainnya sesuai aturan.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apa dasar hukum pendirian Ormas berbadan hukum?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Pendirian Ormas berbadan hukum mengacu pada Undang-Undang Nomor 17 Tahun 2013 tentang Organisasi Kemasyarakatan beserta peraturan pelaksananya, yaitu Peraturan Pemerintah Nomor 58 Tahun 2016.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Siapa yang mengesahkan status badan hukum Ormas?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Status badan hukum disahkan oleh Menteri Hukum dan Hak Asasi Manusia melalui Sistem Administrasi Badan Hukum (SABH) milik Direktorat Jenderal Administrasi Hukum Umum (Ditjen AHU). Proses pendaftaran suatu Ormas agar memiliki status Badan Hukum dibantu oleh Notaris yang berwenang.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apa kewajiban Ormas setelah memperoleh badan hukum?</summary>
                        <div class="mt-2 text-sm text-gray-600 text-justify">
                            <p>Setelah berhasil memperoleh Surat Keputusan (SK) Pengesahan Badan Hukum dari Kemenkumham, Ormas memiliki kewajiban administratif, operasional, dan sosial yang diatur dalam Undang-Undang Ormas, antara lain:</p>
                            <ul class="mt-2 ml-4 space-y-1 list-disc list-inside">
                                <li>Melapor keberadaan ormas kepada Bakesbangpol di tingkat Kabupaten/Kota atau Provinsi tempat sekretariat berada</li>
                                <li>Melaporkan kegiatan organisasi secara berkala</li>
                                <li>Bersama-sama Pemerintah bersinergi dalam menjaga ketertiban umum dalam rangka kondusivitas</li>
                            </ul>
                        </div>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-3 sm:p-4 cursor-pointer">
                        <summary class="font-semibold text-gray-800 text-sm sm:text-base">Apa perbedaan Ormas berbadan hukum dan tidak berbadan hukum?</summary>
                        <p class="mt-2 text-sm text-gray-600 text-justify">Ormas berbadan hukum memperoleh pengesahan dari Kementerian Hukum dan HAM sehingga dapat melakukan tindakan perdata atas nama organisasi, sedangkan Ormas tidak berbadan hukum cukup memperoleh Surat Keterangan Terdaftar (SKT) dari Kementerian Dalam Negeri.</p>
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
                    Untuk konsultasi atau informasi lebih lanjut mengenai pendirian Ormas berbadan hukum, silakan menghubungi:
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
                <p class="text-sm text-gray-600 mb-2">Organisasi Anda belum berbadan hukum?</p>
                <a href="{{ route('persyaratan-tidak-berbadan-hukum') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
                    <i class="fas fa-arrow-right"></i>
                    Pelajari Persyaratan Ormas Tidak Berbadan Hukum
                </a>
            </section>
        </div>
    </div>
</div>
@endsection