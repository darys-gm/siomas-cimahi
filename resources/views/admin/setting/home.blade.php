@extends('layouts.admin')

@section('title', 'Manajemen Home - Admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Home</h1>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left"></i> Kembali
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

    <!-- Edit Running Text -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-bullhorn text-orange-500"></i>
            Running Text Hero Section
        </h2>
        <p class="text-sm text-gray-500 mb-4">Teks ini akan tampil di hero section halaman utama (beranda) dengan background orange dan teks putih.</p>

        <form action="{{ route('admin.setting.update-running-text') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Teks Running Text <span class="text-red-500">*</span></label>
                <textarea name="running_text" id="running_text" rows="4" 
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Masukkan teks untuk running text...&#10;Tulis satu kalimat per baris.">{{ old('running_text', $runningText) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Tulis setiap kalimat dalam satu baris. Kalimat akan otomatis dipisahkan dengan tanda bullet (•).
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kecepatan Running Text</label>
                <div class="flex items-center gap-4">
                    <input type="range" name="running_speed" id="running_speed" 
                           min="5" max="60" value="{{ old('running_speed', $runningSpeed ?? 20) }}" 
                           class="w-full max-w-xs h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    <span id="speedDisplay" class="text-sm font-semibold text-gray-700 min-w-[60px]">
                        {{ $runningSpeed ?? 20 }}s
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Semakin kecil angka, semakin cepat teks berjalan (5s = cepat, 60s = lambat).</p>
            </div>

            <!-- Preview Running Text -->
            <div class="mb-4 p-3 bg-orange-500/20 border border-orange-400/30 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Preview:</p>
                <div class="bg-orange-500/20 backdrop-blur-sm border border-orange-400/30 rounded-lg px-4 py-2 overflow-hidden">
                    <div class="running-text-preview overflow-hidden whitespace-nowrap">
                        <p class="inline-block text-gray-800 text-sm font-medium" id="previewText" 
                           style="animation: scrollPreview {{ $runningSpeed ?? 20 }}s linear infinite;">
                            <i class="fas fa-bullhorn text-orange-500 mr-2"></i>
                            {{ $runningText ?? 'Selamat datang di SIOMAS Kota Cimahi - Sistem Informasi Organisasi Masyarakat' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 flex-wrap">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <button type="button" onclick="resetDefault()" 
                        class="px-4 py-2.5 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition cursor-pointer">
                    <i class="fas fa-undo"></i> Reset Default
                </button>
                <button type="button" onclick="addNewLine()" 
                        class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-plus"></i> Tambah Kalimat
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .animate-preview {
        animation: scrollPreview 20s linear infinite;
    }
    .running-text-preview {
        overflow: hidden;
        white-space: nowrap;
    }
    @keyframes scrollPreview {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    .running-text-preview:hover .animate-preview {
        animation-play-state: paused;
    }
</style>

<script>
    // Update speed display
    document.getElementById('running_speed').addEventListener('input', function() {
        const speed = this.value;
        document.getElementById('speedDisplay').textContent = speed + 's';
        document.getElementById('previewText').style.animationDuration = speed + 's';
    });

    // Update preview text
    function updatePreview() {
        const text = document.getElementById('running_text').value || 'Selamat datang di SIOMAS Kota Cimahi';
        // Hapus newline dan ganti dengan bullet
        const formatted = text.split('\n')
            .filter(line => line.trim() !== '')
            .join(' • ');
        document.getElementById('previewText').innerHTML = '<i class="fas fa-bullhorn text-orange-500 mr-2"></i> ' + formatted;
    }

    // Reset to default
    function resetDefault() {
        const defaultText = 'Selamat datang di SIOMAS Kota Cimahi - Sistem Informasi Organisasi Masyarakat\nKota Cimahi Maju, Kota Cimahi Sejahtera\nLayanan Prima untuk Masyarakat Cimahi';
        document.getElementById('running_text').value = defaultText;
        document.getElementById('running_speed').value = 20;
        document.getElementById('speedDisplay').textContent = '20s';
        document.getElementById('previewText').style.animationDuration = '20s';
        updatePreview();
    }

    // Add new line
    function addNewLine() {
        const textarea = document.getElementById('running_text');
        const currentText = textarea.value;
        // Tambah newline di akhir
        if (currentText && !currentText.endsWith('\n')) {
            textarea.value = currentText + '\n';
        } else if (!currentText) {
            textarea.value = 'Tulis kalimat baru di sini...\n';
        }
        textarea.focus();
        // Pindahkan kursor ke baris baru
        const length = textarea.value.length;
        textarea.setSelectionRange(length, length);
        updatePreview();
    }

    // Update preview on input
    document.getElementById('running_text').addEventListener('input', updatePreview);

    // Update preview on page load
    document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection