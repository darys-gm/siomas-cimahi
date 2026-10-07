@extends('layouts.admin')

@section('title', 'Manajemen Struktur Organisasi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Struktur Organisasi</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola gambar struktur organisasi Bakesbangpol Kota Cimahi</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
        <p class="text-sm text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Info:</strong> Hanya ada 1 gambar struktur yang bisa aktif. Ketika Anda upload gambar baru, gambar lama akan otomatis terhapus.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Preview Gambar -->
        <div class="lg:col-span-2">
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-image text-blue-600"></i>
                    Preview Gambar Struktur
                </h2>

                @if($struktur && $struktur->gambar)
                    <div class="bg-white rounded-lg border border-gray-200 p-3">
                        <img src="{{ asset('storage/' . $struktur->gambar) }}" 
                             alt="Struktur Organisasi" 
                             class="w-full h-auto rounded-lg">
                        
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                Terakhir diupdate: {{ $struktur->updated_at->translatedFormat('d F Y, H:i') }} WIB
                            </div>
                            <button onclick="confirmDelete({{ $struktur->id }})" 
                                    class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition inline-flex items-center gap-2">
                                <i class="fas fa-trash"></i> Hapus Gambar
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                        <i class="fas fa-image text-5xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500">Belum ada gambar struktur organisasi</p>
                        <p class="text-xs text-gray-400 mt-1">Silakan upload gambar di form sebelah kanan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Form Upload -->
        <div class="lg:col-span-1">
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sticky top-24">
                <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-cloud-upload-alt text-blue-600"></i>
                    {{ $struktur ? 'Ganti Gambar' : 'Upload Gambar' }}
                </h2>

                <form action="{{ route('admin.struktur.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <!-- Dropzone -->
                    <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer bg-white">
                        <input type="file" name="gambar" id="fileInput" accept="image/*" class="hidden" required>
                        
                        <div id="dropzoneContent">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-600">Klik atau drag gambar ke sini</p>
                            <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, WEBP</p>
                            <p class="text-xs text-gray-400">Max: 5 MB</p>
                        </div>

                        <div id="previewContainer" class="hidden mt-3">
                            <img id="imagePreview" src="#" alt="Preview" class="max-h-40 mx-auto rounded-lg shadow">
                            <button type="button" id="removeImage" class="mt-2 text-red-500 hover:text-red-700 text-xs">
                                <i class="fas fa-times"></i> Hapus Gambar
                            </button>
                        </div>
                    </div>

                    @error('gambar')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror

                    <button type="submit" 
                            class="w-full mt-4 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium inline-flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        {{ $struktur ? 'Simpan Perubahan' : 'Upload Sekarang' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI HAPUS                       -->
<!-- ============================================ -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeDeleteModal()"></div>
    
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center animate-pulse">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 text-center text-sm mb-6">
                Apakah Anda yakin ingin menghapus gambar struktur organisasi ini?<br>
                <span class="text-xs text-red-500 mt-2 inline-block">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    Data yang dihapus tidak dapat dikembalikan!
                </span>
            </p>

            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" 
                        class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition font-medium">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button onclick="executeDelete()" 
                        class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium inline-flex items-center gap-2 shadow-lg shadow-red-200">
                    <i class="fas fa-trash-alt"></i> Ya, Hapus!
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #deleteModalContent {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #deleteModalContent.show {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }
</style>

<script>
    let deleteId = null;

    // ============================================
    // DROPZONE UPLOAD
    // ============================================
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

        removeImage.addEventListener('click', function(e) {
            e.stopPropagation();
            clearImage();
        });

        function handleFile(file) {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5 MB.');
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

    // ============================================
    // MODAL HAPUS
    // ============================================
    function confirmDelete(id) {
        deleteId = id;
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            content.classList.add('show');
        }, 50);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        
        content.classList.remove('show');
        document.body.style.overflow = 'auto';
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        
        deleteId = null;
    }

    function executeDelete() {
        if (!deleteId) return;
        
        const id = deleteId;
        closeDeleteModal();

        fetch('/admin/struktur/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Gambar struktur berhasil dihapus!');
                location.reload();
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus.');
        });
    }

    // Tutup modal klik luar
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // Tutup modal ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endsection