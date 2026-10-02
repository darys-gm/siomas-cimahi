@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Berita</h1>
        <a href="{{ route('admin.berita.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Kelola Berita
        </a>
    </div>

    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" id="beritaForm">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror" required>
                @error('judul')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Berita <span class="text-red-500">*</span></label>
                <textarea name="isi" rows="8" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('isi') border-red-500 @enderror" required>{{ old('isi') }}</textarea>
                @error('isi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Drag & Drop Upload Gambar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer">
                    <input type="file" name="gambar" id="fileInput" accept="image/*" class="hidden">
                    <div id="dropzoneContent">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">Seret & Lepas gambar di sini</p>
                        <p class="text-gray-400 text-sm">atau klik untuk memilih file</p>
                        <p class="text-xs text-gray-400 mt-2">Max 5MB. Format: JPG, PNG, JPEG, WebP</p>
                        <p class="text-xs text-green-500 mt-1">✓ Gambar akan otomatis dikonversi ke WebP</p>
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

            <!-- Tombol Publikasikan -->
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                <input type="checkbox" name="is_published" id="is_published" value="1" checked
                       class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_published" class="ml-3 text-sm text-gray-700">
                    <span class="font-semibold">Publikasikan</span>
                    <p class="text-xs text-gray-500">Jika dicentang, berita akan tampil di halaman user. Jika tidak, hanya tampil di preview admin.</p>
                </label>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.berita.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
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

        // Klik dropzone untuk membuka file dialog
        dropzone.addEventListener('click', function(e) {
            if (e.target === dropzone || e.target.closest('#dropzoneContent')) {
                fileInput.click();
            }
        });

        // Drag & Drop events
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
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });

        // File input change
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFile(this.files[0]);
            }
        });

        // Hapus gambar
        removeImage.addEventListener('click', function() {
            clearImage();
        });

        function handleFile(file) {
            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, WebP, atau GIF.');
                return;
            }

            // Validasi ukuran (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                return;
            }

            // Tampilkan preview
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                dropzoneContent.classList.add('hidden');
                
                // Update file input
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