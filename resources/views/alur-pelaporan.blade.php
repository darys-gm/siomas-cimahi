@extends('layouts.home')

@section('title', 'Alur Pelaporan ORMAS - SIOMAS Kota Cimahi')

@section('content')
<div class="container mx-auto px-4 py-12 mt-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">Alur Pelaporan Keberadaan ORMAS</h1>
            <p class="text-gray-600 text-lg">Panduan lengkap tata cara pendaftaran Organisasi Masyarakat (ORMAS) di Kota Cimahi</p>
            <div class="w-20 h-1 bg-red-600 mx-auto mt-3 rounded-full"></div>
        </div>

        <!-- Alur Pendaftaran - Grid 2x2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- ============================================ -->
            <!-- LANGKAH 1: Mengisi Form Pelaporan              -->
            <!-- ============================================ -->
            <div class="text-center group">
                <div class="w-65 h-65 mx-auto mb-4">
                    @php
                        $imgPath1 = public_path('images/img_p2.png');
                        $imgUrl1 = file_exists($imgPath1) ? asset('images/img_p2.png') : null;
                    @endphp
                    @if($imgUrl1)
                        <img src="{{ $imgUrl1 }}" alt="Langkah 1" class="w-full h-full object-contain group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full bg-red-100 flex items-center justify-center rounded-2xl">
                            <span class="text-6xl font-bold text-red-400">1</span>
                        </div>
                    @endif
                </div>
                <div class="px-2">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">1</span>
                        <h3 class="font-bold text-base text-gray-800">Mengisi Form Pelaporan</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed max-w-xs mx-auto">
                        Mengisi data ORMAS melalui form pelaporan ormas yang tersedia di website SIOMAS. 
                        Data akan diverifikasi oleh admin Kesbangpol Kota Cimahi.
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('pelaporan-ormas.create') }}" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-file-alt mr-1"></i>
                            Isi Form Pelaporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- LANGKAH 2: Mengisi Surat Permohonan           -->
            <!-- ============================================ -->
            <div class="text-center group">
                <div class="w-65 h-65 mx-auto mb-4">
                    @php
                        $imgPath2 = public_path('images/img_p1.png');
                        $imgUrl2 = file_exists($imgPath2) ? asset('images/img_p1.png') : null;
                    @endphp
                    @if($imgUrl2)
                        <img src="{{ $imgUrl2 }}" alt="Langkah 2" class="w-full h-full object-contain group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full bg-green-100 flex items-center justify-center rounded-2xl">
                            <span class="text-6xl font-bold text-green-400">2</span>
                        </div>
                    @endif
                </div>
                <div class="px-2">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">2</span>
                        <h3 class="font-bold text-base text-gray-800">Mengajukan Surat Permohonan</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed max-w-xs mx-auto">
                        Mengisi surat permohonan pelaporan ormas untuk dibawa ke kantor Bakesbangpol beserta dokumen pendukung lainnya. 
                        Surat permohonan dan dokumen pelaporan di-print dan dibawa saat verifikasi fisik.
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('download.formulir-ormas') }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-download mr-1"></i>
                            Download Formulir
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- LANGKAH 3: Verifikasi Dokumen                 -->
            <!-- ============================================ -->
            <div class="text-center group">
                <div class="w-65 h-65 mx-auto mb-4">
                    @php
                        $imgPath3 = public_path('images/img_p3.png');
                        $imgUrl3 = file_exists($imgPath3) ? asset('images/img_p3.png') : null;
                    @endphp
                    @if($imgUrl3)
                        <img src="{{ $imgUrl3 }}" alt="Langkah 3" class="w-full h-full object-contain group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full bg-yellow-100 flex items-center justify-center rounded-2xl">
                            <span class="text-6xl font-bold text-yellow-400">3</span>
                        </div>
                    @endif
                </div>
                <div class="px-2">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">3</span>
                        <h3 class="font-bold text-base text-gray-800">Verifikasi Dokumen dan Lapangan</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed max-w-xs mx-auto">
                        Membawa berkas dokumen persyaratan fisik termasuk surat permohonan pelaporan ormas 
                        ke kantor Kesbangpol Kota Cimahi untuk verifikasi langsung dan petugas akan melakukan survei lapangan.
                    </p>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- LANGKAH 4: Selesai                           -->
            <!-- ============================================ -->
            <div class="text-center group">
                <div class="w-65 h-65 mx-auto mb-4">
                    @php
                        $imgPath4 = public_path('images/img_p4.png');
                        $imgUrl4 = file_exists($imgPath4) ? asset('images/img_p4.png') : null;
                    @endphp
                    @if($imgUrl4)
                        <img src="{{ $imgUrl4 }}" alt="Langkah 4" class="w-full h-full object-contain group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full bg-green-100 flex items-center justify-center rounded-2xl">
                            <span class="text-6xl font-bold text-green-400">4</span>
                        </div>
                    @endif
                </div>
                <div class="px-2">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <span class="flex-shrink-0 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs">4</span>
                        <h3 class="font-bold text-base text-gray-800">Selesai</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed max-w-xs mx-auto">
                        ORMAS terverifikasi dan terdaftar secara resmi telah melaporkan keberadaanya di Kota Cimahi. 
                        Anda akan mendapatkan surat keterangan tanggapan keberadaan ormas
                        dari Kantor Kesbangpol.
                    </p>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECTION PEMBERITAHUAN TARIF GRATIS            -->
        <!-- ============================================ -->
        <section id="pemberitahuan" class="py-5 md:py-3 bg-red-600 overflow-hidden scroll-animate mt-12">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-center gap-6 md:gap-10 max-w-5xl mx-auto">
                    <!-- Maskot - Muncul dari Kiri -->
                    <div class="maskot-container flex-shrink-0">
                        @php
                            $maskotPath = public_path('images/maskot1.png');
                            $maskotUrl = file_exists($maskotPath) ? asset('images/maskot1.png') : null;
                        @endphp
                        @if($maskotUrl)
                            <img src="{{ $maskotUrl }}" 
                                 alt="Maskot" 
                                 class="maskot-image w-60 h-60 md:w-60 md:h-60 lg:w-70 lg:h-70 object-contain drop-shadow-2xl">
                        @else
                            <div class="w-48 h-48 md:w-56 md:h-56 lg:w-64 lg:h-64 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-gem text-white text-6xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Teks - Muncul dari Bawah -->
                    <div class="text-container text-center md:text-left">
                        <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-2">
                            Tarif Pelayanan <span class="text-orange-400">100% Gratis</span>
                        </h2>
                        <p class="text-white/90 text-sm md:text-base lg:text-lg max-w-lg leading-relaxed">
                            Pelayanan pendataan ORMAS di Kota Cimahi tidak dipungut biaya apapun. 
                            Sepenuhnya gratis untuk masyarakat.
                        </p>
                        <div class="flex flex-wrap items-center gap-3 mt-3 text-white/80 text-xs md:text-sm">
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

        <!-- Dokumen Persyaratan - Tanpa Background Berwarna -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Dokumen Persyaratan</h2>
            
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 text-center">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle text-red-500 mr-2"></i>
                    Dokumen dibawa ke kantor Kesbangpol untuk verifikasi fisik
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div class="flex items-start p-2 bg-gray-50 rounded">
                    <span class="text-sm font-semibold text-gray-700 mr-2">1.</span>
                    <span class="text-sm text-gray-700">Surat permohonan pelaporan ormas</span>
                </div>
                <div class="flex items-start p-2 bg-gray-50 rounded">
                    <span class="text-sm font-semibold text-gray-700 mr-2">2.</span>
                    <span class="text-sm text-gray-700">Surat keterangan domisili kelurahan dan kecamatan</span>
                </div>
                <div class="flex items-start p-2 bg-gray-50 rounded">
                    <span class="text-sm font-semibold text-gray-700 mr-2">3.</span>
                    <span class="text-sm text-gray-700">Surat Keterangan AHU dari Kemenkumham atau SKT Kemendagri</span>
                </div>
                <div class="flex items-start p-2 bg-gray-50 rounded">
                    <span class="text-sm font-semibold text-gray-700 mr-2">4.</span>
                    <span class="text-sm text-gray-700">SK Kepengurusan terbaru</span>
                </div>
                <div class="flex items-start p-2 bg-gray-50 rounded">
                    <span class="text-sm font-semibold text-gray-700 mr-2">5.</span>
                    <span class="text-sm text-gray-700">Akta Pendirian notaris disertai AD, ART</span>
                </div>
                <div class="flex items-start p-2 bg-gray-50 rounded">
                    <span class="text-sm font-semibold text-gray-700 mr-2">6.</span>
                    <span class="text-sm text-gray-700">Fotokopi KTP, Pas Foto dan Biodata pengurus (KSB)</span>
                </div>
                <div class="flex items-start p-2 bg-gray-50 rounded">
                    <span class="text-sm font-semibold text-gray-700 mr-2">7.</span>
                    <span class="text-sm text-gray-700">Program Kerja</span>
                </div>
                <div class="flex items-start p-2 bg-gray-50 rounded">
                    <span class="text-sm font-semibold text-gray-700 mr-2">8.</span>
                    <span class="text-sm text-gray-700">Dokumen pendukung lainnya</span>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- CSS TAMBAHAN                                 -->
<!-- ============================================ -->
<style>
    /* ============================================ */
    /* SECTION PEMBERITAHUAN TARIF GRATIS            */
    /* ============================================ */
    #pemberitahuan {
        position: relative;
        overflow: hidden;
    }

    /* Maskot - Muncul dari Kiri */
    .maskot-container {
        opacity: 0;
        transform: translateX(-80px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .maskot-container.visible {
        opacity: 1;
        transform: translateX(0);
    }

    /* Teks - Muncul dari Bawah */
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

    /* Efek gemerlap pada maskot */
    .maskot-image {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Tambahan efek bintang berkilau */
    #pemberitahuan::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    /* Scroll Animation */
    .scroll-animate {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scroll-animate.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<!-- ============================================ -->
<!-- JAVASCRIPT UNTUK SCROLL ANIMATION            -->
<!-- ============================================ -->
<script>
    (function() {
        'use strict';

        // ===== SCROLL ANIMATION =====
        function handleScrollAnimation() {
            const elements = document.querySelectorAll('.scroll-animate');
            
            elements.forEach(function(el) {
                const rect = el.getBoundingClientRect();
                const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                
                if (rect.top <= windowHeight - 100) {
                    el.classList.add('visible');
                } else {
                    el.classList.remove('visible');
                }
            });

            // ===== ANIMASI KHUSUS UNTUK MASKOT & TEKS =====
            const maskotContainer = document.querySelector('.maskot-container');
            const textContainer = document.querySelector('.text-container');
            
            if (maskotContainer) {
                const rect = maskotContainer.getBoundingClientRect();
                const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                if (rect.top <= windowHeight - 100) {
                    maskotContainer.classList.add('visible');
                } else {
                    maskotContainer.classList.remove('visible');
                }
            }
            
            if (textContainer) {
                const rect = textContainer.getBoundingClientRect();
                const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                if (rect.top <= windowHeight - 100) {
                    textContainer.classList.add('visible');
                } else {
                    textContainer.classList.remove('visible');
                }
            }
        }

        // Jalankan saat scroll
        window.addEventListener('scroll', handleScrollAnimation);
        
        // Jalankan saat load
        window.addEventListener('load', function() {
            setTimeout(handleScrollAnimation, 300);
        });

        // Jalankan saat resize
        window.addEventListener('resize', handleScrollAnimation);
    })();
</script>
@endsection