<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin - SIOMAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #0a1628 0%, #1a2a4a 50%, #0d1f3c 100%);
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .login-card:hover {
            border-color: rgba(59, 130, 246, 0.3);
        }
        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
            background: rgba(255, 255, 255, 0.08);
        }
        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        .input-field.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo -->
            <div class="text-center">
                @php
                    $logoPath = public_path('images/logo-siomas1.png');
                    $logoUrl = file_exists($logoPath) ? asset('images/logo-siomas1.png') : null;
                @endphp
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="SIOMAS" class="h-20 w-auto mx-auto">
                @else
                    <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto">
                        <span class="text-4xl font-bold text-white">S</span>
                    </div>
                @endif
                <h2 class="mt-4 text-center text-3xl font-extrabold text-white">
                    Login
                </h2>
                <p class="mt-2 text-center text-sm text-gray-400">
                    SIOMAS - Sistem Informasi Organisasi Masyarakat
                </p>
                <p class="text-center text-xs text-blue-400">Kota Cimahi</p>
            </div>

            <!-- Form Login -->
            <div class="login-card rounded-2xl shadow-2xl p-8">
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf

                    @if(session('success'))
                    <div class="bg-green-500/20 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg text-sm">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                        <input id="username" name="username" type="text" required 
                               class="input-field mt-1 appearance-none rounded-lg relative block w-full px-3 py-2 placeholder-gray-400 sm:text-sm @error('username') error @enderror"
                               value="{{ old('username') }}" placeholder="Masukkan username">
                        @error('username')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="input-field mt-1 appearance-none rounded-lg relative block w-full px-3 py-2 placeholder-gray-400 sm:text-sm"
                               placeholder="Masukkan password">
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk
                        </button>
                    </div>

                    <div class="text-sm text-center border-t border-gray-700 pt-4">
                        <a href="{{ route('home') }}" class="font-medium text-gray-400 hover:text-white transition">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>