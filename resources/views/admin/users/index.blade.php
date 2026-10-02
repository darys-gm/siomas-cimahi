@extends('layouts.admin')

@section('title', 'Manajemen ORMAS')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen ORMAS</h1>
    </div>

    <!-- ===== NOTIFIKASI SESSION ===== -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
    @endif

    <!-- ===== SEARCH & FILTER ===== -->
    <div class="bg-gray-50 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4" id="filterForm">
            <!-- Row 1: Search - Full Width -->
            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari ORMAS</label>
                <div class="relative">
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                           placeholder="Cari berdasarkan nama ORMAS..." 
                           class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    @if(request('search'))
                    <button type="button" onclick="clearSearch()" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times-circle text-lg"></i>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Row 2: Filter Dropdowns -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                <!-- Filter Kecamatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                    <div class="relative">
                        <select name="kecamatan" id="filterKecamatan" class="w-full px-4 py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm appearance-none bg-white">
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
                            <i class="fas fa-times-circle text-sm"></i>
                        </button>
                        @endif
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Kelurahan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                    <div class="relative">
                        <select name="kelurahan" id="filterKelurahan" class="w-full px-4 py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm appearance-none bg-white">
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
                            <i class="fas fa-times-circle text-sm"></i>
                        </button>
                        @endif
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Bentuk Ormas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bentuk Ormas</label>
                    <div class="relative">
                        <select name="bentuk" id="filterBentuk" class="w-full px-4 py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm appearance-none bg-white">
                            <option value="">Semua Bentuk</option>
                            <option value="Yayasan" {{ request('bentuk') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                            <option value="Perkumpulan" {{ request('bentuk') == 'Perkumpulan' ? 'selected' : '' }}>Perkumpulan</option>
                        </select>
                        @if(request('bentuk'))
                        <button type="button" onclick="clearFilter('bentuk')" 
                                class="absolute right-7 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i class="fas fa-times-circle text-sm"></i>
                        </button>
                        @endif
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Bidang Kegiatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Kegiatan</label>
                    <div class="relative">
                        <select name="bidang" id="filterBidang" class="w-full px-4 py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm appearance-none bg-white">
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
                            <i class="fas fa-times-circle text-sm"></i>
                        </button>
                        @endif
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- ===== FILTER STATUS AKTIF/NONAKTIF ===== -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="relative">
                        <select name="status_aktif" id="filterStatusAktif" class="w-full px-4 py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm appearance-none bg-white">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status_aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status_aktif') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @if(request('status_aktif') !== null && request('status_aktif') !== '')
                        <button type="button" onclick="clearFilter('status_aktif')" 
                                class="absolute right-7 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i class="fas fa-times-circle text-sm"></i>
                        </button>
                        @endif
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- ===== FILTER STATUS PELAPORAN ===== -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pelaporan</label>
                    <div class="relative">
                        <select name="status_pelaporan" id="filterStatusPelaporan" class="w-full px-4 py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm appearance-none bg-white">
                            <option value="">Semua Pelaporan</option>
                            <option value="sudah" {{ request('status_pelaporan') == 'sudah' ? 'selected' : '' }}>Sudah</option>
                            <option value="belum" {{ request('status_pelaporan') == 'belum' ? 'selected' : '' }}>Belum</option>
                            <option value="tidak_ada" {{ request('status_pelaporan') == 'tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                        </select>
                        @if(request('status_pelaporan'))
                        <button type="button" onclick="clearFilter('status_pelaporan')" 
                                class="absolute right-7 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i class="fas fa-times-circle text-sm"></i>
                        </button>
                        @endif
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tombol Cari & Reset -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        <i class="fas fa-search mr-1"></i>
                    </button>
                    @if(request('search') || request('bentuk') || request('bidang') || request('kecamatan') || request('kelurahan') || request('status_aktif') !== null || request('status_pelaporan'))
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-sm font-medium whitespace-nowrap">
                        <i class="fas fa-times"></i> Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- ===== HASIL PENCARIAN ===== -->
    @if(request('search') || request('bentuk') || request('bidang') || request('kecamatan') || request('kelurahan') || request('status_aktif') !== null || request('status_pelaporan'))
    <div class="mb-4 text-sm text-gray-500">
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
        @if(request('status_aktif') !== null && request('status_aktif') !== '')
        @if(request('search') || request('kecamatan') || request('kelurahan') || request('bentuk') || request('bidang')) <span class="mx-1">|</span> @endif
        <span class="font-medium text-gray-700">Status: {{ request('status_aktif') == '1' ? 'Aktif' : 'Nonaktif' }}</span>
        @endif
        @if(request('status_pelaporan'))
        @if(request('search') || request('kecamatan') || request('kelurahan') || request('bentuk') || request('bidang') || request('status_aktif') !== null) <span class="mx-1">|</span> @endif
        <span class="font-medium text-gray-700">Pelaporan: 
            @if(request('status_pelaporan') == 'sudah') Sudah
            @elseif(request('status_pelaporan') == 'belum') Belum
            @elseif(request('status_pelaporan') == 'tidak_ada') Tidak Ada
            @endif
        </span>
        @endif
        <span class="ml-2">({{ $ormas->total() }} ORMAS ditemukan)</span>
    </div>
    @endif

    <!-- ===== BULK ACTION DROPDOWN ===== -->
    <div class="mb-4 flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2">
            <span class="text-sm font-medium text-gray-700">Aksi Massal:</span>
            
            <!-- Dropdown Pelaporan -->
            <div class="relative">
                <select id="bulkPelaporan" class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Update Pelaporan</option>
                    <option value="sudah">Sudah</option>
                    <option value="belum">Belum</option>
                    <option value="tidak_ada">Tidak Ada</option>
                </select>
            </div>

            <!-- Dropdown Status Aktif/Nonaktif -->
            <div class="relative">
                <select id="bulkStatus" class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Update Status</option>
                    <option value="aktif">Aktifkan Semua</option>
                    <option value="nonaktif">Nonaktifkan Semua</option>
                </select>
            </div>

            <button onclick="executeBulkAction()" 
                    class="px-4 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-play mr-1"></i> Jalankan
            </button>
        </div>
        <div id="bulkResult" class="text-sm hidden"></div>
    </div>

    <!-- Daftar ORMAS yang Disetujui -->
    <div>
        <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
            <i class="fas fa-folder-open text-blue-600"></i>
            ORMAS Terverifikasi
            <span class="text-sm text-gray-400 font-normal">({{ $ormas->total() }} ORMAS)</span>
        </h2>

        @if($ormas->count() > 0)
        <div class="space-y-3">
            @foreach($ormas as $item)
            <div class="border border-gray-200 rounded-lg overflow-hidden {{ !$item->is_active ? 'opacity-60' : '' }}">
                <!-- Header Folder -->
                <div class="folder-header bg-gray-50 hover:bg-gray-100 transition cursor-pointer p-4 flex items-center justify-between" onclick="toggleFolder('folder-{{ $item->id }}')">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-folder text-yellow-500 text-xl"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $item->nama }}</h3>
                            <div class="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                                <span><i class="fas fa-tag mr-1"></i> {{ $item->jenisOrmas->nama ?? '-' }}</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $item->kecamatan->nama ?? '-' }}</span>
                                <span><i class="fas fa-users mr-1"></i> {{ $item->pengurus->count() }} Pengurus</span>
                                <span><i class="fas fa-user-friends mr-1"></i> {{ $item->jumlah_anggota ?? 0 }} Anggota</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- ===== DROPDOWN PELAPORAN ===== -->
                        <div class="relative" onclick="event.stopPropagation();">
                            <select onchange="updatePelaporan({{ $item->id }}, this.value)" 
                                    class="text-xs px-3 py-1 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer">
                                <option value="belum" {{ ($item->pelaporan ?? 'belum') == 'belum' ? 'selected' : '' }}>Belum</option>
                                <option value="sudah" {{ ($item->pelaporan ?? 'belum') == 'sudah' ? 'selected' : '' }}>Sudah</option>
                                <option value="tidak_ada" {{ ($item->pelaporan ?? 'belum') == 'tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                            </select>
                        </div>
                        
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_active_badge }}">
                            <i class="fas {{ $item->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $item->status_active_text }}
                        </span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                        </span>
                        <!-- ===== TOMBOL HAPUS ORMAS ===== -->
                        <button onclick="confirmHapusOrmas({{ $item->id }}, '{{ addslashes($item->nama) }}')" 
                                class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 hover:bg-red-200 transition">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="icon-folder-{{ $item->id }}"></i>
                    </div>
                </div>

                <!-- Isi Folder -->
                <div id="folder-{{ $item->id }}" class="folder-content hidden p-4 border-t border-gray-200 bg-white">
                    <!-- Data ORMAS -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-sm font-semibold text-gray-700">
                                <i class="fas fa-building text-blue-600 mr-1"></i> Data ORMAS
                            </h4>
                            <div class="flex items-center gap-2">
                                <button onclick="confirmToggleActive({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->is_active ? 'true' : 'false' }})" 
                                        class="text-xs px-3 py-1 rounded-lg transition {{ $item->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                    <i class="fas {{ $item->is_active ? 'fa-times-circle' : 'fa-check-circle' }} mr-1"></i>
                                    {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                                <button onclick="editOrmas({{ $item->id }})" class="text-xs text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
                            <div><span class="text-gray-500">Nama:</span> <span class="font-medium">{{ $item->nama }}</span></div>
                            <div><span class="text-gray-500">Singkatan:</span> <span class="font-medium">{{ $item->singkatan ?? '-' }}</span></div>
                            <div><span class="text-gray-500">No AHU/SKT:</span> <span class="font-medium">{{ $item->nomor_registrasi ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Bentuk Ormas:</span> <span class="font-medium">{{ $item->jenisOrmas->nama ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Bidang Kegiatan Ormas:</span> <span class="font-medium">{{ $item->bidangKegiatan->nama ?? '-' }}</span></div>
                            <div class="col-span-2"><span class="text-gray-500">Alamat:</span> <span class="font-medium">{{ $item->alamat_kesekretariatan ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Kecamatan:</span> <span class="font-medium">{{ $item->kecamatan->nama ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Kelurahan:</span> <span class="font-medium">{{ $item->kelurahan->nama ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Latitude:</span> <span class="font-medium">{{ $item->latitude ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Longitude:</span> <span class="font-medium">{{ $item->longitude ?? '-' }}</span></div>
                            <div><span class="text-gray-500">No Telepon:</span> <span class="font-medium">{{ $item->no_telepon ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $item->email ?? '-' }}</span></div>
                            <div><span class="text-gray-500">Status:</span> 
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $item->status_active_badge }}">
                                    {{ $item->status_active_text }}
                                </span>
                            </div>
                            <!-- ===== STATUS PELAPORAN ===== -->
                            <div><span class="text-gray-500">Pelaporan:</span> 
                                @php
                                    $pelaporanLabels = [
                                        'sudah' => 'Sudah',
                                        'belum' => 'Belum',
                                        'tidak_ada' => 'Tidak Ada'
                                    ];
                                    $pelaporanColors = [
                                        'sudah' => 'bg-green-100 text-green-700',
                                        'belum' => 'bg-yellow-100 text-yellow-700',
                                        'tidak_ada' => 'bg-gray-100 text-gray-700'
                                    ];
                                    $pelaporanValue = $item->pelaporan ?? 'belum';
                                @endphp
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $pelaporanColors[$pelaporanValue] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $pelaporanLabels[$pelaporanValue] ?? 'Belum' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- ===== DATA KEANGGOTAAN ===== -->
                        <div class="mt-3 p-3">
                            <h5 class="text-sm font-semibold text-black-700 mb-2">
                                <i class="fas fa-users text-blue-600 mr-1"></i> Data Keanggotaan
                            </h5>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Total Anggota:</span>
                                    <span class="font-medium text-black-600 ml-1">{{ $item->jumlah_anggota ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Perempuan:</span>
                                    <span class="font-medium text-black-600 ml-1">{{ $item->jumlah_anggota_perempuan ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Perempuan (16-30):</span>
                                    <span class="font-medium text-black-600 ml-1">{{ $item->anggota_perempuan_rentang_16_30 ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Laki-laki:</span>
                                    <span class="font-medium text-black-600 ml-1">{{ $item->jumlah_anggota_laki_laki ?? 0 }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Laki-laki (16-30):</span>
                                    <span class="font-medium text-black-600 ml-1">{{ $item->anggota_laki_laki_rentang_16_30 ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- ===== LOKASI PETA ===== -->
                        @if($item->latitude && $item->longitude)
                        <div class="mt-3">
                            <h5 class="text-xs font-semibold text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> Lokasi Peta
                            </h5>
                            <div id="detailMap-{{ $item->id }}" class="w-full h-48 rounded-lg border border-gray-300 overflow-hidden"></div>
                        </div>
                        @else
                        <div class="mt-3 text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <i class="fas fa-map-marker-alt text-gray-300 text-2xl block mb-1"></i>
                            <p class="text-xs text-gray-400">Tidak ada data koordinat</p>
                        </div>
                        @endif
                    </div>

                    <!-- ============================================ -->
                    <!-- DATA PENGURUS - DENGAN JABATAN DINAMIS       -->
                    <!-- ============================================ -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-sm font-semibold text-gray-700">
                                <i class="fas fa-users text-blue-600 mr-1"></i> Data Pengurus
                            </h4>
                            <button onclick="tambahPengurus({{ $item->id }})" 
                                    class="text-xs bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus"></i> Tambah Pengurus
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @php
                                $pengurusList = $item->pengurus;

                                // Mapping jabatan berdasarkan urutan
                                $jabatanList = ['Ketua', 'Sekretaris', 'Bendahara'];
                            @endphp

                            @if($pengurusList->count() > 0)
                                @foreach($pengurusList as $index => $pengurus)
                                @php
                                    // Ambil jabatan berdasarkan urutan, jika lebih dari 3 maka "Pengurus"
                                    $jabatan = $jabatanList[$index] ?? 'Pengurus';

                                    // Warna icon beda untuk jabatan inti (opsional, biar keren)
                                    $iconColor = match($jabatan) {
                                        'Ketua'      => 'text-blue-600',
                                        'Sekretaris' => 'text-blue-600',
                                        'Bendahara'  => 'text-blue-600',
                                        default      => 'text-blue-600',
                                    };
                                @endphp
                                <div class="p-3 border border-gray-200 rounded-lg bg-white">
                                    <h5 class="font-semibold {{ $iconColor }} text-sm mb-2">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $jabatan }}
                                    </h5>
                                    <div class="space-y-1 text-sm">
                                        <p><span class="text-gray-500">Nama:</span> <span class="font-medium">{{ $pengurus->nama }}</span></p>
                                        <p><span class="text-gray-500">Alamat:</span> {{ $pengurus->alamat ?? '-' }}</p>
                                        <p><span class="text-gray-500">No Telepon:</span> {{ $pengurus->no_hp ?? '-' }}</p>
                                        
                                        <!-- ===== TOMBOL EDIT & HAPUS ===== -->
                                        <div class="flex items-center gap-3 mt-2 pt-2 border-t border-gray-100">
                                            <button onclick="editPengurus({{ $pengurus->id }})" 
                                                    class="text-xs text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button onclick="confirmHapusPengurus({{ $pengurus->id }}, '{{ addslashes($pengurus->nama) }}')" 
                                                    class="text-xs text-red-600 hover:text-red-800 inline-flex items-center gap-1">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-span-3 text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                    <p class="text-sm text-gray-400">Belum ada data pengurus</p>
                                    <button onclick="tambahPengurus({{ $item->id }})" 
                                            class="mt-2 text-xs bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-plus"></i> Tambah Pengurus
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- ===== PAGINATION ===== -->
        <div class="mt-4">
            {{ $ormas->links() }}
        </div>
        
        @else
        <div class="text-center py-8 bg-gray-50 rounded-lg">
            <i class="fas fa-folder-open text-4xl text-gray-300 block mb-3"></i>
            <p class="text-gray-500">Belum ada ORMAS yang terverifikasi</p>
        </div>
        @endif
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI BULK ACTION                -->
<!-- ============================================ -->
<div id="confirmBulkModal" class="hidden fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
        <div class="text-center">
            <div id="bulkIcon" class="text-5xl mb-3">
                <i class="fas fa-question-circle text-yellow-500"></i>
            </div>
            <h3 id="bulkTitle" class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Aksi Massal</h3>
            <p id="bulkMessage" class="text-gray-600 text-sm">Apakah Anda yakin ingin melakukan aksi ini?</p>
            <p id="bulkDetail" class="text-sm font-semibold text-blue-600 mt-1"></p>
            <p class="text-xs text-gray-400 mt-2">Aksi ini akan mempengaruhi semua ORMAS yang terverifikasi!</p>
            <div class="flex gap-2 mt-4 justify-center">
                <button id="bulkConfirmBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-check mr-1"></i> Ya, Lanjutkan
                </button>
                <button onclick="closeBulkConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI HAPUS ORMAS                 -->
<!-- ============================================ -->
<div id="confirmDeleteModal" class="hidden fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6">
        <div class="text-center">
            <div class="text-5xl mb-3 text-red-500">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p id="confirmDeleteMessage" class="text-gray-600 text-sm">Apakah Anda yakin ingin menghapus data ini?</p>
            <p id="confirmDeleteName" class="text-sm font-semibold text-red-600 mt-1"></p>
            <p class="text-xs text-gray-400 mt-2">Data yang dihapus tidak dapat dikembalikan!</p>
            <div class="flex gap-2 mt-4 justify-center">
                <button onclick="executeDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
                <button onclick="closeConfirmDelete()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI HAPUS PENGURUS              -->
<!-- ============================================ -->
<div id="confirmDeletePengurusModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[10000] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-6" style="position: relative; z-index: 10001;">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center animate-pulse">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 text-sm mb-2">Apakah Anda yakin ingin menghapus pengurus ini?</p>
            <p id="confirmDeletePengurusName" class="text-sm font-semibold text-red-600 mt-1 break-words"></p>
            <p class="text-xs text-gray-400 mt-3">
                <i class="fas fa-info-circle mr-1"></i>
                Data yang dihapus tidak dapat dikembalikan!
            </p>

            <div class="flex gap-2 mt-5 justify-center">
                <button onclick="executeDeletePengurus()" 
                        class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium inline-flex items-center gap-2 shadow-lg shadow-red-200">
                    <i class="fas fa-trash"></i> Ya, Hapus
                </button>
                <button onclick="closeConfirmDeletePengurus()" 
                        class="px-5 py-2.5 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium inline-flex items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI TOGGLE AKTIF/NONAKTIF      -->
<!-- ============================================ -->
<div id="confirmToggleModal" class="hidden fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6">
        <div class="text-center">
            <div id="toggleIcon" class="text-5xl mb-3">
                <i class="fas fa-question-circle text-yellow-500"></i>
            </div>
            <h3 id="toggleTitle" class="text-xl font-bold text-gray-800 mb-2">Konfirmasi</h3>
            <p id="toggleMessage" class="text-gray-600 text-sm">Apakah Anda yakin ingin mengubah status ORMAS ini?</p>
            <p id="toggleName" class="text-sm font-semibold text-blue-600 mt-1"></p>
            <div class="flex gap-2 mt-4 justify-center">
                <button id="toggleConfirmBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-check mr-1"></i> Ya
                </button>
                <button onclick="closeToggleModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL NOTIFIKASI                             -->
<!-- ============================================ -->
<div id="notificationModal" class="hidden fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6">
        <div class="text-center">
            <div id="notificationIcon" class="text-5xl mb-3">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <h3 id="notificationTitle" class="text-xl font-bold text-gray-800 mb-2">Berhasil!</h3>
            <p id="notificationMessage" class="text-gray-600 text-sm">Data berhasil disimpan.</p>
            <button onclick="closeNotification()" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                OK
            </button>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL EDIT ORMAS (DENGAN MAP)                -->
<!-- ============================================ -->
<div id="editOrmasModal" class="hidden fixed inset-0 bg-black/50 z-[9998] flex items-start justify-center pt-8 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 my-4">
        <div class="sticky top-0 bg-white z-[60] pb-3 mb-4 border-b border-gray-200" style="position: sticky; top: 0; background: white; z-index: 60;">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-edit text-blue-600 mr-2"></i> Edit Data ORMAS
                </h3>
                <button onclick="closeModal('editOrmasModal')" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="editOrmasForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama ORMAS <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="ormas_nama" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Singkatan</label>
                    <input type="text" name="singkatan" id="ormas_singkatan" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No AHU/SKT</label>
                    <input type="text" name="nomor_registrasi" id="ormas_nomor_registrasi" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ada</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bentuk Ormas</label>
                    <select name="jenis_ormas_id" id="ormas_jenis" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Bentuk Ormas</option>
                        <option value="Yayasan">Yayasan</option>
                        <option value="Perkumpulan">Perkumpulan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Kegiatan Ormas</label>
                    <select name="bidang_kegiatan_id" id="ormas_bidang" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Bidang Kegiatan Ormas</option>
                        <option value="Keagamaan dan Kepercayaan">Keagamaan dan Kepercayaan</option>
                        <option value="Sosial, Kemanusiaan dan Kemasyarakatan">Sosial, Kemanusiaan dan Kemasyarakatan</option>
                        <option value="Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga">Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga</option>
                        <option value="Kepemudaan, Olahraga dan Seni Budaya">Kepemudaan, Olahraga dan Seni Budaya</option>
                        <option value="Pendidikan dan Pemberdayaan SDM">Pendidikan dan Pemberdayaan SDM</option>
                        <option value="Profesi dan Keahlian">Profesi dan Keahlian</option>
                        <option value="Lingkungan Hidup dan Kebencanaan">Lingkungan Hidup dan Kebencanaan</option>
                        <option value="Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat">Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat</option>
                        <option value="Kebangsaan dan Bela Negara">Kebangsaan dan Bela Negara</option>
                        <option value="Hukum, HAM dan Advokasi Publik">Hukum, HAM dan Advokasi Publik</option>
                        <option value="Komunitas, Minat dan Hobi">Komunitas, Minat dan Hobi</option>
                        <option value="Bidang Kegiatan Lainnya">Bidang Kegiatan Lainnya</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Kesekretariatan</label>
                    <input type="text" name="alamat_kesekretariatan" id="ormas_alamat" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Koordinat Lokasi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Koordinat Lokasi</label>
                    <p class="text-xs text-gray-400 mb-2">Klik pada peta, drag marker, atau masukkan koordinat manual</p>
                    
                    <div id="editMap" class="w-full h-64 md:h-72 rounded-lg border border-gray-300 overflow-hidden mb-3" style="position: relative; z-index: 1;"></div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Latitude</label>
                            <input type="text" name="latitude" id="edit_latitude" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                   placeholder="Contoh: -6.870367">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Longitude</label>
                            <input type="text" name="longitude" id="edit_longitude" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                   placeholder="Contoh: 107.554704">
                        </div>
                    </div>
                    
                    <input type="hidden" name="latitude_hidden" id="latitude_hidden">
                    <input type="hidden" name="longitude_hidden" id="longitude_hidden">
                    
                    <p class="text-xs text-gray-400 mt-1">Masukkan koordinat atau klik pada peta untuk menentukan titik lokasi ORMAS</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                    <select name="kecamatan_id" id="ormas_kecamatan" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kecamatan</option>
                        @foreach(\App\Models\Kecamatan::all() as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                    <select name="kelurahan_id" id="ormas_kelurahan" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kelurahan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                    <input type="text" name="no_telepon" id="ormas_no_telepon" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="ormas_email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <!-- ===== FIELD PELAPORAN DI FORM EDIT ===== -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pelaporan</label>
                    <select name="pelaporan" id="ormas_pelaporan" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="belum">Belum</option>
                        <option value="sudah">Sudah</option>
                        <option value="tidak_ada">Tidak Ada</option>
                    </select>
                </div>

                <!-- ===== DATA KEANGGOTAAN ===== -->
                <div class="md:col-span-2 mt-4 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-users text-blue-600 mr-2"></i> Data Keanggotaan
                    </h4>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Total Anggota</label>
                                <input type="number" name="jumlah_anggota" id="ormas_jumlah_anggota" 
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="0" min="0">
                                <p class="text-xs text-gray-400 mt-1">Jumlah total anggota ORMAS</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggota Perempuan</label>
                                <input type="number" name="jumlah_anggota_perempuan" id="ormas_jumlah_anggota_perempuan" 
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="0" min="0">
                                <p class="text-xs text-gray-400 mt-1">Jumlah anggota perempuan (opsional)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Anggota Perempuan (Usia 16-30)</label>
                                <input type="number" name="anggota_perempuan_rentang_16_30" id="ormas_anggota_perempuan_rentang_16_30" 
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="0" min="0">
                                <p class="text-xs text-gray-400 mt-1">Jumlah anggota perempuan usia 16-30 tahun (opsional)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggota Laki-laki</label>
                                <input type="number" name="jumlah_anggota_laki_laki" id="ormas_jumlah_anggota_laki_laki" 
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="0" min="0">
                                <p class="text-xs text-gray-400 mt-1">Jumlah anggota laki-laki (opsional)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Anggota Laki-laki (Usia 16-30)</label>
                                <input type="number" name="anggota_laki_laki_rentang_16_30" id="ormas_anggota_laki_laki_rentang_16_30" 
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="0" min="0">
                                <p class="text-xs text-gray-400 mt-1">Jumlah anggota laki-laki usia 16-30 tahun (opsional)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pt-4 mt-4 border-t border-gray-200 flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
                <button type="button" onclick="closeModal('editOrmasModal')" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL EDIT PENGURUS (Z-INDEX DIPERBAIKI)     -->
<!-- ============================================ -->
<div id="editPengurusModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[10000] flex items-start justify-center pt-16 overflow-y-auto p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto my-4" style="position: relative; z-index: 10001;">
        <div class="sticky top-0 bg-white z-[10002] px-6 py-4 border-b border-gray-100 flex justify-between items-center" style="position: sticky; top: 0; background: white; z-index: 10002;">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-user-edit text-blue-600 mr-2"></i> Edit Data Pengurus
            </h3>
            <button onclick="closeModal('editPengurusModal')" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6">
            <form id="editPengurusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="pengurus_nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input type="text" name="alamat" id="pengurus_alamat" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                        <input type="text" name="no_hp" id="pengurus_no_hp" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <input type="hidden" name="ormas_id" id="pengurus_ormas_id">
                </div>
                <div class="mt-4 flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" onclick="closeModal('editPengurusModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL TAMBAH PENGURUS (Z-INDEX DIPERBAIKI)   -->
<!-- ============================================ -->
<div id="tambahPengurusModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[10000] flex items-start justify-center pt-16 overflow-y-auto p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto my-4" style="position: relative; z-index: 10001;">
        <div class="sticky top-0 bg-white z-[10002] px-6 py-4 border-b border-gray-100 flex justify-between items-center" style="position: sticky; top: 0; background: white; z-index: 10002;">
            <h3 class="text-xl font-bold text-gray-800" id="tambahPengurusTitle">
                <i class="fas fa-user-plus text-blue-600 mr-2"></i> Tambah Pengurus
            </h3>
            <button onclick="closeModal('tambahPengurusModal')" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6">
            <form id="tambahPengurusForm" method="POST">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="tambah_pengurus_nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input type="text" name="alamat" id="tambah_pengurus_alamat" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                        <input type="text" name="no_hp" id="tambah_pengurus_no_hp" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <input type="hidden" name="ormas_id" id="tambah_pengurus_ormas_id">
                </div>
                <div class="mt-4 flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" onclick="closeModal('tambahPengurusModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- STYLE UNTUK MENGATASI Z-INDEX LEAFLET      -->
<!-- ============================================ -->
<style>
    /* ============================================================ */
    /* MENGATASI Z-INDEX LEAFLET — peta TIDAK menutupi modal       */
    /* ============================================================ */
    .leaflet-control-container,
    .leaflet-top,
    .leaflet-bottom,
    .leaflet-control,
    .leaflet-pane,
    .leaflet-map-pane {
        z-index: 5 !important;
    }
    
    /* Modal Edit ORMAS — di atas peta */
    #editOrmasModal {
        z-index: 9998 !important;
    }
    
    #editOrmasModal .bg-white {
        position: relative;
        z-index: 9999;
    }
    
    #editOrmasModal .sticky {
        z-index: 9999 !important;
    }
    
    /* ============================================================ */
    /* MODAL EDIT & TAMBAH PENGURUS — HARUS DI ATAS PETA          */
    /* ============================================================ */
    #editPengurusModal,
    #tambahPengurusModal,
    #confirmDeletePengurusModal {
        z-index: 10000 !important;
    }
    
    #editPengurusModal .bg-white,
    #tambahPengurusModal .bg-white,
    #confirmDeletePengurusModal .bg-white {
        position: relative;
        z-index: 10001 !important;
    }
    
    #editPengurusModal .sticky,
    #tambahPengurusModal .sticky {
        z-index: 10002 !important;
    }
    
    /* ============================================================ */
    /* SEMUA MODAL KONFIRMASI — PALING ATAS                        */
    /* ============================================================ */
    #confirmBulkModal,
    #confirmDeleteModal,
    #confirmToggleModal,
    #notificationModal {
        z-index: 99999 !important;
    }
    
    /* ============================================================ */
    /* PETA DI DALAM FOLDER — tidak menutupi elemen lain          */
    /* ============================================================ */
    .folder-content .leaflet-container {
        z-index: 1 !important;
    }
    
    .folder-content .leaflet-pane {
        z-index: 1 !important;
    }
</style>

<!-- ============================================ -->
<!-- LEAFLET + OPENSTREETMAP                       -->
<!-- ============================================ -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
    document.getElementById('filterForm').submit();
}

function clearFilter(type) {
    const form = document.getElementById('filterForm');
    if (type === 'bentuk') {
        document.getElementById('filterBentuk').value = '';
    } else if (type === 'bidang') {
        document.getElementById('filterBidang').value = '';
    } else if (type === 'kecamatan') {
        document.getElementById('filterKecamatan').value = '';
        document.getElementById('filterKelurahan').innerHTML = '<option value="">Semua Kelurahan</option>';
    } else if (type === 'kelurahan') {
        document.getElementById('filterKelurahan').value = '';
    } else if (type === 'status_aktif') {
        document.getElementById('filterStatusAktif').value = '';
    } else if (type === 'status_pelaporan') {
        document.getElementById('filterStatusPelaporan').value = '';
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
// BULK ACTION DENGAN MODAL
// ============================================
let bulkActionData = {
    pelaporan: null,
    status: null
};

function executeBulkAction() {
    const pelaporan = document.getElementById('bulkPelaporan').value;
    const status = document.getElementById('bulkStatus').value;
    const resultDiv = document.getElementById('bulkResult');
    
    if (!pelaporan && !status) {
        resultDiv.className = 'text-sm text-yellow-600';
        resultDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Pilih aksi terlebih dahulu!';
        resultDiv.classList.remove('hidden');
        return;
    }

    bulkActionData.pelaporan = pelaporan;
    bulkActionData.status = status;

    let icon = document.getElementById('bulkIcon');
    let title = document.getElementById('bulkTitle');
    let message = document.getElementById('bulkMessage');
    let detail = document.getElementById('bulkDetail');
    let confirmBtn = document.getElementById('bulkConfirmBtn');

    if (pelaporan && status) {
        icon.innerHTML = '<i class="fas fa-sync-alt text-blue-500"></i>';
        title.textContent = 'Konfirmasi Update Massal';
        message.textContent = 'Apakah Anda yakin ingin mengupdate pelaporan dan status semua ORMAS?';
        detail.innerHTML = 'Pelaporan: <strong>' + getPelaporanLabel(pelaporan) + '</strong> | Status: <strong>' + (status === 'aktif' ? 'Aktif' : 'Nonaktif') + '</strong>';
        confirmBtn.className = 'px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition';
        confirmBtn.innerHTML = '<i class="fas fa-sync-alt mr-1"></i> Update Semua';
    } else if (pelaporan) {
        icon.innerHTML = '<i class="fas fa-file-alt text-green-500"></i>';
        title.textContent = 'Konfirmasi Update Pelaporan';
        message.textContent = 'Apakah Anda yakin ingin mengupdate pelaporan semua ORMAS?';
        detail.innerHTML = 'Pelaporan akan diubah menjadi: <strong>' + getPelaporanLabel(pelaporan) + '</strong>';
        confirmBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition';
        confirmBtn.innerHTML = '<i class="fas fa-file-alt mr-1"></i> Update Pelaporan';
    } else if (status) {
        const isActive = status === 'aktif';
        icon.innerHTML = isActive ? '<i class="fas fa-check-circle text-green-500"></i>' : '<i class="fas fa-times-circle text-red-500"></i>';
        title.textContent = 'Konfirmasi ' + (isActive ? 'Aktifkan' : 'Nonaktifkan') + ' Semua';
        message.textContent = 'Apakah Anda yakin ingin ' + (isActive ? 'mengaktifkan' : 'menonaktifkan') + ' semua ORMAS?';
        detail.innerHTML = 'Semua ORMAS akan <strong>' + (isActive ? 'diaktifkan' : 'dinonaktifkan') + '</strong>';
        confirmBtn.className = 'px-4 py-2 ' + (isActive ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700') + ' text-white rounded-lg transition';
        confirmBtn.innerHTML = '<i class="fas ' + (isActive ? 'fa-check-circle' : 'fa-times-circle') + ' mr-1"></i> ' + (isActive ? 'Aktifkan' : 'Nonaktifkan') + ' Semua';
    }

    document.getElementById('confirmBulkModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBulkConfirmModal() {
    document.getElementById('confirmBulkModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    bulkActionData.pelaporan = null;
    bulkActionData.status = null;
}

function getPelaporanLabel(value) {
    const labels = {
        'sudah': 'Sudah',
        'belum': 'Belum',
        'tidak_ada': 'Tidak Ada'
    };
    return labels[value] || value;
}

document.addEventListener('DOMContentLoaded', function() {
    const bulkConfirmBtn = document.getElementById('bulkConfirmBtn');
    if (bulkConfirmBtn) {
        bulkConfirmBtn.addEventListener('click', function() {
            executeBulkActionConfirm();
        });
    }
});

function executeBulkActionConfirm() {
    const pelaporan = bulkActionData.pelaporan;
    const status = bulkActionData.status;
    const resultDiv = document.getElementById('bulkResult');
    
    closeBulkConfirmModal();

    resultDiv.className = 'text-sm text-blue-600';
    resultDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
    resultDiv.classList.remove('hidden');

    let requests = [];

    if (pelaporan) {
        requests.push(
            fetch('/admin/users/bulk-update-pelaporan', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ pelaporan: pelaporan })
            })
        );
    }

    if (status) {
        requests.push(
            fetch('/admin/users/bulk-update-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status: status })
            })
        );
    }

    Promise.all(requests)
        .then(responses => Promise.all(responses.map(r => r.json())))
        .then(results => {
            let successMessages = [];
            let errorMessages = [];

            results.forEach((data) => {
                if (data.success) {
                    successMessages.push(data.message);
                } else {
                    errorMessages.push(data.message);
                }
            });

            if (successMessages.length > 0) {
                resultDiv.className = 'text-sm text-green-600';
                resultDiv.innerHTML = '<i class="fas fa-check-circle mr-1"></i> ' + successMessages.join(' | ');
                showNotification('success', 'Berhasil!', successMessages.join(' | '));
                
                document.getElementById('bulkPelaporan').value = '';
                document.getElementById('bulkStatus').value = '';
                
                setTimeout(() => location.reload(), 2000);
            } else if (errorMessages.length > 0) {
                resultDiv.className = 'text-sm text-red-600';
                resultDiv.innerHTML = '<i class="fas fa-times-circle mr-1"></i> ' + errorMessages.join(' | ');
                showNotification('error', 'Gagal!', errorMessages.join(' | '));
            }
        })
        .catch(error => {
            resultDiv.className = 'text-sm text-red-600';
            resultDiv.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Terjadi kesalahan: ' + error.message;
            showNotification('error', 'Error!', 'Terjadi kesalahan saat memproses aksi.');
        });
}

// ============================================
// VARIABLE HAPUS / TOGGLE
// ============================================
let deleteId = null;
let deleteName = '';
let toggleId = null;
let toggleName = '';
let toggleIsActive = false;

// ============================================
// VARIABLE HAPUS PENGURUS
// ============================================
let deletePengurusId = null;
let deletePengurusName = '';

// ============================================
// VARIABLE MAP
// ============================================
let editMapInstance = null;
let editMarkerInstance = null;
let detailMaps = {};

// ============================================
// KONFIRMASI HAPUS ORMAS
// ============================================
function confirmHapusOrmas(id, nama) {
    deleteId = id;
    deleteName = nama;
    document.getElementById('confirmDeleteMessage').textContent = 'Apakah Anda yakin ingin menghapus ORMAS ini?';
    document.getElementById('confirmDeleteName').textContent = '"' + nama + '"';
    document.getElementById('confirmDeleteModal').classList.remove('hidden');
}

function closeConfirmDelete() {
    document.getElementById('confirmDeleteModal').classList.add('hidden');
    deleteId = null;
    deleteName = '';
}

function executeDelete() {
    if (!deleteId) return;
    
    const id = deleteId;
    const nama = deleteName;
    
    closeConfirmDelete();
    
    fetch('/admin/ormas/' + id + '/delete', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Berhasil!', 'Data ORMAS "' + nama + '" berhasil dihapus!');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showNotification('error', 'Error!', 'Terjadi kesalahan saat menghapus data.');
    });
}

// ============================================
// HAPUS PENGURUS
// ============================================
function confirmHapusPengurus(id, nama) {
    deletePengurusId = id;
    deletePengurusName = nama;

    document.getElementById('confirmDeletePengurusName').textContent = '"' + nama + '"';
    document.getElementById('confirmDeletePengurusModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmDeletePengurus() {
    document.getElementById('confirmDeletePengurusModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    deletePengurusId = null;
    deletePengurusName = '';
}

function executeDeletePengurus() {
    if (!deletePengurusId) return;

    const id = deletePengurusId;
    const nama = deletePengurusName;

    closeConfirmDeletePengurus();

    fetch('/admin/pengurus/' + id + '/delete', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Berhasil!', 'Pengurus "' + nama + '" berhasil dihapus!');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan saat menghapus.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error!', 'Terjadi kesalahan saat menghapus data pengurus.');
    });
}

// ============================================
// KONFIRMASI TOGGLE AKTIF/NONAKTIF
// ============================================
function confirmToggleActive(id, nama, isActive) {
    toggleId = id;
    toggleName = nama;
    toggleIsActive = isActive;
    
    const icon = document.getElementById('toggleIcon');
    const title = document.getElementById('toggleTitle');
    const message = document.getElementById('toggleMessage');
    const nameEl = document.getElementById('toggleName');
    const confirmBtn = document.getElementById('toggleConfirmBtn');
    
    if (isActive) {
        icon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
        title.textContent = 'Konfirmasi Nonaktifkan';
        message.textContent = 'Apakah Anda yakin ingin menonaktifkan ORMAS ini?';
        confirmBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition';
        confirmBtn.innerHTML = '<i class="fas fa-times mr-1"></i> Nonaktifkan';
    } else {
        icon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
        title.textContent = 'Konfirmasi Aktifkan';
        message.textContent = 'Apakah Anda yakin ingin mengaktifkan ORMAS ini?';
        confirmBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition';
        confirmBtn.innerHTML = '<i class="fas fa-check mr-1"></i> Aktifkan';
    }
    
    nameEl.textContent = '"' + nama + '"';
    document.getElementById('confirmToggleModal').classList.remove('hidden');
}

function closeToggleModal() {
    document.getElementById('confirmToggleModal').classList.add('hidden');
    toggleId = null;
    toggleName = '';
    toggleIsActive = false;
}

function executeToggle() {
    if (!toggleId) return;
    
    const id = toggleId;
    const nama = toggleName;
    const isActive = toggleIsActive;
    
    closeToggleModal();
    
    const action = isActive ? 'nonaktifkan' : 'aktifkan';
    
    fetch('/admin/ormas/' + id + '/toggle-active', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Berhasil!', 'ORMAS "' + nama + '" berhasil di' + action + '!');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showNotification('error', 'Error!', 'Terjadi kesalahan saat mengubah status.');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleConfirmBtn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            executeToggle();
        });
    }
});

// ============================================
// UPDATE PELAPORAN
// ============================================
function updatePelaporan(id, value) {
    const select = document.querySelector(`select[onchange*="updatePelaporan(${id},"]`);
    
    fetch('/admin/ormas/' + id + '/update-pelaporan', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ pelaporan: value })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const container = select?.closest('.flex');
            if (container) {
                const badges = container.querySelectorAll('.rounded-full');
                badges.forEach(badge => {
                    const text = badge.textContent.trim();
                    if (text === 'Sudah' || text === 'Belum' || text === 'Tidak Ada') {
                        const colors = {
                            'sudah': 'bg-green-100 text-green-700',
                            'belum': 'bg-yellow-100 text-yellow-700',
                            'tidak_ada': 'bg-gray-100 text-gray-700'
                        };
                        const labels = {
                            'sudah': 'Sudah',
                            'belum': 'Belum',
                            'tidak_ada': 'Tidak Ada'
                        };
                        badge.className = `px-2 py-0.5 text-xs font-semibold rounded-full ${colors[value]}`;
                        badge.textContent = labels[value];
                    }
                });
            }
            
            const folderContent = document.getElementById('folder-' + id);
            if (folderContent) {
                const gridBadges = folderContent.querySelectorAll('.grid .rounded-full');
                gridBadges.forEach(badge => {
                    const text = badge.textContent.trim();
                    if (text === 'Sudah' || text === 'Belum' || text === 'Tidak Ada') {
                        const colors = {
                            'sudah': 'bg-green-100 text-green-700',
                            'belum': 'bg-yellow-100 text-yellow-700',
                            'tidak_ada': 'bg-gray-100 text-gray-700'
                        };
                        const labels = {
                            'sudah': 'Sudah',
                            'belum': 'Belum',
                            'tidak_ada': 'Tidak Ada'
                        };
                        badge.className = `px-2 py-0.5 text-xs font-semibold rounded-full ${colors[value]}`;
                        badge.textContent = labels[value];
                    }
                });
            }
            
            showNotification('success', 'Berhasil!', 'Status pelaporan berhasil diupdate!');
        } else {
            showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error!', 'Terjadi kesalahan saat mengupdate status pelaporan.');
    });
}

// ============================================
// NOTIFIKASI MODAL
// ============================================
function showNotification(type, title, message) {
    const modal = document.getElementById('notificationModal');
    const icon = document.getElementById('notificationIcon');
    const titleEl = document.getElementById('notificationTitle');
    const msgEl = document.getElementById('notificationMessage');
    
    if (type === 'success') {
        icon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
        titleEl.className = 'text-xl font-bold text-gray-800 mb-2';
    } else if (type === 'error') {
        icon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
        titleEl.className = 'text-xl font-bold text-red-600 mb-2';
    } else if (type === 'warning') {
        icon.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500"></i>';
        titleEl.className = 'text-xl font-bold text-yellow-600 mb-2';
    }
    
    titleEl.textContent = title;
    msgEl.textContent = message;
    modal.classList.remove('hidden');
}

function closeNotification() {
    document.getElementById('notificationModal').classList.add('hidden');
}

// ============================================
// TOGGLE FOLDER
// ============================================
function toggleFolder(id) {
    const content = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (content) content.classList.toggle('hidden');
    if (icon) {
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    }
    
    setTimeout(function() {
        const folderContent = document.getElementById(id);
        if (folderContent && !folderContent.classList.contains('hidden')) {
            const mapEl = folderContent.querySelector('[id^="detailMap-"]');
            if (mapEl) {
                const mapId = mapEl.id;
                const ormasId = mapId.replace('detailMap-', '');
                const ormasData = @json($ormas->items());
                const data = ormasData.find(o => o.id == parseInt(ormasId));
                
                if (data && data.latitude && data.longitude && !detailMaps[mapId]) {
                    const map = L.map(mapId).setView([data.latitude, data.longitude], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);
                    L.marker([data.latitude, data.longitude]).addTo(map);
                    detailMaps[mapId] = map;
                    
                    setTimeout(function() {
                        if (detailMaps[mapId]) {
                            detailMaps[mapId].invalidateSize();
                        }
                    }, 500);
                }
            }
        }
    }, 300);
}

// ============================================
// FUNGSI UPDATE KOORDINAT
// ============================================
function updateCoordinates(lat, lng) {
    document.getElementById('edit_latitude').value = lat.toFixed(8);
    document.getElementById('edit_longitude').value = lng.toFixed(8);
    document.getElementById('latitude_hidden').value = lat.toFixed(8);
    document.getElementById('longitude_hidden').value = lng.toFixed(8);
    
    if (editMarkerInstance) {
        editMarkerInstance.setLatLng([lat, lng]);
    }
    if (editMapInstance) {
        editMapInstance.setView([lat, lng], editMapInstance.getZoom());
    }
}

function updateMapFromCoordinates() {
    const latInput = document.getElementById('edit_latitude');
    const lngInput = document.getElementById('edit_longitude');
    
    const lat = parseFloat(latInput.value);
    const lng = parseFloat(lngInput.value);
    
    if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
        document.getElementById('latitude_hidden').value = lat.toFixed(8);
        document.getElementById('longitude_hidden').value = lng.toFixed(8);
        
        if (editMarkerInstance) {
            editMarkerInstance.setLatLng([lat, lng]);
        }
        if (editMapInstance) {
            editMapInstance.setView([lat, lng], editMapInstance.getZoom());
        }
    }
}

// ============================================
// INIT EDIT MAP
// ============================================
function initEditMap(lat, lng) {
    const mapContainer = document.getElementById('editMap');
    if (!mapContainer) return;
    
    if (editMapInstance) {
        editMapInstance.remove();
        editMapInstance = null;
        editMarkerInstance = null;
    }
    
    const defaultLat = (lat && !isNaN(parseFloat(lat))) ? parseFloat(lat) : -6.870367;
    const defaultLng = (lng && !isNaN(parseFloat(lng))) ? parseFloat(lng) : 107.554704;
    
    editMapInstance = L.map('editMap').setView([defaultLat, defaultLng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(editMapInstance);
    
    editMarkerInstance = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(editMapInstance);
    
    editMarkerInstance.on('dragend', function() {
        const pos = editMarkerInstance.getLatLng();
        updateCoordinates(pos.lat, pos.lng);
    });
    
    editMapInstance.on('click', function(e) {
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });
    
    setTimeout(function() {
        if (editMapInstance) {
            editMapInstance.invalidateSize();
        }
    }, 300);
}

// ============================================
// EDIT ORMAS
// ============================================
function editOrmas(id) {
    const modal = document.getElementById('editOrmasModal');
    modal.classList.remove('hidden');
    document.getElementById('editOrmasForm').action = '/admin/ormas/' + id + '/update-json';
    document.body.style.overflow = 'hidden';

    fetch('/admin/ormas/' + id + '/edit-json')
        .then(response => response.json())
        .then(data => {
            document.getElementById('ormas_nama').value = data.nama || '';
            document.getElementById('ormas_singkatan').value = data.singkatan || '';
            document.getElementById('ormas_nomor_registrasi').value = data.nomor_registrasi || '';
            document.getElementById('ormas_alamat').value = data.alamat_kesekretariatan || '';
            document.getElementById('ormas_no_telepon').value = data.no_telepon || '';
            document.getElementById('ormas_email').value = data.email || '';
            document.getElementById('ormas_pelaporan').value = data.pelaporan || 'belum';
            
            document.getElementById('ormas_jumlah_anggota').value = data.jumlah_anggota ?? 0;
            document.getElementById('ormas_jumlah_anggota_perempuan').value = data.jumlah_anggota_perempuan ?? 0;
            document.getElementById('ormas_anggota_perempuan_rentang_16_30').value = data.anggota_perempuan_rentang_16_30 ?? 0;
            document.getElementById('ormas_jumlah_anggota_laki_laki').value = data.jumlah_anggota_laki_laki ?? 0;
            document.getElementById('ormas_anggota_laki_laki_rentang_16_30').value = data.anggota_laki_laki_rentang_16_30 ?? 0;
            
            const lat = data.latitude || '';
            const lng = data.longitude || '';
            document.getElementById('edit_latitude').value = lat;
            document.getElementById('edit_longitude').value = lng;
            document.getElementById('latitude_hidden').value = lat;
            document.getElementById('longitude_hidden').value = lng;
            
            document.getElementById('ormas_kecamatan').value = data.kecamatan_id || '';
            
            const kelurahanSelect = document.getElementById('ormas_kelurahan');
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            
            if (data.kecamatan_id && kelurahanData[data.kecamatan_id]) {
                kelurahanData[data.kecamatan_id].forEach(function(kelurahan) {
                    const option = document.createElement('option');
                    option.value = kelurahan.id;
                    option.textContent = kelurahan.nama;
                    if (kelurahan.id == data.kelurahan_id) {
                        option.selected = true;
                    }
                    kelurahanSelect.appendChild(option);
                });
            }
            
            const bentukSelect = document.getElementById('ormas_jenis');
            const bentukValue = data.jenis_ormas ? data.jenis_ormas.nama : '';
            for (let i = 0; i < bentukSelect.options.length; i++) {
                if (bentukSelect.options[i].value == bentukValue || 
                    bentukSelect.options[i].textContent == bentukValue) {
                    bentukSelect.options[i].selected = true;
                    break;
                }
            }
            
            const bidangSelect = document.getElementById('ormas_bidang');
            const bidangValue = data.bidang_kegiatan ? data.bidang_kegiatan.nama : '';
            for (let i = 0; i < bidangSelect.options.length; i++) {
                if (bidangSelect.options[i].value == bidangValue || 
                    bidangSelect.options[i].textContent == bidangValue) {
                    bidangSelect.options[i].selected = true;
                    break;
                }
            }
            
            setTimeout(function() {
                initEditMap(lat || -6.870367, lng || 107.554704);
            }, 400);
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Gagal!', 'Gagal mengambil data ORMAS');
        });
}

// ============================================
// EDIT PENGURUS
// ============================================
function editPengurus(id) {
    const modal = document.getElementById('editPengurusModal');
    modal.classList.remove('hidden');
    document.getElementById('editPengurusForm').action = '/admin/pengurus/' + id + '/update-json';
    document.body.style.overflow = 'hidden';

    fetch('/admin/pengurus/' + id + '/edit-json')
        .then(response => response.json())
        .then(data => {
            document.getElementById('pengurus_nama').value = data.nama || '';
            document.getElementById('pengurus_alamat').value = data.alamat || '';
            document.getElementById('pengurus_no_hp').value = data.no_hp || '';
            document.getElementById('pengurus_ormas_id').value = data.ormas_id || '';
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Gagal!', 'Gagal mengambil data pengurus');
        });
}

// ============================================
// TAMBAH PENGURUS
// ============================================
function tambahPengurus(ormasId) {
    const modal = document.getElementById('tambahPengurusModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    document.getElementById('tambahPengurusTitle').textContent = 'Tambah Pengurus';
    document.getElementById('tambah_pengurus_ormas_id').value = ormasId;
    document.getElementById('tambah_pengurus_nama').value = '';
    document.getElementById('tambah_pengurus_alamat').value = '';
    document.getElementById('tambah_pengurus_no_hp').value = '';
    
    document.getElementById('tambahPengurusForm').action = '/admin/pengurus/tambah';
}

// ============================================
// EVENT LISTENER KECAMATAN
// ============================================
document.addEventListener('change', function(e) {
    if (e.target.id === 'ormas_kecamatan') {
        const kecamatanId = e.target.value;
        const kelurahanSelect = document.getElementById('ormas_kelurahan');
        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        
        if (kecamatanId && kelurahanData[kecamatanId]) {
            kelurahanData[kecamatanId].forEach(function(kelurahan) {
                const option = document.createElement('option');
                option.value = kelurahan.id;
                option.textContent = kelurahan.nama;
                kelurahanSelect.appendChild(option);
            });
        }
    }
});

// ============================================
// CLOSE MODAL
// ============================================
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    if (id === 'editOrmasModal' && editMapInstance) {
        editMapInstance.remove();
        editMapInstance = null;
        editMarkerInstance = null;
    }
}

// ============================================
// CLOSE MODAL SAAT KLIK DI LUAR
// ============================================
document.querySelectorAll('.fixed.inset-0').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
});

// ============================================
// EVENT LISTENER INPUT KOORDINAT MANUAL
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('edit_latitude');
    const lngInput = document.getElementById('edit_longitude');
    
    if (latInput && lngInput) {
        latInput.addEventListener('change', function() {
            updateMapFromCoordinates();
        });
        lngInput.addEventListener('change', function() {
            updateMapFromCoordinates();
        });
        latInput.addEventListener('blur', function() {
            updateMapFromCoordinates();
        });
        lngInput.addEventListener('blur', function() {
            updateMapFromCoordinates();
        });
        latInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                updateMapFromCoordinates();
            }
        });
        lngInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                updateMapFromCoordinates();
            }
        });
    }
});

// ============================================
// SUBMIT FORM ORMAS
// ============================================
document.getElementById('editOrmasForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const url = form.action;

    const lat = document.getElementById('edit_latitude').value;
    const lng = document.getElementById('edit_longitude').value;
    
    formData.set('latitude', lat);
    formData.set('longitude', lng);
    formData.append('_method', 'PUT');

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Berhasil!', 'Data ORMAS berhasil diupdate!');
            closeModal('editOrmasModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showNotification('error', 'Error!', 'Terjadi kesalahan saat menyimpan data.');
    });
});

// ============================================
// SUBMIT FORM PENGURUS EDIT
// ============================================
document.getElementById('editPengurusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const url = form.action;

    formData.append('_method', 'PUT');

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Berhasil!', 'Data pengurus berhasil diupdate!');
            closeModal('editPengurusModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showNotification('error', 'Error!', 'Terjadi kesalahan saat menyimpan data.');
    });
});

// ============================================
// SUBMIT FORM TAMBAH PENGURUS
// ============================================
document.getElementById('tambahPengurusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const url = form.action;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Berhasil!', 'Data pengurus berhasil ditambahkan!');
            closeModal('tambahPengurusModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showNotification('error', 'Error!', 'Terjadi kesalahan saat menyimpan data.');
    });
});

// ============================================
// INISIALISASI PETA DETAIL SAAT HALAMAN DIMUAT
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.folder-content:not(.hidden)').forEach(function(content) {
        const mapEl = content.querySelector('[id^="detailMap-"]');
        if (mapEl) {
            const mapId = mapEl.id;
            const ormasId = mapId.replace('detailMap-', '');
            const ormasData = @json($ormas->items());
            const data = ormasData.find(o => o.id == parseInt(ormasId));
            
            if (data && data.latitude && data.longitude && !detailMaps[mapId]) {
                const map = L.map(mapId).setView([data.latitude, data.longitude], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                L.marker([data.latitude, data.longitude]).addTo(map);
                detailMaps[mapId] = map;
            }
        }
    });
});
</script>
@endsection