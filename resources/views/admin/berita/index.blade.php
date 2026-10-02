@extends('layouts.admin')

@section('title', 'Kelola Berita')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Berita</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.berita.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus"></i> Tambah Berita
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($beritas ?? [] as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->judul }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($item->is_published)
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-eye mr-1"></i> Draft (Preview)
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.berita.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 inline-flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.berita.toggle-publish', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $item->is_published ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }} inline-flex items-center gap-1">
                                    <i class="fas {{ $item->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    {{ $item->is_published ? 'Sembunyikan' : 'Publikasikan' }}
                                </button>
                            </form>
                            <!-- ===== TOMBOL HAPUS DENGAN MODAL ===== -->
                            <button type="button" 
                                    onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->judul) }}')" 
                                    class="text-red-600 hover:text-red-900 inline-flex items-center gap-1">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        <div class="py-8">
                            <i class="fas fa-newspaper text-4xl text-gray-300 block mb-3"></i>
                            <p>Belum ada berita</p>
                            <a href="{{ route('admin.berita.create') }}" class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus"></i> Tambah Berita Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($beritas))
    <div class="mt-4">
        {{ $beritas->links() }}
    </div>
    @endif
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
            <p class="text-gray-600 text-center mb-2">Apakah Anda yakin ingin menghapus berita ini?</p>
            
            <!-- Judul Berita -->
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
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium flex items-center gap-2 shadow-lg shadow-red-200">
                        <i class="fas fa-trash-alt"></i>
                        Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Animation */
    #deleteModalContent {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #deleteModalContent.show {
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
    // ============================================
    // VARIABEL UNTUK HAPUS
    // ============================================
    let deleteId = null;

    // ============================================
    // FUNGSI OPEN MODAL HAPUS
    // ============================================
    function confirmDelete(id, judul) {
        deleteId = id;
        
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        const judulEl = document.getElementById('deleteJudul');
        const form = document.getElementById('deleteForm');
        
        // Set judul berita yang akan dihapus
        judulEl.textContent = '"' + judul + '"';
        
        // Set action form
        form.action = '/admin/berita/' + id;
        
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
    // TUTUP MODAL DENGAN ESCAPE KEY
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // ============================================
    // AUTO DISMISS FLASH MESSAGES
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.bg-green-100.border-green-400, .bg-red-100.border-red-400');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    });
</script>
@endsection