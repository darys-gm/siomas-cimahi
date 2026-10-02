@extends('layouts.home')

@section('title', 'Semua Berita - SIOMAS Kota Cimahi')

@section('content')

<div class="relative min-h-screen py-8 sm:py-12 mt-8">
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full">
        <img src="{{ asset('images/bg-pola.png') }}" 
             alt="Background" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-white/70 via-white/80 to-white/90"></div>
    </div>
    
    <div class="container mx-auto px-3 sm:px-4 relative z-10">
        <!-- Header -->
        <div class="mb-4 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Semua Berita</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1">Berita terbaru seputar ORMAS dan Kesbangpol Kota Cimahi</p>
        </div>

        <!-- Berita Terbaru (Featured) -->
        @if($beritaTerbaru)
        <div class="mb-6 sm:mb-10">
            <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-md overflow-hidden hover:shadow-lg transition border border-gray-100/50">
                <div class="flex flex-col md:flex-row">
                    <!-- Gambar -->
                    <div class="md:w-5/12">
                        @if($beritaTerbaru->gambar)
                        <img src="{{ asset('storage/' . $beritaTerbaru->gambar) }}" 
                             alt="{{ $beritaTerbaru->judul }}" 
                             class="w-full h-48 sm:h-56 md:h-full object-cover">
                        @else
                        <div class="w-full h-48 sm:h-56 md:h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                            <i class="fas fa-newspaper text-4xl sm:text-5xl md:text-6xl text-white/30"></i>
                        </div>
                        @endif
                    </div>
                    <!-- Content -->
                    <div class="md:w-7/12 p-4 sm:p-6 md:p-8 flex flex-col justify-center">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-500 mb-2 sm:mb-3">
                            <span><i class="far fa-calendar-alt mr-1"></i> {{ $beritaTerbaru->published_at ? $beritaTerbaru->published_at->format('d F Y') : $beritaTerbaru->created_at->format('d F Y') }}</span>
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-[10px] sm:text-xs font-medium">
                                <i class="fas fa-star mr-1"></i> Terbaru
                            </span>
                        </div>
                        <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mb-2 sm:mb-3 hover:text-red-600 transition">
                            <a href="{{ route('berita.show', $beritaTerbaru->slug ?? $beritaTerbaru->id) }}">
                                {{ $beritaTerbaru->judul }}
                            </a>
                        </h2>
                        <p class="text-gray-600 text-xs sm:text-sm mb-3 sm:mb-4 line-clamp-3">{{ $beritaTerbaru->excerpt }}</p>
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="text-xs sm:text-sm text-gray-500">
                                <i class="far fa-user mr-1"></i> {{ $beritaTerbaru->user->name ?? 'Admin' }}
                            </span>
                            <a href="{{ route('berita.show', $beritaTerbaru->slug ?? $beritaTerbaru->id) }}" 
                               class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-xs sm:text-sm">
                                Baca Selengkapnya
                                <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Berita Lainnya (Grid) -->
        <div class="mb-6 sm:mb-8">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                <i class="fas fa-list text-red-600"></i>
                Berita Lainnya
            </h3>
            
            @if($beritaLainnya->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 md:gap-6">
                @foreach($beritaLainnya as $item)
                <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-sm border border-gray-100/50 hover:shadow-lg transition group overflow-hidden">
                    <!-- Gambar dengan wrapper untuk mencegah overflow -->
                    <div class="overflow-hidden">
                        @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" 
                             alt="{{ $item->judul }}" 
                             class="w-full h-40 sm:h-44 md:h-48 object-cover group-hover:scale-105 transition duration-500">
                        @else
                        <div class="w-full h-40 sm:h-44 md:h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-newspaper text-3xl sm:text-4xl text-gray-400"></i>
                        </div>
                        @endif
                    </div>
                    <div class="p-3 sm:p-4">
                        <div class="flex items-center gap-2 text-[10px] sm:text-xs text-gray-500 mb-1 sm:mb-2">
                            <span><i class="far fa-calendar-alt mr-1"></i> {{ $item->published_at ? $item->published_at->format('d/m/Y') : $item->created_at->format('d/m/Y') }}</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 group-hover:text-red-600 transition text-sm sm:text-base line-clamp-2">
                            <a href="{{ route('berita.show', $item->slug ?? $item->id) }}">
                                {{ $item->judul }}
                            </a>
                        </h4>
                        <p class="text-gray-500 text-[11px] sm:text-xs mt-1 line-clamp-2">{{ $item->excerpt }}</p>
                        <div class="mt-2 sm:mt-3 flex flex-wrap items-center justify-between gap-1">
                            <span class="text-[10px] sm:text-xs text-gray-400">{{ $item->user->name ?? 'Admin' }}</span>
                            <a href="{{ route('berita.show', $item->slug ?? $item->id) }}" 
                               class="text-red-600 hover:text-red-800 text-[11px] sm:text-xs font-medium">
                                Baca →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination - Responsive -->
            <div class="mt-6 sm:mt-8">
                @if($beritaLainnya->hasPages())
                <div class="flex flex-col items-center gap-3">
                    <div class="flex flex-wrap items-center justify-center gap-1 w-full">
                        {{-- Previous Page Link --}}
                        @if ($beritaLainnya->onFirstPage())
                            <span class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-gray-100 text-xs sm:text-sm font-medium text-gray-400 cursor-not-allowed min-w-[28px] sm:min-w-[32px]">
                                <i class="fas fa-chevron-left text-[9px] sm:text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $beritaLainnya->previousPageUrl() }}" class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[28px] sm:min-w-[32px]">
                                <i class="fas fa-chevron-left text-[9px] sm:text-xs"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $beritaLainnya->currentPage();
                            $lastPage = $beritaLainnya->lastPage();
                            $half = 1;
                            $start = max(1, $currentPage - $half);
                            $end = min($lastPage, $currentPage + $half);
                            $showStartEllipsis = ($start > 2);
                            $showEndEllipsis = ($end < $lastPage - 1);
                        @endphp

                        {{-- Halaman Pertama --}}
                        @if($currentPage > 2)
                            <a href="{{ $beritaLainnya->url(1) }}" class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[24px] sm:min-w-[30px]">
                                1
                            </a>
                        @endif

                        {{-- Ellipsis awal --}}
                        @if($showStartEllipsis)
                            <span class="inline-flex items-center justify-center px-1 sm:px-1.5 py-1 text-[10px] sm:text-xs font-medium text-gray-500 min-w-[16px] sm:min-w-[20px]">
                                ...
                            </span>
                        @endif

                        {{-- Halaman sebelum current --}}
                        @if($currentPage > 1)
                            <a href="{{ $beritaLainnya->url($currentPage - 1) }}" class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[24px] sm:min-w-[30px]">
                                {{ $currentPage - 1 }}
                            </a>
                        @endif

                        {{-- Halaman current --}}
                        <span class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-red-600 bg-red-600 text-[10px] sm:text-xs font-medium text-white min-w-[24px] sm:min-w-[30px]">
                            {{ $currentPage }}
                        </span>

                        {{-- Halaman setelah current --}}
                        @if($currentPage < $lastPage)
                            <a href="{{ $beritaLainnya->url($currentPage + 1) }}" class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[24px] sm:min-w-[30px]">
                                {{ $currentPage + 1 }}
                            </a>
                        @endif

                        {{-- Ellipsis akhir --}}
                        @if($showEndEllipsis)
                            <span class="inline-flex items-center justify-center px-1 sm:px-1.5 py-1 text-[10px] sm:text-xs font-medium text-gray-500 min-w-[16px] sm:min-w-[20px]">
                                ...
                            </span>
                        @endif

                        {{-- Halaman Terakhir --}}
                        @if($currentPage < $lastPage - 1)
                            <a href="{{ $beritaLainnya->url($lastPage) }}" class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[24px] sm:min-w-[30px]">
                                {{ $lastPage }}
                            </a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($beritaLainnya->hasMorePages())
                            <a href="{{ $beritaLainnya->nextPageUrl() }}" class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[28px] sm:min-w-[32px]">
                                <i class="fas fa-chevron-right text-[9px] sm:text-xs"></i>
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-gray-100 text-xs sm:text-sm font-medium text-gray-400 cursor-not-allowed min-w-[28px] sm:min-w-[32px]">
                                <i class="fas fa-chevron-right text-[9px] sm:text-xs"></i>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="text-center py-8 sm:py-12 bg-white/80 backdrop-blur-sm rounded-xl border border-gray-100/50">
                <i class="fas fa-newspaper text-4xl sm:text-5xl text-gray-300 mb-3 block"></i>
                <p class="text-gray-500 text-sm sm:text-base">Belum ada berita lainnya</p>
            </div>
            @endif
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-4 sm:mt-6">
            <a href="{{ route('home') }}#berita" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-100/80 backdrop-blur-sm text-gray-700 rounded-lg hover:bg-gray-200/90 transition text-xs sm:text-sm border border-gray-200/50">
                <i class="fas fa-arrow-left mr-1 sm:mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ============================================ */
    /* RESPONSIVE UNTUK MOBILE 320px, 375px, 425px */
    /* ============================================ */
    @media (max-width: 374px) {
        /* 320px */
        .container {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .text-2xl {
            font-size: 18px !important;
        }
        .text-sm {
            font-size: 11px !important;
        }
        .text-xs {
            font-size: 10px !important;
        }
        .text-\[10px\] {
            font-size: 8px !important;
        }
        .text-\[11px\] {
            font-size: 9px !important;
        }
        .p-4 {
            padding: 10px !important;
        }
        .p-3 {
            padding: 6px !important;
        }
        .px-3 {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
        .py-1\.5 {
            padding-top: 3px !important;
            padding-bottom: 3px !important;
        }
        .gap-2 {
            gap: 3px !important;
        }
        .gap-3 {
            gap: 4px !important;
        }
        .mb-3 {
            margin-bottom: 6px !important;
        }
        .mb-4 {
            margin-bottom: 8px !important;
        }
        .mb-6 {
            margin-bottom: 12px !important;
        }
        .mt-1 {
            margin-top: 2px !important;
        }
        .mt-2 {
            margin-top: 4px !important;
        }
        .mt-4 {
            margin-top: 8px !important;
        }
        .mt-6 {
            margin-top: 12px !important;
        }
        .py-8 {
            padding-top: 16px !important;
            padding-bottom: 16px !important;
        }
        .h-48 {
            height: 140px !important;
        }
        .h-40 {
            height: 120px !important;
        }
        .grid-cols-1 {
            grid-template-columns: 1fr !important;
        }
        .sm\:grid-cols-2 {
            grid-template-columns: 1fr !important;
        }
        .text-base {
            font-size: 13px !important;
        }
        .text-lg {
            font-size: 15px !important;
        }
        .rounded-xl {
            border-radius: 8px !important;
        }
        .rounded-lg {
            border-radius: 6px !important;
        }
        .gap-1 {
            gap: 2px !important;
        }
        .w-16 {
            width: 40px !important;
        }
        .w-20 {
            width: 48px !important;
        }
        
        /* Pagination 320px */
        .pagination-arrow {
            min-width: 24px !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
            font-size: 8px !important;
        }
        .pagination-arrow i {
            font-size: 7px !important;
        }
        .pagination-number {
            min-width: 20px !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
            font-size: 8px !important;
            border-radius: 3px !important;
        }
        .pagination-ellipsis {
            font-size: 8px !important;
            min-width: 12px !important;
            padding-left: 1px !important;
            padding-right: 1px !important;
        }
        .pagination-arrow, .pagination-number {
            padding-top: 3px !important;
            padding-bottom: 3px !important;
            border-width: 1px !important;
        }
        .pagination-wrapper {
            gap: 2px !important;
        }
    }

    @media (min-width: 375px) and (max-width: 424px) {
        /* 375px */
        .container {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .text-2xl {
            font-size: 20px !important;
        }
        .text-sm {
            font-size: 12px !important;
        }
        .p-4 {
            padding: 12px !important;
        }
        .px-3 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .py-8 {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }
        .h-48 {
            height: 160px !important;
        }
        .h-40 {
            height: 140px !important;
        }
        .gap-3 {
            gap: 6px !important;
        }
        .mb-6 {
            margin-bottom: 14px !important;
        }
        
        /* Pagination 375px */
        .pagination-arrow {
            min-width: 28px !important;
            padding-left: 5px !important;
            padding-right: 5px !important;
            font-size: 9px !important;
        }
        .pagination-arrow i {
            font-size: 8px !important;
        }
        .pagination-number {
            min-width: 24px !important;
            padding-left: 5px !important;
            padding-right: 5px !important;
            font-size: 9px !important;
        }
        .pagination-ellipsis {
            font-size: 9px !important;
            min-width: 16px !important;
            padding-left: 2px !important;
            padding-right: 2px !important;
        }
        .pagination-wrapper {
            gap: 3px !important;
        }
    }

    @media (min-width: 425px) and (max-width: 640px) {
        /* 425px */
        .text-2xl {
            font-size: 22px !important;
        }
        .py-8 {
            padding-top: 24px !important;
            padding-bottom: 24px !important;
        }
        .h-48 {
            height: 180px !important;
        }
        .h-40 {
            height: 150px !important;
        }
        .mb-6 {
            margin-bottom: 16px !important;
        }
        
        /* Pagination 425px */
        .pagination-arrow {
            min-width: 30px !important;
            padding-left: 7px !important;
            padding-right: 7px !important;
            font-size: 10px !important;
        }
        .pagination-arrow i {
            font-size: 9px !important;
        }
        .pagination-number {
            min-width: 26px !important;
            padding-left: 7px !important;
            padding-right: 7px !important;
            font-size: 10px !important;
        }
        .pagination-ellipsis {
            font-size: 10px !important;
            min-width: 18px !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
        }
        .pagination-wrapper {
            gap: 3px !important;
        }
    }

    @media (min-width: 641px) and (max-width: 1024px) {
        /* Tablet */
        .pagination-arrow {
            min-width: 34px !important;
            padding-left: 9px !important;
            padding-right: 9px !important;
            font-size: 12px !important;
        }
        .pagination-number {
            min-width: 32px !important;
            padding-left: 9px !important;
            padding-right: 9px !important;
            font-size: 12px !important;
        }
        .pagination-ellipsis {
            font-size: 12px !important;
            min-width: 22px !important;
        }
        .pagination-wrapper {
            gap: 4px !important;
        }
    }
</style>
@endsection