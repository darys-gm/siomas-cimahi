@extends('layouts.admin')

@section('title', 'Manajemen Poster')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Poster</h1>
        <a href="{{ route('admin.poster.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus"></i> Tambah Poster
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                    <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posters as $item)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="w-16 h-16 object-cover rounded-lg">
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $item->judul }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $item->urutan }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="toggleActive({{ $item->id }})" 
                                    class="text-{{ $item->is_active ? 'red' : 'green' }}-600 hover:text-{{ $item->is_active ? 'red' : 'green' }}-800">
                                <i class="fas {{ $item->is_active ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                            </button>
                            <a href="{{ route('admin.poster.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->judul) }}')" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-image text-3xl text-gray-300 block mb-2"></i>
                        Belum ada poster
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI HAPUS                       -->
<!-- ============================================ -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeDeleteModal()"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center animate-pulse">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
            
            <!-- Message -->
            <p class="text-gray-600 text-center mb-2">Apakah Anda yakin ingin menghapus poster ini?</p>
            
            <!-- Judul Poster -->
            <p id="deleteJudul" class="text-sm font-semibold text-red-600 text-center mb-2 break-words"></p>
            
            <!-- Warning -->
            <p class="text-xs text-gray-400 text-center mb-6">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Data yang dihapus tidak dapat dikembalikan!
            </p>
            
            <!-- Buttons -->
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" 
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition font-medium flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button onclick="executeDelete()" 
                        class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium flex items-center gap-2 shadow-lg shadow-red-200">
                    <i class="fas fa-trash-alt"></i>
                    Ya, Hapus!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL NOTIFIKASI SUKSES                      -->
<!-- ============================================ -->
<div id="successModal" class="fixed inset-0 z-[9999] hidden">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeSuccessModal()"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="successModalContent">
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center animate-pulse">
                    <i class="fas fa-check-circle text-4xl text-green-600"></i>
                </div>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Berhasil!</h3>
            
            <!-- Message -->
            <p id="successMessage" class="text-gray-600 text-center mb-6">Status berhasil diubah.</p>
            
            <!-- Button -->
            <div class="flex justify-center">
                <button onclick="closeSuccessModal()" 
                        class="px-6 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-medium flex items-center gap-2 shadow-lg shadow-green-200">
                    <i class="fas fa-check"></i>
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Animation */
    #deleteModalContent, #successModalContent {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #deleteModalContent.show, #successModalContent.show {
        transform: scale(1) translateY(0);
        opacity: 1;
    }

    /* Pulse animation for icon */
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }

    /* Break words untuk judul panjang */
    .break-words {
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
    }
</style>

<script>
    let deleteId = null;
    let successTimeout = null;

    // ============================================
    // FUNGSI TOGGLE ACTIVE (DENGAN MODAL SUKSES)
    // ============================================
    function toggleActive(id) {
        fetch('/admin/poster/' + id + '/toggle-active', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessModal(data.message || 'Status berhasil diubah.');
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan.');
        });
    }

    // ============================================
    // FUNGSI TAMPILKAN MODAL SUKSES
    // ============================================
    function showSuccessModal(message, reloadAfterClose = true) {
        const modal = document.getElementById('successModal');
        const content = document.getElementById('successModalContent');
        const messageEl = document.getElementById('successMessage');
        
        messageEl.textContent = message;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            content.classList.add('show');
        }, 50);

        // Auto close dan reload setelah 2 detik
        if (successTimeout) clearTimeout(successTimeout);
        successTimeout = setTimeout(() => {
            closeSuccessModal(reloadAfterClose);
        }, 2000);
    }

    // ============================================
    // FUNGSI TUTUP MODAL SUKSES
    // ============================================
    function closeSuccessModal(reloadAfterClose = true) {
        const modal = document.getElementById('successModal');
        const content = document.getElementById('successModalContent');
        
        content.classList.remove('show');
        document.body.style.overflow = 'auto';
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        
        if (successTimeout) {
            clearTimeout(successTimeout);
            successTimeout = null;
        }
        
        // Reload halaman setelah modal tertutup
        if (reloadAfterClose) {
            setTimeout(() => {
                location.reload();
            }, 300);
        }
    }

    // ============================================
    // FUNGSI OPEN MODAL HAPUS
    // ============================================
    function confirmDelete(id, judul) {
        deleteId = id;
        
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        const judulEl = document.getElementById('deleteJudul');
        
        // Set judul poster yang akan dihapus
        judulEl.textContent = '"' + judul + '"';
        
        // Show modal dengan animasi
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            content.classList.add('show');
        }, 50);
    }

    // ============================================
    // FUNGSI CLOSE MODAL HAPUS
    // ============================================
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

    // ============================================
    // FUNGSI EKSEKUSI HAPUS
    // ============================================
    function executeDelete() {
        if (!deleteId) return;
        
        const id = deleteId;
        closeDeleteModal();

        fetch('/admin/poster/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            // Cek apakah response OK (status 2xx)
            if (!response.ok) {
                throw new Error('HTTP Error: ' + response.status);
            }
            
            // Cek content-type
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response bukan JSON');
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccessModal(data.message || 'Poster berhasil dihapus.');
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    }

    // ============================================
    // EVENT LISTENER
    // ============================================
    // Tutup modal hapus saat klik area luar
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Tutup modal sukses saat klik area luar
    document.getElementById('successModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSuccessModal();
        }
    });

    // Tutup modal dengan Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeSuccessModal();
        }
    });
</script>
@endsection