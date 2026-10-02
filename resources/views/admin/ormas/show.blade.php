@extends('layouts.admin')

@section('title', 'Detail ORMAS')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail ORMAS</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.ormas.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-list"></i> Kembali ke Data ORMAS
            </a>
        </div>
    </div>

    @if(isset($ormas))
    <!-- Status -->
    <div class="mb-6 p-4 rounded-lg {{ $ormas->status === 'disetujui' ? 'bg-green-50 border border-green-200' : ($ormas->status === 'ditolak' ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200') }}">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-sm font-medium text-gray-700">Status:</span>
                <span class="ml-2 px-3 py-1 text-sm font-semibold rounded-full {{ $ormas->status_badge ?? 'bg-gray-500' }} text-white">
                    {{ $ormas->status_text ?? $ormas->status }}
                </span>
                @if($ormas->status === 'menunggu_verifikasi')
                <span class="ml-2 text-xs text-yellow-600">
                    <i class="fas fa-clock mr-1"></i> Menunggu verifikasi admin
                </span>
                @endif
                <!-- ===== STATUS AKTIF/NONAKTIF ===== -->
                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full {{ $ormas->status_active_badge }}">
                    <i class="fas {{ $ormas->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                    {{ $ormas->status_active_text }}
                </span>
                <!-- ===== STATUS PELAPORAN ===== -->
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
                    $pelaporanValue = $ormas->pelaporan ?? 'belum';
                @endphp
                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full {{ $pelaporanColors[$pelaporanValue] ?? 'bg-gray-100 text-gray-700' }}">
                    <i class="fas fa-file-alt mr-1"></i>
                    {{ $pelaporanLabels[$pelaporanValue] ?? 'Belum' }}
                </span>
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar mr-1"></i>
                Dilaporkan: {{ $ormas->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
        @if($ormas->status === 'revisi' && $ormas->catatan_revisi)
        <div class="mt-2 p-3 bg-orange-100 rounded-lg">
            <p class="text-sm text-orange-700">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Catatan Revisi:</strong> {{ $ormas->catatan_revisi }}
            </p>
        </div>
        @endif
        @if($ormas->status === 'ditolak' && $ormas->catatan_revisi)
        <div class="mt-2 p-3 bg-red-100 rounded-lg">
            <p class="text-sm text-red-700">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Alasan Ditolak:</strong> {{ $ormas->catatan_revisi }}
            </p>
        </div>
        @endif
    </div>

    <!-- Informasi ORMAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Data ORMAS -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
                <i class="fas fa-building text-blue-600 mr-2"></i> Data ORMAS
            </h3>
            <div class="space-y-2">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Nama ORMAS</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->nama }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Singkatan</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->singkatan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">No AHU/SKT</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->nomor_registrasi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Bentuk Ormas</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->jenisOrmas->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Bidang Kegiatan Ormas</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->bidangKegiatan->nama ?? '-' }}</p>
                </div>
                <!-- ===== STATUS PELAPORAN ===== -->
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Status Pelaporan</p>
                    <p class="font-semibold text-gray-800">
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $pelaporanColors[$pelaporanValue] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $pelaporanLabels[$pelaporanValue] ?? 'Belum' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Kontak & Alamat -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
                <i class="fas fa-address-card text-blue-600 mr-2"></i> Kontak & Alamat
            </h3>
            <div class="space-y-2">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Alamat Kesekretariatan</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->alamat_kesekretariatan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Kecamatan</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->kecamatan->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Kelurahan</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->kelurahan->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">No Telepon</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->no_telepon ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">E-Mail</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->email ?? '-' }}</p>
                </div>
                <!-- ===== KOORDINAT ===== -->
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Koordinat</p>
                    <p class="font-semibold text-gray-800">
                        @if($ormas->latitude && $ormas->longitude)
                            {{ $ormas->latitude }}, {{ $ormas->longitude }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== DATA KEANGGOTAAN ===== -->
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
            <i class="fas fa-users text-blue-600 mr-2"></i> Data Keanggotaan
        </h3>
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Total Anggota</p>
                    <p class="text-xl font-bold text-black-600">{{ $ormas->jumlah_anggota ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Anggota Perempuan</p>
                    <p class="text-xl font-bold text-black-600">{{ $ormas->jumlah_anggota_perempuan ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Perempuan (16-30 th)</p>
                    <p class="text-xl font-bold text-black-600">{{ $ormas->anggota_perempuan_rentang_16_30 ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Anggota Laki-laki</p>
                    <p class="text-xl font-bold text-black-600">{{ $ormas->jumlah_anggota_laki_laki ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Laki-laki (16-30 th)</p>
                    <p class="text-xl font-bold text-black-600">{{ $ormas->anggota_laki_laki_rentang_16_30 ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== PETA LOKASI ===== -->
    @if($ormas->latitude && $ormas->longitude)
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
            <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i> Lokasi Peta
        </h3>
        <div class="bg-gray-50 rounded-lg p-4">
            <div id="detailMap" class="w-full h-80 rounded-lg border border-gray-300 overflow-hidden"></div>
            <div class="mt-2 text-xs text-gray-500 text-center">
                <i class="fas fa-map-pin text-red-500 mr-1"></i>
                Lokasi: {{ $ormas->alamat_kesekretariatan ?? 'Tidak ada alamat' }}
            </div>
        </div>
    </div>
    @endif

    <!-- Data Pengurus -->
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
            <i class="fas fa-users text-blue-600 mr-2"></i> Data Pengurus
        </h3>
        @php
            $pengurusList = $ormas->pengurus;
        @endphp
        
        @if($pengurusList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($pengurusList as $pengurus)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h4 class="font-semibold text-blue-600 mb-2 text-sm">
                    <i class="fas fa-user mr-1"></i>
                    {{ $pengurus->jabatan ?? 'Pengurus' }}
                </h4>
                <div class="space-y-1 text-sm">
                    <p><span class="text-gray-500">Nama:</span> <span class="font-medium">{{ $pengurus->nama }}</span></p>
                    <p><span class="text-gray-500">Alamat:</span> {{ $pengurus->alamat ?? '-' }}</p>
                    <p><span class="text-gray-500">No Telepon:</span> {{ $pengurus->no_hp ?? '-' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            <i class="fas fa-users text-3xl text-gray-300 mb-2 block"></i>
            <p class="text-gray-500">Belum ada data pengurus</p>
        </div>
        @endif
    </div>

    <!-- Aksi Verifikasi (Hanya untuk status menunggu_verifikasi) -->
    @if($ormas->status === 'menunggu_verifikasi' || $ormas->status === 'revisi')
    <div class="border-t pt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Aksi Verifikasi</h3>
        <div class="flex flex-wrap gap-2">
            <form action="{{ route('admin.verifikasi.approve', $ormas->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                    <i class="fas fa-check"></i> Setujui
                </button>
            </form>

            <button onclick="showRevisiForm()" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition flex items-center gap-2">
                <i class="fas fa-edit"></i> Minta Revisi
            </button>

            <button onclick="showTolakForm()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex items-center gap-2">
                <i class="fas fa-times"></i> Tolak
            </button>
        </div>

        <!-- Form Revisi -->
        <div id="revisiForm" class="hidden mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
            <form action="{{ route('admin.verifikasi.revisi', $ormas->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Revisi <span class="text-red-500">*</span></label>
                    <textarea name="catatan" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" placeholder="Berikan catatan untuk revisi data" required></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                        Kirim Revisi
                    </button>
                    <button type="button" onclick="hideRevisiForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <!-- Form Tolak -->
        <div id="tolakForm" class="hidden mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
            <form action="{{ route('admin.verifikasi.reject', $ormas->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Berikan alasan penolakan" required></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Tolak
                    </button>
                    <button type="button" onclick="hideTolakForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endif
</div>

<!-- ===== LEAFET + OPENSTREETMAP (Untuk Detail Map) ===== -->
@if(isset($ormas) && $ormas->latitude && $ormas->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $ormas->latitude }};
        const lng = {{ $ormas->longitude }};

        const map = L.map('detailMap').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lng], {
            draggable: false
        }).addTo(map)
        .bindPopup('<b>{{ $ormas->nama }}</b><br>{{ $ormas->alamat_kesekretariatan ?? "Tidak ada alamat" }}<br><b>Jumlah Anggota:</b> {{ $ormas->jumlah_anggota ?? 0 }}<br><b>Perempuan:</b> {{ $ormas->jumlah_anggota_perempuan ?? 0 }}<br><b>Laki-laki:</b> {{ $ormas->jumlah_anggota_laki_laki ?? 0 }}')
        .openPopup();

        // Force refresh map
        setTimeout(function() {
            map.invalidateSize();
        }, 300);
    });
</script>
@endif

<script>
function showRevisiForm() {
    document.getElementById('revisiForm').classList.remove('hidden');
    document.getElementById('tolakForm').classList.add('hidden');
}
function hideRevisiForm() {
    document.getElementById('revisiForm').classList.add('hidden');
}
function showTolakForm() {
    document.getElementById('tolakForm').classList.remove('hidden');
    document.getElementById('revisiForm').classList.add('hidden');
}
function hideTolakForm() {
    document.getElementById('tolakForm').classList.add('hidden');
}
</script>
@endsection