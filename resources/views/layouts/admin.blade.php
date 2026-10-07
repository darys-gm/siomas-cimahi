<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo-siomas1.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/logo-siomas1.png') }}" type="image/png">
    <!-- Untuk Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('images/logo-siomas1.png') }}">
    <title>@yield('title', 'Dashboard Admin - SIOMAS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: #f0f4f8;
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            background: #0a1628;
            transition: all 0.3s ease;
        }
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #2d3748;
            border-radius: 10px;
        }
        .sidebar-hero {
            padding: 20px 20px 16px;
            background: linear-gradient(135deg, #0d1f3c 0%, #1a2a4a 50%, #0d1f3c 100%);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            position: relative;
            overflow: hidden;
        }
        .sidebar-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        .sidebar-hero .hero-content {
            position: relative;
            z-index: 1;
        }
        .sidebar-hero .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-hero .logo-img {
            height: 45px;
            width: auto;
            object-fit: contain;
        }
        .sidebar-hero .logo-text {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .sidebar-hero .logo-text span {
            color: #3b82f6;
        }
        .sidebar-hero .hero-sub {
            font-size: 10px;
            color: #6b7280;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .sidebar-hero .hero-divider {
            height: 1px;
            background: linear-gradient(to right, rgba(59,130,246,0.3), transparent);
            margin-top: 14px;
        }
        .sidebar-menu {
            padding: 16px 12px;
        }
        .sidebar-menu .menu-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #4a5568;
            letter-spacing: 0.5px;
            padding: 8px 12px 6px;
            font-weight: 600;
        }
        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            margin: 2px 0;
            border-radius: 10px;
            color: #a0aec0;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            position: relative;
        }
        .sidebar-menu .menu-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #fff;
        }
        .sidebar-menu .menu-item.active {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }
        .sidebar-menu .menu-item i {
            width: 22px;
            font-size: 16px;
            margin-right: 12px;
            text-align: center;
        }
        .sidebar-menu .menu-item .badge {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .sidebar-menu .menu-item .badge-yellow {
            margin-left: auto;
            background: #eab308;
            color: #fff;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .sidebar-menu .menu-item .badge-green {
            margin-left: auto;
            background: #22c55e;
            color: #fff;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .sidebar-menu .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.05);
            margin: 10px 12px;
        }
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }
        .content-wrapper {
            padding: 24px 30px;
        }
        .topbar {
            background: #fff;
            padding: 14px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .topbar .page-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
        }
        .topbar .page-title span {
            color: #3b82f6;
        }
        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar .user-info .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1a202c;
        }
        .topbar .user-info .user-role {
            font-size: 12px;
            color: #6b7280;
        }
        .topbar .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
        }
        .badge-update {
            animation: badgePulse 0.5s ease;
        }
        @keyframes badgePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); background: #ef4444; }
            100% { transform: scale(1); }
        }
        .badge-yellow-update {
            animation: badgePulseYellow 0.5s ease;
        }
        @keyframes badgePulseYellow {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); background: #eab308; }
            100% { transform: scale(1); }
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            .sidebar .menu-label,
            .sidebar .menu-item span:not(.badge),
            .sidebar .logo-text,
            .sidebar .hero-sub,
            .sidebar .hero-divider {
                display: none;
            }
            .sidebar .menu-item i {
                margin-right: 0;
                font-size: 18px;
            }
            .sidebar .menu-item {
                justify-content: center;
                padding: 12px;
            }
            .sidebar-hero .logo-img {
                height: 30px;
            }
            .main-content {
                margin-left: 60px;
            }
            .topbar {
                padding: 12px 16px;
            }
            .topbar .page-title {
                font-size: 14px;
            }
            .content-wrapper {
                padding: 16px;
            }
            .sidebar-menu .menu-item .badge,
            .sidebar-menu .menu-item .badge-yellow,
            .sidebar-menu .menu-item .badge-green {
                position: absolute;
                top: 2px;
                right: 2px;
                font-size: 8px;
                padding: 1px 5px;
                min-width: 14px;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================ -->
    <!-- DEFINISI VARIABEL UNTUK BADGE                -->
    <!-- ============================================ -->
    @php
        $menungguVerifikasi = \App\Models\Ormas::where('status', 'menunggu_verifikasi')->count();
        $totalSaranBaru = \App\Models\Saran::where('status', 'baru')->count();
    @endphp

    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Hero Section dalam Sidebar -->
        <div class="sidebar-hero">
            <div class="hero-content">
                <div class="logo-wrapper">
                    @php
                        $logoPath = public_path('images/logo-siomas1.png');
                        $logoUrl = file_exists($logoPath) ? asset('images/logo-siomas1.png') : null;
                    @endphp
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="SIOMAS" class="logo-img">
                    @else
                        <div class="w-11 h-11 bg-blue-600 rounded-xl flex items-center justify-center">
                            <span class="text-xl font-bold text-white">S</span>
                        </div>
                    @endif
                    <div>
                        <div class="logo-text">SIOMAS</div>
                        <div class="hero-sub">Kota Cimahi</div>
                    </div>
                </div>
                <div class="hero-divider"></div>
            </div>
        </div>

        <!-- Menu -->
        <nav class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>
            
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('admin.ormas.index') }}" class="menu-item {{ request()->routeIs('admin.ormas.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                <span>Ormas</span>
            </a>

            <!-- ===== VERIFIKASI ===== -->
            <a href="{{ route('admin.verifikasi.index') }}" class="menu-item {{ request()->routeIs('admin.verifikasi.*') ? 'active' : '' }}">
                <i class="fas fa-check-double"></i>
                <span>Verifikasi</span>
                <span id="badgeVerifikasi" class="badge {{ $menungguVerifikasi > 0 ? '' : 'hidden' }}">
                    {{ $menungguVerifikasi }}
                </span>
            </a>

            <!-- ===== KOTAK SARAN ===== -->
            <a href="{{ route('admin.saran') }}" class="menu-item {{ request()->routeIs('admin.saran') ? 'active' : '' }}">
                <i class="fas fa-inbox"></i>
                <span>Kotak Saran</span>
                <span id="badgeSaran" class="badge-yellow {{ $totalSaranBaru > 0 ? '' : 'hidden' }}">
                    {{ $totalSaranBaru }}
                </span>
            </a>

            <div class="menu-divider"></div>
            <div class="menu-label">Manajemen</div>

            <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Manajemen ORMAS</span>
            </a>

            <a href="{{ route('admin.berita.index') }}" class="menu-item {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span>Manajemen Berita</span>
            </a>
            
            <a href="{{ route('admin.produk-hukum.index') }}" class="menu-item {{ request()->routeIs('admin.produk-hukum.*') ? 'active' : '' }}">
                <i class="fas fa-gavel"></i>
                <span>Manajemen Produk Hukum</span>
            </a>

            <a href="{{ route('admin.poster.index') }}" class="menu-item {{ request()->routeIs('admin.poster.*') ? 'active' : '' }}">
                <i class="fas fa-image"></i>
                <span>Manajemen Poster</span>
            </a>

            <a href="{{ route('admin.galeri.index') }}" class="menu-item {{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                <span>Manajemen Galeri</span>
            </a>

            <a href="{{ route('admin.agenda.index') }}" class="menu-item  {{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i>
                <span>Manajemen Agenda</span>
            </a>

            <a href="{{ route('admin.setting.home') }}" class="menu-item {{ request()->routeIs('admin.setting.*') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Manajemen Home</span>
            </a>

            <a href="{{ route('admin.struktur.index') }}" class="menu-item {{ request()->routeIs('admin.struktur.*') ? 'active' : '' }}">
                <i class="fas fa-sitemap"></i>
                <span>Manajemen Profil</span>
            </a>

            <a href="{{ route('admin.laporan.index') }}" class="menu-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Statistik & Laporan</span>
            </a>

            <!-- ===== LOG AKTIVITAS - PERBAIKAN DISINI ===== -->
            <a href="{{ route('admin.logs.index') }}" class="menu-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Log Aktivitas</span>
            </a>

            <div class="menu-divider"></div>
            <div class="menu-label">Pengaturan</div>

            <a href="{{ route('admin.change-password') }}" class="menu-item {{ request()->routeIs('admin.change-password') ? 'active' : '' }}">
                <i class="fas fa-key"></i>
                <span>Ubah Password</span>
            </a>

            <div class="menu-divider"></div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item w-full text-red-400 hover:text-red-300 hover:bg-red-500/10">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="page-title">
                @yield('title', 'Dashboard')
            </div>
            <div class="user-info">
                <div class="text-right hidden sm:block">
                    <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
                <div class="user-avatar">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-wrapper">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- ============================================ -->
    <!-- SCRIPT UNTUK AUTO UPDATE BADGE SIDEBAR      -->
    <!-- ============================================ -->
    <script>
        (function() {
            'use strict';

            let badgeInterval = null;
            let isUpdating = false;

            function updateBadges() {
                if (isUpdating) return;
                isUpdating = true;

                fetch('{{ route("admin.badges") }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    isUpdating = false;
                    
                    if (data.success) {
                        // Update badge verifikasi
                        const badgeVerif = document.getElementById('badgeVerifikasi');
                        if (badgeVerif) {
                            const count = parseInt(data.menungguVerifikasi) || 0;
                            if (count > 0) {
                                badgeVerif.textContent = count;
                                badgeVerif.classList.remove('hidden');
                                badgeVerif.classList.add('badge-update');
                                setTimeout(() => {
                                    badgeVerif.classList.remove('badge-update');
                                }, 500);
                            } else {
                                badgeVerif.classList.add('hidden');
                            }
                        }

                        // Update badge saran
                        const badgeSaran = document.getElementById('badgeSaran');
                        if (badgeSaran) {
                            const count = parseInt(data.saranBaru) || 0;
                            if (count > 0) {
                                badgeSaran.textContent = count;
                                badgeSaran.classList.remove('hidden');
                                badgeSaran.classList.add('badge-yellow-update');
                                setTimeout(() => {
                                    badgeSaran.classList.remove('badge-yellow-update');
                                }, 500);
                            } else {
                                badgeSaran.classList.add('hidden');
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating badges:', error);
                    isUpdating = false;
                });
            }

            function startBadgeAutoUpdate() {
                if (badgeInterval) {
                    clearInterval(badgeInterval);
                }
                setTimeout(updateBadges, 3000);
                badgeInterval = setInterval(updateBadges, 10000);
            }

            function stopBadgeAutoUpdate() {
                if (badgeInterval) {
                    clearInterval(badgeInterval);
                    badgeInterval = null;
                }
            }

            // Init
            if (document.readyState === 'complete') {
                startBadgeAutoUpdate();
            } else {
                document.addEventListener('DOMContentLoaded', function() {
                    startBadgeAutoUpdate();
                });
            }

            window.startBadgeAutoUpdate = startBadgeAutoUpdate;
            window.stopBadgeAutoUpdate = stopBadgeAutoUpdate;
            window.updateBadges = updateBadges;

        })();
    </script>

    @stack('scripts')
</body>
</html>