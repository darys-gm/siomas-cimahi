@extends('layouts.admin')

@section('title', 'Edit Agenda')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Agenda</h1>
        <a href="{{ route('admin.agenda.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('admin.agenda.update', $agenda->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Agenda <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $agenda->judul) }}" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror"
                       placeholder="Masukkan judul agenda">
                @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $agenda->tanggal) }}" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror">
                @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Tanggal saat ini: <strong>{{ $agenda->tanggal ? \Carbon\Carbon::parse($agenda->tanggal)->format('d F Y') : '-' }}</strong>
                </p>
            </div>

            <!-- Status Aktif/Nonaktif -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Agenda</label>
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $agenda->is_active) ? 'checked' : '' }}
                               onchange="updateStatusPreview()"
                               class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                            <span class="font-semibold">Aktif</span>
                            <p class="text-xs text-gray-500">Centang untuk membuat agenda aktif</p>
                        </label>
                    </div>
                    
                    <!-- Preview Status -->
                    <div class="ml-auto flex items-center gap-2">
                        <span class="text-sm text-gray-500">Status:</span>
                        <span id="statusPreview" class="px-3 py-1 text-xs font-semibold rounded-full {{ $agenda->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $agenda->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Status aktif berarti agenda akan tampil di halaman publik. Nonaktif berarti hanya tampil di preview admin.
                </p>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.agenda.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    function updateStatusPreview() {
        const checkbox = document.getElementById('is_active');
        const statusPreview = document.getElementById('statusPreview');
        
        if (checkbox.checked) {
            statusPreview.textContent = 'Aktif';
            statusPreview.className = 'px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
        } else {
            statusPreview.textContent = 'Nonaktif';
            statusPreview.className = 'px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800';
        }
    }

    // Jalankan saat halaman dimuat untuk set status awal
    document.addEventListener('DOMContentLoaded', function() {
        updateStatusPreview();
    });
</script>
@endsection