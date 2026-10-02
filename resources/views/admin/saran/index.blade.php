@extends('layouts.admin')

@section('title', 'Kotak Saran - Admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-gray-800">Kotak Saran</h1>
            <span id="totalBaruBadge" class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-500 text-white">
                {{ $totalBaru }} Baru
            </span>
            <span class="text-xs text-gray-400 flex items-center gap-1">
                <i class="fas fa-circle text-green-500 text-[6px]"></i>
                <span id="statusText">Otomatis diperbarui</span>
            </span>
        </div>
        <div class="flex gap-2">
            <button onclick="refreshData()" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- ===== TABEL SARAN ===== -->
    <div class="overflow-x-auto" id="saranTableWrapper">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Nama</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Email</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Status</th>
                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-44">Tanggal</th>
                    <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="saranTableBody" class="bg-white divide-y divide-gray-200">
                @include('admin.saran.partials.table_rows', ['saran' => $saran])
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4" id="paginationWrapper">
        @if($saran->hasPages())
            {{ $saran->links() }}
        @endif
    </div>
</div>

<!-- ===== NOTIFIKASI SARAN BARU (Toast) ===== -->
<div id="newSaranToast" class="hidden fixed top-20 right-4 z-[200] max-w-sm w-full bg-white rounded-xl shadow-2xl border-l-4 border-yellow-500 p-4 animate-slide-in">
    <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-envelope text-yellow-600"></i>
            </div>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-semibold text-gray-800">Saran Baru Masuk!</h4>
            <p id="toastMessage" class="text-sm text-gray-600 mt-1">Ada saran baru dari pengguna.</p>
            <button onclick="refreshData()" class="mt-2 text-xs text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-sync-alt mr-1"></i> Refresh untuk melihat
            </button>
        </div>
        <button onclick="closeToast()" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- ===== MODAL-MODAL ===== -->
<!-- Modal Lihat Pesan -->
<div id="pesanModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800" id="pesanModalTitle">Detail Saran</h3>
            <button onclick="closePesanModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                <div><span class="text-gray-500">Nama:</span> <span class="font-medium" id="det_nama">-</span></div>
                <div><span class="text-gray-500">Email:</span> <span class="font-medium" id="det_email">-</span></div>
                <div class="col-span-2"><span class="text-gray-500">Tanggal:</span> <span class="font-medium" id="det_tanggal">-</span></div>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <p class="text-sm text-gray-500 mb-2">Pesan:</p>
                <p class="text-gray-700 whitespace-pre-line" id="det_pesan">-</p>
            </div>
        </div>
        <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-3 flex justify-end">
            <button onclick="closePesanModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Notifikasi -->
<div id="notificationModal" class="hidden fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6">
        <div class="text-center">
            <div id="notificationIcon" class="text-5xl mb-3">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <h3 id="notificationTitle" class="text-xl font-bold text-gray-800 mb-2">Berhasil!</h3>
            <p id="notificationMessage" class="text-gray-600 text-sm">Data berhasil disimpan.</p>
            <button onclick="closeNotification()" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                OK
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="confirmDeleteModal" class="hidden fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6">
        <div class="text-center">
            <div class="text-5xl mb-3 text-red-500">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p id="confirmDeleteMessage" class="text-gray-600 text-sm">Apakah Anda yakin ingin menghapus saran ini?</p>
            <p class="text-xs text-gray-400 mt-2">Data yang dihapus tidak dapat dikembalikan!</p>
            <div class="flex gap-2 mt-4 justify-center">
                <button onclick="executeDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
                <button onclick="closeConfirmDelete()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .animate-slide-in {
        animation: slideIn 0.5s ease-out;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    /* Baru muncul effect */
    .new-row {
        animation: highlightRow 2s ease-out;
    }
    @keyframes highlightRow {
        0% { background-color: #fef3c7; }
        100% { background-color: transparent; }
    }
    
    /* ===== BADGE STATUS YANG LEBIH BAGUS ===== */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
        min-width: 100px;
        justify-content: center;
    }
    
    .badge-status .icon {
        font-size: 12px;
    }
    
    .badge-status.belum-dibaca {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .badge-status.sudah-dibaca {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    
    .badge-status .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        animation: pulse-dot 1.5s ease-in-out infinite;
    }
    
    .badge-status.belum-dibaca .dot {
        background: #dc2626;
    }
    
    .badge-status.sudah-dibaca .dot {
        background: #16a34a;
        animation: none;
    }
    
    @keyframes pulse-dot {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.5;
            transform: scale(0.8);
        }
    }
    
    /* Tombol aksi lebih rapi */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }
    
    .btn-action.btn-read {
        background: #eff6ff;
        color: #2563eb;
    }
    
    .btn-action.btn-read:hover {
        background: #dbeafe;
    }
    
    .btn-action.btn-delete {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-action.btn-delete:hover {
        background: #fee2e2;
    }
    
    .btn-action.btn-view {
        background: #f3f4f6;
        color: #4b5563;
    }
    
    .btn-action.btn-view:hover {
        background: #e5e7eb;
    }
    
    /* Container aksi */
    .action-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        flex-wrap: nowrap;
    }
    
    /* Pesan preview */
    .pesan-preview {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        color: #4b5563;
        font-size: 13px;
    }
</style>

<script>
    // ============================================
    // VARIABLE
    // ============================================
    let deleteId = null;
    let lastSaranCount = {{ $saran->count() }};
    let totalBaruCount = {{ $totalBaru }};
    let refreshInterval = null;
    let isRefreshing = false;
    let toastTimeout = null;

    // ============================================
    // AUTO REFRESH (POLLING)
    // ============================================
    function startAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        // Cek setiap 10 detik
        refreshInterval = setInterval(function() {
            checkNewSaran();
        }, 10000);
        document.getElementById('statusText').textContent = 'Otomatis diperbarui setiap 10 detik';
    }

    function stopAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
            refreshInterval = null;
        }
        document.getElementById('statusText').textContent = 'Auto refresh dihentikan';
    }

    // ============================================
    // CEK SARAN BARU
    // ============================================
    function checkNewSaran() {
        if (isRefreshing) return;
        isRefreshing = true;

        fetch('{{ route("admin.saran.check-new") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            isRefreshing = false;
            
            if (data.success) {
                const currentCount = parseInt(data.total);
                const currentBaru = parseInt(data.totalBaru);
                
                // Jika ada saran baru
                if (currentCount > lastSaranCount) {
                    const newCount = currentCount - lastSaranCount;
                    showToast('Ada ' + newCount + ' saran baru masuk!');
                    // Update badge
                    updateBadge(currentBaru);
                    // Refresh tabel
                    refreshDataSilent();
                } else if (currentBaru > totalBaruCount) {
                    // Jika ada saran baru yang belum dibaca
                    const newBaru = currentBaru - totalBaruCount;
                    showToast('Ada ' + newBaru + ' saran baru yang belum dibaca!');
                    updateBadge(currentBaru);
                    refreshDataSilent();
                }
                
                lastSaranCount = currentCount;
                totalBaruCount = currentBaru;
            }
        })
        .catch(error => {
            console.error('Error checking new saran:', error);
            isRefreshing = false;
        });
    }

    // ============================================
    // UPDATE BADGE
    // ============================================
    function updateBadge(total) {
        const badge = document.getElementById('totalBaruBadge');
        if (badge) {
            if (total > 0) {
                badge.textContent = total + ' Baru';
                badge.classList.remove('bg-green-500');
                badge.classList.add('bg-yellow-500');
            } else {
                badge.textContent = '0 Baru';
                badge.classList.remove('bg-yellow-500');
                badge.classList.add('bg-green-500');
            }
        }
    }

    // ============================================
    // TOAST NOTIFIKASI
    // ============================================
    function showToast(message) {
        const toast = document.getElementById('newSaranToast');
        const toastMessage = document.getElementById('toastMessage');
        
        if (toastMessage) {
            toastMessage.textContent = message;
        }
        
        toast.classList.remove('hidden');
        
        // Auto close setelah 8 detik
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }
        toastTimeout = setTimeout(function() {
            closeToast();
        }, 8000);
    }

    function closeToast() {
        const toast = document.getElementById('newSaranToast');
        toast.classList.add('hidden');
        if (toastTimeout) {
            clearTimeout(toastTimeout);
            toastTimeout = null;
        }
    }

    // ============================================
    // REFRESH DATA
    // ============================================
    function refreshData() {
        if (isRefreshing) return;
        
        const btn = document.querySelector('[onclick="refreshData()"]');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
        }
        
        refreshDataSilent(function() {
            if (btn) {
                btn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                btn.disabled = false;
            }
            closeToast();
        });
    }

    function refreshDataSilent(callback) {
        if (isRefreshing) return;
        isRefreshing = true;

        fetch('{{ route("admin.saran.refresh") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            isRefreshing = false;
            
            if (data.success) {
                // Update tabel
                document.getElementById('saranTableBody').innerHTML = data.html;
                document.getElementById('paginationWrapper').innerHTML = data.pagination || '';
                
                // Update badge
                updateBadge(data.totalBaru);
                lastSaranCount = data.total;
                totalBaruCount = data.totalBaru;
                
                // Highlight baris baru
                document.querySelectorAll('#saranTableBody tr:first-child').forEach(function(row) {
                    row.classList.add('new-row');
                });
            }
            
            if (callback) callback();
        })
        .catch(error => {
            console.error('Error refreshing data:', error);
            isRefreshing = false;
            if (callback) callback();
        });
    }

    // ============================================
    // FUNGSI NOTIFIKASI MODAL
    // ============================================
    function showNotification(type, title, message) {
        const modal = document.getElementById('notificationModal');
        const icon = document.getElementById('notificationIcon');
        const titleEl = document.getElementById('notificationTitle');
        const msgEl = document.getElementById('notificationMessage');
        
        if (type === 'success') {
            icon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
            titleEl.className = 'text-xl font-bold text-gray-800 mb-2';
        } else if (type === 'error') {
            icon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
            titleEl.className = 'text-xl font-bold text-red-600 mb-2';
        } else if (type === 'warning') {
            icon.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500"></i>';
            titleEl.className = 'text-xl font-bold text-yellow-600 mb-2';
        }
        
        titleEl.textContent = title;
        msgEl.textContent = message;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeNotification() {
        document.getElementById('notificationModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ============================================
    // FUNGSI KONFIRMASI HAPUS
    // ============================================
    function confirmHapus(id) {
        deleteId = id;
        document.getElementById('confirmDeleteMessage').textContent = 'Apakah Anda yakin ingin menghapus saran ini?';
        document.getElementById('confirmDeleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmDelete() {
        document.getElementById('confirmDeleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        deleteId = null;
    }

    function executeDelete() {
        if (!deleteId) return;
        
        const id = deleteId;
        closeConfirmDelete();

        fetch('/admin/saran/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Terjadi kesalahan server');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('success', 'Berhasil!', data.message || 'Saran berhasil dihapus.');
                setTimeout(() => refreshData(), 1500);
            } else {
                showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error!', error.message || 'Terjadi kesalahan saat menghapus data.');
        });
    }

    // ============================================
    // FUNGSI TANDAI DIBACA
    // ============================================
    function tandaiDibaca(id) {
        fetch('/admin/saran/' + id + '/tandai-dibaca', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Terjadi kesalahan server');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('success', 'Berhasil!', data.message || 'Saran ditandai sudah dibaca.');
                setTimeout(() => refreshData(), 1500);
            } else {
                showNotification('error', 'Gagal!', data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error!', error.message || 'Terjadi kesalahan.');
        });
    }

    // ============================================
    // FUNGSI LIHAT PESAN
    // ============================================
    function lihatPesan(id, pesan, nama, email, tanggal) {
        document.getElementById('pesanModal').classList.remove('hidden');
        document.getElementById('det_nama').textContent = nama;
        document.getElementById('det_email').textContent = email;
        document.getElementById('det_tanggal').textContent = tanggal + ' WIB';
        document.getElementById('det_pesan').textContent = pesan;
        document.body.style.overflow = 'hidden';
    }

    function closePesanModal() {
        document.getElementById('pesanModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ============================================
    // CLOSE MODAL SAAT KLIK DI LUAR
    // ============================================
    document.getElementById('pesanModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePesanModal();
        }
    });

    document.getElementById('notificationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeNotification();
        }
    });

    document.getElementById('confirmDeleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmDelete();
        }
    });

    // ============================================
    // INIT
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        // Start auto refresh setelah 5 detik
        setTimeout(function() {
            startAutoRefresh();
        }, 5000);
    });
</script>
@endsection