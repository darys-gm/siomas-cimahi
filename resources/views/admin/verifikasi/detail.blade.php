@extends('layouts.admin')

@section('title', 'Detail Verifikasi')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Detail Verifikasi</h1>
        <a href="{{ route('admin.verifikasi.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
            Kembali
        </a>
    </div>

    @if(isset($ormas))
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">Nama ORMAS</p>
            <p class="font-medium">{{ $ormas->nama }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Status Saat Ini</p>
            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $ormas->status_badge ?? 'bg-gray-100' }}">
                {{ $ormas->status_text ?? $ormas->status }}
            </span>
        </div>
    </div>

    @if($ormas->status === 'menunggu_verifikasi' || $ormas->status === 'revisi')
    <div class="border-t pt-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Verifikasi</h3>
        
        <form action="{{ route('admin.verifikasi.approve', $ormas->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                <i class="fas fa-check"></i> Setujui
            </button>
        </form>

        <button onclick="showRevisiForm()" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 ml-2">
            <i class="fas fa-edit"></i> Minta Revisi
        </button>

        <button onclick="showTolakForm()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 ml-2">
            <i class="fas fa-times"></i> Tolak
        </button>

        <div id="revisiForm" class="hidden mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
            <form action="{{ route('admin.verifikasi.revisi', $ormas->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Revisi</label>
                    <textarea name="catatan" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    Kirim Revisi
                </button>
                <button type="button" onclick="hideRevisiForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 ml-2">
                    Batal
                </button>
            </form>
        </div>

        <div id="tolakForm" class="hidden mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
            <form action="{{ route('admin.verifikasi.reject', $ormas->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea name="catatan" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Tolak
                </button>
                <button type="button" onclick="hideTolakForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 ml-2">
                    Batal
                </button>
            </form>
        </div>
    </div>
    @endif

    @if($ormas->catatan_revisi)
    <div class="mt-4 p-4 bg-orange-50 rounded-lg border border-orange-200">
        <p class="text-sm font-medium text-orange-800">Catatan Revisi:</p>
        <p class="text-sm text-orange-700">{{ $ormas->catatan_revisi }}</p>
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