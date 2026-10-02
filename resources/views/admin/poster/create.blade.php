@extends('layouts.admin')

@section('title', 'Tambah Poster')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Poster</h1>
        <a href="{{ route('admin.poster.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.poster.store') }}" method="POST" enctype="multipart/form-data" id="posterForm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('judul')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Link (opsional)</label>
                <input type="url" name="link" value="{{ old('link') }}" 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://...">
                @error('link')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">Semakin kecil angka, semakin atas tampilannya.</p>
                @error('urutan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-end">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span class="text-sm text-gray-700">Aktif</span>
                </label>
            </div>

            <!-- ===== DRAG & DROP GAMBAR ===== -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar <span class="text-red-500">*</span></label>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer relative">
                    <input type="file" name="gambar" id="fileInput" accept="image/*" class="hidden" required>
                    
                    <div id="dropZoneContent">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 text-sm">Seret & letakkan gambar di sini</p>
                        <p class="text-gray-400 text-xs mt-1">atau klik untuk memilih file</p>
                        <p class="text-gray-400 text-xs mt-2">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="hidden mt-3">
                        <img id="previewImg" src="#" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow">
                        <button type="button" onclick="removeImage()" class="mt-2 text-red-500 hover:text-red-700 text-sm">
                            <i class="fas fa-times"></i> Hapus Gambar
                        </button>
                    </div>
                </div>
                @error('gambar')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.poster.index') }}" class="px-6 py-2.5 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    // ============================================
    // DRAG & DROP IMAGE
    // ============================================
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const dropZoneContent = document.getElementById('dropZoneContent');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    // Klik pada drop zone untuk membuka file dialog
    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    // Drag & Drop events
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                handleFile(file);
            } else {
                alert('File harus berupa gambar!');
            }
        }
    });

    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFile(this.files[0]);
        }
    });

    // Handle file
    function handleFile(file) {
        // Validasi ukuran (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran gambar terlalu besar! Maksimal 2MB.');
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            dropZoneContent.classList.add('hidden');
            imagePreview.classList.remove('hidden');
            
            // Update file input untuk dikirim ke server
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        };
        reader.readAsDataURL(file);
    }

    // Remove image
    function removeImage() {
        previewImg.src = '#';
        imagePreview.classList.add('hidden');
        dropZoneContent.classList.remove('hidden');
        fileInput.value = '';
    }
</script>
@endsection