@extends('layouts.admin')

@section('title', 'Data ORMAS')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Data ORMAS</h1>
        <div class="flex gap-2">
            <button onclick="openModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus"></i> Tambah ORMAS
            </button>
        </div>
    </div>

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

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- ===== STATUS FILTER BUTTONS ===== -->
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.ormas.index') }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Semua ({{ $ormas->total() }})
        </a>
        <a href="{{ route('admin.ormas.index', array_merge(request()->except('status'), ['status' => 'menunggu_verifikasi'])) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'menunggu_verifikasi' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }}">
            <i class="fas fa-clock mr-1"></i> Menunggu ({{ $totalMenunggu }})
        </a>
        <a href="{{ route('admin.ormas.index', array_merge(request()->except('status'), ['status' => 'revisi'])) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'revisi' ? 'bg-orange-500 text-white' : 'bg-orange-100 text-orange-700 hover:bg-orange-200' }}">
            <i class="fas fa-edit mr-1"></i> Revisi ({{ $totalRevisi }})
        </a>
        <a href="{{ route('admin.ormas.index', array_merge(request()->except('status'), ['status' => 'disetujui'])) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'disetujui' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
            <i class="fas fa-check-circle mr-1"></i> Disetujui ({{ $totalDisetujui }})
        </a>
        <a href="{{ route('admin.ormas.index', array_merge(request()->except('status'), ['status' => 'ditolak'])) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'ditolak' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
            <i class="fas fa-times-circle mr-1"></i> Ditolak ({{ $totalDitolak }})
        </a>
    </div>

    <!-- ===== SEARCH & FILTER ===== -->
    <div class="bg-gray-50 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.ormas.index') }}" class="space-y-4" id="filterForm">
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
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
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

                <!-- Tombol Cari & Reset -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        <i class="fas fa-search mr-1"></i>
                    </button>
                    @if(request('search') || request('bentuk') || request('bidang') || request('kecamatan') || request('kelurahan') || request('status'))
                    <a href="{{ route('admin.ormas.index') }}" class="px-4 py-2.5 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-sm font-medium whitespace-nowrap">
                        <i class="fas fa-times"></i> Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- ===== HASIL PENCARIAN ===== -->
    @if(request('search') || request('bentuk') || request('bidang') || request('kecamatan') || request('kelurahan') || request('status'))
    <div class="mb-4 text-sm text-gray-500">
        Menampilkan hasil untuk: 
        @if(request('search'))
        <span class="font-medium text-gray-700">"{{ request('search') }}"</span>
        @endif
        @if(request('status'))
        @if(request('search')) <span class="mx-1">|</span> @endif
        <span class="font-medium text-gray-700">Status: 
            @if(request('status') == 'menunggu_verifikasi') Menunggu Verifikasi
            @elseif(request('status') == 'revisi') Revisi
            @elseif(request('status') == 'disetujui') Disetujui
            @elseif(request('status') == 'ditolak') Ditolak
            @else {{ request('status') }}
            @endif
        </span>
        @endif
        @if(request('kecamatan'))
        @if(request('search') || request('status')) <span class="mx-1">|</span> @endif
        <span class="font-medium text-gray-700">Kecamatan: {{ \App\Models\Kecamatan::find(request('kecamatan'))->nama ?? '' }}</span>
        @endif
        @if(request('kelurahan'))
        @if(request('search') || request('status') || request('kecamatan')) <span class="mx-1">|</span> @endif
        <span class="font-medium text-gray-700">Kelurahan: {{ \App\Models\Kelurahan::find(request('kelurahan'))->nama ?? '' }}</span>
        @endif
        @if(request('bentuk'))
        @if(request('search') || request('status') || request('kecamatan') || request('kelurahan')) <span class="mx-1">|</span> @endif
        <span class="font-medium text-gray-700">Bentuk: {{ request('bentuk') }}</span>
        @endif
        @if(request('bidang'))
        @if(request('search') || request('status') || request('kecamatan') || request('kelurahan') || request('bentuk')) <span class="mx-1">|</span> @endif
        <span class="font-medium text-gray-700">Bidang: {{ request('bidang') }}</span>
        @endif
        <span class="ml-2">({{ $ormas->total() }} ORMAS ditemukan)</span>
    </div>
    @endif

    <!-- Tabel Data ORMAS -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No AHU/SKT</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bentuk Ormas</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelurahan</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelaporan</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ormas ?? [] as $index => $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $ormas->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->nomor_registrasi ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->jenisOrmas->nama ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->kelurahan->nama ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusColors = [
                                'menunggu_verifikasi' => 'bg-yellow-500',
                                'revisi' => 'bg-orange-500',
                                'disetujui' => 'bg-green-500',
                                'ditolak' => 'bg-red-500',
                                'draft' => 'bg-gray-500'
                            ];
                            $statusLabels = [
                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                'revisi' => 'Revisi',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                                'draft' => 'Draft'
                            ];
                            $statusColor = $statusColors[$item->status] ?? 'bg-gray-500';
                            $statusLabel = $statusLabels[$item->status] ?? $item->status;
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }} text-white">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $pelaporanColors[$pelaporanValue] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $pelaporanLabels[$pelaporanValue] ?? 'Belum' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.ormas.show', $item->id) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($item->status == 'ditolak')
                            <button onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama }}')" 
                                    class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                        @if(request('search') || request('bentuk') || request('bidang') || request('kecamatan') || request('kelurahan') || request('status'))
                            Tidak ada data ORMAS yang sesuai dengan filter
                        @else
                            Belum ada data ORMAS
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ===== PAGINATION - DENGAN ELLIPSIS ===== -->
    @if(isset($ormas) && $ormas->hasPages())
    <div class="mt-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-sm text-gray-500">
                Menampilkan data {{ $ormas->firstItem() }} sampai {{ $ormas->lastItem() }} dari {{ $ormas->total() }} data
            </div>
            <div>
                <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    {{-- Previous Page Link --}}
                    @if ($ormas->onFirstPage())
                        <span class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $ormas->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $currentPage = $ormas->currentPage();
                        $lastPage = $ormas->lastPage();
                        $half = 2;
                        $start = max(1, $currentPage - $half);
                        $end = min($lastPage, $currentPage + $half);
                        $showStartEllipsis = $start > 3;
                        $showEndEllipsis = $end < $lastPage - 2;
                    @endphp

                    {{-- Halaman Pertama --}}
                    @if($start > 1)
                        <a href="{{ $ormas->url(1) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            1
                        </a>
                    @endif

                    {{-- Ellipsis Awal --}}
                    @if($showStartEllipsis)
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500">
                            ...
                        </span>
                    @endif

                    {{-- Halaman Tengah --}}
                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $currentPage)
                            <span class="relative inline-flex items-center px-4 py-2 border border-blue-600 bg-blue-600 text-sm font-medium text-white">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ $ormas->url($i) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor

                    {{-- Ellipsis Akhir --}}
                    @if($showEndEllipsis)
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500">
                            ...
                        </span>
                    @endif

                    {{-- Halaman Terakhir --}}
                    @if($end < $lastPage)
                        <a href="{{ $ormas->url($lastPage) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            {{ $lastPage }}
                        </a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($ormas->hasMorePages())
                        <a href="{{ $ormas->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- ============================================ -->
<!-- MODAL TAMBAH ORMAS                            -->
<!-- ============================================ -->
<div id="ormasModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-start justify-center pt-8 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto my-4">
        <!-- Modal Header - STICKY DENGAN Z-INDEX TINGGI -->
        <div class="sticky top-0 bg-white z-[60] px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Tambah ORMAS</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="ormasForm" action="{{ route('admin.ormas.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Data ORMAS -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-building text-blue-600 mr-2"></i> Data ORMAS
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama ORMAS <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Singkatan</label>
                                <input type="text" name="singkatan" id="singkatan" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ada</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No AHU/SKT</label>
                                <input type="text" name="nomor_registrasi" id="nomor_registrasi" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ada</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bentuk Ormas</label>
                                <select name="jenis_ormas_id" id="jenis_ormas_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Bentuk Ormas</option>
                                    <option value="Yayasan">Yayasan</option>
                                    <option value="Perkumpulan">Perkumpulan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Kegiatan Ormas</label>
                                <select name="bidang_kegiatan_id" id="bidang_kegiatan_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        </div>
                    </div>

                    <!-- Kontak & Alamat -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-address-card text-blue-600 mr-2"></i> Kontak & Alamat
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Kesekretariatan</label>
                                <textarea name="alamat_kesekretariatan" id="alamat_kesekretariatan" rows="2" 
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Masukkan alamat lengkap..."></textarea>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i> 
                                    Masukkan alamat lengkap kesekretariatan
                                </p>
                            </div>
                            
                            <!-- ===== KOORDINAT LOKASI - INDEPENDEN ===== -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Koordinat Lokasi</label>
                                <p class="text-xs text-gray-400 mb-2">Klik pada peta atau drag marker untuk menentukan titik koordinat (opsional)</p>
                                <p class="text-xs text-yellow-600 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i> 
                                    Peta dan koordinat bersifat independen. Silakan klik pada peta untuk menentukan titik lokasi.
                                </p>
                                
                                <div id="map" class="w-full h-72 md:h-80 rounded-lg border border-gray-300 overflow-hidden mb-3" style="z-index: 1; position: relative;"></div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Latitude</label>
                                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', '') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('latitude') border-red-500 @enderror"
                                               placeholder="Latitude">
                                        @error('latitude')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Longitude</label>
                                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', '') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('longitude') border-red-500 @enderror"
                                               placeholder="Longitude">
                                        @error('longitude')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-edit mr-1"></i>
                                    Kosongkan koordinat jika tidak ingin mengisi lokasi
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                                <select name="kecamatan_id" id="kecamatan_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Kecamatan</option>
                                    <option value="1">Cimahi Selatan</option>
                                    <option value="2">Cimahi Tengah</option>
                                    <option value="3">Cimahi Utara</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                                <select name="kelurahan_id" id="kelurahan_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                                <input type="text" name="no_telepon" id="no_telepon" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                                <input type="text" name="email" id="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan email (opsional)">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan atau isi dengan "-" jika tidak memiliki email</p>
                            </div>
                        </div>
                    </div>

                    <!-- ===== DATA KEANGGOTAAN ===== -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-users text-blue-600 mr-2"></i> Data Keanggotaan
                        </h4>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Total Anggota</label>
                                    <input type="number" name="jumlah_anggota" id="jumlah_anggota" value="{{ old('jumlah_anggota', 0) }}" 
                                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="0" min="0">
                                    <p class="text-xs text-gray-400 mt-1">Jumlah total anggota ORMAS</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggota Perempuan</label>
                                    <input type="number" name="jumlah_anggota_perempuan" id="jumlah_anggota_perempuan" value="{{ old('jumlah_anggota_perempuan', 0) }}" 
                                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="0" min="0">
                                    <p class="text-xs text-gray-400 mt-1">Jumlah anggota perempuan (opsional)</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Anggota Perempuan (Usia 16-30)</label>
                                    <input type="number" name="anggota_perempuan_rentang_16_30" id="anggota_perempuan_rentang_16_30" value="{{ old('anggota_perempuan_rentang_16_30', 0) }}" 
                                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="0" min="0">
                                    <p class="text-xs text-gray-400 mt-1">Jumlah anggota perempuan usia 16-30 tahun (opsional)</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggota Laki-laki</label>
                                    <input type="number" name="jumlah_anggota_laki_laki" id="jumlah_anggota_laki_laki" value="{{ old('jumlah_anggota_laki_laki', 0) }}" 
                                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="0" min="0">
                                    <p class="text-xs text-gray-400 mt-1">Jumlah anggota laki-laki (opsional)</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Anggota Laki-laki (Usia 16-30)</label>
                                    <input type="number" name="anggota_laki_laki_rentang_16_30" id="anggota_laki_laki_rentang_16_30" value="{{ old('anggota_laki_laki_rentang_16_30', 0) }}" 
                                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="0" min="0">
                                    <p class="text-xs text-gray-400 mt-1">Jumlah anggota laki-laki usia 16-30 tahun (opsional)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pengurus -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-users text-blue-600 mr-2"></i> Data Pengurus
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Ketua (Wajib) -->
                            <div class="border border-blue-200 rounded-lg p-3 bg-blue-50">
                                <h5 class="font-semibold text-blue-600 text-sm mb-2">
                                    <i class="fas fa-user-tie mr-1"></i> Ketua <span class="text-red-500">*</span>
                                </h5>
                                <div class="space-y-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama</label>
                                        <input type="text" name="ketua_nama" id="ketua_nama" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                                        <input type="text" name="ketua_alamat" id="ketua_alamat" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">No Telepon</label>
                                        <input type="text" name="ketua_no_hp" id="ketua_no_hp" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Sekretaris (Opsional) -->
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <h5 class="font-semibold text-gray-600 text-sm mb-2">
                                    <i class="fas fa-user-edit mr-1"></i> Sekretaris <span class="text-xs text-gray-400">(Opsional)</span>
                                </h5>
                                <div class="space-y-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama</label>
                                        <input type="text" name="sekretaris_nama" id="sekretaris_nama" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                                        <input type="text" name="sekretaris_alamat" id="sekretaris_alamat" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">No Telepon</label>
                                        <input type="text" name="sekretaris_no_hp" id="sekretaris_no_hp" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Bendahara (Opsional) -->
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <h5 class="font-semibold text-gray-600 text-sm mb-2">
                                    <i class="fas fa-user-tie mr-1"></i> Bendahara <span class="text-xs text-gray-400">(Opsional)</span>
                                </h5>
                                <div class="space-y-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama</label>
                                        <input type="text" name="bendahara_nama" id="bendahara_nama" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                                        <input type="text" name="bendahara_alamat" id="bendahara_alamat" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">No Telepon</label>
                                        <input type="text" name="bendahara_no_hp" id="bendahara_no_hp" class="w-full px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Ketua wajib diisi. Sekretaris dan Bendahara bersifat opsional.
                        </p>
                    </div>
                </div>

                <input type="hidden" name="status" value="disetujui">
                <input type="hidden" name="is_active" value="1">

                <div class="mt-6 flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL HAPUS ORMAS                            -->
<!-- ============================================ -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Konfirmasi Hapus</h3>
            <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-800 font-medium">Apakah Anda yakin ingin menghapus ORMAS ini?</p>
                    <p class="text-sm text-gray-500 mt-1" id="deleteModalText">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 bg-red-50 p-3 rounded-lg border border-red-200">
                <i class="fas fa-info-circle text-red-500 mr-1"></i>
                ORMAS dengan status <strong>Ditolak</strong> akan dihapus secara permanen.
            </p>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Batal
            </button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- LEAFET + OPENSTREETMAP                        -->
<!-- ============================================ -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- ============================================ -->
<!-- STYLE TAMBAHAN UNTUK MENGATASI Z-INDEX MAP  -->
<!-- ============================================ -->
<style>
    .leaflet-control-container,
    .leaflet-top,
    .leaflet-bottom,
    .leaflet-control {
        z-index: 5 !important;
    }
    
    #ormasModal .sticky {
        z-index: 100 !important;
    }
    
    #deleteModal .bg-white {
        position: relative;
        z-index: 10000;
    }
    
    #deleteModal {
        z-index: 9999 !important;
    }
    
    #map {
        z-index: 1 !important;
        position: relative;
    }
    
    #ormasModal {
        z-index: 9999 !important;
    }
    
    #ormasModal .bg-white {
        position: relative;
        z-index: 10000;
    }
    
    .fixed.inset-0.bg-black\/50 {
        z-index: 9998 !important;
    }
</style>

<script>
// ============================================
// DATA KELURAHAN PER KECAMATAN
// ============================================
const kelurahanData = {
    '1': [
        { id: 1, nama: 'Cibeber' },
        { id: 2, nama: 'Cibeureum' },
        { id: 3, nama: 'Leuwigajah' },
        { id: 4, nama: 'Melong' },
        { id: 5, nama: 'Utama' }
    ],
    '2': [
        { id: 6, nama: 'Baros' },
        { id: 7, nama: 'Cigugur Tengah' },
        { id: 8, nama: 'Cimahi' },
        { id: 9, nama: 'Karangmekar' },
        { id: 10, nama: 'Padasuka' },
        { id: 11, nama: 'Setiamanah' }
    ],
    '3': [
        { id: 12, nama: 'Cibabat' },
        { id: 13, nama: 'Cipageran' },
        { id: 14, nama: 'Citeureup' },
        { id: 15, nama: 'Pasirkaliki' }
    ]
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
let mapInstance = null;
let markerInstance = null;
let isMapInitialized = false;
let isUpdatingFromCoords = false;

// ============================================
// FUNGSI INISIALISASI PETA - INDEPENDEN
// ============================================
function initMap() {
    if (isMapInitialized) {
        if (mapInstance) {
            setTimeout(function() {
                mapInstance.invalidateSize();
            }, 100);
        }
        return;
    }

    const defaultLat = parseFloat(document.getElementById('latitude').value) || -6.870367;
    const defaultLng = parseFloat(document.getElementById('longitude').value) || 107.554704;

    mapInstance = L.map('map', {
        zoomControl: true,
        attributionControl: true
    }).setView([defaultLat, defaultLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapInstance);

    markerInstance = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(mapInstance);

    // ===== DRAG MARKER =====
    markerInstance.on('dragend', function() {
        const pos = markerInstance.getLatLng();
        document.getElementById('latitude').value = pos.lat.toFixed(8);
        document.getElementById('longitude').value = pos.lng.toFixed(8);
    });

    // ===== KLIK PETA =====
    mapInstance.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        markerInstance.setLatLng([lat, lng]);
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
    });

    // ===== MANUAL COORDINATE INPUT =====
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    function updateMapFromManualCoords() {
        const lat = parseFloat(latitudeInput.value);
        const lng = parseFloat(longitudeInput.value);

        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            if (isUpdatingFromCoords) return;
            isUpdatingFromCoords = true;

            mapInstance.setView([lat, lng], 16);
            markerInstance.setLatLng([lat, lng]);

            setTimeout(() => {
                isUpdatingFromCoords = false;
            }, 100);
        }
    }

    latitudeInput.addEventListener('change', updateMapFromManualCoords);
    latitudeInput.addEventListener('blur', updateMapFromManualCoords);
    longitudeInput.addEventListener('change', updateMapFromManualCoords);
    longitudeInput.addEventListener('blur', updateMapFromManualCoords);

    isMapInitialized = true;
}

// ============================================
// FUNGSI OPEN MODAL TAMBAH
// ============================================
function openModal() {
    document.getElementById('ormasModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    document.querySelectorAll('.border-red-500').forEach(function(el) {
        el.classList.remove('border-red-500');
    });
    document.querySelectorAll('.text-red-500.text-xs').forEach(function(el) {
        el.remove();
    });

    // Reset koordinat ke kosong saat modal dibuka
    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';

    setTimeout(function() {
        initMap();
    }, 300);
}

// ============================================
// FUNGSI CLOSE MODAL TAMBAH
// ============================================
function closeModal() {
    document.getElementById('ormasModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('ormasForm').reset();
    document.getElementById('kelurahan_id').innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';
    
    isUpdatingFromCoords = false;
    
    if (mapInstance) {
        mapInstance.remove();
        mapInstance = null;
        markerInstance = null;
        isMapInitialized = false;
    }
}

// ============================================
// FUNGSI OPEN MODAL HAPUS
// ============================================
function openDeleteModal(id, nama) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const text = document.getElementById('deleteModalText');
    
    form.action = '/admin/ormas/' + id;
    text.innerHTML = 'Anda akan menghapus ORMAS <strong>"' + nama + '"</strong>. Data yang dihapus tidak dapat dikembalikan.';
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// ============================================
// FUNGSI CLOSE MODAL HAPUS
// ============================================
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// ============================================
// CLOSE MODAL SAAT KLIK DI LUAR
// ============================================
document.getElementById('ormasModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// ============================================
// EVENT LISTENER KECAMATAN DI MODAL
// ============================================
document.getElementById('kecamatan_id').addEventListener('change', function() {
    const kecamatanId = this.value;
    const kelurahanSelect = document.getElementById('kelurahan_id');
    
    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    if (kecamatanId && kelurahanData[kecamatanId]) {
        kelurahanData[kecamatanId].forEach(function(kelurahan) {
            const option = document.createElement('option');
            option.value = kelurahan.id;
            option.textContent = kelurahan.nama;
            kelurahanSelect.appendChild(option);
        });
    }
});
</script>
@endsection