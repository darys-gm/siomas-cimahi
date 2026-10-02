@extends('layouts.admin')

@section('title', 'Detail Verifikasi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Verifikasi ORMAS</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <a href="{{ route('admin.verifikasi.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-list"></i> Kembali ke Verifikasi
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
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Jenis ORMAS</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->jenisOrmas->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Bidang ORMAS</p>
                    <p class="font-semibold text-gray-800">{{ $ormas->bidangKegiatan->nama ?? '-' }}</p>
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
            </div>
        </div>
    </div>

    <!-- Data Pengurus -->
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">
            <i class="fas fa-users text-blue-600 mr-2"></i> Data Pengurus
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $pengurusGroup = $ormas->pengurus->groupBy('jabatan');
                $jabatanOrder = ['Ketua', 'Sekretaris', 'Bendahara'];
            @endphp
            @foreach($jabatanOrder as $jabatan)
                @php
                    $pengurus = $pengurusGroup->get($jabatan, collect())->first();
                @endphp
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h4 class="font-semibold text-blue-600 mb-2 text-sm">
                        <i class="fas {{ $jabatan == 'Ketua' ? 'fa-user-tie' : ($jabatan == 'Sekretaris' ? 'fa-user-edit' : 'fa-user-tie') }} mr-1"></i>
                        {{ $jabatan }}
                    </h4>
                    @if($pengurus)
                    <div class="space-y-1 text-sm">
                        <p><span class="text-gray-500">Nama:</span> <span class="font-medium">{{ $pengurus->nama }}</span></p>
                        <p><span class="text-gray-500">Alamat:</span> {{ $pengurus->alamat ?? '-' }}</p>
                        <p><span class="text-gray-500">No Telepon:</span> {{ $pengurus->no_hp ?? '-' }}</p>
                        <p><span class="text-gray-500">NIK:</span> {{ $pengurus->nik ?? '-' }}</p>
                    </div>
                    @else
                    <p class="text-sm text-gray-400">Data tidak tersedia</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Aksi Verifikasi -->
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