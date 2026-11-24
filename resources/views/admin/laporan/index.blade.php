@extends('layouts.admin')

@section('title', 'Statistik & Laporan ORMAS')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800">Statistik & Laporan ORMAS</h1>
    </div>

    <!-- ============================================ -->
    <!-- FORM UTAMA (Membungkus Filter, Pencarian & Tabel) -->
    <!-- ============================================ -->
    <form method="GET" action="{{ route('admin.laporan.index') }}" id="filterForm">

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-600 font-medium uppercase">Total Pendaftaran</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $totalOrmas }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-building text-blue-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-green-600 font-medium uppercase">Disetujui</p>
                        <p class="text-2xl font-bold text-green-700">{{ $totalDisetujui }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-red-600 font-medium uppercase">Ditolak</p>
                        <p class="text-2xl font-bold text-red-700">{{ $totalDitolak }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-500"></i>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-yellow-600 font-medium uppercase">Menunggu Verifikasi</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $totalMenunggu }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Batang -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Grafik Pendaftaran ORMAS per Bulan ({{ $tahun }})</h3>
            <div class="relative h-72">
                <canvas id="grafikChart"></canvas>
            </div>
        </div>

        <!-- Section Filter dan Cetak File -->
        <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <!-- Pilih Tahun -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tahun</label>
                <select name="tahun" id="filterTahun" class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    @foreach($tahunTersedia as $t)
                        <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Cetak -->
            <div class="border-t border-gray-200 pt-4 mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Filter Cetak Laporan</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- Filter Kecamatan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Kecamatan</label>
                        <select name="cetak_kecamatan" id="filterKecamatan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Semua Kecamatan</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}" {{ request('cetak_kecamatan') == $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kelurahan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Kelurahan</label>
                        <select name="cetak_kelurahan" id="filterKelurahan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Semua Kelurahan</option>
                            @if(request('cetak_kecamatan'))
                                @php
                                    $kelurahans = \App\Models\Kelurahan::where('kecamatan_id', request('cetak_kecamatan'))->orderBy('nama')->get();
                                @endphp
                                @foreach($kelurahans as $kel)
                                    <option value="{{ $kel->id }}" {{ request('cetak_kelurahan') == $kel->id ? 'selected' : '' }}>{{ $kel->nama }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Filter Bentuk Ormas -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Bentuk Ormas</label>
                        <select name="cetak_jenis" id="filterJenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Semua Bentuk</option>
                            @foreach($jenisOrmas as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('cetak_jenis') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Bidang Kegiatan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Bidang Kegiatan</label>
                        <select name="cetak_bidang" id="filterBidang" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Semua Bidang</option>
                            @foreach($bidangKegiatan as $bidang)
                                <option value="{{ $bidang->id }}" {{ request('cetak_bidang') == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
                        <i class="fas fa-filter"></i> Terapkan Filter
                    </button>

                    @if(request('search') || request('cetak_kecamatan') || request('cetak_kelurahan') || request('cetak_jenis') || request('cetak_bidang'))
                    <a href="{{ route('admin.laporan.index', ['tahun' => $tahun]) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-sm flex items-center gap-2">
                        <i class="fas fa-times"></i> Reset Filter
                    </a>
                    @endif

                    <div class="border-l border-gray-300 mx-2"></div> <!-- Garis Pemisah -->

                    <button type="button" onclick="downloadAllData(event)" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm flex items-center gap-2">
                        <i class="fas fa-file-pdf"></i> Cetak Semua Data
                    </button>
                    <button type="button" onclick="downloadExcel(event)" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Pencarian Nama ORMAS -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <h3 class="text-sm font-semibold text-gray-700">Pencarian Data ORMAS</h3>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                               placeholder="Ketik nama ORMAS..."
                               class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @if(request('search'))
                        <button type="button" onclick="clearSearch()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i class="fas fa-times-circle"></i>
                        </button>
                        @endif
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2 flex-shrink-0">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </div>

        <!-- Data ORMAS Terbaru -->
        <div class="mt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Data ORMAS {{ $tahun }}</h3>
                <span class="text-sm text-gray-500">Menampilkan {{ $ormas->firstItem() ?? 0 }} - {{ $ormas->lastItem() ?? 0 }} dari {{ $ormas->total() }} data</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Nama ORMAS</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">No AHU/SKT</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Bentuk Ormas</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Kecamatan</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($ormas ?? [] as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration + (($ormas->currentPage() - 1) * $ormas->perPage()) }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $item->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item->nomor_registrasi ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item->jenisOrmas->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item->kecamatan->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item->kelurahan->nama ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_badge ?? 'bg-gray-500' }} text-white">
                                    {{ $item->status_text ?? $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="downloadDetailData({{ $item->id }})"
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 hover:bg-red-200 transition inline-flex items-center gap-1">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button type="button" onclick="downloadDetailExcel({{ $item->id }})"
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 hover:bg-green-200 transition inline-flex items-center gap-1">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl text-gray-300 block mb-3"></i>
                                @if(request('search'))
                                    <p>Tidak ada ORMAS dengan nama "<strong>{{ request('search') }}</strong>" untuk tahun {{ $tahun }}.</p>
                                @else
                                    <p>Belum ada data ORMAS untuk tahun {{ $tahun }} dengan filter/pencarian yang dipilih.</p>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION CUSTOM -->
            @if($ormas->hasPages())
            <div class="mt-4 px-2 py-3">
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
                            <a href="{{ $ormas->previousPageUrl() }}" class="pagination-arrow inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition min-w-[28px] sm:min-w-[32px]">
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
                            <a href="{{ $ormas->url(1) }}" class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition min-w-[24px] sm:min-w-[30px]">
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
                            <a href="{{ $ormas->url($currentPage - 1) }}" class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition min-w-[24px] sm:min-w-[30px]">
                                {{ $currentPage - 1 }}
                            </a>
                        @endif

                        {{-- Halaman current --}}
                        <span class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-blue-600 bg-blue-600 text-[10px] sm:text-xs font-medium text-white min-w-[24px] sm:min-w-[30px]">
                            {{ $currentPage }}
                        </span>

                        {{-- Halaman setelah current --}}
                        @if($currentPage < $lastPage)
                            <a href="{{ $ormas->url($currentPage + 1) }}" class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition min-w-[24px] sm:min-w-[30px]">
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
                            <a href="{{ $ormas->url($lastPage) }}" class="pagination-number inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-[10px] sm:text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition min-w-[24px] sm:min-w-[30px]">
                                {{ $lastPage }}
                            </a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($ormas->hasMorePages())
                            <a href="{{ $ormas->nextPageUrl() }}" class="pagination-arrow inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-1.5 rounded-md border border-gray-300 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition min-w-[28px] sm:min-w-[32px]">
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
    </form> <!-- END FORM UTAMA -->
</div>

<!-- ============================================ -->
<!-- MODAL LOADING                                -->
<!-- ============================================ -->
<div id="loadingModal" class="hidden fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center">
        <!-- CSS Loader -->
        <div class="loader mx-auto mb-4"></div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2" id="loadingTitle">Sedang Memproses...</h3>
        <p class="text-sm text-gray-500" id="loadingMessage">Mohon tunggu, sedang menyiapkan file.</p>
        <button onclick="cancelDownload()"
                class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
            <i class="fas fa-times mr-1"></i> Batalkan
        </button>
    </div>
</div>

<!-- ============================================ -->
<!-- CSS LOADER & PAGINATION STYLE                -->
<!-- ============================================ -->
<style>
    /* CSS Loader */
    .loader {
        width: fit-content;
        font-weight: bold;
        font-family: monospace;
        font-size: 30px;
        clip-path: inset(0 3ch 0 0);
        animation: l4 1s steps(4) infinite;
        color: #dc2626;
    }
    .loader:before {
        content: "Loading...";
    }
    @keyframes l4 {
        to { clip-path: inset(0 -1ch 0 0); }
    }

    /* Pagination Styling */
    .pagination {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .pagination .page-item {
        display: inline-block;
    }
    .pagination .page-link {
        padding: 6px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        color: #4a5568;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        background: white;
    }
    .pagination .page-link:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
    }
    .pagination .active .page-link {
        background: #1a56db;
        color: white;
        border-color: #1a56db;
    }
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    // FUNGSI UPDATE KELURAHAN BERDASARKAN KECAMATAN
    // ============================================
    function updateKelurahan() {
        const kecamatanId = document.getElementById('filterKecamatan').value;
        const kelurahanSelect = document.getElementById('filterKelurahan');
        const selectedKelurahan = '{{ request('cetak_kelurahan') }}';
        
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

    document.addEventListener('DOMContentLoaded', function() {
        // Jalankan saat pertama kali load jika ada filter kecamatan
        const kecamatanId = document.getElementById('filterKecamatan').value;
        if (kecamatanId) {
            updateKelurahan();
        }

        // Event listener saat Kecamatan diubah
        document.getElementById('filterKecamatan').addEventListener('change', function() {
            updateKelurahan();
            this.closest('form').submit(); // Opsional: Auto submit form
        });
    });

    // ============================================
    // FUNGSI CLEAR SEARCH
    // ============================================
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterForm').submit();
    }

    // ============================================
    // VARIABEL UNTUK LOADING
    // ============================================
    let downloadTimeout = null;
    let isDownloading = false;

    // ============================================
    // FUNGSI DOWNLOAD ALL DATA (PDF)
    // ============================================
    function downloadAllData(event) {
        if (event) event.preventDefault();

        const form = document.getElementById('filterForm');
        const tahun = form.querySelector('select[name="tahun"]').value;
        const kecamatan = form.querySelector('select[name="cetak_kecamatan"]').value;
        const kelurahan = form.querySelector('select[name="cetak_kelurahan"]').value;
        const jenis = form.querySelector('select[name="cetak_jenis"]').value;
        const bidang = form.querySelector('select[name="cetak_bidang"]').value;

        let url = "{{ route('admin.laporan.download-all') }}?" +
            "tahun=" + encodeURIComponent(tahun) +
            "&kecamatan_id=" + encodeURIComponent(kecamatan) +
            "&kelurahan_id=" + encodeURIComponent(kelurahan) +
            "&jenis_ormas_id=" + encodeURIComponent(jenis) +
            "&bidang_kegiatan_id=" + encodeURIComponent(bidang);

        showLoading('Sedang Menyiapkan Laporan PDF', 'Mohon tunggu, sedang menyiapkan file PDF semua data ORMAS.');

        setTimeout(function() {
            window.location.href = url;
            hideLoading();
        }, 1000);
    }

    // ============================================
    // FUNGSI DOWNLOAD EXCEL
    // ============================================
    function downloadExcel(event) {
        if (event) event.preventDefault();

        const form = document.getElementById('filterForm');
        const tahun = form.querySelector('select[name="tahun"]').value;
        const kecamatan = form.querySelector('select[name="cetak_kecamatan"]').value;
        const kelurahan = form.querySelector('select[name="cetak_kelurahan"]').value;
        const jenis = form.querySelector('select[name="cetak_jenis"]').value;
        const bidang = form.querySelector('select[name="cetak_bidang"]').value;

        let url = "{{ route('admin.laporan.export-excel') }}?" +
            "tahun=" + encodeURIComponent(tahun) +
            "&kecamatan_id=" + encodeURIComponent(kecamatan) +
            "&kelurahan_id=" + encodeURIComponent(kelurahan) +
            "&jenis_ormas_id=" + encodeURIComponent(jenis) +
            "&bidang_kegiatan_id=" + encodeURIComponent(bidang);

        showLoading('Sedang Menyiapkan Laporan Excel', 'Mohon tunggu, sedang menyiapkan file Excel semua data ORMAS.');

        setTimeout(function() {
            window.location.href = url;
            hideLoading();
        }, 1000);
    }

    // ============================================
    // FUNGSI DOWNLOAD DETAIL PDF
    // ============================================
    function downloadDetailData(id) {
        if (!id) {
            console.error('ID tidak valid:', id);
            alert('Terjadi kesalahan: ID ORMAS tidak ditemukan.');
            return;
        }

        let url = "{{ route('admin.laporan.download-detail', ['id' => ':id']) }}";
        url = url.replace(':id', id);

        showLoading('Sedang Menyiapkan Detail PDF', 'Mohon tunggu, sedang menyiapkan file PDF detail ORMAS.');

        setTimeout(function() {
            window.location.href = url;
            hideLoading();
        }, 1000);
    }

    // ============================================
    // FUNGSI DOWNLOAD DETAIL EXCEL
    // ============================================
    function downloadDetailExcel(id) {
        if (!id) {
            console.error('ID tidak valid:', id);
            alert('Terjadi kesalahan: ID ORMAS tidak ditemukan.');
            return;
        }

        let url = "{{ route('admin.laporan.export-detail-excel', ['id' => ':id']) }}";
        url = url.replace(':id', id);

        showLoading('Sedang Menyiapkan Detail Excel', 'Mohon tunggu, sedang menyiapkan file Excel detail ORMAS.');

        setTimeout(function() {
            window.location.href = url;
            hideLoading();
        }, 1000);
    }

    // ============================================
    // FUNGSI SHOW LOADING
    // ============================================
    function showLoading(title, message) {
        const modal = document.getElementById('loadingModal');
        const titleEl = document.getElementById('loadingTitle');
        const messageEl = document.getElementById('loadingMessage');

        titleEl.textContent = title || 'Sedang Memproses...';
        messageEl.textContent = message || 'Mohon tunggu, sedang menyiapkan file.';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (downloadTimeout) clearTimeout(downloadTimeout);
        downloadTimeout = setTimeout(function() {
            hideLoading();
        }, 30000); // 30 detik timeout
    }

    // ============================================
    // FUNGSI HIDE LOADING
    // ============================================
    function hideLoading() {
        const modal = document.getElementById('loadingModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';

        if (downloadTimeout) {
            clearTimeout(downloadTimeout);
            downloadTimeout = null;
        }
    }

    // ============================================
    // FUNGSI CANCEL DOWNLOAD
    // ============================================
    function cancelDownload() {
        hideLoading();
    }

    // ============================================
    // CHART.JS - GRAFIK
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('grafikChart');
        if (!ctx) return;

        const grafikData = @json($grafikBulanan);

        const labels = grafikData && grafikData.bulan ? grafikData.bulan : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const totalData = grafikData && grafikData.total ? grafikData.total : Array(12).fill(0);
        const disetujuiData = grafikData && grafikData.disetujui ? grafikData.disetujui : Array(12).fill(0);
        const ditolakData = grafikData && grafikData.ditolak ? grafikData.ditolak : Array(12).fill(0);
        const menungguData = grafikData && grafikData.menunggu ? grafikData.menunggu : Array(12).fill(0);

        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Pendaftaran',
                        data: totalData,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'Disetujui',
                        data: disetujuiData,
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'Ditolak',
                        data: ditolakData,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'Menunggu Verifikasi',
                        data: menungguData,
                        backgroundColor: 'rgba(234, 179, 8, 0.7)',
                        borderColor: 'rgba(234, 179, 8, 1)',
                        borderWidth: 2,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { size: 11, weight: '500' },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    });

    // ============================================
    // AUTO SUBMIT SAAT DROPDOWN BERUBAH
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = ['filterTahun', 'filterKecamatan', 'filterKelurahan', 'filterJenis', 'filterBidang'];

        dropdowns.forEach(function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
    });

    // ============================================
    // CLOSE MODAL SAAT KLIK DI LUAR
    // ============================================
    document.getElementById('loadingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            cancelDownload();
        }
    });
</script>
@endsection