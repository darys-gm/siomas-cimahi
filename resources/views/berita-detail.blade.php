@extends('layouts.home')

@section('title', $berita->judul . ' - SIOMAS Kota Cimahi')

@section('content')
<!-- Pembungkus utama dengan z-0 agar konten berada di bawah navbar -->
<div class="relative min-h-screen py-8 sm:py-12 z-0">
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full z-0">
        <img src="{{ asset('images/bg-pola.png') }}" 
             alt="Background" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-white/70 via-white/80 to-white/90"></div>
    </div>
    
    <!-- Kontainer Utama Konten -->
    <div class="container mx-auto px-3 sm:px-4 relative z-10">
        <div class="flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8">
            <!-- Kolom Kiri: Detail Berita Utama (70%) -->
            <div class="lg:w-9/12">
                <!-- Breadcrumb -->
                <nav class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
                    <a href="{{ route('home') }}" class="hover:text-red-600 transition">Beranda</a>
                    <span class="mx-1 sm:mx-2">/</span>
                    <a href="{{ route('berita.semua') }}#berita" class="hover:text-red-600 transition">Berita</a>
                    <span class="mx-1 sm:mx-2">/</span>
                    <span class="text-gray-700 font-medium">{{ Str::limit($berita->judul, 40) }}</span>
                </nav>

                <!-- Header Berita -->
                <div class="mb-4 sm:mb-6">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-2 sm:mb-3">{{ $berita->judul }}</h1>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-500">
                        <span><i class="far fa-calendar-alt mr-1"></i> {{ $berita->published_at ? $berita->published_at->format('d F Y') : $berita->created_at->format('d F Y') }}</span>
                        <span><i class="far fa-user mr-1"></i> {{ $berita->user->name ?? 'Admin' }}</span>
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-[10px] sm:text-xs font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Published
                        </span>
                    </div>
                </div>

                <!-- Gambar Berita -->
                @if($berita->gambar)
                <div class="mb-4 sm:mb-6 rounded-xl overflow-hidden shadow-md">
                    <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="w-full h-auto object-cover max-h-[350px] sm:max-h-[400px]">
                </div>
                @endif

                <!-- Isi Berita -->
                <div class="bg-white/95 backdrop-blur-sm rounded-xl p-4 sm:p-6 lg:p-8 border border-gray-100/50 shadow-sm">
                    <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none">
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line text-sm sm:text-base">
                            {{ $berita->isi }}
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200/70">
                    <a href="{{ route('berita.semua') }}#berita" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-100/80 backdrop-blur-sm text-gray-700 rounded-lg hover:bg-gray-200/90 transition inline-flex items-center gap-2 text-xs sm:text-sm border border-gray-200/50">
                        <i class="fas fa-arrow-left"></i> Kembali ke Berita
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Berita Terkait (30%) -->
            <div class="lg:w-3/12">
                <!-- Top disesuaikan (top-28 lg:top-32) agar tidak tertutup navbar saat scroll -->
                <div class="sticky top-28 lg:top-32">
                    <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/50 p-4 sm:p-5">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 pb-2 sm:pb-3 border-b border-gray-200 flex items-center gap-2">
                            <i class="fas fa-newspaper text-red-600"></i>
                            Berita Terkait
                        </h3>

                        @if($beritaTerbaru->count() > 0)
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($beritaTerbaru as $item)
                            <a href="{{ route('berita.show', $item->slug ?? $item->id) }}" class="group flex gap-2 sm:gap-3 hover:bg-gray-50/80 p-2 rounded-lg transition">
                                @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="w-16 sm:w-20 h-16 sm:h-20 object-cover rounded-lg flex-shrink-0">
                                @else
                                <div class="w-16 sm:w-20 h-16 sm:h-20 bg-gray-200 flex items-center justify-center rounded-lg flex-shrink-0">
                                    <i class="fas fa-newspaper text-xl sm:text-2xl text-gray-400"></i>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800 group-hover:text-red-600 transition text-xs sm:text-sm line-clamp-2">{{ $item->judul }}</h4>
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-0.5 sm:mt-1">{{ $item->published_at ? $item->published_at->format('d/m/Y') : $item->created_at->format('d/m/Y') }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada berita terkait</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Style khusus untuk format teks berita (prose) -->
<style>
    .prose p {
        margin-bottom: 1rem;
        line-height: 1.8;
    }
    .prose h2, .prose h3 {
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        font-weight: 700;
        color: #1a202c;
    }
    .prose ul, .prose ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .prose li {
        margin-bottom: 0.25rem;
    }
    .prose blockquote {
        border-left: 4px solid #dc2626;
        padding-left: 1rem;
        margin: 1rem 0;
        color: #4a5568;
        font-style: italic;
    }
    .prose img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }
</style>
@endsection