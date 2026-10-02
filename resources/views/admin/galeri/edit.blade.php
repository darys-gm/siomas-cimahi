@extends('layouts.admin')

@section('title', 'Edit Galeri')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Edit Galeri</h1>
        <a href="{{ route('admin.galeri.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Kelola Galeri
        </a>
    </div>

    <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data" id="galeriForm">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $galeri->judul) }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror" required>
                @error('judul')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ============================================ -->
            <!-- FIELD KATEGORI (BARU)                        -->
            <!-- ============================================ -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($kategoriList as $key => $label)
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-gray-50 {{ old('kategori', $galeri->kategori) == $key ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <input type="radio" name="kategori" value="{{ $key }}" 
                               {{ old('kategori', $galeri->kategori) == $key ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 focus:ring-blue-500" required>
                        <div class="ml-3 flex items-center gap-2">
                            <i class="fas {{ $key == 'ormas' ? 'fa-users text-blue-600' : 'fa-building text-purple-600' }}"></i>
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('kategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                @error('deskripsi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if($galeri->gambar)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                <img src="{{ asset('storage/' . $galeri->gambar) }}" alt="{{ $galeri->judul }}" class="h-32 object-cover rounded">
                <p class="text-xs text-gray-500 mt-1">Format: {{ pathinfo($galeri->gambar, PATHINFO_EXTENSION) }}</p>
            </div>
            @endif

            <!-- Drag & Drop Upload Gambar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ganti Gambar</label>
                <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer">
                    <input type="file" name="gambar" id="fileInput" accept="image/*" class="hidden">
                    <div id="dropzoneContent">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">Seret & Lepas gambar di sini</p>
                        <p class="text-gray-400 text-sm">atau klik untuk memilih file</p>
                        <p class="text-xs text-gray-400 mt-2">Max 5MB. Format: JPG, PNG, JPEG, WebP, GIF</p>
                        <p class="text-xs text-green-500 mt-1">✓ Gambar akan otomatis dioptimalkan</p>
                    </div>
                    <div id="previewContainer" class="hidden mt-4">
                        <img id="imagePreview" src="#" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow">
                        <button type="button" id="removeImage" class="mt-2 text-red-500 hover:text-red-700 text-sm">
                            <i class="fas fa-times"></i> Hapus Gambar
                        </button>
                    </div>
                </div>
                @error('gambar')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $galeri->is_active ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Aktifkan</label>
            </div>

            <div class="p-3 rounded-lg {{ $galeri->is_active ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                <p class="text-sm {{ $galeri->is_active ? 'text-green-700' : 'text-yellow-700' }}">
                    <i class="fas {{ $galeri->is_active ? 'fa-check-circle' : 'fa-clock' }} mr-2"></i>
                    Status saat ini: <strong>{{ $galeri->is_active ? 'Aktif' : 'Nonaktif' }}</strong>
                </p>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('admin.galeri.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const dropzoneContent = document.getElementById('dropzoneContent');
        const removeImage = document.getElementById('removeImage');

        dropzone.addEventListener('click', function(e) {
            if (e.target === dropzone || e.target.closest('#dropzoneContent')) {
                fileInput.click();
            }
        });

        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500', 'bg-blue-50');
            const files = e.dataTransfer.files;
            if (files.length > 0) handleFile(files[0]);
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) handleFile(this.files[0]);
        });

        removeImage.addEventListener('click', function() { clearImage(); });

        function handleFile(file) {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, WebP, atau GIF.');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                dropzoneContent.classList.add('hidden');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            };
            reader.readAsDataURL(file);
        }

        function clearImage() {
            imagePreview.src = '#';
            previewContainer.classList.add('hidden');
            dropzoneContent.classList.remove('hidden');
            fileInput.value = '';
        }
    });
</script>

<style>
    #dropzone {
        transition: all 0.3s ease;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #dropzone:hover {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }
    #imagePreview {
        max-height: 200px;
        object-fit: contain;
    }
</style>
@endsection