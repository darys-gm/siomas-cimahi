@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
<!-- Statistik Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                <i class="fas fa-building text-blue-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total ORMAS</p>
                <p class="text-2xl font-bold text-gray-800" id="totalOrmas">0</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">ORMAS Aktif</p>
                <p class="text-2xl font-bold text-gray-800" id="ormasAktif">0</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-800" id="menungguVerifikasi">0</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">ORMAS Ditolak</p>
                <p class="text-2xl font-bold text-gray-800" id="ditolak">0</p>
            </div>
        </div>
    </div>
</div>

<!-- Grafik & Data -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Grafik Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-sm font-semibold text-gray-700">Status ORMAS</h3>
            <a href="{{ route('admin.ormas.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat semua →</a>
        </div>
        <div id="grafikStatusContainer" class="space-y-3">
            <!-- Akan diisi oleh JavaScript -->
        </div>
    </div>

    <!-- ORMAS per Kecamatan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-sm font-semibold text-gray-700">ORMAS per Kecamatan</h3>
            <a href="{{ route('admin.laporan.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat laporan →</a>
        </div>
        <div id="ormasPerKecamatanContainer" class="space-y-3">
            <!-- Akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

<!-- Pengajuan & Log -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pengajuan Baru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center space-x-2">
                <i class="fas fa-bell text-yellow-500"></i>
                <h3 class="text-sm font-semibold text-gray-700">Pengajuan Baru</h3>
                <span id="pengajuanCount" class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">0</span>
            </div>
            <a href="{{ route('admin.verifikasi.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat semua →</a>
        </div>
        <div id="pengajuanContainer" class="max-h-72 overflow-y-auto">
            <!-- Akan diisi oleh JavaScript -->
        </div>
    </div>

    <!-- Log Aktivitas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center space-x-2">
                <i class="fas fa-history text-blue-500"></i>
                <h3 class="text-sm font-semibold text-gray-700">Aktivitas Terbaru</h3>
            </div>
            <a href="{{ route('admin.logs.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat semua →</a>
        </div>
        <div id="logsContainer" class="max-h-72 overflow-y-auto">
            <!-- Akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

<script>
    // Fetch data dashboard
    async function fetchDashboardData() {
        try {
            const response = await fetch('{{ route("admin.dashboard.data") }}');
            const result = await response.json();

            if (result.success) {
                const data = result.data;
                
                // Update statistik cards
                document.getElementById('totalOrmas').textContent = data.totalOrmas;
                document.getElementById('ormasAktif').textContent = data.ormasAktif;
                document.getElementById('menungguVerifikasi').textContent = data.menungguVerifikasi;
                document.getElementById('ditolak').textContent = data.ditolak;

                renderGrafikStatus(data);
                renderOrmasPerKecamatan(data);
                renderPengajuan(data);
                renderLogs(data);
            }
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
        }
    }

    // Render Grafik Status
    function renderGrafikStatus(data) {
        const container = document.getElementById('grafikStatusContainer');
        if (data.grafikStatus && data.grafikStatus.length > 0) {
            const colors = {
                'draft': 'bg-gray-400',
                'menunggu_verifikasi': 'bg-yellow-500',
                'revisi': 'bg-orange-500',
                'disetujui': 'bg-green-500',
                'ditolak': 'bg-red-500'
            };
            const total = data.totalOrmas || 1;
            container.innerHTML = data.grafikStatus.map(function(item) {
                const percentage = (item.total / total) * 100;
                const color = colors[item.status] || 'bg-gray-400';
                const label = item.status.replace('_', ' ');
                return `
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">${label.charAt(0).toUpperCase() + label.slice(1)}</span>
                            <span class="font-medium text-gray-800">${item.total}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="${color} h-2 rounded-full transition-all duration-500" style="width: ${percentage}%"></div>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.innerHTML = '<p class="text-center text-gray-400 py-4 text-sm">Belum ada data</p>';
        }
    }

    // Render ORMAS per Kecamatan
    function renderOrmasPerKecamatan(data) {
        const container = document.getElementById('ormasPerKecamatanContainer');
        if (data.ormasPerKecamatan && data.ormasPerKecamatan.length > 0) {
            const max = Math.max.apply(null, data.ormasPerKecamatan.map(function(item) { return item.total; })) || 1;
            container.innerHTML = data.ormasPerKecamatan.map(function(item) {
                const percentage = (item.total / max) * 100;
                return `
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">${item.nama}</span>
                            <span class="font-medium text-gray-800">${item.total}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: ${percentage}%"></div>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.innerHTML = '<p class="text-center text-gray-400 py-4 text-sm">Belum ada data</p>';
        }
    }

    // Render Pengajuan Baru
    function renderPengajuan(data) {
        const container = document.getElementById('pengajuanContainer');
        const countBadge = document.getElementById('pengajuanCount');
        
        if (data.pengajuanBaru && data.pengajuanBaru.length > 0) {
            countBadge.textContent = data.pengajuanBaru.length;
            countBadge.className = 'bg-red-500 text-white text-xs px-2 py-0.5 rounded-full';
            
            container.innerHTML = data.pengajuanBaru.map(function(item) {
                return `
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 hover:bg-gray-50 px-3 rounded-lg transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                <i class="fas fa-building text-blue-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">${item.nama}</p>
                                <div class="flex items-center space-x-2 text-xs text-white">
                                    <span class="text-gray-500">${item.created_at}</span>
                                    <span class="px-2 py-0.5 rounded-full ${item.status_badge || 'bg-yellow-100 text-white'} text-xs">${item.status_text}</span>
                                </div>
                            </div>
                        </div>
                        <a href="/admin/verifikasi/${item.id}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded-lg hover:bg-blue-600 transition">
                            Verifikasi
                        </a>
                    </div>
                `;
            }).join('');
        } else {
            countBadge.textContent = '0';
            countBadge.className = 'bg-gray-300 text-white text-xs px-2 py-0.5 rounded-full';
            container.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-300 text-3xl block mb-2"></i>
                    <p class="text-gray-400 text-sm">Tidak ada pengajuan baru</p>
                </div>
            `;
        }
    }

    // Render Log Aktivitas
    function renderLogs(data) {
        const container = document.getElementById('logsContainer');
        if (data.logs && data.logs.length > 0) {
            container.innerHTML = data.logs.map(function(item) {
                const colors = {
                    'Login': 'bg-green-100 text-green-600',
                    'Logout': 'bg-red-100 text-red-600',
                    'Verifikasi': 'bg-blue-100 text-blue-600',
                    'Edit Profil ORMAS': 'bg-yellow-100 text-yellow-600',
                    'Ubah Password': 'bg-purple-100 text-purple-600',
                    'Tambah Berita': 'bg-indigo-100 text-indigo-600',
                    'Update Berita': 'bg-indigo-100 text-indigo-600',
                    'Hapus Berita': 'bg-red-100 text-red-600',
                    'Manajemen User': 'bg-pink-100 text-pink-600'
                };
                const color = colors[item.aktivitas] || 'bg-gray-100 text-gray-600';
                return `
                    <div class="flex items-center space-x-3 py-3 border-b border-gray-50 hover:bg-gray-50 px-3 rounded-lg transition">
                        <div class="w-8 h-8 rounded-full ${color} flex items-center justify-center text-xs font-bold">
                            ${item.user_initial}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800">
                                <span class="font-medium">${item.user_name}</span>
                                <span class="text-gray-500">${item.aktivitas}</span>
                            </p>
                            <p class="text-xs text-gray-400">${item.created_at}</p>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-gray-300 text-3xl block mb-2"></i>
                    <p class="text-gray-400 text-sm">Belum ada aktivitas</p>
                </div>
            `;
        }
    }

    // Fetch data setiap 5 detik
    setInterval(fetchDashboardData, 5000);
    fetchDashboardData();
</script>
@endsection