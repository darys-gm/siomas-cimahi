@extends('layouts.home')

@section('title', $produkHukum->judul . ' - SIOMAS Kota Cimahi')

@section('content')
<!-- Pembungkus utama dengan z-0 agar konten tidak menembus navbar -->
<div class="relative min-h-screen py-8 sm:py-12 z-0">
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full z-0">
        <img src="{{ asset('images/bg-pola.png') }}" 
             alt="Background" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-white/70 via-white/80 to-white/90"></div>
    </div>
    
    <!-- Kontainer Konten -->
    <div class="container mx-auto px-3 sm:px-4 relative z-10">
        <div class="flex flex-col lg:flex-row gap-4 sm:gap-6">
            <!-- Kolom Kiri: PDF Viewer (70%) -->
            <div class="lg:w-8/12">
                <!-- Breadcrumb -->
                <nav class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
                    <a href="{{ route('home') }}" class="hover:text-red-600 transition">Beranda</a>
                    <span class="mx-1 sm:mx-2">/</span>
                    <a href="{{ route('produk-hukum.index') }}" class="hover:text-red-600 transition">Produk Hukum</a>
                    <span class="mx-1 sm:mx-2">/</span>
                    <span class="text-gray-700 font-medium">{{ Str::limit($produkHukum->judul, 40) }}</span>
                </nav>

                <!-- Judul -->
                <div class="mb-3 sm:mb-4">
                    <h1 class="text-lg sm:text-xl font-bold text-gray-800">{{ $produkHukum->judul }}</h1>
                    @if($produkHukum->keterangan)
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $produkHukum->keterangan }}</p>
                    @endif
                </div>

                <!-- PDF Viewer -->
                @if($fileExists)
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/50 overflow-hidden">
                    <!-- Menggunakan class tinggi bawaan Tailwind agar responsif -->
                    <div class="h-[400px] sm:h-[500px] md:h-[600px] lg:h-[700px]">
                        <iframe src="{{ route('produk-hukum.pdf', $produkHukum->id) }}" 
                                class="w-full h-full border-0"
                                title="{{ $produkHukum->judul }}">
                        </iframe>
                    </div>
                </div>
                @else
                <div class="bg-yellow-50/80 backdrop-blur-sm border border-yellow-200 rounded-xl p-6 sm:p-8 text-center">
                    <i class="fas fa-exclamation-triangle text-3xl sm:text-4xl text-yellow-500 mb-2 sm:mb-3 block"></i>
                    <p class="text-sm sm:text-base text-yellow-700">File PDF tidak ditemukan</p>
                    <a href="{{ route('produk-hukum.index') }}" class="mt-2 sm:mt-3 inline-block text-red-600 hover:text-red-800 text-sm sm:text-base">
                        Kembali ke daftar produk hukum
                    </a>
                </div>
                @endif

                <!-- Tombol Kembali -->
                <div class="mt-3 sm:mt-4">
                    <a href="{{ route('produk-hukum.index') }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-100/80 backdrop-blur-sm text-gray-700 rounded-lg hover:bg-gray-200/90 transition text-xs sm:text-sm border border-gray-200/50">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Produk Hukum Lainnya (30%) -->
            <div class="lg:w-4/12">
                <!-- Sticky sidebar: top disesuaikan agar tidak tertutup Top Bar + Navbar -->
                <div class="sticky top-28 lg:top-32">
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/50 p-4 sm:p-5">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 pb-2 sm:pb-3 border-b border-gray-200 flex items-center gap-2">
                            <i class="fas fa-file-pdf text-red-500"></i>
                            Produk Hukum Lainnya
                        </h3>

                        @if($produkHukumLainnya->count() > 0)
                        <div class="space-y-2 sm:space-y-3 max-h-[400px] sm:max-h-[500px] md:max-h-[600px] overflow-y-auto pr-1 sm:pr-2">
                            @foreach($produkHukumLainnya as $item)
                            <a href="{{ route('produk-hukum.show', $item->id) }}" 
                               class="block group hover:bg-red-50/50 p-2 sm:p-3 rounded-lg transition border border-gray-100/50 hover:border-red-200">
                                <div class="flex items-start gap-2 sm:gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <i class="fas fa-file-pdf text-red-500 text-xs sm:text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-800 group-hover:text-red-600 transition text-xs sm:text-sm line-clamp-2">
                                            {{ $item->judul }}
                                        </h4>
                                        @if($item->keterangan)
                                        <p class="text-[10px] sm:text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $item->keterangan }}</p>
                                        @endif
                                        <span class="text-[10px] sm:text-xs text-gray-400 mt-0.5 block">{{ $item->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada produk hukum lainnya</p>
                        @endif

                        <!-- Tombol Lihat Semua -->
                        <div class="mt-3 sm:mt-4 pt-2 sm:pt-3 border-t border-gray-200">
                            <a href="{{ route('produk-hukum.index') }}" class="text-xs sm:text-sm text-red-600 hover:text-red-800 font-medium">
                                Lihat semua produk hukum →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Style khusus untuk line-clamp teks -->
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection