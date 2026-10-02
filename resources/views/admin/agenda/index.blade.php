@extends('layouts.admin')

@section('title', 'Manajemen Agenda')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Agenda</h1>
        <a href="{{ route('admin.agenda.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus"></i> Tambah Agenda
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
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($agendas as $index => $item)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration + (($agendas->currentPage() - 1) * $agendas->perPage()) }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->judul }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d F Y') : '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.agenda.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmToggle({{ $item->id }}, '{{ addslashes($item->judul) }}', {{ $item->is_active ? 'true' : 'false' }})" 
                                    class="text-{{ $item->is_active ? 'red' : 'green' }}-600 hover:text-{{ $item->is_active ? 'red' : 'green' }}-800">
                                <i class="fas {{ $item->is_active ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                            </button>
                            <button onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->judul) }}')" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-calendar-day text-3xl text-gray-300 block mb-2"></i>
                        Belum ada agenda
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $agendas->links() }}
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI TOGGLE STATUS              -->
<!-- ============================================ -->
<div id="toggleModal" class="fixed inset-0 z-[9999] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeToggleModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="toggleModalContent">
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div id="toggleIconContainer" class="w-20 h-20 rounded-full flex items-center justify-center">
                    <i id="toggleIcon" class="fas fa-question-circle text-4xl text-yellow-500"></i>
                </div>
            </div>
            
            <!-- Title -->
            <h3 id="toggleTitle" class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi</h3>
            
            <!-- Message -->
            <p id="toggleMessage" class="text-gray-600 text-center mb-2">Apakah Anda yakin ingin mengubah status agenda ini?</p>
            
            <!-- Nama Agenda -->
            <p id="toggleName" class="text-sm font-semibold text-blue-600 text-center mb-6"></p>
            
            <!-- Buttons -->
            <div class="flex gap-3 justify-center">
                <button onclick="closeToggleModal()" 
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition font-medium flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button id="toggleConfirmBtn" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium flex items-center gap-2 shadow-lg shadow-blue-200">
                    <i class="fas fa-check"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI HAPUS                      -->
<!-- ============================================ -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
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
            <p class="text-gray-600 text-center mb-2">Apakah Anda yakin ingin menghapus agenda ini?</p>
            
            <!-- Nama Agenda -->
            <p id="deleteName" class="text-sm font-semibold text-red-600 text-center mb-2"></p>
            
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
                <button id="deleteConfirmBtn" 
                        class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium flex items-center gap-2 shadow-lg shadow-red-200">
                    <i class="fas fa-trash-alt"></i>
                    Ya, Hapus!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL NOTIFIKASI SUKSES / ERROR             -->
<!-- ============================================ -->
<div id="notificationModal" class="fixed inset-0 z-[9999] hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeNotification()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="notificationContent">
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div id="notificationIconContainer" class="w-20 h-20 rounded-full flex items-center justify-center">
                    <i id="notificationIcon" class="fas fa-check-circle text-4xl text-green-500"></i>
                </div>
            </div>
            
            <!-- Title -->
            <h3 id="notificationTitle" class="text-xl font-bold text-gray-800 text-center mb-2">Berhasil</h3>
            
            <!-- Message -->
            <p id="notificationMessage" class="text-gray-600 text-center mb-6"></p>
            
            <!-- Button -->
            <div class="flex justify-center">
                <button onclick="closeNotification()" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium flex items-center gap-2 shadow-lg shadow-blue-200">
                    <i class="fas fa-check"></i>
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Animation */
    #toggleModalContent, #deleteModalContent, #notificationContent {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #toggleModalContent.show, #deleteModalContent.show, #notificationContent.show {
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

    /* Overlay Animation */
    .fixed.inset-0.bg-black\/60, .fixed.inset-0.bg-black\/50 {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .show-overlay .fixed.inset-0.bg-black\/60,
    .show-overlay .fixed.inset-0.bg-black\/50 {
        opacity: 1;
    }
</style>

<script>
    // ============================================
    // CSRF TOKEN
    // ============================================
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    // ============================================
    // VARIABEL
    // ============================================
    let toggleId = null;
    let deleteId = null;
    let isProcessing = false;

    // ============================================
    // FUNGSI TOGGLE STATUS
    // ============================================
    function confirmToggle(id, judul, isActive) {
        toggleId = id;
        const icon = document.getElementById('toggleIcon');
        const iconContainer = document.getElementById('toggleIconContainer');
        const title = document.getElementById('toggleTitle');
        const message = document.getElementById('toggleMessage');
        const nameEl = document.getElementById('toggleName');
        const confirmBtn = document.getElementById('toggleConfirmBtn');
        const modal = document.getElementById('toggleModal');
        const content = document.getElementById('toggleModalContent');
        
        if (isActive) {
            icon.className = 'fas fa-times-circle text-4xl text-red-500';
            iconContainer.className = 'w-20 h-20 bg-red-100 rounded-full flex items-center justify-center';
            title.textContent = 'Konfirmasi Nonaktifkan';
            message.textContent = 'Apakah Anda yakin ingin menonaktifkan agenda ini?';
            confirmBtn.className = 'px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium flex items-center gap-2 shadow-lg shadow-red-200';
            confirmBtn.innerHTML = '<i class="fas fa-times"></i> Nonaktifkan';
        } else {
            icon.className = 'fas fa-check-circle text-4xl text-green-500';
            iconContainer.className = 'w-20 h-20 bg-green-100 rounded-full flex items-center justify-center';
            title.textContent = 'Konfirmasi Aktifkan';
            message.textContent = 'Apakah Anda yakin ingin mengaktifkan agenda ini?';
            confirmBtn.className = 'px-6 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-medium flex items-center gap-2 shadow-lg shadow-green-200';
            confirmBtn.innerHTML = '<i class="fas fa-check"></i> Aktifkan';
        }
        
        nameEl.textContent = '"' + judul + '"';
        
        // Show modal dengan animasi
        modal.classList.remove('hidden');
        modal.classList.add('show-overlay');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            content.classList.add('show');
        }, 50);
    }

    function closeToggleModal() {
        const modal = document.getElementById('toggleModal');
        const content = document.getElementById('toggleModalContent');
        
        content.classList.remove('show');
        modal.classList.remove('show-overlay');
        document.body.style.overflow = 'auto';
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        toggleId = null;
    }

    // ============================================
    // FUNGSI DELETE
    // ============================================
    function confirmDelete(id, judul) {
        deleteId = id;
        document.getElementById('deleteName').textContent = '"' + judul + '"';
        
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('show-overlay');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            content.classList.add('show');
        }, 50);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        
        content.classList.remove('show');
        modal.classList.remove('show-overlay');
        document.body.style.overflow = 'auto';
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        deleteId = null;
    }

    // ============================================
    // FUNGSI NOTIFIKASI
    // ============================================
    function showNotification(type, title, message) {
        const modal = document.getElementById('notificationModal');
        const content = document.getElementById('notificationContent');
        const icon = document.getElementById('notificationIcon');
        const iconContainer = document.getElementById('notificationIconContainer');
        const titleEl = document.getElementById('notificationTitle');
        const messageEl = document.getElementById('notificationMessage');
        
        if (type === 'success') {
            icon.className = 'fas fa-check-circle text-4xl text-green-500';
            iconContainer.className = 'w-20 h-20 bg-green-100 rounded-full flex items-center justify-center';
            titleEl.className = 'text-xl font-bold text-green-700 text-center mb-2';
        } else {
            icon.className = 'fas fa-exclamation-circle text-4xl text-red-500';
            iconContainer.className = 'w-20 h-20 bg-red-100 rounded-full flex items-center justify-center';
            titleEl.className = 'text-xl font-bold text-red-700 text-center mb-2';
        }
        
        titleEl.textContent = title;
        messageEl.textContent = message;
        
        modal.classList.remove('hidden');
        modal.classList.add('show-overlay');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            content.classList.add('show');
        }, 50);
    }

    function closeNotification() {
        const modal = document.getElementById('notificationModal');
        const content = document.getElementById('notificationContent');
        
        content.classList.remove('show');
        modal.classList.remove('show-overlay');
        document.body.style.overflow = 'auto';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            // Reload setelah notifikasi sukses
            if (window.shouldReload) {
                window.shouldReload = false;
                location.reload();
            }
        }, 300);
    }

    // ============================================
    // EVENT LISTENER TOGGLE CONFIRM
    // ============================================
    document.getElementById('toggleConfirmBtn').addEventListener('click', function() {
        if (!toggleId || isProcessing) return;
        isProcessing = true;
        
        const id = toggleId;
        closeToggleModal();
        
        fetch('/admin/agenda/' + id + '/toggle-active', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            isProcessing = false;
            if (data.success) {
                window.shouldReload = true;
                showNotification('success', 'Berhasil!', data.message);
            } else {
                showNotification('error', 'Gagal!', data.message);
            }
        })
        .catch(error => {
            isProcessing = false;
            showNotification('error', 'Error!', 'Terjadi kesalahan: ' + error.message);
        });
    });

    // ============================================
    // EVENT LISTENER DELETE CONFIRM
    // ============================================
    document.getElementById('deleteConfirmBtn').addEventListener('click', function() {
        if (!deleteId || isProcessing) return;
        isProcessing = true;
        
        const id = deleteId;
        closeDeleteModal();
        
        fetch('/admin/agenda/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            isProcessing = false;
            if (data.success) {
                window.shouldReload = true;
                showNotification('success', 'Berhasil!', data.message);
            } else {
                showNotification('error', 'Gagal!', data.message);
            }
        })
        .catch(error => {
            isProcessing = false;
            showNotification('error', 'Error!', 'Terjadi kesalahan: ' + error.message);
        });
    });

    // ============================================
    // TUTUP MODAL DENGAN ESCAPE KEY
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeToggleModal();
            closeDeleteModal();
            closeNotification();
        }
    });

    // ============================================
    // TUTUP MODAL SAAT KLIK DI LUAR
    // ============================================
    document.querySelectorAll('.fixed.inset-0.bg-black\\/60, .fixed.inset-0.bg-black\\/50').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                if (this.closest('#toggleModal')) closeToggleModal();
                if (this.closest('#deleteModal')) closeDeleteModal();
                if (this.closest('#notificationModal')) closeNotification();
            }
        });
    });
</script>
@endsection