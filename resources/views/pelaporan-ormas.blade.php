@extends('layouts.home')

@section('title', 'Pelaporan ORMAS - SIOMAS Kota Cimahi')

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
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-10">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-2 sm:mb-3">Pelaporan Keberadaan ORMAS</h1>
                <p class="text-sm sm:text-base md:text-lg text-gray-600">Isi form di bawah ini untuk melaporkan keberadaan Organisasi Masyarakat (ORMAS) di Kota Cimahi</p>
                <div class="w-16 sm:w-20 h-1 bg-red-600 mx-auto mt-2 sm:mt-3 rounded-full"></div>
            </div>

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 sm:px-6 py-3 sm:py-4 rounded-xl mb-4 sm:mb-6 flex items-start gap-3">
                <i class="fas fa-check-circle text-green-500 text-lg sm:text-xl mt-1"></i>
                <div>
                    <p class="font-semibold text-sm sm:text-base">Berhasil!</p>
                    <p class="text-xs sm:text-sm">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 sm:px-6 py-3 sm:py-4 rounded-xl mb-4 sm:mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-lg sm:text-xl mt-1"></i>
                <div>
                    <p class="font-semibold text-sm sm:text-base">Gagal!</p>
                    <p class="text-xs sm:text-sm">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 sm:px-6 py-3 sm:py-4 rounded-xl mb-4 sm:mb-6">
                <p class="font-semibold text-sm sm:text-base mb-1 sm:mb-2">Terjadi kesalahan pada form:</p>
                <ul class="list-disc list-inside text-xs sm:text-sm space-y-0.5 sm:space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-4 sm:px-6 py-3 sm:py-4">
                    <h2 class="text-white font-semibold text-base sm:text-lg">
                        <i class="fas fa-building mr-2"></i> Formulir Pelaporan ORMAS
                    </h2>
                </div>

                <form action="{{ route('pelaporan-ormas.store') }}" method="POST" class="p-4 sm:p-6 md:p-8" enctype="multipart/form-data" id="pelaporanForm">
                    @csrf

                    <!-- Data ORMAS -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">
                            <i class="fas fa-info-circle text-red-600 mr-2"></i> Data ORMAS
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Nama ORMAS -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">
                                        Nama ORMAS <span class="text-red-500">*</span>
                                    </label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="nama_ormas"></i>
                                </div>
                                <input type="text" name="nama_ormas" id="nama_ormas" value="{{ old('nama_ormas') }}" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('nama_ormas') border-red-500 @enderror" 
                                       placeholder="Masukkan nama ORMAS" required>
                                @error('nama_ormas')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Singkatan -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">Singkatan</label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="singkatan"></i>
                                </div>
                                <input type="text" name="singkatan" id="singkatan" value="{{ old('singkatan') }}" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('singkatan') border-red-500 @enderror"
                                       placeholder="Masukkan singkatan ORMAS">
                                <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Kosongkan jika tidak ada</p>
                                @error('singkatan')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Legalitas AHU/SKT -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">Legalitas AHU (KEMENKUMHAM) / SKT (KEMENDAGRI) <span class="text-red-500">*</span></label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="no_ahu_skt"></i>
                                </div>
                                <input type="text" name="no_ahu_skt" id="no_ahu_skt" value="{{ old('no_ahu_skt') }}" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('no_ahu_skt') border-red-500 @enderror"
                                       placeholder="Masukkan No AHU/SKT">
                                @error('no_ahu_skt')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bentuk Ormas -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">Bentuk Ormas <span class="text-red-500">*</span></label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="jenis_ormas_id"></i>
                                </div>
                                <select name="jenis_ormas_id" id="jenis_ormas_id" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('jenis_ormas_id') border-red-500 @enderror" required>
                                    <option value="">Pilih Bentuk Ormas</option>
                                    <option value="yayasan" {{ old('jenis_ormas_id') == 'yayasan' ? 'selected' : '' }}>Yayasan</option>
                                    <option value="perkumpulan" {{ old('jenis_ormas_id') == 'perkumpulan' ? 'selected' : '' }}>Perkumpulan</option>
                                </select>
                                @error('jenis_ormas_id')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bidang Kegiatan -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">Bidang Kegiatan Ormas <span class="text-red-500">*</span></label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="bidang_kegiatan_id"></i>
                                </div>
                                <select name="bidang_kegiatan_id" id="bidang_kegiatan_id" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('bidang_kegiatan_id') border-red-500 @enderror" required>
                                    <option value="">Pilih Bidang Kegiatan Ormas</option>
                                    <option value="Keagamaan dan Kepercayaan" {{ old('bidang_kegiatan_id') == 'Keagamaan dan Kepercayaan' ? 'selected' : '' }}>Keagamaan dan Kepercayaan</option>
                                    <option value="Sosial, Kemanusiaan dan Kemasyarakatan" {{ old('bidang_kegiatan_id') == 'Sosial, Kemanusiaan dan Kemasyarakatan' ? 'selected' : '' }}>Sosial, Kemanusiaan dan Kemasyarakatan</option>
                                    <option value="Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga" {{ old('bidang_kegiatan_id') == 'Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga' ? 'selected' : '' }}>Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga</option>
                                    <option value="Kepemudaan, Olahraga dan Seni Budaya" {{ old('bidang_kegiatan_id') == 'Kepemudaan, Olahraga dan Seni Budaya' ? 'selected' : '' }}>Kepemudaan, Olahraga dan Seni Budaya</option>
                                    <option value="Pendidikan dan Pemberdayaan SDM" {{ old('bidang_kegiatan_id') == 'Pendidikan dan Pemberdayaan SDM' ? 'selected' : '' }}>Pendidikan dan Pemberdayaan SDM</option>
                                    <option value="Profesi dan Keahlian" {{ old('bidang_kegiatan_id') == 'Profesi dan Keahlian' ? 'selected' : '' }}>Profesi dan Keahlian</option>
                                    <option value="Lingkungan Hidup dan Kebencanaan" {{ old('bidang_kegiatan_id') == 'Lingkungan Hidup dan Kebencanaan' ? 'selected' : '' }}>Lingkungan Hidup dan Kebencanaan</option>
                                    <option value="Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat" {{ old('bidang_kegiatan_id') == 'Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat' ? 'selected' : '' }}>Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat</option>
                                    <option value="Kebangsaan dan Bela Negara" {{ old('bidang_kegiatan_id') == 'Kebangsaan dan Bela Negara' ? 'selected' : '' }}>Kebangsaan dan Bela Negara</option>
                                    <option value="Hukum, HAM dan Advokasi Publik" {{ old('bidang_kegiatan_id') == 'Hukum, HAM dan Advokasi Publik' ? 'selected' : '' }}>Hukum, HAM dan Advokasi Publik</option>
                                    <option value="Komunitas, Minat dan Hobi" {{ old('bidang_kegiatan_id') == 'Komunitas, Minat dan Hobi' ? 'selected' : '' }}>Komunitas, Minat dan Hobi</option>
                                    <option value="Bidang Kegiatan Lainnya" {{ old('bidang_kegiatan_id') == 'Bidang Kegiatan Lainnya' ? 'selected' : '' }}>Bidang Kegiatan Lainnya</option>
                                </select>
                                @error('bidang_kegiatan_id')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2 field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">Alamat Lengkap Sekretariat (Jl, Gang dan No) <span class="text-red-500">*</span></label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="alamat_kesekretariatan"></i>
                                </div>
                                <textarea name="alamat_kesekretariatan" id="alamat_kesekretariatan" rows="2" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('alamat_kesekretariatan') border-red-500 @enderror" placeholder="Masukkan alamat kesekretariatan" required>{{ old('alamat_kesekretariatan') }}</textarea>
                                @error('alamat_kesekretariatan')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Koordinat Lokasi -->
                            <div class="md:col-span-2 field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">Koordinat Lokasi <span class="text-red-500">*</span></label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="latitude"></i>
                                </div>
                                <p class="text-[10px] sm:text-xs text-gray-400 mb-1.5 sm:mb-2">Klik pada peta atau drag marker untuk menentukan titik koordinat</p>
                                
                                <div id="map" class="w-full h-56 sm:h-72 md:h-80 rounded-lg border border-gray-300 overflow-hidden mb-2 sm:mb-3" style="z-index: 1; position: relative;"></div>

                                <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                    <div>
                                        <label class="block text-[10px] sm:text-xs font-medium text-gray-600 mb-0.5 sm:mb-1">Latitude <span class="text-red-500">*</span></label>
                                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', '0') }}" 
                                               class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-xs sm:text-sm @error('latitude') border-red-500 @enderror"
                                               placeholder="Latitude" readonly required>
                                        @error('latitude')
                                        <p class="text-red-500 text-[10px] sm:text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[10px] sm:text-xs font-medium text-gray-600 mb-0.5 sm:mb-1">Longitude <span class="text-red-500">*</span></label>
                                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', '0') }}" 
                                               class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-xs sm:text-sm @error('longitude') border-red-500 @enderror"
                                               placeholder="Longitude" readonly required>
                                        @error('longitude')
                                        <p class="text-red-500 text-[10px] sm:text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <p class="text-[10px] sm:text-xs text-gray-400">Klik pada peta untuk menentukan titik lokasi ORMAS</p>
                                    <button type="button" onclick="resetKoordinat()" 
                                            class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-700 text-xs rounded-lg hover:bg-gray-300 transition">
                                        <i class="fas fa-undo mr-1"></i> Reset Koordinat
                                    </button>
                                </div>
                            </div>

                            <!-- Kecamatan -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">Kecamatan <span class="text-red-500">*</span></label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="kecamatan_id"></i>
                                </div>
                                <select name="kecamatan_id" id="kecamatan_id" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('kecamatan_id') border-red-500 @enderror" required>
                                    <option value="">Pilih Kecamatan</option>
                                    <option value="Cimahi Selatan" {{ old('kecamatan_id') == 'Cimahi Selatan' ? 'selected' : '' }}>Cimahi Selatan</option>
                                    <option value="Cimahi Tengah" {{ old('kecamatan_id') == 'Cimahi Tengah' ? 'selected' : '' }}>Cimahi Tengah</option>
                                    <option value="Cimahi Utara" {{ old('kecamatan_id') == 'Cimahi Utara' ? 'selected' : '' }}>Cimahi Utara</option>
                                </select>
                                @error('kecamatan_id')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kelurahan -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">Kelurahan <span class="text-red-500">*</span></label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="kelurahan_id"></i>
                                </div>
                                <select name="kelurahan_id" id="kelurahan_id" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('kelurahan_id') border-red-500 @enderror" required>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                                @error('kelurahan_id')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Telepon -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">No Telepon <span class="text-red-500">*</span></label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="no_telepon"></i>
                                </div>
                                <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('no_telepon') border-red-500 @enderror"
                                       placeholder="Masukkan no telepon" required>
                                @error('no_telepon')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email - OPSIONAL -->
                            <div class="field-group">
                                <div class="flex items-center gap-2 mb-1">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700">E-mail</label>
                                    <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="email"></i>
                                </div>
                                <input type="text" name="email" id="email" value="{{ old('email') }}" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('email') border-red-500 @enderror"
                                       placeholder="Masukkan email (opsional), kosongkan atau isi dengan '-' jika tidak memiliki email">
                                <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Kosongkan atau isi dengan tanda <strong>-</strong> jika tidak memiliki email</p>
                                @error('email')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Data Pengurus -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 pb-2 border-b border-gray-200">
                            <i class="fas fa-users text-red-600 mr-2"></i> Biodata Pengurus
                        </h3>

                        <!-- Ketua (WAJIB) -->
                        <div class="mb-3 sm:mb-4 p-3 sm:p-4 bg-red-50 rounded-lg border border-red-200">
                            <h4 class="font-semibold text-red-700 text-sm sm:text-base mb-2 sm:mb-3">
                                <i class="fas fa-user-tie text-red-600 mr-2"></i> Ketua <span class="text-red-500">*</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Nama Ketua <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="ketua_nama"></i>
                                    </div>
                                    <input type="text" name="ketua_nama" id="ketua_nama" value="{{ old('ketua_nama') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('ketua_nama') border-red-500 @enderror" 
                                           placeholder="Nama ketua" required>
                                    @error('ketua_nama')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Alamat Ketua <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="ketua_alamat"></i>
                                    </div>
                                    <input type="text" name="ketua_alamat" id="ketua_alamat" value="{{ old('ketua_alamat') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('ketua_alamat') border-red-500 @enderror"
                                           placeholder="Alamat ketua">
                                    @error('ketua_alamat')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">No Telepon Ketua <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="ketua_no_hp"></i>
                                    </div>
                                    <input type="text" name="ketua_no_hp" id="ketua_no_hp" value="{{ old('ketua_no_hp') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('ketua_no_hp') border-red-500 @enderror" 
                                           placeholder="No telepon ketua" required>
                                    @error('ketua_no_hp')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sekretaris (OPSIONAL) -->
                        <div class="mb-3 sm:mb-4 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-semibold text-gray-700 text-sm sm:text-base mb-2 sm:mb-3">
                                <i class="fas fa-user-edit text-red-600 mr-2"></i> Sekretaris
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Nama Sekretaris <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="sekretaris_nama"></i>
                                    </div>
                                    <input type="text" name="sekretaris_nama" id="sekretaris_nama" value="{{ old('sekretaris_nama') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('sekretaris_nama') border-red-500 @enderror" 
                                           placeholder="Nama sekretaris">
                                    @error('sekretaris_nama')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Alamat Sekretaris <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="sekretaris_alamat"></i>
                                    </div>
                                    <input type="text" name="sekretaris_alamat" id="sekretaris_alamat" value="{{ old('sekretaris_alamat') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('sekretaris_alamat') border-red-500 @enderror"
                                           placeholder="Alamat sekretaris">
                                    @error('sekretaris_alamat')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">No Telepon Sekretaris <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="sekretaris_no_hp"></i>
                                    </div>
                                    <input type="text" name="sekretaris_no_hp" id="sekretaris_no_hp" value="{{ old('sekretaris_no_hp') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('sekretaris_no_hp') border-red-500 @enderror" 
                                           placeholder="No telepon sekretaris">
                                    @error('sekretaris_no_hp')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bendahara (OPSIONAL) -->
                        <div class="mb-3 sm:mb-4 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-semibold text-gray-700 text-sm sm:text-base mb-2 sm:mb-3">
                                <i class="fas fa-user-tie text-red-600 mr-2"></i> Bendahara
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Nama Bendahara <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="bendahara_nama"></i>
                                    </div>
                                    <input type="text" name="bendahara_nama" id="bendahara_nama" value="{{ old('bendahara_nama') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('bendahara_nama') border-red-500 @enderror" 
                                           placeholder="Nama bendahara">
                                    @error('bendahara_nama')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Alamat Bendahara <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="bendahara_alamat"></i>
                                    </div>
                                    <input type="text" name="bendahara_alamat" id="bendahara_alamat" value="{{ old('bendahara_alamat') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('bendahara_alamat') border-red-500 @enderror"
                                           placeholder="Alamat bendahara">
                                    @error('bendahara_alamat')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">No Telepon Bendahara <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="bendahara_no_hp"></i>
                                    </div>
                                    <input type="text" name="bendahara_no_hp" id="bendahara_no_hp" value="{{ old('bendahara_no_hp') }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('bendahara_no_hp') border-red-500 @enderror" 
                                           placeholder="No telepon bendahara">
                                    @error('bendahara_no_hp')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ===== DATA KEANGGOTAAN ===== -->
                        <div class="p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200 mt-3 sm:mt-4">
                            <h4 class="font-semibold text-black-700 text-sm sm:text-base mb-2 sm:mb-3">
                                <i class="fas fa-users text-red-600 mr-2"></i> Data Keanggotaan
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                <!-- Jumlah Total Anggota -->
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Jumlah Total Anggota <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="jumlah_anggota"></i>
                                    </div>
                                    <input type="number" name="jumlah_anggota" id="jumlah_anggota" value="{{ old('jumlah_anggota', 0) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('jumlah_anggota') border-red-500 @enderror"
                                           placeholder="Masukkan jumlah total anggota" min="0" required>
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Masukkan jumlah total anggota ORMAS (minimal 0)</p>
                                    @error('jumlah_anggota')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jumlah Anggota Perempuan -->
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Jumlah Anggota Perempuan <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="jumlah_anggota_perempuan"></i>
                                    </div>
                                    <input type="number" name="jumlah_anggota_perempuan" id="jumlah_anggota_perempuan" value="{{ old('jumlah_anggota_perempuan', 0) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('jumlah_anggota_perempuan') border-red-500 @enderror"
                                           placeholder="Masukkan jumlah anggota perempuan" min="0">
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Jumlah anggota perempuan</p>
                                    @error('jumlah_anggota_perempuan')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Anggota Perempuan Rentang Usia 16-30 -->
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Anggota Perempuan (Usia 16-30) <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="anggota_perempuan_rentang_16_30"></i>
                                    </div>
                                    <input type="number" name="anggota_perempuan_rentang_16_30" id="anggota_perempuan_rentang_16_30" value="{{ old('anggota_perempuan_rentang_16_30', 0) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('anggota_perempuan_rentang_16_30') border-red-500 @enderror"
                                           placeholder="Masukkan jumlah anggota perempuan usia 16-30" min="0">
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Jumlah anggota perempuan usia 16-30 tahun</p>
                                    @error('anggota_perempuan_rentang_16_30')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jumlah Anggota Laki-laki -->
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Jumlah Anggota Laki-laki <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="jumlah_anggota_laki_laki"></i>
                                    </div>
                                    <input type="number" name="jumlah_anggota_laki_laki" id="jumlah_anggota_laki_laki" value="{{ old('jumlah_anggota_laki_laki', 0) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('jumlah_anggota_laki_laki') border-red-500 @enderror"
                                           placeholder="Masukkan jumlah anggota laki-laki" min="0">
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Jumlah anggota laki-laki</p>
                                    @error('jumlah_anggota_laki_laki')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Anggota Laki-laki Rentang Usia 16-30 -->
                                <div class="field-group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Anggota Laki-laki (Usia 16-30) <span class="text-red-500">*</span></label>
                                        <i class="fas fa-check-circle field-check text-gray-300 text-xs sm:text-sm" data-field="anggota_laki_laki_rentang_16_30"></i>
                                    </div>
                                    <input type="number" name="anggota_laki_laki_rentang_16_30" id="anggota_laki_laki_rentang_16_30" value="{{ old('anggota_laki_laki_rentang_16_30', 0) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base @error('anggota_laki_laki_rentang_16_30') border-red-500 @enderror"
                                           placeholder="Masukkan jumlah anggota laki-laki usia 16-30" min="0">
                                    <p class="text-[10px] sm:text-xs text-gray-400 mt-1">Jumlah anggota laki-laki usia 16-30 tahun</p>
                                    @error('anggota_laki_laki_rentang_16_30')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-gray-200">
                        <button type="submit" class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition flex items-center justify-center gap-2 text-sm sm:text-base">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Laporan
                        </button>
                        <a href="{{ route('home') }}" class="px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition flex items-center justify-center gap-2 text-sm sm:text-base">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Beranda
                        </a>
                    </div>

                    <div class="mt-3 sm:mt-4 p-2.5 sm:p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-[11px] sm:text-sm text-yellow-700 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Setelah mengisi form jangan lupa untuk Verifikasi berkas fisik ke kantor Bakesbangpol Kota Cimahi
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ===== LEAFET + OPENSTREETMAP ===== -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* === RESPONSIVE UNTUK MOBILE 320px, 375px, 425px === */
    @media (max-width: 374px) {
        .container {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .text-2xl {
            font-size: 18px !important;
        }
        .text-sm {
            font-size: 11px !important;
        }
        .text-xs {
            font-size: 10px !important;
        }
        .text-\[10px\] {
            font-size: 8px !important;
        }
        .text-\[11px\] {
            font-size: 9px !important;
        }
        .p-4 {
            padding: 10px !important;
        }
        .px-3 {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
        .py-2 {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }
        .gap-2 {
            gap: 3px !important;
        }
        .gap-3 {
            gap: 4px !important;
        }
        .space-y-3 > * + * {
            margin-top: 6px !important;
        }
        .mb-2 {
            margin-bottom: 3px !important;
        }
        .mb-3 {
            margin-bottom: 6px !important;
        }
        .mb-4 {
            margin-bottom: 8px !important;
        }
        .mt-1 {
            margin-top: 2px !important;
        }
        .mt-1.5 {
            margin-top: 3px !important;
        }
        .mt-2 {
            margin-top: 4px !important;
        }
        .py-8 {
            padding-top: 16px !important;
            padding-bottom: 16px !important;
        }
        .mb-6 {
            margin-bottom: 12px !important;
        }
        .h-56 {
            height: 180px !important;
        }
        .grid-cols-1 {
            grid-template-columns: 1fr !important;
        }
        .md\:grid-cols-2 {
            grid-template-columns: 1fr !important;
        }
        .md\:grid-cols-3 {
            grid-template-columns: 1fr !important;
        }
        .p-3 {
            padding: 6px !important;
        }
        .p-2.5 {
            padding: 4px !important;
        }
        .rounded-lg {
            border-radius: 6px !important;
        }
        .text-base {
            font-size: 13px !important;
        }
        .text-lg {
            font-size: 15px !important;
        }
        .py-1.5 {
            padding-top: 3px !important;
            padding-bottom: 3px !important;
        }
        .w-16 {
            width: 40px !important;
        }
        .w-20 {
            width: 48px !important;
        }
    }

    @media (min-width: 375px) and (max-width: 424px) {
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
        .p-4 {
            padding: 12px !important;
        }
        .px-3 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .py-8 {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }
        .h-56 {
            height: 200px !important;
        }
        .gap-3 {
            gap: 6px !important;
        }
        .space-y-3 > * + * {
            margin-top: 8px !important;
        }
    }

    @media (min-width: 425px) and (max-width: 640px) {
        .text-2xl {
            font-size: 22px !important;
        }
        .py-8 {
            padding-top: 24px !important;
            padding-bottom: 24px !important;
        }
        .h-56 {
            height: 220px !important;
        }
    }

    /* === TEXTAREA RESIZE === */
    textarea {
        resize: vertical;
        min-height: 60px;
    }

    /* === MAP Z-INDEX === */
    #map {
        z-index: 1 !important;
        position: relative !important;
    }
    
    .leaflet-control-container {
        z-index: 2 !important;
    }
</style>

<script>
// ============================================
// DATA KELURAHAN
// ============================================
document.getElementById('kecamatan_id').addEventListener('change', function() {
    let kelurahanSelect = document.getElementById('kelurahan_id');
    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    
    let kelurahanData = {
        'Cimahi Selatan': ['Cibeber', 'Cibeureum', 'Leuwigajah', 'Melong', 'Utama'],
        'Cimahi Tengah': ['Baros', 'Cigugur Tengah', 'Cimahi', 'Karangmekar', 'Padasuka', 'Setiamanah'],
        'Cimahi Utara': ['Cibabat', 'Cipageran', 'Citeureup', 'Pasirkaliki']
    };
    
    let selectedKecamatan = this.value;
    if (selectedKecamatan && kelurahanData[selectedKecamatan]) {
        kelurahanData[selectedKecamatan].forEach(function(kelurahan) {
            let option = document.createElement('option');
            option.value = kelurahan;
            option.textContent = kelurahan;
            if ('{{ old("kelurahan_id") }}' === kelurahan) {
                option.selected = true;
            }
            kelurahanSelect.appendChild(option);
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    let kecamatanSelect = document.getElementById('kecamatan_id');
    if (kecamatanSelect.value) {
        kecamatanSelect.dispatchEvent(new Event('change'));
    }
});

// ============================================
// ICON CEKLIS DINAMIS
// ============================================
function updateCheckIcons() {
    // Daftar field yang icon-nya tidak aktif secara default
    const optionalFields = [
        'jumlah_anggota_perempuan',
        'anggota_perempuan_rentang_16_30',
        'jumlah_anggota_laki_laki',
        'anggota_laki_laki_rentang_16_30'
    ];
    
    document.querySelectorAll('.field-check').forEach(function(icon) {
        const fieldId = icon.getAttribute('data-field');
        const field = document.getElementById(fieldId);
        
        if (field) {
            // Jika field adalah optional (data anggota)
            if (optionalFields.includes(fieldId)) {
                // Hanya aktif jika field memiliki nilai > 0
                if (field.value && parseInt(field.value) > 0) {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-green-500');
                } else {
                    icon.classList.remove('text-green-500');
                    icon.classList.add('text-gray-300');
                }
            } else if (field.tagName === 'SELECT') {
                if (field.value && field.value !== '') {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-green-500');
                } else {
                    icon.classList.remove('text-green-500');
                    icon.classList.add('text-gray-300');
                }
            } else if (field.type === 'checkbox' || field.type === 'radio') {
                if (field.checked) {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-green-500');
                } else {
                    icon.classList.remove('text-green-500');
                    icon.classList.add('text-gray-300');
                }
            } else {
                // Untuk field input text/number
                if (field.value && field.value.trim() !== '' && field.value !== '0') {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-green-500');
                } else {
                    icon.classList.remove('text-green-500');
                    icon.classList.add('text-gray-300');
                }
            }
        }
    });
}

// Event listener untuk semua input, select, textarea
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pelaporanForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(function(input) {
        input.addEventListener('input', updateCheckIcons);
        input.addEventListener('change', updateCheckIcons);
    });
    
    setTimeout(updateCheckIcons, 100);
});

// ============================================
// RESET KOORDINAT
// ============================================
function resetKoordinat() {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    latInput.value = '0';
    lngInput.value = '0';
    
    if (window.markerInstance) {
        window.markerInstance.setLatLng([0, 0]);
    }
    if (window.mapInstance) {
        window.mapInstance.setView([-6.870367, 107.554704], 13);
    }
    
    updateCheckIcons();
}

// ============================================
// LEAFET + OPENSTREETMAP
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const cimahiLat = -6.870367;
    const cimahiLng = 107.554704;
    
    const defaultLat = parseFloat(document.getElementById('latitude').value) || 0;
    const defaultLng = parseFloat(document.getElementById('longitude').value) || 0;

    const map = L.map('map', {
        zoomControl: true,
        attributionControl: true
    }).setView([cimahiLat, cimahiLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    window.mapInstance = map;
    window.markerInstance = marker;

    marker.on('dragend', function() {
        const pos = marker.getLatLng();
        document.getElementById('latitude').value = pos.lat.toFixed(8);
        document.getElementById('longitude').value = pos.lng.toFixed(8);
        updateCheckIcons();
    });

    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
        updateCheckIcons();
    });
});
</script>
@endsection