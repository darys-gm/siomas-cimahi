@extends('layouts.admin')

@section('title', 'Kelola Produk Hukum')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Produk Hukum</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.produk-hukum.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus"></i> Tambah Produk Hukum
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">File</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produkHukums ?? [] as $item)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-sm font-medium">{{ $item->judul }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('produk-hukum.show', $item->id) }}" target="_blank" 
                           class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-file-pdf"></i> Lihat PDF
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.produk-hukum.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.produk-hukum.toggle-active', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $item->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                    <i class="fas {{ $item->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <!-- ===== TOMBOL HAPUS DENGAN MODAL ===== -->
                            <button type="button" 
                                    onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->judul) }}')" 
                                    class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada produk hukum</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($produkHukums))
    <div class="mt-4">
        {{ $produkHukums->links() }}
    </div>
    @endif
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI HAPUS (SAMA SEPERTI KELOLA BERITA) -->
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
            <p class="text-gray-600 text-center mb-2">Apakah Anda yakin ingin menghapus produk hukum ini?</p>
            
            <!-- Judul Produk Hukum -->
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
        
        // Set judul produk hukum yang akan dihapus
        judulEl.textContent = '"' + judul + '"';
        
        // Set action form
        form.action = '/admin/produk-hukum/' + id;
        
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
</script>
@endsection