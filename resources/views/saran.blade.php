@extends('layouts.home')

@section('title', 'Kotak Saran - SIOMAS Kota Cimahi')

@section('content')
<div class="relative min-h-screen py-8 sm:py-12 md:py-16 mt-8">
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full">
        <img src="{{ asset('images/bg-pola.png') }}" 
             alt="Background" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-white/70 via-white/80 to-white/90"></div>
    </div>
    
    <div class="container mx-auto px-3 sm:px-4 relative z-10">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-10">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-2 sm:mb-3">Kotak Saran</h1>
                <p class="text-sm sm:text-base md:text-lg text-gray-600">Kirimkan saran, kritik, atau masukan Anda untuk kemajuan Kota Cimahi</p>
                <div class="w-16 sm:w-20 h-1 bg-red-600 mx-auto mt-2 sm:mt-3 rounded-full"></div>
            </div>

            <!-- Form Saran -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-4 sm:px-6 py-3 sm:py-4">
                    <h2 class="text-white font-semibold text-base sm:text-lg">
                        <i class="fas fa-pen mr-2"></i> Kirim Saran
                    </h2>
                </div>

                <form id="saranForm" class="p-4 sm:p-6 md:p-8">
                    @csrf
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base"
                                   placeholder="Masukkan nama Anda" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base"
                                   placeholder="Masukkan email Anda (opsional)">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pesan <span class="text-red-500">*</span></label>
                            <textarea name="pesan" id="pesan" rows="5" 
                                      class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base"
                                      placeholder="Tulis saran, kritik, atau masukan Anda..." required></textarea>
                            <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Anda bebas menulis pesan tanpa batasan minimal karakter.</p>
                        </div>

                        <button type="submit" id="submitBtn" 
                                class="w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition flex items-center justify-center gap-2 text-sm sm:text-base">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Saran
                        </button>
                    </div>
                </form>

                <!-- ===== NOTIFIKASI SUKSES ===== -->
                <div id="successMessage" class="hidden p-3 sm:p-4 bg-green-50 border-t border-green-200">
                    <div class="flex items-center gap-2 sm:gap-3 text-green-700">
                        <i class="fas fa-check-circle text-lg sm:text-xl text-green-500"></i>
                        <div>
                            <p class="font-semibold text-sm sm:text-base">Berhasil!</p>
                            <p class="text-xs sm:text-sm" id="successText">Saran Anda berhasil dikirim. Terima kasih!</p>
                        </div>
                    </div>
                </div>

                <!-- ===== NOTIFIKASI ERROR ===== -->
                <div id="errorMessage" class="hidden p-3 sm:p-4 bg-red-50 border-t border-red-200">
                    <div class="flex items-center gap-2 sm:gap-3 text-red-700">
                        <i class="fas fa-exclamation-circle text-lg sm:text-xl text-red-500"></i>
                        <div>
                            <p class="font-semibold text-sm sm:text-base">Gagal!</p>
                            <p class="text-xs sm:text-sm" id="errorText">Terjadi kesalahan. Silakan coba lagi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Tambahan -->
            <div class="mt-4 sm:mt-6 text-center text-xs sm:text-sm text-gray-500">
                <p><i class="fas fa-info-circle text-red-500 mr-1"></i> Setiap saran yang Anda kirim akan kami baca dan tindak lanjuti.</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* === RESPONSIVE UNTUK MOBILE 320px, 375px, 425px === */
    @media (max-width: 374px) {
        /* 320px */
        .container {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .text-2xl {
            font-size: 20px !important;
        }
        .text-sm {
            font-size: 12px !important;
        }
        .text-xs {
            font-size: 10px !important;
        }
        .p-4 {
            padding: 12px !important;
        }
        .px-3 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .py-2 {
            padding-top: 6px !important;
            padding-bottom: 6px !important;
        }
        .gap-2 {
            gap: 4px !important;
        }
        .space-y-3 > * + * {
            margin-top: 8px !important;
        }
        .mb-2 {
            margin-bottom: 4px !important;
        }
        .mt-2 {
            margin-top: 4px !important;
        }
        .py-8 {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }
        .mb-6 {
            margin-bottom: 16px !important;
        }
    }

    @media (min-width: 375px) and (max-width: 424px) {
        /* 375px */
        .container {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }
        .text-2xl {
            font-size: 22px !important;
        }
        .text-sm {
            font-size: 13px !important;
        }
        .p-4 {
            padding: 14px !important;
        }
        .px-3 {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .py-8 {
            padding-top: 24px !important;
            padding-bottom: 24px !important;
        }
    }

    @media (min-width: 425px) and (max-width: 640px) {
        /* 425px */
        .text-2xl {
            font-size: 24px !important;
        }
        .py-8 {
            padding-top: 28px !important;
            padding-bottom: 28px !important;
        }
    }

    /* === TEXTAREA RESIZE === */
    textarea {
        resize: vertical;
        min-height: 100px;
    }
</style>

<script>
document.getElementById('saranForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('submitBtn');
    const successMsg = document.getElementById('successMessage');
    const errorMsg = document.getElementById('errorMessage');
    const successText = document.getElementById('successText');
    const errorText = document.getElementById('errorText');
    
    // Sembunyikan notifikasi sebelumnya
    successMsg.classList.add('hidden');
    errorMsg.classList.add('hidden');
    
    // Tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    
    const formData = new FormData(form);
    
    fetch("{{ route('saran.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
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
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Saran';
        
        if (data.success) {
            successText.textContent = data.message;
            successMsg.classList.remove('hidden');
            form.reset();
            
            setTimeout(() => {
                successMsg.classList.add('hidden');
            }, 5000);
        } else {
            errorText.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
            errorMsg.classList.remove('hidden');
            
            setTimeout(() => {
                errorMsg.classList.add('hidden');
            }, 5000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Saran';
        
        errorText.textContent = error.message || 'Terjadi kesalahan jaringan. Silakan coba lagi.';
        errorMsg.classList.remove('hidden');
        
        setTimeout(() => {
            errorMsg.classList.add('hidden');
        }, 5000);
    });
});
</script>
@endsection