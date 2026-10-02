@extends('layouts.admin')

@section('title', 'Edit Produk Hukum')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Edit Produk Hukum</h1>
        <a href="{{ route('admin.produk-hukum.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.produk-hukum.update', $produkHukum->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Produk Hukum <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $produkHukum->judul) }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror" required>
                @error('judul')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $produkHukum->keterangan) }}</textarea>
                @error('keterangan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File PDF Saat Ini</label>
                @if($produkHukum->file_pdf)
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                    <i class="fas fa-file-pdf text-red-500"></i>
                    <span class="text-sm text-gray-600">{{ $produkHukum->file_pdf }}</span>
                    <a href="{{ route('produk-hukum.show', $produkHukum->id) }}" target="_blank" 
                       class="text-blue-600 hover:text-blue-800 text-sm ml-auto">
                        <i class="fas fa-eye"></i> Lihat
                    </a>
                </div>
                @else
                <p class="text-sm text-gray-400">Belum ada file</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti File PDF</label>
                <input type="file" name="file_pdf" accept=".pdf" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('file_pdf') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Max 10MB. Format: PDF. Kosongkan jika tidak ingin mengganti</p>
                @error('file_pdf')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $produkHukum->is_active ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Aktifkan</label>
            </div>

            <div class="p-3 rounded-lg {{ $produkHukum->is_active ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                <p class="text-sm {{ $produkHukum->is_active ? 'text-green-700' : 'text-yellow-700' }}">
                    <i class="fas {{ $produkHukum->is_active ? 'fa-check-circle' : 'fa-clock' }} mr-2"></i>
                    Status saat ini: <strong>{{ $produkHukum->is_active ? 'Aktif' : 'Nonaktif' }}</strong>
                </p>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.produk-hukum.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection