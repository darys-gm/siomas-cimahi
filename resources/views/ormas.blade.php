@extends('layouts.home')

@section('title', 'Data ORMAS - SIOMAS Kota Cimahi')

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
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Data ORMAS Kota Cimahi</h1>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/50 p-3 sm:p-4 mb-4 sm:mb-6">
            <form method="GET" action="{{ route('ormas') }}" class="space-y-3" id="searchForm">
                <!-- Row 1: Search - Full Width -->
                <div class="w-full">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Cari ORMAS</label>
                    <div class="relative">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan nama ORMAS..." 
                               class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base bg-white/80">
                        @if(request('search'))
                        <button type="button" onclick="clearSearch()" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i class="fas fa-times-circle text-base sm:text-lg"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Row 2: Filter Dropdowns - Responsive Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-3">
                    <!-- Filter Kecamatan -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                        <div class="relative">
                            <select name="kecamatan" id="filterKecamatan" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-xs sm:text-sm appearance-none bg-white/80">
                                <option value="">Semua Kecamatan</option>
                                @foreach(\App\Models\Kecamatan::all() as $kec)
                                    <option value="{{ $kec->id }}" {{ request('kecamatan') == $kec->id ? 'selected' : '' }}>
                                        {{ $kec->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if(request('kecamatan'))
                            <button type="button" onclick="clearFilter('kecamatan')" 
                                    class="absolute right-7 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-times-circle text-xs sm:text-sm"></i>
                            </button>
                            @endif
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-3 w-3 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Kelurahan -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                        <div class="relative">
                            <select name="kelurahan" id="filterKelurahan" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-xs sm:text-sm appearance-none bg-white/80">
                                <option value="">Semua Kelurahan</option>
                                @if(request('kecamatan'))
                                    @php
                                        $kelurahans = \App\Models\Kelurahan::where('kecamatan_id', request('kecamatan'))->orderBy('nama')->get();
                                    @endphp
                                    @foreach($kelurahans as $kel)
                                        <option value="{{ $kel->id }}" {{ request('kelurahan') == $kel->id ? 'selected' : '' }}>
                                            {{ $kel->nama }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @if(request('kelurahan'))
                            <button type="button" onclick="clearFilter('kelurahan')" 
                                    class="absolute right-7 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-times-circle text-xs sm:text-sm"></i>
                            </button>
                            @endif
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-3 w-3 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Bentuk Ormas -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Bentuk Ormas</label>
                        <div class="relative">
                            <select name="bentuk" id="filterBentuk" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-xs sm:text-sm appearance-none bg-white/80">
                                <option value="">Semua Bentuk</option>
                                <option value="Yayasan" {{ request('bentuk') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                                <option value="Perkumpulan" {{ request('bentuk') == 'Perkumpulan' ? 'selected' : '' }}>Perkumpulan</option>
                            </select>
                            @if(request('bentuk'))
                            <button type="button" onclick="clearFilter('bentuk')" 
                                    class="absolute right-7 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-times-circle text-xs sm:text-sm"></i>
                            </button>
                            @endif
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-3 w-3 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Bidang Kegiatan -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Bidang Kegiatan</label>
                        <div class="relative">
                            <select name="bidang" id="filterBidang" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-xs sm:text-sm appearance-none bg-white/80">
                                <option value="">Semua Bidang</option>
                                <option value="Keagamaan dan Kepercayaan" {{ request('bidang') == 'Keagamaan dan Kepercayaan' ? 'selected' : '' }}>Keagamaan dan Kepercayaan</option>
                                <option value="Sosial, Kemanusiaan dan Kemasyarakatan" {{ request('bidang') == 'Sosial, Kemanusiaan dan Kemasyarakatan' ? 'selected' : '' }}>Sosial, Kemanusiaan dan Kemasyarakatan</option>
                                <option value="Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga" {{ request('bidang') == 'Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga' ? 'selected' : '' }}>Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga</option>
                                <option value="Kepemudaan, Olahraga dan Seni Budaya" {{ request('bidang') == 'Kepemudaan, Olahraga dan Seni Budaya' ? 'selected' : '' }}>Kepemudaan, Olahraga dan Seni Budaya</option>
                                <option value="Pendidikan dan Pemberdayaan SDM" {{ request('bidang') == 'Pendidikan dan Pemberdayaan SDM' ? 'selected' : '' }}>Pendidikan dan Pemberdayaan SDM</option>
                                <option value="Profesi dan Keahlian" {{ request('bidang') == 'Profesi dan Keahlian' ? 'selected' : '' }}>Profesi dan Keahlian</option>
                                <option value="Lingkungan Hidup dan Kebencanaan" {{ request('bidang') == 'Lingkungan Hidup dan Kebencanaan' ? 'selected' : '' }}>Lingkungan Hidup dan Kebencanaan</option>
                                <option value="Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat" {{ request('bidang') == 'Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat' ? 'selected' : '' }}>Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat</option>
                                <option value="Kebangsaan dan Bela Negara" {{ request('bidang') == 'Kebangsaan dan Bela Negara' ? 'selected' : '' }}>Kebangsaan dan Bela Negara</option>
                                <option value="Hukum, HAM dan Advokasi Publik" {{ request('bidang') == 'Hukum, HAM dan Advokasi Publik' ? 'selected' : '' }}>Hukum, HAM dan Advokasi Publik</option>
                                <option value="Komunitas, Minat dan Hobi" {{ request('bidang') == 'Komunitas, Minat dan Hobi' ? 'selected' : '' }}>Komunitas, Minat dan Hobi</option>
                                <option value="Bidang Kegiatan Lainnya" {{ request('bidang') == 'Bidang Kegiatan Lainnya' ? 'selected' : '' }}>Bidang Kegiatan Lainnya</option>
                            </select>
                            @if(request('bidang'))
                            <button type="button" onclick="clearFilter('bidang')" 
                                    class="absolute right-7 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-times-circle text-xs sm:text-sm"></i>
                            </button>
                            @endif
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-3 w-3 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Cari & Reset -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-xs sm:text-sm font-medium">
                            <i class="fas fa-search mr-1"></i> Cari
                        </button>
                        @if(request('search') || request('bentuk') || request('bidang') || request('kecamatan') || request('kelurahan'))
                        <a href="{{ route('ormas') }}" class="px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-xs sm:text-sm font-medium whitespace-nowrap">
                            <i class="fas fa-times"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Hasil Pencarian -->
        @if(request('search') || request('bentuk') || request('bidang') || request('kecamatan') || request('kelurahan'))
        <div class="mb-4 text-xs sm:text-sm text-gray-500 break-words">
            Menampilkan hasil untuk: 
            @if(request('search'))
            <span class="font-medium text-gray-700">"{{ request('search') }}"</span>
            @endif
            @if(request('kecamatan'))
            @if(request('search')) <span class="mx-1">|</span> @endif
            <span class="font-medium text-gray-700">Kecamatan: {{ \App\Models\Kecamatan::find(request('kecamatan'))->nama ?? '' }}</span>
            @endif
            @if(request('kelurahan'))
            @if(request('search') || request('kecamatan')) <span class="mx-1">|</span> @endif
            <span class="font-medium text-gray-700">Kelurahan: {{ \App\Models\Kelurahan::find(request('kelurahan'))->nama ?? '' }}</span>
            @endif
            @if(request('bentuk'))
            @if(request('search') || request('kecamatan') || request('kelurahan')) <span class="mx-1">|</span> @endif
            <span class="font-medium text-gray-700">Bentuk: {{ request('bentuk') }}</span>
            @endif
            @if(request('bidang'))
            @if(request('search') || request('kecamatan') || request('kelurahan') || request('bentuk')) <span class="mx-1">|</span> @endif
            <span class="font-medium text-gray-700">Bidang: {{ request('bidang') }}</span>
            @endif
            <span class="ml-2">({{ $ormas->total() }} ORMAS ditemukan)</span>
        </div>
        @endif

        <!-- Data ORMAS - Tampilan Tabel untuk Desktop, Card untuk Mobile -->
        @if($ormas->count() > 0)
        <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/50 overflow-hidden">
            <!-- Tabel untuk Desktop (lg:block) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama ORMAS</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Singkatan</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ketua</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bentuk Ormas</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang Kegiatan</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No AHU/SKT</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Daftar</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update/Pelaporan</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 divide-y divide-gray-200">
                        @foreach($ormas as $index => $item)
                        <tr class="hover:bg-red-50/50 transition">
                            <td class="px-3 py-3 text-sm text-gray-500">
                                {{ $ormas->firstItem() + $index }}
                            </td>
                            <td class="px-3 py-3 text-sm font-medium text-gray-800">{{ $item->nama }}</td>
                            <td class="px-3 py-3 text-sm text-gray-500">{{ $item->singkatan ?? '-' }}</td>
                            <td class="px-3 py-3 text-sm text-gray-500">
                                {{ $item->pengurus->first()->nama ?? '-' }}
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-500">
                                {{ $item->kecamatan->nama ?? '-' }}{{ $item->kelurahan->nama ? ', ' . $item->kelurahan->nama : '' }}
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-500">{{ $item->jenisOrmas->nama ?? '-' }}</td>
                            <td class="px-3 py-3 text-sm text-gray-500">{{ $item->bidangKegiatan->nama ?? '-' }}</td>
                            <!-- ===== PERUBAHAN NO AHU/SKT DI SINI ===== -->
                            <td class="px-3 py-3 text-sm text-gray-500">
                                @if(!empty($item->nomor_registrasi))
                                    Ada
                                @else
                                    -
                                @endif
                            </td>
                            <!-- ====================================== -->
                            <td class="px-3 py-3 text-sm text-gray-500">{{ $item->created_at ? $item->created_at->format('Y') : '-' }}</td>
                            <td class="px-3 py-3 text-sm">
                                @php
                                    $pelaporanLabels = [
                                        'sudah' => 'Sudah',
                                        'belum' => 'Belum',
                                        'tidak_ada' => '-'
                                    ];
                                    $pelaporanColors = [
                                        'sudah' => 'bg-green-100 text-green-700',
                                        'belum' => 'bg-yellow-100 text-yellow-700',
                                        'tidak_ada' => 'bg-gray-100 text-gray-700'
                                    ];
                                    $pelaporanValue = $item->pelaporan ?? 'belum';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $pelaporanColors[$pelaporanValue] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $pelaporanLabels[$pelaporanValue] ?? 'Belum' }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-sm">
                                @if($item->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                <button onclick="openModal({{ $item->id }})" 
                                        class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Card untuk Mobile & Tablet (lg:hidden) -->
            <div class="lg:hidden">
                @foreach($ormas as $index => $item)
                <div class="border-b border-gray-200 p-3 sm:p-4 hover:bg-red-50/30 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs text-gray-400 font-medium">#{{ $ormas->firstItem() + $index }}</span>
                                <h3 class="text-sm sm:text-base font-semibold text-gray-800 truncate">{{ $item->nama }}</h3>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <i class="fas fa-tag text-gray-400 mr-1"></i>
                                {{ $item->singkatan ?? '-' }}
                            </p>
                        </div>
                        <button onclick="openModal({{ $item->id }})" 
                                class="flex-shrink-0 px-2.5 py-1.5 sm:px-3 sm:py-1.5 bg-red-600 text-white text-[10px] sm:text-xs rounded-lg hover:bg-red-700 transition ml-2">
                            <i class="fas fa-eye mr-1"></i> Detail
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 sm:gap-2 text-xs sm:text-sm">
                        <div>
                            <span class="text-gray-500">Ketua:</span>
                            <span class="font-medium text-gray-700">{{ $item->pengurus->first()->nama ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Alamat:</span>
                            <span class="font-medium text-gray-700">{{ $item->kecamatan->nama ?? '-' }}{{ $item->kelurahan->nama ? ', ' . $item->kelurahan->nama : '' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Bentuk:</span>
                            <span class="font-medium text-gray-700">{{ $item->jenisOrmas->nama ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Bidang:</span>
                            <span class="font-medium text-gray-700">{{ $item->bidangKegiatan->nama ?? '-' }}</span>
                        </div>
                        <!-- ===== PERUBAHAN NO AHU/SKT DI MOBILE ===== -->
                        <div>
                            <span class="text-gray-500">No AHU/SKT:</span>
                            <span class="font-medium text-gray-700">
                                @if(!empty($item->nomor_registrasi))
                                    Ada
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <!-- ========================================== -->
                        <div>
                            <span class="text-gray-500">Tahun Daftar:</span>
                            <span class="font-medium text-gray-700">{{ $item->created_at ? $item->created_at->format('Y') : '-' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2 mt-2 pt-2 border-t border-gray-100">
                        @php
                            $pelaporanLabels = [
                                'sudah' => 'Sudah',
                                'belum' => 'Belum',
                                'tidak_ada' => '-'
                            ];
                            $pelaporanColors = [
                                'sudah' => 'bg-green-100 text-green-700',
                                'belum' => 'bg-yellow-100 text-yellow-700',
                                'tidak_ada' => 'bg-gray-100 text-gray-700'
                            ];
                            $pelaporanValue = $item->pelaporan ?? 'belum';
                        @endphp
                        <span class="px-2 py-0.5 text-[10px] sm:text-xs font-semibold rounded-full {{ $pelaporanColors[$pelaporanValue] ?? 'bg-gray-100 text-gray-700' }}">
                            <i class="fas fa-sync-alt mr-1"></i> {{ $pelaporanLabels[$pelaporanValue] ?? 'Belum' }}
                        </span>
                        @if($item->is_active)
                            <span class="px-2 py-0.5 text-[10px] sm:text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        @else
                            <span class="px-2 py-0.5 text-[10px] sm:text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                <i class="fas fa-times-circle mr-1"></i> Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- ===== PAGINATION - DENGAN ELLIPSIS & RESPONSIVE ===== -->
            @if($ormas->hasPages())
            <div class="px-3 sm:px-4 py-3 border-t border-gray-200">
                <div class="flex flex-col items-center gap-3">
                    <!-- Info Data -->
                    <div class="text-xs sm:text-sm text-gray-500 text-center w-full">
                        Menampilkan data {{ $ormas->firstItem() }} sampai {{ $ormas->lastItem() }} dari {{ $ormas->total() }} data
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="pagination-wrapper flex flex-wrap items-center justify-center gap-1 sm:gap-1.5 w-full">
                        {{-- Previous Page Link --}}
                        @if ($ormas->onFirstPage())
                            <span class="pagination-arrow inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-gray-100 text-xs sm:text-sm font-medium text-gray-400 cursor-not-allowed min-w-[28px] sm:min-w-[32px]">
                                <i class="fas fa-chevron-left text-[9px] sm:text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $ormas->previousPageUrl() }}" class="pagination-arrow inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[28px] sm:min-w-[32px]">
                                <i class="fas fa-chevron-left text-[9px] sm:text-xs"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $ormas->currentPage();
                            $lastPage = $ormas->lastPage();
                            
                            $showFirst = true;
                            $showLast = true;
                            $half = 1;
                            $start = max(1, $currentPage - $half);
                            $end = min($lastPage, $currentPage + $half);
                            $showStartEllipsis = ($start > 2);
                            $showEndEllipsis = ($end < $lastPage - 1);
                        @endphp

                        {{-- Halaman Pertama --}}
                        @if($showFirst && $currentPage > 2)
                            <a href="{{ $ormas->url(1) }}" class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[24px] sm:min-w-[30px]">
                                1
                            </a>
                        @endif

                        {{-- Ellipsis awal --}}
                        @if($showStartEllipsis)
                            <span class="pagination-ellipsis inline-flex items-center justify-center px-1 sm:px-1.5 py-1 text-[10px] sm:text-xs font-medium text-gray-500 min-w-[16px] sm:min-w-[20px]">
                                ...
                            </span>
                        @endif

                        {{-- Halaman sebelum current --}}
                        @if($currentPage > 1)
                            <a href="{{ $ormas->url($currentPage - 1) }}" class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[24px] sm:min-w-[30px]">
                                {{ $currentPage - 1 }}
                            </a>
                        @endif

                        {{-- Halaman current --}}
                        <span class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-red-600 bg-red-600 text-[10px] sm:text-xs font-medium text-white min-w-[24px] sm:min-w-[30px]">
                            {{ $currentPage }}
                        </span>

                        {{-- Halaman setelah current --}}
                        @if($currentPage < $lastPage)
                            <a href="{{ $ormas->url($currentPage + 1) }}" class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[24px] sm:min-w-[30px]">
                                {{ $currentPage + 1 }}
                            </a>
                        @endif

                        {{-- Ellipsis akhir --}}
                        @if($showEndEllipsis)
                            <span class="pagination-ellipsis inline-flex items-center justify-center px-1 sm:px-1.5 py-1 text-[10px] sm:text-xs font-medium text-gray-500 min-w-[16px] sm:min-w-[20px]">
                                ...
                            </span>
                        @endif

                        {{-- Halaman Terakhir --}}
                        @if($showLast && $currentPage < $lastPage - 1)
                            <a href="{{ $ormas->url($lastPage) }}" class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[24px] sm:min-w-[30px]">
                                {{ $lastPage }}
                            </a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($ormas->hasMorePages())
                            <a href="{{ $ormas->nextPageUrl() }}" class="pagination-arrow inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-red-300 transition min-w-[28px] sm:min-w-[32px]">
                                <i class="fas fa-chevron-right text-[9px] sm:text-xs"></i>
                            </a>
                        @else
                            <span class="pagination-arrow inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-gray-100 text-xs sm:text-sm font-medium text-gray-400 cursor-not-allowed min-w-[28px] sm:min-w-[32px]">
                                <i class="fas fa-chevron-right text-[9px] sm:text-xs"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="text-center py-8 sm:py-12 bg-white/80 backdrop-blur-sm rounded-xl border border-gray-100/50">
            <i class="fas fa-building text-3xl sm:text-4xl text-gray-300 block mb-2 sm:mb-3"></i>
            <p class="text-sm sm:text-base text-gray-500">Tidak ada data ORMAS yang ditemukan</p>
            @if(request('search') || request('bentuk') || request('bidang') || request('kecamatan') || request('kelurahan'))
            <a href="{{ route('ormas') }}" class="mt-2 inline-block text-red-600 hover:text-red-800 text-xs sm:text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke semua
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL DETAIL ORMAS                          -->
<!-- ============================================ -->
<div id="ormasModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center overflow-y-auto p-3 sm:p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header - STICKY DENGAN Z-INDEX TINGGI -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-4 sm:px-6 py-3 sm:py-4 flex justify-between items-center z-[100]">
            <h3 class="text-base sm:text-xl font-bold text-gray-800" id="modalTitle">Detail ORMAS</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 sm:p-2 transition">
                <i class="fas fa-times text-lg sm:text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4 sm:p-6" id="modalBody">
            <!-- Loading -->
            <div id="modalLoading" class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl sm:text-3xl text-red-600"></i>
                <p class="text-gray-500 mt-2 text-sm">Memuat data...</p>
            </div>

            <!-- Content -->
            <div id="modalContent" class="hidden">
                <!-- Data ORMAS -->
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 pb-1 border-b border-gray-200 flex items-center gap-2">
                        <i class="fas fa-building text-red-600"></i> Data ORMAS
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 sm:gap-2 text-sm">
                        <div class="col-span-1 sm:col-span-2"><span class="text-gray-500">Nama:</span> <span class="font-medium" id="det_nama">-</span></div>
                        <div><span class="text-gray-500">Singkatan:</span> <span class="font-medium" id="det_singkatan">-</span></div>
                        <div><span class="text-gray-500">No AHU/SKT:</span> <span class="font-medium" id="det_nomor_registrasi">-</span></div>
                        <div><span class="text-gray-500">Bentuk Ormas:</span> <span class="font-medium" id="det_jenis">-</span></div>
                        <div class="col-span-1 sm:col-span-2"><span class="text-gray-500">Bidang Kegiatan:</span> <span class="font-medium" id="det_bidang">-</span></div>
                        <div><span class="text-gray-500">Status:</span> <span class="font-medium" id="det_status">-</span></div>
                        <div><span class="text-gray-500">Update/Pelaporan:</span> <span class="font-medium" id="det_pelaporan">-</span></div>
                    </div>
                </div>

                <!-- Kontak & Alamat - NO TELEPON DIHAPUS -->
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 pb-1 border-b border-gray-200 flex items-center gap-2">
                        <i class="fas fa-address-card text-red-600"></i> Kontak & Alamat
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 sm:gap-2 text-sm">
                        <div class="col-span-1 sm:col-span-2"><span class="text-gray-500">Alamat:</span> <span class="font-medium" id="det_alamat">-</span></div>
                        <div><span class="text-gray-500">Kecamatan:</span> <span class="font-medium" id="det_kecamatan">-</span></div>
                        <div><span class="text-gray-500">Kelurahan:</span> <span class="font-medium" id="det_kelurahan">-</span></div>
                        <div><span class="text-gray-500">Email:</span> <span class="font-medium" id="det_email">-</span></div>
                    </div>
                </div>

                <!-- ===== MAP LOKASI - DENGAN Z-INDEX RENDAH ===== -->
                <div class="mb-4 relative" style="z-index: 1;">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 pb-1 border-b border-gray-200 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-600"></i> Lokasi Peta
                    </h4>
                    <div id="modalMapContainer" class="relative" style="z-index: 1;">
                        <div id="modalMap" class="w-full h-48 sm:h-64 rounded-lg border border-gray-300 overflow-hidden" style="z-index: 1;"></div>
                        <div id="mapLoadingText" class="absolute inset-0 flex items-center justify-center bg-gray-100/80 rounded-lg" style="z-index: 2;">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin text-xl sm:text-2xl text-red-600"></i>
                                <p class="text-xs sm:text-sm text-gray-500 mt-2">Memuat peta...</p>
                            </div>
                        </div>
                        <!-- Tombol Google Maps -->
                        <div class="mt-3 text-center">
                            <button id="googleMapsBtn" onclick="openGoogleMaps()"
                                    class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-green-600 text-white text-xs sm:text-sm rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-directions mr-2"></i> Buka di Google Maps
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ===== DATA PENGURUS - TANPA NO TELEPON & TANPA ALAMAT ===== -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 pb-1 border-b border-gray-200 flex items-center gap-2">
                        <i class="fas fa-users text-red-600"></i> Data Pengurus
                    </h4>
                    <div id="det_pengurus" class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                        <!-- Akan diisi oleh JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer - STICKY DENGAN Z-INDEX TINGGI -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 px-4 sm:px-6 py-3 flex justify-end z-[100]">
            <button onclick="closeModal()" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-400 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- LEAFET CSS & JS                             -->
<!-- ============================================ -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- ============================================ -->
<!-- STYLE TAMBAHAN UNTUK MENGATASI Z-INDEX MAP  -->
<!-- ============================================ -->
<style>
    /* === MENGATASI Z-INDEX LEAFLET YANG TERLALU TINGGI === */
    .leaflet-control-container,
    .leaflet-top,
    .leaflet-bottom,
    .leaflet-control {
        z-index: 5 !important;
    }
    
    /* === MODAL HEADER DAN FOOTER HARUS DI ATAS MAP === */
    #ormasModal .sticky {
        z-index: 100 !important;
    }
    
    /* === MAP CONTAINER DENGAN Z-INDEX RENDAH === */
    #modalMapContainer {
        z-index: 1 !important;
        position: relative;
    }
    
    #modalMap {
        z-index: 1 !important;
        position: relative;
    }
    
    /* === MAP LOADING TEXT DI ATAS MAP === */
    #mapLoadingText {
        z-index: 2 !important;
    }
    
    /* === PASTIKAN MODAL TIDAK TERCOVER MAP === */
    #ormasModal {
        z-index: 9999 !important;
    }
    
    #ormasModal .bg-white {
        position: relative;
        z-index: 10000;
    }
    
    /* === OVERLAY MODAL === */
    .fixed.inset-0.bg-black\/50 {
        z-index: 9998 !important;
    }

    /* === TRUNCATE TEXT UNTUK MOBILE === */
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    /* === RESPONSIVE GRID UNTUK CARD === */
    @media (max-width: 640px) {
        .grid-cols-1 {
            grid-template-columns: 1fr !important;
        }
    }

    /* === RESPONSIVE UNTUK MOBILE 320px, 375px, 425px === */
    @media (max-width: 374px) {
        .container {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .text-xl {
            font-size: 16px !important;
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
        .p-3 {
            padding: 6px !important;
        }
        .p-4 {
            padding: 8px !important;
        }
        .px-3 {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
        .py-2 {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }
        .py-8 {
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }
        .mb-4 {
            margin-bottom: 8px !important;
        }
        .mb-6 {
            margin-bottom: 12px !important;
        }
        .mt-2 {
            margin-top: 4px !important;
        }
        .mt-4 {
            margin-top: 8px !important;
        }
        .gap-2 {
            gap: 3px !important;
        }
        .gap-3 {
            gap: 4px !important;
        }
        .rounded-xl {
            border-radius: 8px !important;
        }
        .rounded-lg {
            border-radius: 6px !important;
        }
        .h-48 {
            height: 140px !important;
        }
    }

    @media (min-width: 375px) and (max-width: 424px) {
        .container {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .text-xl {
            font-size: 18px !important;
        }
        .text-sm {
            font-size: 12px !important;
        }
        .p-3 {
            padding: 8px !important;
        }
        .p-4 {
            padding: 10px !important;
        }
        .px-3 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .py-8 {
            padding-top: 16px !important;
            padding-bottom: 16px !important;
        }
        .mb-4 {
            margin-bottom: 10px !important;
        }
        .mb-6 {
            margin-bottom: 14px !important;
        }
        .gap-3 {
            gap: 6px !important;
        }
        .h-48 {
            height: 160px !important;
        }
    }

    @media (min-width: 425px) and (max-width: 640px) {
        .text-xl {
            font-size: 20px !important;
        }
        .py-8 {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }
        .p-3 {
            padding: 10px !important;
        }
        .p-4 {
            padding: 12px !important;
        }
        .mb-4 {
            margin-bottom: 12px !important;
        }
        .mb-6 {
            margin-bottom: 16px !important;
        }
        .gap-3 {
            gap: 8px !important;
        }
        .h-48 {
            height: 180px !important;
        }
    }

    /* ============================================ */
    /* PAGINATION - RESPONSIVE                     */
    /* ============================================ */
    @media (max-width: 374px) {
        .pagination-wrapper {
            gap: 3px !important;
        }
        .pagination-arrow {
            min-width: 26px !important;
            padding-left: 4px !important;
            padding-right: 4px !important;
            font-size: 9px !important;
        }
        .pagination-arrow i {
            font-size: 8px !important;
        }
        .pagination-number {
            min-width: 22px !important;
            padding-left: 4px !important;
            padding-right: 4px !important;
            font-size: 9px !important;
            border-radius: 4px !important;
        }
        .pagination-ellipsis {
            font-size: 9px !important;
            min-width: 14px !important;
            padding-left: 2px !important;
            padding-right: 2px !important;
        }
        .pagination-arrow, .pagination-number {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
            border-width: 1px !important;
        }
    }

    @media (min-width: 375px) and (max-width: 424px) {
        .pagination-wrapper {
            gap: 4px !important;
        }
        .pagination-arrow {
            min-width: 30px !important;
            padding-left: 6px !important;
            padding-right: 6px !important;
            font-size: 10px !important;
        }
        .pagination-arrow i {
            font-size: 9px !important;
        }
        .pagination-number {
            min-width: 28px !important;
            padding-left: 6px !important;
            padding-right: 6px !important;
            font-size: 10px !important;
        }
        .pagination-ellipsis {
            font-size: 10px !important;
            min-width: 18px !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
        }
    }

    @media (min-width: 425px) and (max-width: 640px) {
        .pagination-wrapper {
            gap: 4px !important;
        }
        .pagination-arrow {
            min-width: 32px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
            font-size: 11px !important;
        }
        .pagination-arrow i {
            font-size: 10px !important;
        }
        .pagination-number {
            min-width: 30px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
            font-size: 11px !important;
        }
        .pagination-ellipsis {
            font-size: 11px !important;
            min-width: 20px !important;
            padding-left: 4px !important;
            padding-right: 4px !important;
        }
    }

    @media (min-width: 641px) and (max-width: 1024px) {
        .pagination-arrow {
            min-width: 36px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
            font-size: 13px !important;
        }
        .pagination-number {
            min-width: 34px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
            font-size: 13px !important;
        }
        .pagination-ellipsis {
            font-size: 13px !important;
            min-width: 24px !important;
        }
        .pagination-wrapper {
            gap: 5px !important;
        }
    }

    @media (min-width: 1025px) {
        .pagination-arrow {
            min-width: 40px !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
            font-size: 14px !important;
        }
        .pagination-number {
            min-width: 38px !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
            font-size: 14px !important;
        }
        .pagination-ellipsis {
            font-size: 14px !important;
            min-width: 28px !important;
        }
        .pagination-wrapper {
            gap: 6px !important;
        }
    }
</style>

<script>
    // ============================================
    // DATA KELURAHAN PER KECAMATAN
    // ============================================
    const kelurahanData = {
        @foreach(\App\Models\Kecamatan::all() as $kec)
            '{{ $kec->id }}': [
                @foreach($kec->kelurahan as $kel)
                    { id: '{{ $kel->id }}', nama: '{{ $kel->nama }}' },
                @endforeach
            ],
        @endforeach
    };

    // ============================================
    // FUNGSI CLEAR SEARCH & FILTER
    // ============================================
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchForm').submit();
    }

    function clearFilter(type) {
        const form = document.getElementById('searchForm');
        if (type === 'bentuk') {
            document.getElementById('filterBentuk').value = '';
        } else if (type === 'bidang') {
            document.getElementById('filterBidang').value = '';
        } else if (type === 'kecamatan') {
            document.getElementById('filterKecamatan').value = '';
            document.getElementById('filterKelurahan').innerHTML = '<option value="">Semua Kelurahan</option>';
        } else if (type === 'kelurahan') {
            document.getElementById('filterKelurahan').value = '';
        }
        form.submit();
    }

    // ============================================
    // FUNGSI UPDATE KELURAHAN BERDASARKAN KECAMATAN
    // ============================================
    function updateKelurahan() {
        const kecamatanId = document.getElementById('filterKecamatan').value;
        const kelurahanSelect = document.getElementById('filterKelurahan');
        const selectedKelurahan = '{{ request('kelurahan') }}';
        
        kelurahanSelect.innerHTML = '<option value="">Semua Kelurahan</option>';
        
        if (kecamatanId && kelurahanData[kecamatanId]) {
            const sortedKelurahan = [...kelurahanData[kecamatanId]].sort((a, b) => a.nama.localeCompare(b.nama));
            sortedKelurahan.forEach(function(kelurahan) {
                const option = document.createElement('option');
                option.value = kelurahan.id;
                option.textContent = kelurahan.nama;
                if (kelurahan.id == selectedKelurahan) {
                    option.selected = true;
                }
                kelurahanSelect.appendChild(option);
            });
        }
    }

    // ============================================
    // EVENT LISTENER UNTUK FILTER
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const kecamatanId = document.getElementById('filterKecamatan').value;
        if (kecamatanId) {
            updateKelurahan();
        }
    });

    document.getElementById('filterKecamatan').addEventListener('change', function() {
        updateKelurahan();
        this.closest('form').submit();
    });
    document.getElementById('filterKelurahan').addEventListener('change', function() {
        this.closest('form').submit();
    });
    document.getElementById('filterBentuk').addEventListener('change', function() {
        this.closest('form').submit();
    });
    document.getElementById('filterBidang').addEventListener('change', function() {
        this.closest('form').submit();
    });

    // ============================================
    // VARIABLE MAP
    // ============================================
    let modalMapInstance = null;
    let modalMarkerInstance = null;
    let isMapInitialized = false;
    let currentLat = null;
    let currentLng = null;

    // ============================================
    // FUNGSI BUKA GOOGLE MAPS
    // ============================================
    function openGoogleMaps() {
        if (currentLat !== null && currentLng !== null) {
            const url = `https://www.google.com/maps/search/?api=1&query=${currentLat},${currentLng}`;
            window.open(url, '_blank');
        } else {
            alert('Koordinat lokasi tidak tersedia');
        }
    }

    // ============================================
    // OPEN MODAL
    // ============================================
    function openModal(id) {
        const modal = document.getElementById('ormasModal');
        const loading = document.getElementById('modalLoading');
        const content = document.getElementById('modalContent');
        const mapLoadingText = document.getElementById('mapLoadingText');
        const googleBtn = document.getElementById('googleMapsBtn');
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loading.classList.remove('hidden');
        content.classList.add('hidden');
        if (mapLoadingText) mapLoadingText.style.display = 'flex';
        if (googleBtn) {
            googleBtn.disabled = true;
            googleBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memuat...';
        }
        
        isMapInitialized = false;
        currentLat = null;
        currentLng = null;

        fetch('/ormas/detail/' + id)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const data = result.data;
                    fillModalData(data);
                    loading.classList.add('hidden');
                    content.classList.remove('hidden');
                    
                    setTimeout(function() {
                        initModalMap(data);
                    }, 400);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loading.innerHTML = '<p class="text-red-500 text-sm">Gagal memuat data</p>';
                if (googleBtn) {
                    googleBtn.disabled = false;
                    googleBtn.innerHTML = '<i class="fas fa-directions mr-2"></i> Buka di Google Maps';
                }
            });
    }

    // ============================================
    // INIT MODAL MAP
    // ============================================
    function initModalMap(data) {
        const mapContainer = document.getElementById('modalMap');
        const loadingText = document.getElementById('mapLoadingText');
        const googleBtn = document.getElementById('googleMapsBtn');
        
        const lat = parseFloat(data.latitude);
        const lng = parseFloat(data.longitude);
        
        if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
            mapContainer.innerHTML = `
                <div class="w-full h-48 sm:h-64 rounded-lg border border-gray-300 bg-gray-100 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marker-alt text-3xl sm:text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500 text-xs sm:text-sm">Koordinat lokasi tidak tersedia</p>
                    </div>
                </div>
            `;
            if (loadingText) loadingText.style.display = 'none';
            if (googleBtn) {
                googleBtn.disabled = true;
                googleBtn.innerHTML = '<i class="fas fa-directions mr-2"></i> Google Maps (Tidak Tersedia)';
                googleBtn.className = 'inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-400 text-white text-xs sm:text-sm rounded-lg cursor-not-allowed';
            }
            return;
        }
        
        currentLat = lat;
        currentLng = lng;
        
        if (loadingText) loadingText.style.display = 'none';
        
        if (googleBtn) {
            googleBtn.disabled = false;
            googleBtn.innerHTML = '<i class="fas fa-directions mr-2"></i> Buka di Google Maps';
            googleBtn.className = 'inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-green-600 text-white text-xs sm:text-sm rounded-lg hover:bg-green-700 transition';
        }
        
        if (modalMapInstance) {
            modalMapInstance.remove();
            modalMapInstance = null;
            modalMarkerInstance = null;
            isMapInitialized = false;
        }
        
        setTimeout(function() {
            modalMapInstance = L.map('modalMap', {
                zoomControl: true,
                attributionControl: true
            }).setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(modalMapInstance);
            
            modalMarkerInstance = L.marker([lat, lng], {
                draggable: false
            }).addTo(modalMapInstance);
            
            const popupContent = `<b>${data.nama}</b><br>${data.alamat_kesekretariatan || 'Alamat tidak tersedia'}`;
            modalMarkerInstance.bindPopup(popupContent);
            
            setTimeout(function() {
                if (modalMapInstance) {
                    modalMapInstance.invalidateSize();
                    modalMarkerInstance.openPopup();
                }
            }, 300);
            
            isMapInitialized = true;
        }, 100);
    }

    // ============================================
    // FILL MODAL DATA - NO TELEPON DIHAPUS
    // ============================================
    function fillModalData(data) {
        document.getElementById('det_nama').textContent = data.nama || '-';
        document.getElementById('det_singkatan').textContent = data.singkatan || '-';
        
        // ===== PERUBAHAN NO AHU/SKT DI MODAL =====
        const nomorReg = data.nomor_registrasi;
        document.getElementById('det_nomor_registrasi').textContent = (nomorReg && nomorReg.trim() !== '') ? 'Ada' : '-';
        // =========================================
        
        document.getElementById('det_jenis').textContent = data.jenis_ormas?.nama || '-';
        document.getElementById('det_bidang').textContent = data.bidang_kegiatan?.nama || '-';
        
        const statusText = data.is_active ? 'Aktif' : 'Nonaktif';
        const statusColor = data.is_active ? 'text-green-600' : 'text-red-600';
        document.getElementById('det_status').innerHTML = `<span class="${statusColor} font-semibold">${statusText}</span>`;
        
        const pelaporanLabels = {
            'sudah': 'Sudah',
            'belum': 'Belum',
            'tidak_ada': '-'
        };
        const pelaporanColors = {
            'sudah': 'text-green-600',
            'belum': 'text-yellow-600',
            'tidak_ada': 'text-gray-500'
        };
        const pelaporanValue = data.pelaporan || 'belum';
        document.getElementById('det_pelaporan').innerHTML = 
            `<span class="font-semibold ${pelaporanColors[pelaporanValue]}">${pelaporanLabels[pelaporanValue]}</span>`;
        
        document.getElementById('det_alamat').textContent = data.alamat_kesekretariatan || '-';
        document.getElementById('det_kecamatan').textContent = data.kecamatan?.nama || '-';
        document.getElementById('det_kelurahan').textContent = data.kelurahan?.nama || data.kelurahan_id || '-';
        document.getElementById('det_email').textContent = data.email || '-';
        document.getElementById('modalTitle').textContent = 'Detail ' + data.nama;

        // ===== DATA PENGURUS - TANPA NO TELEPON & TANPA ALAMAT =====
        const pengurusContainer = document.getElementById('det_pengurus');
        const pengurusList = data.pengurus || [];
        
        // Daftar Jabatan berdasarkan urutan
        const jabatanList = ['Ketua', 'Sekretaris', 'Bendahara'];
        
        pengurusContainer.innerHTML = '';
        
        if (pengurusList.length > 0) {
            pengurusList.forEach(function(pengurus, index) {
                // Ambil jabatan berdasarkan urutan, jika lebih dari 3 maka "Pengurus"
                const jabatan = jabatanList[index] || 'Pengurus';
                
                const card = document.createElement('div');
                card.className = 'border border-gray-200 rounded-lg p-3 bg-white';
                card.innerHTML = `
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-bold text-xs sm:text-sm">
                            ${index + 1}
                        </div>
                        <h5 class="font-semibold text-gray-800 text-xs sm:text-sm">${jabatan}</h5>
                    </div>
                    <div class="space-y-1 text-xs sm:text-sm">
                        <p><span class="text-gray-500">Nama:</span> <span class="font-medium">${pengurus.nama || '-'}</span></p>
                    </div>
                `;
                pengurusContainer.appendChild(card);
            });
        } else {
            pengurusContainer.innerHTML = `
                <div class="col-span-2 text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <i class="fas fa-users text-2xl sm:text-3xl text-gray-300 mb-2 block"></i>
                    <p class="text-xs sm:text-sm text-gray-400">Belum ada data pengurus</p>
                </div>
            `;
        }
    }

    // ============================================
    // CLOSE MODAL
    // ============================================
    function closeModal() {
        document.getElementById('ormasModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        currentLat = null;
        currentLng = null;
        
        if (modalMapInstance) {
            modalMapInstance.remove();
            modalMapInstance = null;
            modalMarkerInstance = null;
            isMapInitialized = false;
        }
    }

    // ============================================
    // CLOSE MODAL SAAT KLIK DI LUAR
    // ============================================
    document.getElementById('ormasModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // ============================================
    // REFRESH MAP SAAT MODAL DITAMPILKAN
    // ============================================
    const modalObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const modal = document.getElementById('ormasModal');
                if (!modal.classList.contains('hidden') && modalMapInstance) {
                    setTimeout(function() {
                        if (modalMapInstance) {
                            modalMapInstance.invalidateSize();
                        }
                    }, 300);
                }
            }
        });
    });

    const modalElement = document.getElementById('ormasModal');
    if (modalElement) {
        modalObserver.observe(modalElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    }
</script>
@endsection