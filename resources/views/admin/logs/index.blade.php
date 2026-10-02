@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Log Aktivitas</h1>
            <p class="text-sm text-gray-500 mt-1">Total log: {{ $logs->total() }}</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button onclick="openModal('clearOld')" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm flex items-center gap-2">
                <i class="fas fa-clock"></i> Hapus Log Lama (>30 hari)
            </button>
            <button onclick="openModal('clearAll')" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm flex items-center gap-2">
                <i class="fas fa-trash-alt"></i> Hapus Semua
            </button>
        </div>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded mb-4">
        <p class="text-sm text-blue-700">
            <i class="fas fa-info-circle mr-1"></i>
            <strong>Info:</strong> Log yang berusia kurang dari 30 hari tidak dapat dihapus satu per satu untuk menjaga integritas audit trail. Gunakan <strong>"Hapus Log Lama"</strong> untuk membersihkan log >30 hari secara massal.
        </p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs ?? [] as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration + (($logs->currentPage() - 1) * $logs->perPage()) }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $item->user->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @if($item->aktivitas == 'Login') bg-green-100 text-green-800
                            @elseif($item->aktivitas == 'Logout') bg-red-100 text-red-800
                            @elseif($item->aktivitas == 'Verifikasi' || $item->aktivitas == 'Approve') bg-blue-100 text-blue-800
                            @elseif($item->aktivitas == 'Reject') bg-red-100 text-red-800
                            @elseif($item->aktivitas == 'Revisi') bg-yellow-100 text-yellow-800
                            @elseif($item->aktivitas == 'Edit Profil ORMAS') bg-yellow-100 text-yellow-800
                            @elseif($item->aktivitas == 'Tambah Data' || $item->aktivitas == 'Create') bg-indigo-100 text-indigo-800
                            @elseif($item->aktivitas == 'Update' || $item->aktivitas == 'Edit') bg-purple-100 text-purple-800
                            @elseif($item->aktivitas == 'Delete' || $item->aktivitas == 'Hapus') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $item->aktivitas }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $item->deskripsi ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->ip_address ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        @php
                            $logAgeInDays = $item->created_at->diffInDays(now());
                            $canDelete = $logAgeInDays >= 30;
                        @endphp

                        @if($canDelete)
                            <button onclick="openModal('delete', {{ $item->id }}, '{{ addslashes($item->aktivitas) }}')" 
                                    class="text-red-600 hover:text-red-800 text-xs flex items-center gap-1 transition">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        @else
                            <span class="text-gray-400 text-xs flex items-center gap-1 cursor-not-allowed"
                                  title="Log berusia kurang dari 30 hari tidak dapat dihapus">
                                <i class="fas fa-lock"></i> Terkunci
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl text-gray-300 block mb-2"></i>
                        Belum ada log aktivitas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($logs) && $logs->hasPages())
    <div class="mt-4">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Menampilkan <span class="font-medium">{{ $logs->firstItem() }}</span> - 
                <span class="font-medium">{{ $logs->lastItem() }}</span> dari 
                <span class="font-medium">{{ $logs->total() }}</span> data
            </div>
            <div>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- ============================================ -->
<!-- MODAL KONFIRMASI HAPUS                       -->
<!-- ============================================ -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeModal()"></div>
    
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center animate-pulse">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 text-center mb-2" id="modalTitle">Konfirmasi Hapus</h3>
            
            <p class="text-gray-600 text-center mb-4" id="modalMessage">
                Apakah Anda yakin ingin menghapus data ini?
            </p>
            
            <div id="modalDetail" class="bg-gray-50 rounded-lg p-4 mb-6 hidden">
                <div class="flex items-center gap-3">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    <div>
                        <p class="text-xs text-gray-500">Aktivitas yang akan dihapus:</p>
                        <p class="text-sm font-semibold text-gray-800" id="modalAktivitas"></p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 justify-center">
                <button onclick="closeModal()" 
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
    .pagination {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .pagination .page-item {
        display: inline-block;
    }
    .pagination .page-link {
        padding: 6px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        color: #4a5568;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        background: white;
    }
    .pagination .page-link:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
    }
    .pagination .active .page-link {
        background: #1a56db;
        color: white;
        border-color: #1a56db;
    }
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .truncate {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
    }

    #modalContent {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #modalContent.show {
        transform: scale(1) translateY(0);
        opacity: 1;
    }

    #deleteModal .fixed.inset-0.bg-black\/60 {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    #deleteModal.show .fixed.inset-0.bg-black\/60 {
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
    let currentAction = null;
    let currentId = null;

    const routes = {
        destroy: "{{ route('admin.logs.destroy', ['id' => '__ID__']) }}",
        clear: "{{ route('admin.logs.clear') }}",
        clearOld: "{{ route('admin.logs.clear-old') }}"
    };

    console.log('🔍 Routes loaded:', routes);

    function openModal(action, id = null, aktivitas = null) {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const detail = document.getElementById('modalDetail');
        const aktivitasEl = document.getElementById('modalAktivitas');
        const form = document.getElementById('deleteForm');

        // Reset
        detail.classList.add('hidden');
        currentAction = action;
        currentId = id;

        let actionUrl = '';
        let titleText = '';
        let messageText = '';

        switch(action) {
            case 'delete':
                actionUrl = routes.destroy.replace('__ID__', id);
                titleText = 'Hapus Log Aktivitas';
                messageText = 'Apakah Anda yakin ingin menghapus log aktivitas ini?';
                if (aktivitas) {
                    detail.classList.remove('hidden');
                    aktivitasEl.textContent = aktivitas;
                }
                break;
            case 'clearAll':
                actionUrl = routes.clear;
                titleText = 'Hapus Semua Log';
                messageText = 'Apakah Anda yakin ingin menghapus SEMUA log aktivitas? Tindakan ini tidak dapat dibatalkan!';
                break;
            case 'clearOld':
                actionUrl = routes.clearOld;
                titleText = 'Hapus Log Lama';
                messageText = 'Apakah Anda yakin ingin menghapus semua log yang berusia lebih dari 30 hari?';
                break;
            default:
                return;
        }

        console.log('🔍 Opening modal:', { action, actionUrl });

        title.textContent = titleText;
        message.textContent = messageText;
        form.action = actionUrl;

        modal.classList.remove('hidden');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            content.classList.add('show');
        }, 50);
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');

        content.classList.remove('show');
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // AUTO DISMISS FLASH MESSAGES
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
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