@extends('layouts.home')

@section('title', 'Profil Kesbangpol - SIOMAS Kota Cimahi')

@section('content')
<!-- Header dengan Background Image Full Width -->
<div class="relative w-full h-48 sm:h-56 md:h-72 lg:h-[400px] flex items-center justify-center -mt-8">
    <div class="absolute inset-0 w-full h-full">
        <img src="{{ asset('images/bg-profil.jpg') }}" 
             alt="Profil Kesbangpol" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-red-900/80 to-red-800/60"></div>
    </div>
    <div class="relative z-10 text-white text-center px-3 sm:px-6">
        <div class="flex justify-center mb-1.5 sm:mb-3">
            @php
                $logoPath = public_path('images/logo-siomas1.png');
                $logoUrl = file_exists($logoPath) ? asset('images/logo-siomas1.png') : null;
            @endphp
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="SIOMAS" class="h-12 sm:h-16 md:h-20 lg:h-40 w-auto">
            @else
                <div class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <span class="text-2xl sm:text-4xl md:text-5xl font-bold text-white">S</span>
                </div>
            @endif
        </div>
        <h1 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-0.5 sm:mb-1 text-white drop-shadow-lg">Profil Kesbangpol</h1>
        <p class="text-[10px] sm:text-sm md:text-base lg:text-lg text-red-100 font-light drop-shadow-md leading-tight">Badan Kesatuan Bangsa dan Politik Kota Cimahi</p>
        <div class="w-10 sm:w-16 h-0.5 sm:h-1 bg-red-400 mx-auto mt-1.5 sm:mt-3 rounded-full"></div>
    </div>
</div>

<!-- Konten Utama dengan Background -->
<div class="relative py-3 sm:py-6">
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full">
        <img src="{{ asset('images/bg-pola.png') }}" 
             alt="Background" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-white/70 via-white/80 to-white/90"></div>
    </div>
    
    <div class="container mx-auto px-3 sm:px-4 relative z-10">
        <div class="flex flex-col lg:flex-row gap-3 sm:gap-6">
            <!-- Kolom Kiri (70%) -->
            <div class="lg:w-8/12">
                <!-- Navbar Menu Profil -->
                <div class="bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden mb-3 sm:mb-6">
                    <div class="flex flex-wrap items-center justify-center gap-1 sm:gap-2 p-1.5 sm:p-3">
                        <button class="menu-btn px-2 sm:px-4 md:px-5 py-1 sm:py-2 md:py-2.5 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition bg-red-600 text-white whitespace-nowrap" data-target="struktur">
                            <i class="fas fa-sitemap mr-0.5 sm:mr-2"></i> <span class="text-[9px] sm:text-xs">Struktur</span>
                        </button>
                        <button class="menu-btn px-2 sm:px-4 md:px-5 py-1 sm:py-2 md:py-2.5 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition text-gray-700 hover:bg-red-50 hover:text-red-600 whitespace-nowrap" data-target="sejarah">
                            <i class="fas fa-history mr-0.5 sm:mr-2"></i> <span class="text-[9px] sm:text-xs">Sejarah</span>
                        </button>
                        <button class="menu-btn px-2 sm:px-4 md:px-5 py-1 sm:py-2 md:py-2.5 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition text-gray-700 hover:bg-red-50 hover:text-red-600 whitespace-nowrap" data-target="visimisi">
                            <i class="fas fa-eye mr-0.5 sm:mr-2"></i> <span class="text-[9px] sm:text-xs">Visi & Misi</span>
                        </button>
                        <button class="menu-btn px-2 sm:px-4 md:px-5 py-1 sm:py-2 md:py-2.5 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition text-gray-700 hover:bg-red-50 hover:text-red-600 whitespace-nowrap" data-target="tupoksi">
                            <i class="fas fa-tasks mr-0.5 sm:mr-2"></i> <span class="text-[9px] sm:text-xs">TUPOKSI</span>
                        </button>
                        <button class="menu-btn px-2 sm:px-4 md:px-5 py-1 sm:py-2 md:py-2.5 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition text-gray-700 hover:bg-red-50 hover:text-red-600 whitespace-nowrap" data-target="galeri">
                            <i class="fas fa-images mr-0.5 sm:mr-2"></i> <span class="text-[9px] sm:text-xs">Galeri</span>
                        </button>
                    </div>
                </div>

                <!-- Konten Dinamis -->
                <div id="content-container">
                    <!-- Struktur Organisasi -->
                    <div id="content-struktur" class="content-section bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden">
                        <div class="p-2 sm:p-4 md:p-6">
                            @php
                                $strukturPath = public_path('images/struktur-organisasi.webp');
                                $strukturUrl = file_exists($strukturPath) ? asset('images/struktur-organisasi.webp') : null;
                            @endphp
                            @if($strukturUrl)
                                <div class="w-full">
                                    <img src="{{ $strukturUrl }}" 
                                         alt="Struktur Organisasi Bakesbangpol Kota Cimahi" 
                                         class="w-full h-auto object-contain rounded-lg">
                                    <p class="text-center text-[10px] sm:text-sm text-gray-500 mt-1.5 sm:mt-3">
                                        <i class="fas fa-info-circle text-red-500 mr-1"></i>
                                        Struktur Organisasi Badan Kesatuan Bangsa dan Politik Kota Cimahi
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-8 sm:py-16">
                                    <i class="fas fa-sitemap text-3xl sm:text-5xl md:text-6xl text-red-300 mb-2 sm:mb-4 block"></i>
                                    <p class="text-xs sm:text-base text-gray-500">Gambar Struktur Organisasi belum tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sejarah -->
                    <div id="content-sejarah" class="content-section bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden hidden">
                        <div class="p-3 sm:p-6 md:p-8">
                            <p class="text-gray-700 leading-relaxed text-justify text-xs sm:text-base">
                                Badan Kesatuan Bangsa dan Politik sebagai bagian dari Pemerintah di Kota Cimahi yang dibentuk dengan 
                                Berdasarkan Peraturan Daerah Kota Cimahi Nomor 6 Tahun 2016 tentang Pembentukan dan Susunan Perangkat Daerah 
                                Kota Cimahi jo Peraturan Daerah 6 Tahun 2015 tentang Lembaga Teknis Daerah Kota Cimahi, diperbaharui dengan 
                                Peraturan Wali Kota Cimahi Nomor 59 Tahun 2021 Tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi Serta 
                                Tata Kerja Perangkat Daerah Kota Cimahi dan Peraturan Walikota Cimahi Nomor 62 Tahun 2021, Tanggal 30 Desember 
                                2021 Tentang Tugas Fungsi dan Rincian Tugas Pada Perangkat Daerah Kota Cimahi, merupakan salah satu perangkat 
                                daerah yang mempunyai tugas pokok membantu Wali Kota dalam melaksanakan urusan pemerintahan di bidang kesatuan 
                                bangsa dan politik di wilayah Kota selain itu berdasarkan Peraturan Menteri Dalam Negeri Nomor 11 Tahun 2019 
                                tentang Perangkat Daerah Yang Melaksanakan Urusan Pemerintahan di Bidang Kesatuan Bangsa dan Politik (Kesbangpol) 
                                dan Keputusan Menteri Dalam Negeri Nomor 100-44 Tahun 2019 tentang Nomenklatur Perangkat Daerah Yang 
                                Melaksanakan Urusan Pemerintahan di Bidang Kesatuan Bangsa dan Politik (Kesbangpol).
                            </p>
                        </div>
                    </div>

                    <!-- Visi & Misi -->
                    <div id="content-visimisi" class="content-section bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden hidden">
                        <div class="p-3 sm:p-6 md:p-8">
                            <div class="mb-3 sm:mb-6">
                                <h3 class="text-base sm:text-xl font-bold text-red-600 mb-1.5 sm:mb-2">Visi</h3>
                                <div class="bg-red-50/80 border border-red-200 rounded-lg p-2 sm:p-4">
                                    <p class="text-gray-700 font-semibold text-center text-sm sm:text-lg">
                                        "CIMAHI KOTA CERDAS"
                                    </p>
                                </div>
                                <p class="text-gray-700 leading-relaxed text-justify text-xs sm:text-base mt-2 sm:mt-3">
                                    Dalam Visi RPJPD Kota Cimahi tahun 2005-2025: Kota Cimahi dengan segala potensi dan keterbatasannya 
                                    dituntut untuk menjadi kota yang CERDAS agar dapat bersaing dengan daerah-daerah lainnya. CERDAS, 
                                    "CIMAHI KOTA CERDAS" adalah mewujudkan kota yang mampu mengatasi rintangan dan ancaman yang timbul, 
                                    serta dapat mengambil kesempatan menjadi kota yang unggul, berbekal pada pengembangan kreativitas dalam 
                                    produksi, egaliter dalam kehidupan yang demokratis, serta didukung masyarakat yang religius akan berdaya 
                                    saing untuk dapat membangun kota yang terus maju dan berkembang menuju kemandirian pelayanan kota bagi 
                                    kehidupan yang lebih baik.
                                </p>
                            </div>

                            <div>
                                <h3 class="text-base sm:text-xl font-bold text-red-600 mb-1.5 sm:mb-3">Misi</h3>
                                <ol class="space-y-1.5 sm:space-y-3 text-xs sm:text-base text-gray-700 list-decimal list-inside text-justify">
                                    <li><span class="font-semibold">Creative</span> - Dapat berkreasi dalam bentuk aslinya serta produktif</li>
                                    <li><span class="font-semibold">Egalitarian</span> - Memandang kesamaan derajat manusia atau menjadi sifat dari demokratis</li>
                                    <li><span class="font-semibold">Religious</span> - Sifat kota yang agamis mengamalkan Sila Ketuhanan Yang Maha Esa</li>
                                    <li><span class="font-semibold">Developable</span> - Kota yang berkemampuan kompetitif untuk dibangun</li>
                                    <li><span class="font-semibold">Accretive</span> - Kota memiliki nilai tambah untuk terus maju dan berkembang</li>
                                    <li><span class="font-semibold">Sustainable</span> - Tercapainya kota yang dapat mencukupi kebutuhan warganya secara berkelanjutan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- TUPOKSI -->
                    <div id="content-tupoksi" class="content-section bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden hidden">
                        <div class="p-3 sm:p-6 md:p-8">
                            <p class="text-gray-700 leading-relaxed text-justify text-xs sm:text-base mb-2 sm:mb-4">
                                Berdasarkan Peraturan Wali Kota Nomor 62 Tahun 2021 tentang tugas fungsi dan rincian tugas pada perangkat 
                                daerah Kota Cimahi Badan Kesatuan Bangsa dan Politik Kota Cimahi adalah sebagai berikut:
                            </p>
                            
                            <div class="bg-gray-50/80 border border-gray-200 rounded-lg p-2 sm:p-4 mb-2 sm:mb-4">
                                <h4 class="font-semibold text-gray-800 text-xs sm:text-base mb-1 sm:mb-2">Tugas Pokok</h4>
                                <p class="text-gray-700 text-xs sm:text-base text-justify">
                                    Badan Kesatuan Bangsa dan Politik mempunyai tugas membantu Wali Kota melaksanakan Urusan Pemerintahan 
                                    di bidang kesatuan bangsa dan politik di wilayah Kota.
                                </p>
                            </div>

                            <div class="bg-gray-50/80 border border-gray-200 rounded-lg p-2 sm:p-4">
                                <h4 class="font-semibold text-gray-800 text-xs sm:text-base mb-1 sm:mb-2">Fungsi</h4>
                                <ol class="space-y-1 sm:space-y-2 text-xs sm:text-base text-gray-700 list-decimal list-inside text-justify">
                                    <li>Perumusan kebijakan teknis di bidang kesatuan bangsa dan politik di wilayah kabupaten/kota sesuai dengan ketentuan peraturan perundang-undangan;</li>
                                    <li>Pelaksanaan kebijakan di bidang pembinaan ideologi Pancasila dan wawasan kebangsaan, penyelenggaraan politik dalam negeri dan kehidupan demokrasi, pemeliharaan ketahanan ekonomi, sosial dan budaya, pembinaan kerukunan antarsuku dan intra suku, umat beragama, ras, dan golongan lainnya, pembinaan dan pemberdayaan organisasi kemasyarakatan, serta pelaksanaan kewaspadaan nasional dan penanganan konflik sosial di wilayah kota sesuai dengan ketentuan peraturan perundang-undangan;</li>
                                    <li>Pelaksanaan koordinasi di bidang pembinaan ideologi Pancasila dan wawasan kebangsaan, penyelenggaraan politik dalam negeri dan kehidupan demokrasi, pemeliharaan ketahanan ekonomi, sosial dan budaya, pembinaan kerukunan antarsuku dan intra suku, umat beragama, ras, dan golongan lainnya, fasilitasi organisasi kemasyarakatan, serta pelaksanaan kewaspadaan nasional dan penanganan konflik sosial di wilayah kota sesuai dengan ketentuan peraturan perundang-undangan;</li>
                                    <li>Pelaksanaan evaluasi dan pelaporan di bidang pembinaan ideologi Pancasila dan wawasan kebangsaan, penyelenggaraan politik dalam negeri dan kehidupan demokrasi, pemeliharaan ketahanan ekonomi, sosial dan budaya, pembinaan kerukunan antarsuku dan intra suku, umat beragama, ras, dan golongan lainnya, fasilitasi organisasi kemasyarakatan, serta pelaksanaan kewaspadaan nasional dan penanganan konflik sosial di wilayah kota sesuai dengan ketentuan peraturan perundang-undangan;</li>
                                    <li>Pelaksanaan fasilitasi forum koordinasi pimpinan daerah kota;</li>
                                    <li>Pelaksanaan administrasi kesekretariatan Badan Kesatuan Bangsa dan Politik; dan</li>
                                    <li>Pelaksanaan fungsi lain yang diberikan oleh wali kota.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Galeri -->
                    <div id="content-galeri" class="content-section bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden hidden">
                        <div class="p-2 sm:p-4 md:p-6">
                            @if(isset($galeris) && $galeris->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 sm:gap-3 md:gap-4">
                                @foreach($galeris as $item)
                                <div class="aspect-square rounded-lg overflow-hidden shadow-md hover:shadow-xl transition group relative">
                                    <img src="{{ asset('storage/' . $item->gambar) }}" 
                                         alt="{{ $item->judul }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-1.5 sm:p-3">
                                        <p class="text-white text-[8px] sm:text-xs font-medium line-clamp-1">{{ $item->judul }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4 sm:py-8">
                                <i class="fas fa-images text-2xl sm:text-4xl text-red-300 block mb-1.5 sm:mb-3"></i>
                                <p class="text-xs sm:text-base text-gray-500">Belum ada galeri</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan (30%) - Sticky -->
            <div class="lg:w-4/12">
                <div class="sticky top-24" style="position: sticky; top: 100px; align-self: flex-start; height: fit-content;">
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden">
                        <!-- Kontak -->
                        <div class="border-b border-gray-200 px-2 sm:px-4 md:px-5 py-2.5 sm:py-4 text-center">
                            <h3 class="text-[10px] sm:text-xs md:text-sm font-bold text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-3">Kontak</h3>
                            <div class="space-y-1 sm:space-y-2 text-[10px] sm:text-sm text-gray-600">
                                <div class="flex items-center justify-center gap-1.5 sm:gap-3">
                                    <i class="fas fa-phone text-red-500 w-3 sm:w-5 text-center text-[10px] sm:text-sm"></i>
                                    <span class="text-[10px] sm:text-sm">(022) 6654274</span>
                                </div>
                                <div class="flex items-center justify-center gap-1.5 sm:gap-3">
                                    <i class="fas fa-envelope text-red-500 w-3 sm:w-5 text-center text-[10px] sm:text-sm"></i>
                                    <span class="text-[9px] sm:text-xs md:text-sm break-all">bakesbangpol@cimahikota.go.id</span>
                                </div>
                            </div>
                        </div>

                        <!-- Jam Operasional -->
                        <div class="border-b border-gray-200 px-2 sm:px-4 md:px-5 py-2.5 sm:py-4 text-center">
                            <h3 class="text-[10px] sm:text-xs md:text-sm font-bold text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-3">Jam Operasional</h3>
                            <div class="space-y-0.5 sm:space-y-2 text-[10px] sm:text-sm text-gray-600">
                                <div class="flex items-center justify-center gap-1.5 sm:gap-3">
                                    <i class="fas fa-clock text-red-500 w-3 sm:w-5 text-center text-[10px] sm:text-sm"></i>
                                    <span class="text-[10px] sm:text-sm">Senin - Jumat: 08:00 - 16:00</span>
                                </div>
                                <div class="flex items-center justify-center gap-1.5 sm:gap-3">
                                    <i class="fas fa-clock text-red-500 w-3 sm:w-5 text-center text-[10px] sm:text-sm"></i>
                                    <span class="text-[10px] sm:text-sm">Sabtu: Tutup</span>
                                </div>
                                <div class="flex items-center justify-center gap-1.5 sm:gap-3">
                                    <i class="fas fa-clock text-red-500 w-3 sm:w-5 text-center text-[10px] sm:text-sm"></i>
                                    <span class="text-[10px] sm:text-sm">Minggu: Tutup</span>
                                </div>
                            </div>
                        </div>

                        <!-- Google Maps -->
                        <div class="px-1.5 sm:px-3 md:px-4 py-2.5 sm:py-4 text-center">
                            <h3 class="text-[10px] sm:text-xs md:text-sm font-bold text-gray-700 uppercase tracking-wider mb-1.5 sm:mb-3">Lokasi Kantor</h3>
                            <div class="aspect-video w-full rounded-lg overflow-hidden border border-gray-200">
                                <iframe 
                                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=Badan+Kesatuan+Bangsa+dan+Politik+Kota+Cimahi&center=-6.870367369920411,107.5547037129878&zoom=17&maptype=roadmap"
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            <div class="mt-1 sm:mt-2 text-[9px] sm:text-xs text-gray-500">
                                <i class="fas fa-map-pin text-red-500 mr-0.5 sm:mr-1"></i>
                                Bakesbangpol Kota Cimahi
                            </div>
                            <a href="https://www.google.com/maps/dir//Badan+Kesatuan+Bangsa+dan+Politik+Kota+Cimahi/@-6.870367369920411,107.5547037129878" 
                               target="_blank" 
                               class="mt-0.5 sm:mt-1 inline-flex items-center text-[10px] sm:text-xs md:text-sm text-red-600 hover:text-red-800 transition">
                                <i class="fas fa-directions mr-0.5 sm:mr-1"></i>
                                Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* === LINE CLAMP UNTUK GALERI === */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* === RESPONSIVE BREAKPOINT UNTUK XS (320px) === */
    @media (max-width: 374px) {
        .container {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .menu-btn {
            font-size: 9px !important;
            padding-left: 6px !important;
            padding-right: 6px !important;
            padding-top: 3px !important;
            padding-bottom: 3px !important;
            border-radius: 6px !important;
        }
        .menu-btn i {
            font-size: 8px !important;
            margin-right: 2px !important;
        }
        .menu-btn span {
            font-size: 8px !important;
        }
        
        /* Header */
        .relative.w-full.h-48 {
            height: 120px !important;
        }
        .relative.z-10.text-white.text-center.px-3 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .text-lg {
            font-size: 14px !important;
        }
        .text-\[10px\] {
            font-size: 8px !important;
        }
        .h-12 {
            height: 36px !important;
        }
        .w-10 {
            width: 32px !important;
        }
        
        /* Konten */
        .text-justify {
            text-align: justify !important;
            word-spacing: -0.5px !important;
        }
        .text-xs {
            font-size: 10px !important;
            line-height: 1.5 !important;
        }
        .text-\[10px\] {
            font-size: 8px !important;
        }
        .text-\[9px\] {
            font-size: 7px !important;
        }
        .text-\[8px\] {
            font-size: 7px !important;
        }
        
        /* Padding konten */
        .p-2 {
            padding: 6px !important;
        }
        .p-3 {
            padding: 8px !important;
        }
        .gap-1 {
            gap: 2px !important;
        }
        .py-1 {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
        }
        .px-2 {
            padding-left: 4px !important;
            padding-right: 4px !important;
        }
        .mb-1\.5 {
            margin-bottom: 4px !important;
        }
        .mt-1\.5 {
            margin-top: 4px !important;
        }
        .space-y-1 {
            gap: 2px !important;
        }
        
        /* Visi Misi */
        .text-base {
            font-size: 12px !important;
        }
        .text-sm {
            font-size: 10px !important;
        }
        .space-y-1\.5 {
            gap: 2px !important;
        }
        
        /* Galeri */
        .gap-1\.5 {
            gap: 4px !important;
        }
        .p-1\.5 {
            padding: 3px !important;
        }
        
        /* TUPOKSI */
        .space-y-1 {
            gap: 2px !important;
        }
    }

    /* === RESPONSIVE BREAKPOINT 375px === */
    @media (min-width: 375px) and (max-width: 424px) {
        .menu-btn {
            font-size: 10px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .menu-btn i {
            font-size: 9px !important;
        }
        .text-xs {
            font-size: 11px !important;
        }
        .text-\[10px\] {
            font-size: 9px !important;
        }
        .text-\[9px\] {
            font-size: 8px !important;
        }
    }

    /* === RESPONSIVE BREAKPOINT 425px === */
    @media (min-width: 425px) and (max-width: 640px) {
        .menu-btn {
            font-size: 11px !important;
        }
        .text-xs {
            font-size: 12px !important;
        }
    }

    /* === RESPONSIVE PADDING UNTUK KONTEN === */
    @media (max-width: 640px) {
        .text-justify {
            text-align: justify !important;
        }
    }

    /* === RESPONSIVE GAMBAR STRUKTUR === */
    #content-struktur img {
        max-width: 100%;
        height: auto;
    }
    
    @media (max-width: 640px) {
        #content-struktur .p-3 {
            padding: 6px !important;
        }
    }
</style>

<script>
    document.querySelectorAll('.menu-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            document.querySelectorAll('.menu-btn').forEach(b => {
                b.classList.remove('bg-red-600', 'text-white');
                b.classList.add('text-gray-700', 'hover:bg-red-50', 'hover:text-red-600');
            });
            
            this.classList.remove('text-gray-700', 'hover:bg-red-50', 'hover:text-red-600');
            this.classList.add('bg-red-600', 'text-white');
            
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });
            
            const target = this.dataset.target;
            const content = document.getElementById('content-' + target);
            if (content) {
                content.classList.remove('hidden');
            }
        });
    });

    // Set default aktif untuk Struktur
    document.addEventListener('DOMContentLoaded', function() {
        const defaultBtn = document.querySelector('.menu-btn[data-target="struktur"]');
        if (defaultBtn) {
            defaultBtn.click();
        }
    });
</script>
@endsection