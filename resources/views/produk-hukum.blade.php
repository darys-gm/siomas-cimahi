@extends('layouts.home')

@section('title', 'Produk Hukum - SIOMAS Kota Cimahi')

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
        <div class="text-center mb-6 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-2 sm:mb-3">Produk Hukum</h1>
            <p class="text-sm sm:text-base md:text-lg text-gray-600">Kumpulan produk hukum terkait Organisasi Masyarakat di Kota Cimahi</p>
            <div class="w-16 sm:w-20 h-1 bg-red-600 mx-auto mt-2 sm:mt-3 rounded-full"></div>
        </div>

        @if($produkHukums->count() > 0)
        <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/50 overflow-hidden">
            <!-- Tabel untuk Desktop -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 bg-red-50 text-left text-xs font-medium text-red-700 uppercase tracking-wider">No</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 bg-red-50 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Judul Produk Hukum</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 bg-red-50 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Keterangan</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 bg-red-50 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 divide-y divide-gray-200">
                        @foreach($produkHukums as $item)
                        <tr class="hover:bg-red-50/50 transition">
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm font-medium text-gray-800">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-red-500"></i>
                                    <span class="text-xs sm:text-sm">{{ $item->judul }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm text-gray-600 max-w-xs">
                                {{ $item->keterangan ?? '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('produk-hukum.show', $item->id) }}" 
                                   class="inline-flex items-center px-2.5 sm:px-3 py-1 sm:py-1.5 bg-red-600 text-white text-[10px] sm:text-xs rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-eye mr-1"></i> Lihat PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Card untuk Mobile (sm:hidden) -->
            <div class="sm:hidden">
                @foreach($produkHukums as $item)
                <div class="border-b border-gray-200/70 p-3 hover:bg-red-50/30 transition">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-file-pdf text-red-500 text-lg mt-0.5"></i>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-800">{{ $item->judul }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->keterangan ?? '-' }}</p>
                            <div class="mt-2">
                                <a href="{{ route('produk-hukum.show', $item->id) }}" 
                                   class="inline-flex items-center px-2.5 py-1 bg-red-600 text-white text-[10px] rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-eye mr-1"></i> Lihat PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Total Data -->
        <div class="mt-4 text-xs sm:text-sm text-gray-500 text-center">
            Total {{ $produkHukums->count() }} produk hukum
        </div>
        @else
        <div class="text-center py-8 sm:py-12 bg-white/80 backdrop-blur-sm rounded-xl border border-gray-100/50">
            <i class="fas fa-file-pdf text-3xl sm:text-4xl text-gray-300 block mb-2 sm:mb-3"></i>
            <p class="text-sm sm:text-base text-gray-500">Belum ada produk hukum</p>
        </div>
        @endif
    </div>
</div>

<style>
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
        .px-3 {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
        .px-4 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .py-3 {
            padding-top: 6px !important;
            padding-bottom: 6px !important;
        }
        .py-8 {
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }
        .mb-2 {
            margin-bottom: 4px !important;
        }
        .mb-3 {
            margin-bottom: 6px !important;
        }
        .mb-6 {
            margin-bottom: 12px !important;
        }
        .mb-10 {
            margin-bottom: 16px !important;
        }
        .mt-2 {
            margin-top: 4px !important;
        }
        .mt-4 {
            margin-top: 8px !important;
        }
        .gap-2 {
            gap: 4px !important;
        }
        .w-16 {
            width: 40px !important;
        }
        .w-20 {
            width: 48px !important;
        }
        .rounded-xl {
            border-radius: 8px !important;
        }
        .p-3 {
            padding: 6px !important;
        }
        .text-lg {
            font-size: 14px !important;
        }
        .text-base {
            font-size: 12px !important;
        }
        .py-1 {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
        }
        .px-2\.5 {
            padding-left: 4px !important;
            padding-right: 4px !important;
        }
        .mt-0\.5 {
            margin-top: 1px !important;
        }
        .mt-1 {
            margin-top: 2px !important;
        }
        .mt-2 {
            margin-top: 4px !important;
        }
        .py-8 {
            padding-top: 16px !important;
            padding-bottom: 16px !important;
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
        .px-4 {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .py-3 {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }
        .py-8 {
            padding-top: 16px !important;
            padding-bottom: 16px !important;
        }
        .mb-6 {
            margin-bottom: 14px !important;
        }
        .mb-10 {
            margin-bottom: 18px !important;
        }
        .p-3 {
            padding: 8px !important;
        }
    }

    @media (min-width: 425px) and (max-width: 640px) {
        /* 425px */
        .text-2xl {
            font-size: 22px !important;
        }
        .py-8 {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }
        .mb-6 {
            margin-bottom: 16px !important;
        }
        .mb-10 {
            margin-bottom: 20px !important;
        }
        .p-3 {
            padding: 10px !important;
        }
        .px-4 {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .py-3 {
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }
    }

    /* === RESPONSIVE TABEL DI MOBILE === */
    @media (max-width: 640px) {
        .sm\:hidden {
            display: block !important;
        }
        .hidden.sm\:block {
            display: none !important;
        }
        .min-w-full {
            min-width: 100% !important;
        }
        .overflow-x-auto {
            overflow-x: auto !important;
        }
        .whitespace-nowrap {
            white-space: normal !important;
        }
        .max-w-xs {
            max-width: 100% !important;
        }
        .px-6 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .py-4 {
            padding-top: 6px !important;
            padding-bottom: 6px !important;
        }
        .text-sm {
            font-size: 11px !important;
        }
        .text-xs {
            font-size: 10px !important;
        }
    }

    /* === RESPONSIVE TABEL UNTUK TABLET === */
    @media (min-width: 641px) and (max-width: 1024px) {
        .px-6 {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .py-4 {
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }
        .text-sm {
            font-size: 12px !important;
        }
    }
</style>
@endsection