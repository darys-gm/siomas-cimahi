@extends('layouts.home')

@section('title', 'Beranda - SIOMAS Kota Cimahi')

@section('content')
<!-- ============================================ -->
<!-- LOADING SPINNER - CSS LOADER                -->
<!-- ============================================ -->
<div id="loadingSpinner" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/95 backdrop-blur-sm transition-opacity duration-500">
    <div class="text-center">
        <div class="mt-6">
            @php
                $logoPath = public_path('images/logo-siomas.png');
                $logoUrl = file_exists($logoPath) ? asset('images/logo-siomas1.png') : null;
            @endphp
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="SIOMAS" class="h-14 mx-auto animate-pulse">
            @else
                <div class="text-3xl font-bold text-red-600 animate-pulse">SIOMAS</div>
            @endif
        </div>
        <p class="text-gray-400 text-xs mt-4 animate-pulse">Memuat halaman...</p>
        <div class="loader mx-auto"></div>
    </div>
</div>

<!-- ============================================ -->
<!-- CONTENT HOME                                 -->
<!-- ============================================ -->
<div id="homeContent" class="opacity-0 transition-opacity duration-700">
    <!-- Hero Section -->
    <section id="beranda" class="relative h-[400px] sm:h-[450px] md:h-[500px] lg:h-[600px] flex items-center overflow-hidden -mt-6">
        <div class="absolute inset-0 w-full h-full">
            <div class="hero-slide active" style="background-image: url('{{ asset('images/bg-image1.webp') }}');"></div>
            <div class="hero-slide" style="background-image: url('{{ asset('images/bg-image2.webp') }}');"></div>
            <div class="hero-slide" style="background-image: url('{{ asset('images/bg-image3.webp') }}');"></div>
            <div class="hero-slide" style="background-image: url('{{ asset('images/bg-image4.webp') }}');"></div>
            
            <div class="absolute inset-0 bg-red-700/50"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-red-800/80 via-red-700/60 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                @php
                    $runningText = $runningText ?? 'Selamat datang di SIOMAS Kota Cimahi - Sistem Informasi Organisasi Masyarakat';
                    $runningSpeed = $runningSpeed ?? 20;
                @endphp
                <div class="bg-orange-500/20 backdrop-blur-sm border border-orange-400/30 rounded-lg px-3 py-1 mb-3 md:mb-4 overflow-hidden inline-block w-full" style="margin-top: -10px;">
                    <div class="running-text-container">
                        <div class="running-text-wrapper" style="animation-duration: {{ $runningSpeed }}s;">
                            <p class="running-text text-white text-xs sm:text-sm md:text-base font-medium">
                                <i class="fas fa-bullhorn text-orange-300 mr-2"></i>
                                {{ $runningText }}
                                <i class="fas fa-bullhorn text-orange-300 ml-2"></i>
                            </p>
                            <p class="running-text text-white text-xs sm:text-sm md:text-base font-medium">
                                <i class="fas fa-bullhorn text-orange-300 mr-2"></i>
                                {{ $runningText }}
                                <i class="fas fa-bullhorn text-orange-300 ml-2"></i>
                            </p>
                        </div>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-3 leading-tight text-white scroll-animate">
                    Selamat datang di SIOMAS Kota Cimahi
                </h1>

                <h3 class="text-sm sm:text-base md:text-lg lg:text-xl font-medium text-white/90 leading-relaxed scroll-animate max-w-1xl mx-auto px-2">
                    Layanan digital resmi Badan Kesatuan Bangsa dan Politik (Bakesbangpol) Kota Cimahi untuk pendataan, transparansi, dan sinergi organisasi kemasyarakatan demi mewujudkan Cimahi yang kolaboratif, kondusif, maju, dan harmonis.
                </h3>
                
                <div class="flex flex-wrap gap-3 md:gap-4 mt-4 md:mt-6 justify-center scroll-animate">
                    <a href="{{ route('pelaporan-ormas.create') }}" 
                       class="px-4 py-2 sm:px-5 sm:py-2.5 md:px-6 md:py-3 bg-white text-red-600 rounded-lg font-semibold hover:bg-red-50 transition shadow-lg hover:shadow-xl text-sm sm:text-base">
                        <i class="fas fa-file-alt mr-2"></i> Daftar
                    </a>
                    <a href="{{ route('alur-pelaporan') }}" 
                       class="px-4 py-2 sm:px-5 sm:py-2.5 md:px-6 md:py-3 bg-white/20 backdrop-blur-sm border border-white/30 text-white rounded-lg font-semibold hover:bg-white/30 transition text-sm sm:text-base">
                        <i class="fas fa-book mr-2"></i> Panduan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- SECTION POSTER - MENGGUNAKAN SWIPER.JS     -->
    <!-- ============================================ -->
    @if(isset($posters) && $posters->count() > 0)
    <section id="poster" class="py-6 sm:py-8 md:py-10 bg-gradient-to-br from-red-50 to-white scroll-animate">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="relative max-w-7xl mx-auto">
                <div class="swiper posterSwiper overflow-hidden rounded-xl py-3 sm:py-4">
                    <div class="swiper-wrapper">
                        @foreach($posters as $poster)
                        <div class="swiper-slide">
                            <div class="relative rounded-xl overflow-hidden bg-white shadow-lg">
                                <img src="{{ asset('storage/' . $poster->gambar) }}" 
                                     alt="{{ $poster->judul }}" 
                                     class="w-full h-auto object-cover aspect-[16/9]">
                                @if($poster->link)
                                <a href="{{ $poster->link }}" target="_blank" 
                                   class="absolute bottom-3 right-3 bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-700 transition opacity-0 hover:opacity-100">
                                    <i class="fas fa-external-link-alt mr-1"></i> Lihat
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination !relative !mt-4"></div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ============================================ -->
    <!-- ALUR PELAPORAN                               -->
    <!-- ============================================ -->
    <section id="alur-pelaporan" class="py-8 sm:py-10 md:py-12 bg-red-50 scroll-animate">
        <div class="container mx-auto px-4">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-red-700 scroll-animate">Alur Pelaporan Keberadaan ORMAS</h2>
                <p class="text-gray-600 text-base sm:text-lg mt-2 scroll-animate">Panduan lengkap tata cara pendaftaran Organisasi Masyarakat (ORMAS) di Kota Cimahi</p>
                <div class="w-20 h-1 bg-red-500 mx-auto mt-3 rounded-full scroll-animate"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 max-w-5xl mx-auto">
                @php
                    $imgPaths = ['img_p2.png', 'img_p1.png', 'img_p3.png', 'img_p4.png'];
                    $labels = ['Mengisi Form Pelaporan', 'Mengajukan Surat Permohonan', 'Verifikasi Dokumen dan Lapangan', 'Selesai'];
                    $descs = [
                        'Mengisi data ORMAS melalui form pelaporan yang tersedia di website SIOMAS Cimahi',
                        'Dokumen ajuan surat permohonan dan dokumen pendukung lainnya dibawa ke kantor Bakesbangpol Cimahi',
                        'Petugas akan memverifikasi dokumen dan survei lapangan',
                        'Ormas sudah terverifikasi dan terdata telah melaporkan keberadaannya di Kota Cimahi'
                    ];
                @endphp
                @foreach($imgPaths as $index => $imgPath)
                <div class="text-center group scroll-animate">
                    <div class="w-48 h-40 sm:w-56 sm:h-44 md:w-60 md:h-50 mx-auto mb-3 md:mb-4 rounded-2xl overflow-hidden bg-white">
                        @php
                            $imgUrl = file_exists(public_path('images/' . $imgPath)) ? asset('images/' . $imgPath) : null;
                        @endphp
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}" alt="Langkah {{ $index+1 }}" class="w-full h-full object-contain group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full bg-red-100 flex items-center justify-center">
                                <span class="text-4xl sm:text-5xl md:text-6xl font-bold text-red-400">{{ $index+1 }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="px-2">
                        <div class="flex items-center justify-center gap-2 sm:gap-3 mb-2">
                            <span class="flex-shrink-0 w-6 h-6 sm:w-7 sm:h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-[10px] sm:text-xs">{{ $index+1 }}</span>
                            <h3 class="font-bold text-sm sm:text-base text-gray-800">{{ $labels[$index] }}</h3>
                        </div>
                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed max-w-xs mx-auto">{{ $descs[$index] }}</p>
                        @if($index == 0)
                        <div class="mt-2 sm:mt-3">
                            <a href="{{ route('pelaporan-ormas.create') }}" class="inline-flex items-center px-2.5 py-1.5 sm:px-3 sm:py-1.5 bg-red-600 text-white text-[10px] sm:text-xs rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-file-alt mr-1"></i> Isi Form Pelaporan
                            </a>
                        </div>
                        @endif
                        @if($index == 1)
                        <div class="mt-2 sm:mt-3">
                            <a href="{{ route('download.formulir-ormas') }}" 
                               class="inline-flex items-center px-2.5 py-1.5 sm:px-3 sm:py-1.5 bg-green-600 text-white text-[10px] sm:text-xs rounded-lg hover:bg-green-700 transition">
                                <i class="fas fa-download mr-1"></i> Download Formulir
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-6 sm:mt-8 scroll-animate">
                <a href="{{ route('alur-pelaporan') }}" class="inline-flex items-center px-5 py-2.5 sm:px-6 sm:py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition text-sm sm:text-base">
                    Lihat Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- SECTION PEMBERITAHUAN TARIF GRATIS           -->
    <!-- ============================================ -->
    <section id="pemberitahuan" class="py-4 sm:py-5 md:py-3 bg-red-600 overflow-hidden scroll-animate">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-6 lg:gap-10 max-w-5xl mx-auto">
                <div class="maskot-container flex-shrink-0">
                    @php
                        $maskotUrl = file_exists(public_path('images/maskot1.png')) ? asset('images/maskot1.png') : null;
                    @endphp
                    @if($maskotUrl)
                        <img src="{{ $maskotUrl }}" 
                             alt="Maskot" 
                             class="maskot-image w-40 h-40 sm:w-48 sm:h-48 md:w-56 md:h-56 lg:w-60 lg:h-60 object-contain drop-shadow-2xl">
                    @else
                        <div class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 lg:w-56 lg:h-56 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-gem text-white text-4xl sm:text-5xl md:text-6xl"></i>
                        </div>
                    @endif
                </div>

                <div class="text-container text-center md:text-left">
                    <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-1 sm:mb-2">
                        Tarif Pelayanan <span class="text-orange-400">100% Gratis</span>
                    </h2>
                    <p class="text-white/90 text-xs sm:text-sm md:text-base lg:text-lg max-w-lg leading-relaxed">
                        Pelayanan pendataan ORMAS di Kota Cimahi tidak dipungut biaya apapun. 
                        Sepenuhnya gratis untuk masyarakat.
                    </p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 sm:gap-3 mt-2 sm:mt-3 text-white/80 text-[10px] sm:text-xs md:text-sm">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-check-circle text-orange-400"></i>
                            Transparan
                        </span>
                        <span class="w-1 h-1 bg-white/40 rounded-full"></span>
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-check-circle text-orange-400"></i>
                            Profesional
                        </span>
                        <span class="w-1 h-1 bg-white/40 rounded-full"></span>
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-check-circle text-orange-400"></i>
                            Terpercaya
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- SECTION BERITA + AGENDA (LAYOUT SEJAJAR)   -->
    <!-- ============================================ -->
    @php
        date_default_timezone_set('Asia/Jakarta');
        $todayDate = date('Y-m-d');
        $todayFull = date('l, d F Y');
        $todayDay = date('d');
        $todayMonth = strtoupper(date('M'));
        $currentMonthName = date('F Y');
    @endphp

    <section id="berita-agenda" class="py-8 sm:py-10 md:py-12 bg-gray-50 scroll-animate">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-4 sm:mb-6 md:mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 section-title-red scroll-animate">Berita Terkini</h2>
                <a href="{{ route('berita.semua') }}" class="text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium flex items-center gap-1 scroll-animate">
                    Lihat semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-stretch">
                <!-- KOLOM 1: Gambar Berita Besar (5/12) -->
                <div class="lg:col-span-5 relative scroll-animate">
                    <div class="relative overflow-hidden rounded-xl bg-white shadow-lg h-full min-h-[280px] lg:min-h-[400px]">
                        <div id="beritaSlider" class="absolute inset-0">
                            @php
                                $sliderBeritas = isset($beritas) ? $beritas->take(5) : collect();
                            @endphp
                            @if($sliderBeritas->count() > 0)
                                @foreach($sliderBeritas as $index => $berita)
                                <div class="berita-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" style="position: absolute; inset: 0;">
                                    @if($berita->gambar)
                                    <img src="{{ asset('storage/' . $berita->gambar) }}" 
                                         alt="{{ $berita->judul }}" 
                                         class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full bg-gradient-to-r from-red-600 to-red-800 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-4xl sm:text-5xl md:text-6xl text-white/30"></i>
                                    </div>
                                    @endif
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent">
                                        <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 md:p-5">
                                            <div class="flex items-center gap-2 sm:gap-3 mb-1 sm:mb-2">
                                                <span class="bg-red-600 text-white text-[8px] sm:text-xs font-semibold px-2 py-0.5 sm:px-3 sm:py-1 rounded-full">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    {{ $berita->published_at ? $berita->published_at->format('d F Y') : $berita->created_at->format('d F Y') }}
                                                </span>
                                                <span class="text-white/70 text-[8px] sm:text-xs">
                                                    <i class="fas fa-user mr-1"></i> {{ $berita->user->name ?? 'Admin' }}
                                                </span>
                                            </div>
                                            <a href="{{ route('berita.show', $berita->slug ?? $berita->id) }}" class="block">
                                                <h3 class="text-sm sm:text-lg md:text-xl font-bold text-white hover:text-red-400 transition line-clamp-2">
                                                    {{ $berita->judul }}
                                                </h3>
                                            </a>
                                            <p class="text-white/80 text-[10px] sm:text-xs mt-1 sm:mt-2 line-clamp-2 max-w-2xl hidden sm:block">
                                                {{ $berita->excerpt ?? strip_tags($berita->konten) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <p class="text-gray-500 text-sm">Belum ada berita</p>
                                </div>
                            @endif
                        </div>

                        @if($sliderBeritas->count() > 1)
                        <button onclick="prevBeritaSlide()" 
                                class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center transition z-20">
                            <i class="fas fa-chevron-left text-sm sm:text-base"></i>
                        </button>
                        <button onclick="nextBeritaSlide()" 
                                class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center transition z-20">
                            <i class="fas fa-chevron-right text-sm sm:text-base"></i>
                        </button>
                        <div class="absolute bottom-2 sm:bottom-4 left-1/2 -translate-x-1/2 flex gap-1 sm:gap-2 z-20">
                            @foreach($sliderBeritas as $index => $berita)
                            <button onclick="goToBeritaSlide({{ $index }})" 
                                    class="berita-dot w-2 h-2 sm:w-3 sm:h-3 rounded-full transition {{ $index === 0 ? 'bg-white' : 'bg-white/40 hover:bg-white/60' }}"
                                    data-index="{{ $index }}">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- KOLOM 2: Berita Lainnya (3/12) -->
                <div class="lg:col-span-3 scroll-animate">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 h-full flex flex-col">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-700 border-b border-gray-200 pb-2 flex items-center gap-2 flex-shrink-0">
                            <i class="fas fa-list-ul text-red-600"></i>
                            Berita Lainnya
                        </h3>
                        
                        @php
                            $sidebarBeritas = isset($beritas) ? $beritas->skip(5)->take(5) : collect();
                        @endphp
                        
                        @if($sidebarBeritas->count() > 0)
                            <div class="space-y-1.5 sm:space-y-2 mt-2 sm:mt-3 overflow-y-auto pr-1 flex-1 max-h-[280px] lg:max-h-none">
                                @foreach($sidebarBeritas as $berita)
                                <a href="{{ route('berita.show', $berita->slug ?? $berita->id) }}" 
                                   class="block group hover:bg-red-50 rounded-lg p-1.5 sm:p-2 transition border-l-3 border-transparent hover:border-red-500">
                                    <h4 class="text-xs sm:text-sm font-medium text-gray-800 group-hover:text-red-600 transition line-clamp-2">
                                        {{ $berita->judul }}
                                    </h4>
                                    <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $berita->published_at ? $berita->published_at->format('d F Y') : $berita->created_at->format('d F Y') }}
                                    </p>
                                </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-400 text-sm">Belum ada berita lainnya</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- KOLOM 3: AGENDA KEGIATAN (4/12) -->
                <div class="lg:col-span-4 scroll-animate">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 h-full flex flex-col">
                        <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3 flex-shrink-0">
                            <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-red-600 rounded-xl flex flex-col items-center justify-center text-white text-center shadow-md">
                                <span id="selectedDayHeader" class="text-lg sm:text-xl font-bold leading-none">{{ date('d') }}</span>
                                <span id="selectedMonthHeader" class="text-[6px] sm:text-[8px] font-bold uppercase leading-none">{{ date('M') }}</span>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-800">Agenda Kegiatan</h3>
                                <p id="currentMonthDisplay" class="text-[10px] sm:text-xs text-gray-500">{{ $currentMonthName }}</p>
                            </div>
                        </div>

                        <div class="mb-2 sm:mb-3 overflow-x-auto pb-1 sm:pb-2 flex-shrink-0">
                            <div id="calendarHorizontal" class="flex gap-0.5 sm:gap-1 min-w-max">
                            </div>
                        </div>

                        <div id="agendaListContainer" class="bg-gray-50 rounded-lg p-2 sm:p-3 overflow-y-auto flex-shrink-0 max-h-[150px] sm:max-h-[180px] lg:max-h-[200px]">
                            @if(isset($todayAgendas) && $todayAgendas->count() > 0)
                                <div id="agendaItems" class="space-y-1.5 sm:space-y-2">
                                    @foreach($todayAgendas as $agenda)
                                    <div class="flex flex-col p-1.5 sm:p-2 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition group agenda-item" data-agenda-id="{{ $agenda->id }}">
                                        <div class="flex items-start gap-2 sm:gap-3">
                                            <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-red-600 rounded-lg flex flex-col items-center justify-center text-white text-center shadow-sm mt-0.5">
                                                <span class="text-sm sm:text-base font-bold leading-none">{{ date('d', strtotime($agenda->tanggal)) }}</span>
                                                <span class="text-[6px] sm:text-[7px] font-bold uppercase leading-none">{{ date('M', strtotime($agenda->tanggal)) }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-[10px] sm:text-xs font-semibold text-gray-800 group-hover:text-red-600 transition agenda-title line-clamp-1" 
                                                    data-full-text="{{ $agenda->judul }}">
                                                    {{ strlen($agenda->judul) > 40 ? substr($agenda->judul, 0, 40) . '...' : $agenda->judul }}
                                                </h4>
                                                <p class="text-[8px] sm:text-[9px] text-gray-400">
                                                    {{ date('l, d F Y', strtotime($agenda->tanggal)) }}
                                                </p>
                                            </div>
                                            @php
                                                $isUpcoming = strtotime($agenda->tanggal) >= strtotime(date('Y-m-d'));
                                            @endphp
                                            <span class="flex-shrink-0 text-[7px] sm:text-[8px] font-medium px-1.5 py-0.5 sm:px-2 rounded-full {{ $isUpcoming ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                                <i class="fas {{ $isUpcoming ? 'fa-clock' : 'fa-check-circle' }} mr-0.5"></i>
                                                {{ $isUpcoming ? 'Akan Datang' : 'Selesai' }}
                                            </span>
                                        </div>
                                        
                                        <div class="mt-1 ml-10 sm:ml-12">
                                            @if($agenda->deskripsi)
                                            <div class="agenda-description hidden text-[9px] sm:text-[10px] text-gray-600 border-t border-gray-100 pt-1.5">
                                                {{ $agenda->deskripsi }}
                                            </div>
                                            @endif
                                            
                                            @if(strlen($agenda->judul) > 40 || ($agenda->deskripsi && strlen($agenda->deskripsi) > 50))
                                            <div class="flex justify-end mt-1">
                                                <button onclick="toggleAgendaExpand(this)" 
                                                        class="text-[8px] sm:text-[9px] text-red-600 hover:text-red-800 font-medium transition agenda-toggle-btn hover:underline">
                                                    Lihat Selengkapnya
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div id="agendaItems" class="text-center py-4 sm:py-6">
                                    <div class="relative w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2">
                                        <div class="absolute inset-0 bg-gray-200 rounded-full"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs sm:text-sm font-medium text-gray-600">Tidak ada agenda</p>
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Tidak ada kegiatan/event di hari ini</p>
                                </div>
                            @endif
                        </div>
                        
                        @if(isset($todayAgendas) && $todayAgendas->count() > 2)
                        <div class="text-center mt-1.5 text-[8px] sm:text-[9px] text-gray-400 flex-shrink-0">
                            <i class="fas fa-chevron-down mr-1"></i>
                            Scroll untuk melihat lebih banyak agenda ({{ $todayAgendas->count() }} agenda)
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- SECTION GALERI - DENGAN FILTER KATEGORI      -->
    <!-- ============================================ -->
    @if((isset($galerisBakesbangpol) && $galerisBakesbangpol->count() > 0) || (isset($galerisOrmas) && $galerisOrmas->count() > 0))
    <section id="galeri" class="py-8 sm:py-10 md:py-12 bg-gray-50 scroll-animate">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Galeri</h2>
                <div class="w-20 h-1 bg-red-500 mx-auto mt-3 rounded-full"></div>
                <p class="text-gray-500 text-xs sm:text-sm mt-2">Dokumentasi kegiatan Bakesbangpol & Ormas Kota Cimahi</p>
            </div>

            <!-- Filter Tab Kategori -->
            <div class="flex justify-center mb-6 sm:mb-8">
                <div class="inline-flex bg-white rounded-full shadow-sm border border-gray-200 p-1">
                    <button type="button" 
                            onclick="filterGaleri('bakesbangpol')" 
                            id="tab-bakesbangpol"
                            class="galeri-tab active px-3 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 flex items-center gap-1.5">
                        <i class="fas fa-building"></i>
                        <span>Bakesbangpol</span>
                    </button>
                    <button type="button" 
                            onclick="filterGaleri('ormas')" 
                            id="tab-ormas"
                            class="galeri-tab px-3 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 flex items-center gap-1.5">
                        <i class="fas fa-users"></i>
                        <span>Ormas</span>
                    </button>
                </div>
            </div>

            <!-- Galeri Bakesbangpol (Default Tampil) -->
            <div id="galeri-bakesbangpol" class="galeri-content">
                @if(isset($galerisBakesbangpol) && $galerisBakesbangpol->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
                        @foreach($galerisBakesbangpol as $item)
                        <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition aspect-square cursor-pointer galeri-item"
                             onclick="openLightbox(
                                 '{{ asset('storage/' . $item->gambar) }}',
                                 '{{ addslashes($item->judul) }}',
                                 '{{ addslashes($item->deskripsi ?? '') }}'
                             )">
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 alt="{{ $item->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center transform group-hover:scale-110 transition duration-300 shadow-lg border border-white/30">
                                    <i class="fas fa-search-plus text-white text-sm sm:text-base md:text-xl"></i>
                                </div>
                            </div>
                            
                            <div class="absolute bottom-0 left-0 right-0 p-2 sm:p-3 bg-gradient-to-t from-black/80 to-transparent">
                                <h4 class="text-white font-semibold text-[10px] sm:text-xs md:text-sm line-clamp-1">{{ $item->judul }}</h4>
                                @if($item->deskripsi)
                                <p class="text-white/80 text-[8px] sm:text-[10px] md:text-xs line-clamp-1">{{ $item->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-xl">
                        <i class="fas fa-images text-4xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500 text-sm">Belum ada galeri Bakesbangpol</p>
                    </div>
                @endif
            </div>

            <!-- Galeri Ormas (Hidden by default) -->
            <div id="galeri-ormas" class="galeri-content hidden">
                @if(isset($galerisOrmas) && $galerisOrmas->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
                        @foreach($galerisOrmas as $item)
                        <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition aspect-square cursor-pointer galeri-item"
                             onclick="openLightbox(
                                 '{{ asset('storage/' . $item->gambar) }}',
                                 '{{ addslashes($item->judul) }}',
                                 '{{ addslashes($item->deskripsi ?? '') }}'
                             )">
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 alt="{{ $item->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center transform group-hover:scale-110 transition duration-300 shadow-lg border border-white/30">
                                    <i class="fas fa-search-plus text-white text-sm sm:text-base md:text-xl"></i>
                                </div>
                            </div>
                            
                            <div class="absolute bottom-0 left-0 right-0 p-2 sm:p-3 bg-gradient-to-t from-black/80 to-transparent">
                                <h4 class="text-white font-semibold text-[10px] sm:text-xs md:text-sm line-clamp-1">{{ $item->judul }}</h4>
                                @if($item->deskripsi)
                                <p class="text-white/80 text-[8px] sm:text-[10px] md:text-xs line-clamp-1">{{ $item->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-xl">
                        <i class="fas fa-images text-4xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500 text-sm">Belum ada galeri Ormas</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- ============================================ -->
    <!-- LIGHTBOX MODAL - DENGAN JUDUL & DESKRIPSI   -->
    <!-- ============================================ -->
    <div id="lightboxModal" class="fixed inset-0 bg-black/95 z-[9999] hidden flex items-center justify-center p-3 sm:p-4" onclick="closeLightbox()">
        <button onclick="closeLightbox()" class="absolute top-3 right-3 sm:top-4 sm:right-4 text-white/70 hover:text-white text-2xl sm:text-3xl transition z-10 w-10 h-10 flex items-center justify-center hover:bg-white/10 rounded-full">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="relative max-w-5xl w-full max-h-[95vh] flex flex-col rounded-xl overflow-hidden" onclick="event.stopPropagation()">
            <!-- Gambar -->
            <div class="flex-1 flex items-center justify-center overflow-hidden bg-black/50">
                <img id="lightboxImage" src="" alt="" class="w-full h-auto max-h-[70vh] object-contain">
            </div>
            
            <!-- Info Panel -->
            <div class="bg-white p-4 sm:p-6 max-h-[25vh] overflow-y-auto" id="lightboxInfoPanel">
                <h3 class="text-base sm:text-xl font-bold text-gray-800 mb-2 flex items-start gap-2">
                    <span id="lightboxTitleText">Judul Galeri</span>
                </h3>
                <div id="lightboxDescriptionWrapper" class="hidden">
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed whitespace-pre-line" id="lightboxDescription"></p>
                </div>
                <p id="lightboxEmptyDescription" class="hidden text-gray-400 text-xs sm:text-sm italic">
                    <i class="fas fa-info-circle mr-1"></i> Tidak ada deskripsi untuk galeri ini.
                </p>
            </div>
        </div>
    </div>

    <!-- Link Terkait -->
    <section id="link-terkait" class="py-8 sm:py-10 md:py-12 bg-white scroll-animate">
        <div class="container mx-auto px-4">
            <div class="text-center mb-6 sm:mb-8 md:mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 scroll-animate">Link Terkait</h2>
                <div class="w-20 h-1 bg-red-500 mx-auto mt-3 rounded-full scroll-animate"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8 max-w-4xl mx-auto">
                @php
                    $links = [
                        ['url' => 'https://polpum.kemendagri.go.id/', 'logo' => 'lg-link-1.png', 'name' => 'Polpum Kemendagri'],
                        ['url' => 'https://bakesbangpol.jabarprov.go.id/', 'logo' => 'lg-link-2.png', 'name' => 'Bakesbangpol Jabar'],
                        ['url' => 'https://cimahikota.go.id/beranda', 'logo' => 'lg-link-3.png', 'name' => 'Kota Cimahi'],
                        ['url' => 'https://bakesbangpol.cimahikota.go.id/sejarah', 'logo' => 'lg-link-4.png', 'name' => 'Bakesbangpol Cimahi'],
                    ];
                @endphp
                @foreach($links as $link)
                <a href="{{ $link['url'] }}" target="_blank" 
                   class="text-center group hover:scale-105 transition duration-300 scroll-animate">
                    <div class="flex justify-center mb-2">
                        @php
                            $logoUrl = file_exists(public_path('images/' . $link['logo'])) ? asset('images/' . $link['logo']) : null;
                        @endphp
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $link['name'] }}" class="w-16 h-16 sm:w-20 sm:h-20 object-contain">
                        @else
                            <i class="fas fa-external-link-alt text-3xl sm:text-4xl text-gray-400 group-hover:text-red-500"></i>
                        @endif
                    </div>
                    <h4 class="font-semibold text-gray-700 group-hover:text-red-600 transition text-xs sm:text-sm">{{ $link['name'] }}</h4>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- SECTION STATISTIK PENGUNJUNG (SIMPEL)        -->
    <!-- ============================================ -->
    <section id="statistik-pengunjung" class="py-6 sm:py-8 bg-gradient-to-r from-red-600 to-red-700 scroll-animate">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <div class="text-center mb-4 sm:mb-5">
                    <h2 class="text-lg sm:text-xl font-bold text-white inline-flex items-center gap-2">
                        <i class="fas fa-chart-line"></i>
                        Statistik Pengunjung
                    </h2>
                    <p class="text-white/70 text-xs mt-1">Data kunjungan website SIOMAS Kota Cimahi</p>
                </div>

                <div class="grid grid-cols-3 gap-2 sm:gap-4">
                    
                    <div class="bg-white/15 backdrop-blur-sm rounded-xl p-3 sm:p-4 hover:bg-white/25 transition text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <i class="fas fa-calendar-day text-white/80 text-sm sm:text-base"></i>
                            <span class="text-white/80 text-[10px] sm:text-xs font-medium">Hari Ini</span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-white tabular-nums">
                            {{ number_format($visitorStats['today'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-white/60 text-[9px] sm:text-[10px] mt-1">
                            {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d M Y') }}
                        </div>
                    </div>

                    <div class="bg-white/15 backdrop-blur-sm rounded-xl p-3 sm:p-4 hover:bg-white/25 transition text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <i class="fas fa-calendar-alt text-white/80 text-sm sm:text-base"></i>
                            <span class="text-white/80 text-[10px] sm:text-xs font-medium">Bulan Ini</span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-white tabular-nums">
                            {{ number_format($visitorStats['month'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-white/60 text-[9px] sm:text-[10px] mt-1">
                            {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('F Y') }}
                        </div>
                    </div>

                    <div class="bg-white/15 backdrop-blur-sm rounded-xl p-3 sm:p-4 hover:bg-white/25 transition text-center">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <i class="fas fa-calendar-check text-white/80 text-sm sm:text-base"></i>
                            <span class="text-white/80 text-[10px] sm:text-xs font-medium">Tahun Ini</span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-bold text-white tabular-nums">
                            {{ number_format($visitorStats['year'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-white/60 text-[9px] sm:text-[10px] mt-1">
                            Tahun {{ \Carbon\Carbon::now('Asia/Jakarta')->year }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ============================================ -->
<!-- SWIPER.JS CSS & JS                          -->
<!-- ============================================ -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- ============================================ -->
<!-- CSS TAMBAHAN                                 -->
<!-- ============================================ -->
<style>
    #loadingSpinner {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        transition: opacity 0.5s ease;
    }
    #loadingSpinner.hidden {
        opacity: 0;
        pointer-events: none;
    }

    .loader {
        width: calc(80px / cos(45deg));
        height: 14px;
        background: repeating-linear-gradient(-45deg, #dc2626 0 15px, #0000 0 20px) left/200% 100%;
        animation: l3 2s infinite linear;
        border-radius: 2px;
        margin: 0 auto;
    }
    @keyframes l3 {
        100% { background-position: right; }
    }

    .animate-spin {
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .hero-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 1.2s ease-in-out;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .hero-slide.active {
        opacity: 1;
    }
    
    .running-text-container {
        overflow: hidden;
        white-space: nowrap;
    }
    .running-text-wrapper {
        display: flex;
        gap: 2rem;
        animation: scrollTextWrapper 20s linear infinite;
        width: max-content;
    }
    .running-text {
        display: inline-block;
        white-space: nowrap;
        flex-shrink: 0;
    }
    @keyframes scrollTextWrapper {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    .scroll-animate {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scroll-animate.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .posterSwiper {
        padding-bottom: 10px;
    }
    
    .posterSwiper .swiper-slide {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }
    
    .posterSwiper .swiper-slide:hover {
        transform: scale(1.02);
    }
    
    .posterSwiper .swiper-slide img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .posterSwiper .swiper-pagination {
        position: relative !important;
        margin-top: 16px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 8px !important;
    }
    
    .posterSwiper .swiper-pagination-bullet {
        width: 12px !important;
        height: 12px !important;
        background: #d1d5db !important;
        opacity: 1 !important;
        border-radius: 50% !important;
        transition: all 0.3s ease !important;
        cursor: pointer !important;
        border: 2px solid transparent !important;
    }
    
    .posterSwiper .swiper-pagination-bullet:hover {
        background: #fca5a5 !important;
        transform: scale(1.1);
    }
    
    .posterSwiper .swiper-pagination-bullet-active {
        background: #dc2626 !important;
        width: 32px !important;
        border-radius: 20px !important;
        border-color: #dc2626 !important;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.4) !important;
    }
    
    @media (max-width: 640px) {
        .posterSwiper .swiper-pagination-bullet {
            width: 10px !important;
            height: 10px !important;
        }
        .posterSwiper .swiper-pagination-bullet-active {
            width: 24px !important;
        }
        .posterSwiper .swiper-pagination {
            gap: 6px !important;
            margin-top: 12px !important;
        }
    }
    
    @media (max-width: 374px) {
        .posterSwiper .swiper-pagination-bullet {
            width: 8px !important;
            height: 8px !important;
        }
        .posterSwiper .swiper-pagination-bullet-active {
            width: 20px !important;
        }
        .posterSwiper .swiper-pagination {
            gap: 4px !important;
            margin-top: 10px !important;
        }
    }
    
    .berita-slide {
        display: none;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }
    .berita-slide.active {
        display: block;
        opacity: 1;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .section-title-red {
        position: relative;
        padding-bottom: 10px;
    }
    .section-title-red::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: #dc2626;
        border-radius: 2px;
    }

    .maskot-container {
        opacity: 0;
        transform: translateX(-80px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .maskot-container.visible {
        opacity: 1;
        transform: translateX(0);
    }
    .text-container {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        transition-delay: 0.3s;
    }
    .text-container.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .maskot-image {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .galeri-item {
        cursor: pointer;
    }
    .galeri-item .fa-search-plus {
        transition: all 0.3s ease;
    }
    .galeri-item:hover .fa-search-plus {
        transform: scale(1.1);
    }
    
    #lightboxModal {
        animation: lightboxFadeIn 0.3s ease;
    }
    @keyframes lightboxFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    #lightboxImage {
        animation: lightboxZoomIn 0.3s ease;
    }
    @keyframes lightboxZoomIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* ============================================ */
    /* GALERI FILTER TABS                          */
    /* ============================================ */
    .galeri-tab {
        background: transparent;
        color: #6b7280;
    }
    .galeri-tab:hover:not(.active) {
        color: #dc2626;
        background: #fef2f2;
    }
    .galeri-tab.active {
        background: #dc2626;
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
    .galeri-content {
        animation: fadeInGallery 0.4s ease-in-out;
    }
    @keyframes fadeInGallery {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============================================ */
    /* LIGHTBOX INFO PANEL ANIMATION                */
    /* ============================================ */
    #lightboxInfoPanel {
        animation: slideUpInfo 0.4s ease-out;
    }
    @keyframes slideUpInfo {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    #lightboxInfoPanel::-webkit-scrollbar {
        width: 4px;
    }
    #lightboxInfoPanel::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    #lightboxInfoPanel::-webkit-scrollbar-track {
        background: transparent;
    }

    /* ============================================ */
    /* KALENDER HORIZONTAL STYLE                   */
    /* ============================================ */
    #calendarHorizontal {
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb transparent;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 4px;
        scroll-behavior: smooth;
    }
    #calendarHorizontal::-webkit-scrollbar {
        height: 3px;
    }
    #calendarHorizontal::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 4px;
    }
    #calendarHorizontal::-webkit-scrollbar-track {
        background: transparent;
    }

    .calendar-day-horizontal {
        min-width: 36px;
        height: 44px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        background: transparent;
        font-size: 0.7rem;
        font-weight: 500;
        color: #374151;
        padding: 2px 0;
        flex-shrink: 0;
    }
    @media (min-width: 640px) {
        .calendar-day-horizontal {
            min-width: 44px;
            height: 54px;
            font-size: 0.75rem;
        }
    }
    .calendar-day-horizontal:hover:not(.empty) {
        background: #fef2f2;
        border-color: #fca5a5;
    }
    .calendar-day-horizontal .day-number {
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 1.2;
    }
    @media (min-width: 640px) {
        .calendar-day-horizontal .day-number {
            font-size: 1rem;
        }
    }
    .calendar-day-horizontal .day-name {
        font-size: 0.45rem;
        font-weight: 400;
        color: #9ca3af;
        text-transform: uppercase;
    }
    @media (min-width: 640px) {
        .calendar-day-horizontal .day-name {
            font-size: 0.55rem;
        }
    }
    .calendar-day-horizontal.empty {
        cursor: default;
        color: #d1d5db;
        opacity: 0.4;
    }
    .calendar-day-horizontal.today {
        border-color: #dc2626;
        background: #fef2f2;
        color: #1a1a1a;
    }
    .calendar-day-horizontal.selected {
        border-color: #dc2626;
        background: #dc2626;
        color: white;
    }
    .calendar-day-horizontal.selected .day-name {
        color: rgba(255,255,255,0.7);
    }
    .calendar-day-horizontal.has-agenda {
        position: relative;
    }
    .calendar-day-horizontal.has-agenda::after {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        background: #dc2626;
        border-radius: 50%;
    }
    .calendar-day-horizontal.today.has-agenda::after {
        background: #dc2626;
    }
    .calendar-day-horizontal.selected.has-agenda::after {
        background: white;
    }

    .agenda-item {
        transition: all 0.3s ease;
    }

    .agenda-item.expanded {
        background: #fef2f2;
        border-color: #fca5a5;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
    }

    .agenda-item .agenda-title {
        transition: all 0.3s ease;
    }

    .agenda-item.expanded .agenda-title {
        white-space: normal !important;
        -webkit-line-clamp: unset !important;
        overflow: visible !important;
        line-height: 1.4;
    }

    .agenda-description {
        animation: fadeInExpand 0.3s ease;
    }

    @keyframes fadeInExpand {
        from {
            opacity: 0;
            transform: translateY(-5px);
            max-height: 0;
        }
        to {
            opacity: 1;
            transform: translateY(0);
            max-height: 200px;
        }
    }

    .agenda-toggle-btn {
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .agenda-toggle-btn:hover {
        transform: scale(1.05);
    }

    .agenda-item.expanded .agenda-toggle-btn {
        color: #b91c1c;
    }

    #agendaListContainer {
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb transparent;
    }
    #agendaListContainer::-webkit-scrollbar {
        width: 4px;
    }
    #agendaListContainer::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 4px;
    }
    #agendaListContainer::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }

    .border-l-3 {
        border-left-width: 3px;
    }

    html, body {
        scroll-behavior: auto !important;
        overflow-anchor: none !important;
    }
    * {
        scroll-margin-top: 0 !important;
        scroll-margin-bottom: 0 !important;
    }
    :target {
        scroll-margin-top: 0 !important;
    }
</style>

<!-- ============================================ -->
<!-- JAVASCRIPT                                   -->
<!-- ============================================ -->
<script>
    (function() {
        'use strict';

        window.scrollTo(0, 0);
        
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        const agendaData = @json($allAgendas ?? []);
        
        let currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let currentMonth = currentDate.getMonth();
        let selectedDate = new Date(currentYear, currentMonth, currentDate.getDate());

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const shortDayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        function getAgendasForDate(date) {
            const dateStr = formatDate(date);
            return agendaData.filter(item => {
                if (!item.tanggal) return false;
                const itemDate = new Date(item.tanggal);
                return formatDate(itemDate) === dateStr;
            });
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        }

        function formatDateDisplay(date) {
            return dayNames[date.getDay()] + ', ' + String(date.getDate()).padStart(2, '0') + ' ' + monthNames[date.getMonth()] + ' ' + date.getFullYear();
        }

        function renderHorizontalCalendar(year, month) {
            const container = document.getElementById('calendarHorizontal');
            const today = new Date();
            const todayDate = today.getDate();
            const todayMonth = today.getMonth();
            const todayYear = today.getFullYear();

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            container.innerHTML = '';

            const startDay = firstDay === 0 ? 6 : firstDay - 1;
            for (let i = startDay - 1; i >= 0; i--) {
                const day = daysInPrevMonth - i;
                const div = document.createElement('div');
                div.className = 'calendar-day-horizontal empty';
                div.innerHTML = `
                    <span class="day-number">${day}</span>
                    <span class="day-name">${shortDayNames[(i) % 7]}</span>
                `;
                container.appendChild(div);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const currentDateObj = new Date(year, month, day);
                const isToday = day === todayDate && month === todayMonth && year === todayYear;
                const isSelected = day === selectedDate.getDate() && month === selectedDate.getMonth() && year === selectedDate.getFullYear();
                const hasAgenda = getAgendasForDate(currentDateObj).length > 0;
                const dayOfWeek = currentDateObj.getDay();

                const div = document.createElement('div');
                div.className = 'calendar-day-horizontal';
                if (isToday) div.classList.add('today');
                if (isSelected) div.classList.add('selected');
                if (hasAgenda) div.classList.add('has-agenda');

                div.innerHTML = `
                    <span class="day-number">${day}</span>
                    <span class="day-name">${shortDayNames[dayOfWeek]}</span>
                `;

                div.addEventListener('click', function() {
                    selectDate(year, month, day);
                });

                container.appendChild(div);
            }

            const totalCells = startDay + daysInMonth;
            const remainingCells = (7 - (totalCells % 7)) % 7;
            for (let day = 1; day <= remainingCells; day++) {
                const div = document.createElement('div');
                div.className = 'calendar-day-horizontal empty';
                div.innerHTML = `
                    <span class="day-number">${day}</span>
                    <span class="day-name">${shortDayNames[(day) % 7]}</span>
                `;
                container.appendChild(div);
            }

            document.getElementById('currentMonthDisplay').textContent = monthNames[month] + ' ' + year;

            setTimeout(function() {
                scrollToSelectedDate();
            }, 200);
        }

        function scrollToSelectedDate() {
            const container = document.getElementById('calendarHorizontal');
            if (!container) return;
            
            let targetElement = container.querySelector('.calendar-day-horizontal.selected');
            if (!targetElement) {
                targetElement = container.querySelector('.calendar-day-horizontal.today');
            }
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
        }

        function selectDate(year, month, day) {
            selectedDate = new Date(year, month, day);
            renderHorizontalCalendar(year, month);
            updateAgendaListHorizontal(year, month, day);
            updateHeaderDate(year, month, day);
        }

        function updateHeaderDate(year, month, day) {
            const dayDisplay = document.getElementById('selectedDayHeader');
            const monthDisplay = document.getElementById('selectedMonthHeader');
            
            dayDisplay.textContent = String(day).padStart(2, '0');
            monthDisplay.textContent = monthNames[month].substring(0, 3).toUpperCase();
        }

        function updateAgendaListHorizontal(year, month, day) {
            const dateObj = new Date(year, month, day);
            const agendas = getAgendasForDate(dateObj);
            const container = document.getElementById('agendaItems');

            if (agendas.length > 0) {
                let html = '';
                agendas.forEach(function(agenda) {
                    const agendaDate = new Date(agenda.tanggal);
                    const isUpcoming = agendaDate >= new Date();
                    const statusClass = isUpcoming ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400';
                    const statusIcon = isUpcoming ? 'fa-clock' : 'fa-check-circle';
                    const statusText = isUpcoming ? 'Akan Datang' : 'Selesai';
                    const title = agenda.judul || '';
                    const truncatedTitle = title.length > 40 ? title.substring(0, 40) + '...' : title;
                    const showToggle = title.length > 40 || (agenda.deskripsi && agenda.deskripsi.length > 50);

                    html += `
                        <div class="flex flex-col p-1.5 sm:p-2 bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition group agenda-item" data-agenda-id="${agenda.id}">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-red-600 rounded-lg flex flex-col items-center justify-center text-white text-center shadow-sm mt-0.5">
                                    <span class="text-sm sm:text-base font-bold leading-none">${String(agendaDate.getDate()).padStart(2, '0')}</span>
                                    <span class="text-[6px] sm:text-[7px] font-bold uppercase leading-none">${monthNames[agendaDate.getMonth()].substring(0, 3)}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[10px] sm:text-xs font-semibold text-gray-800 group-hover:text-red-600 transition agenda-title line-clamp-1" 
                                        data-full-text="${title.replace(/"/g, '&quot;')}">
                                        ${truncatedTitle}
                                    </h4>
                                    <p class="text-[8px] sm:text-[9px] text-gray-400">
                                        ${formatDateDisplay(agendaDate)}
                                    </p>
                                </div>
                                <span class="flex-shrink-0 text-[7px] sm:text-[8px] font-medium px-1.5 py-0.5 sm:px-2 rounded-full ${statusClass}">
                                    <i class="fas ${statusIcon} mr-0.5"></i>
                                    ${statusText}
                                </span>
                            </div>
                            
                            <div class="mt-1 ml-10 sm:ml-12">
                                ${agenda.deskripsi ? `<div class="agenda-description hidden text-[9px] sm:text-[10px] text-gray-600 border-t border-gray-100 pt-1.5">${agenda.deskripsi}</div>` : ''}
                                
                                ${showToggle ? `
                                <div class="flex justify-end mt-1">
                                    <button onclick="toggleAgendaExpand(this)" 
                                            class="text-[8px] sm:text-[9px] text-red-600 hover:text-red-800 font-medium transition agenda-toggle-btn hover:underline">
                                        Lihat Selengkapnya
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                    <div class="text-center py-4 sm:py-6">
                        <div class="relative w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2">
                            <div class="absolute inset-0 bg-gray-200 rounded-full"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Tidak ada agenda</p>
                        <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Tidak ada kegiatan/event pada tanggal ini</p>
                    </div>
                `;
            }
        }

        function goToToday() {
            const today = new Date();
            currentYear = today.getFullYear();
            currentMonth = today.getMonth();
            selectDate(currentYear, currentMonth, today.getDate());
            renderHorizontalCalendar(currentYear, currentMonth);
        }

        window.toggleAgendaExpand = function(button) {
            const agendaItem = button.closest('.agenda-item');
            const title = agendaItem.querySelector('.agenda-title');
            const description = agendaItem.querySelector('.agenda-description');
            const fullText = title.getAttribute('data-full-text') || title.textContent;
            
            agendaItem.classList.toggle('expanded');
            
            if (agendaItem.classList.contains('expanded')) {
                title.textContent = fullText;
                title.classList.remove('line-clamp-1');
                
                if (description) {
                    description.classList.remove('hidden');
                }
                
                button.textContent = 'Sembunyikan';
                button.classList.add('text-red-700');
            } else {
                const truncatedText = fullText.length > 40 ? fullText.substring(0, 40) + '...' : fullText;
                title.textContent = truncatedText;
                title.classList.add('line-clamp-1');
                
                if (description) {
                    description.classList.add('hidden');
                }
                
                button.textContent = 'Lihat Selengkapnya';
                button.classList.remove('text-red-700');
            }
        };

        document.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('.agenda-toggle-btn');
            if (toggleBtn) {
                const currentItem = toggleBtn.closest('.agenda-item');
                const allItems = document.querySelectorAll('.agenda-item');
                
                allItems.forEach(function(item) {
                    if (item !== currentItem && item.classList.contains('expanded')) {
                        const otherBtn = item.querySelector('.agenda-toggle-btn');
                        if (otherBtn) {
                            const otherTitle = item.querySelector('.agenda-title');
                            const otherDesc = item.querySelector('.agenda-description');
                            const fullText = otherTitle.getAttribute('data-full-text') || otherTitle.textContent;
                            
                            item.classList.remove('expanded');
                            const truncatedText = fullText.length > 40 ? fullText.substring(0, 40) + '...' : fullText;
                            otherTitle.textContent = truncatedText;
                            otherTitle.classList.add('line-clamp-1');
                            if (otherDesc) otherDesc.classList.add('hidden');
                            otherBtn.textContent = 'Lihat Selengkapnya';
                            otherBtn.classList.remove('text-red-700');
                        }
                    }
                });
            }
        });

        function hideLoadingSpinner() {
            var spinner = document.getElementById('loadingSpinner');
            var content = document.getElementById('homeContent');
            if (spinner) spinner.classList.add('hidden');
            if (content) {
                content.classList.remove('opacity-0');
                content.classList.add('opacity-100');
            }
            window.scrollTo(0, 0);
        }

        if (document.readyState === 'complete') {
            setTimeout(hideLoadingSpinner, 500);
        } else {
            window.addEventListener('load', function() {
                setTimeout(hideLoadingSpinner, 500);
            });
        }
        setTimeout(function() {
            var spinner = document.getElementById('loadingSpinner');
            if (spinner && !spinner.classList.contains('hidden')) {
                hideLoadingSpinner();
            }
        }, 3000);

        window.addEventListener('load', function() {
            setTimeout(function() { window.scrollTo(0, 0); }, 100);
            setTimeout(function() { window.scrollTo(0, 0); }, 300);
            setTimeout(function() { window.scrollTo(0, 0); }, 600);
        });

        function handleScrollAnimation() {
            document.querySelectorAll('.scroll-animate').forEach(function(el) {
                var rect = el.getBoundingClientRect();
                var windowHeight = window.innerHeight || document.documentElement.clientHeight;
                if (rect.top <= windowHeight - 100) {
                    el.classList.add('visible');
                } else {
                    el.classList.remove('visible');
                }
            });
            var maskot = document.querySelector('.maskot-container');
            var text = document.querySelector('.text-container');
            if (maskot) {
                var rect = maskot.getBoundingClientRect();
                var windowHeight = window.innerHeight || document.documentElement.clientHeight;
                if (rect.top <= windowHeight - 100) {
                    maskot.classList.add('visible');
                } else {
                    maskot.classList.remove('visible');
                }
            }
            if (text) {
                var rect = text.getBoundingClientRect();
                var windowHeight = window.innerHeight || document.documentElement.clientHeight;
                if (rect.top <= windowHeight - 100) {
                    text.classList.add('visible');
                } else {
                    text.classList.remove('visible');
                }
            }
        }
        window.addEventListener('scroll', handleScrollAnimation);
        window.addEventListener('load', function() { setTimeout(handleScrollAnimation, 300); });
        window.addEventListener('resize', handleScrollAnimation);

        document.addEventListener('DOMContentLoaded', function() {
            const swiperEl = document.querySelector('.posterSwiper');
            if (swiperEl) {
                const slides = swiperEl.querySelectorAll('.swiper-slide');
                if (slides.length > 0) {
                    const swiper = new Swiper('.posterSwiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        centeredSlides: true,
                        loop: true,
                        autoplay: {
                            delay: 10000,
                            disableOnInteraction: true,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                            dynamicBullets: false,
                        },
                        breakpoints: {
                            320: { slidesPerView: 1.2, spaceBetween: 12, centeredSlides: true },
                            425: { slidesPerView: 1.5, spaceBetween: 16, centeredSlides: true },
                            641: { slidesPerView: 2.2, spaceBetween: 20, centeredSlides: true },
                            769: { slidesPerView: 2.5, spaceBetween: 24, centeredSlides: true },
                            1025: { slidesPerView: 3, spaceBetween: 24, centeredSlides: true },
                        },
                        on: {
                            init: function() {
                                document.querySelector('.posterSwiper .swiper-pagination').classList.add('!relative', '!mt-4');
                            }
                        }
                    });
                }
            }
        });

        var heroSlides = document.querySelectorAll('.hero-slide');
        var heroCurrentIndex = 0;
        var heroTotalSlides = heroSlides.length;
        var heroInterval = null;
        function goToHeroSlide(index) {
            heroSlides.forEach(function(slide) { slide.classList.remove('active'); });
            if (heroSlides[index]) heroSlides[index].classList.add('active');
            heroCurrentIndex = index;
        }
        function nextHeroSlide() {
            var nextIndex = (heroCurrentIndex + 1) % heroTotalSlides;
            goToHeroSlide(nextIndex);
        }
        function startHeroSlide() {
            if (heroInterval) clearInterval(heroInterval);
            if (heroTotalSlides > 1) {
                heroInterval = setInterval(nextHeroSlide, 4000);
            }
        }
        heroSlides.forEach(function(slide) {
            var bgImage = slide.style.backgroundImage;
            var url = bgImage.replace(/url\(["']?(.*?)["']?\)/, '$1');
            if (url) {
                var img = new Image();
                img.onerror = function() {
                    slide.style.backgroundImage = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
                };
                img.src = url;
            }
        });
        startHeroSlide();

        var beritaSlides = document.querySelectorAll('.berita-slide');
        var beritaCurrentIndex = 0;
        var beritaTotalSlides = beritaSlides.length;
        var beritaInterval = null;
        var INTERVAL_TIME = 4000;
        function showBeritaSlide(index) {
            beritaSlides.forEach(function(slide) { slide.classList.remove('active'); });
            if (beritaSlides[index]) beritaSlides[index].classList.add('active');
            document.querySelectorAll('.berita-dot').forEach(function(dot, i) {
                if (i === index) {
                    dot.classList.add('bg-white');
                    dot.classList.remove('bg-white/40', 'hover:bg-white/60');
                } else {
                    dot.classList.remove('bg-white');
                    dot.classList.add('bg-white/40', 'hover:bg-white/60');
                }
            });
            beritaCurrentIndex = index;
        }
        function nextBeritaSlide() {
            var nextIndex = (beritaCurrentIndex + 1) % beritaTotalSlides;
            showBeritaSlide(nextIndex);
        }
        function prevBeritaSlide() {
            var prevIndex = (beritaCurrentIndex - 1 + beritaTotalSlides) % beritaTotalSlides;
            showBeritaSlide(prevIndex);
        }
        function goToBeritaSlide(index) {
            showBeritaSlide(index);
            resetBeritaInterval();
        }
        function startBeritaInterval() {
            if (beritaInterval) clearInterval(beritaInterval);
            if (beritaTotalSlides > 1) {
                beritaInterval = setInterval(nextBeritaSlide, INTERVAL_TIME);
            }
        }
        function stopBeritaInterval() {
            if (beritaInterval) { clearInterval(beritaInterval); beritaInterval = null; }
        }
        function resetBeritaInterval() { stopBeritaInterval(); startBeritaInterval(); }
        if (beritaTotalSlides > 0) {
            showBeritaSlide(0);
            startBeritaInterval();
            var sliderContainer = document.getElementById('beritaSlider');
            if (sliderContainer) {
                sliderContainer.addEventListener('mouseenter', stopBeritaInterval);
                sliderContainer.addEventListener('mouseleave', startBeritaInterval);
            }
        }
        window.nextBeritaSlide = nextBeritaSlide;
        window.prevBeritaSlide = prevBeritaSlide;
        window.goToBeritaSlide = goToBeritaSlide;

        // ============================================
        // LIGHTBOX GALERI - DENGAN JUDUL & DESKRIPSI
        // ============================================
        window.openLightbox = function(imageUrl, title, description) {
            var modal = document.getElementById('lightboxModal');
            var img = document.getElementById('lightboxImage');
            var titleText = document.getElementById('lightboxTitleText');
            var descWrapper = document.getElementById('lightboxDescriptionWrapper');
            var descEl = document.getElementById('lightboxDescription');
            var emptyDescEl = document.getElementById('lightboxEmptyDescription');

            // Set gambar
            img.src = imageUrl;

            // Set judul
            titleText.textContent = title && title.trim() !== '' ? title : 'Galeri';

            // Set deskripsi
            if (description && description.trim() !== '') {
                descEl.textContent = description;
                descWrapper.classList.remove('hidden');
                emptyDescEl.classList.add('hidden');
            } else {
                descWrapper.classList.add('hidden');
                emptyDescEl.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };

        window.closeLightbox = function() {
            var modal = document.getElementById('lightboxModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';

            // Reset konten setelah modal tertutup
            setTimeout(function() {
                document.getElementById('lightboxImage').src = '';
                document.getElementById('lightboxTitleText').textContent = '';
                document.getElementById('lightboxDescription').textContent = '';
            }, 300);
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });

        // ============================================
        // FILTER GALERI BY KATEGORI
        // ============================================
        window.filterGaleri = function(kategori) {
            const contents = document.querySelectorAll('.galeri-content');
            contents.forEach(function(el) {
                el.classList.add('hidden');
            });

            const tabs = document.querySelectorAll('.galeri-tab');
            tabs.forEach(function(el) {
                el.classList.remove('active');
            });

            const target = document.getElementById('galeri-' + kategori);
            if (target) {
                target.classList.remove('hidden');
                target.style.animation = 'none';
                setTimeout(() => { target.style.animation = 'fadeInGallery 0.4s ease-in-out'; }, 10);
            }

            const activeTab = document.getElementById('tab-' + kategori);
            if (activeTab) {
                activeTab.classList.add('active');
            }
        };

        const today = new Date();
        renderHorizontalCalendar(today.getFullYear(), today.getMonth());
        selectDate(today.getFullYear(), today.getMonth(), today.getDate());

        window.goToToday = goToToday;
        window.selectDate = selectDate;

    })();
</script>
@endsection