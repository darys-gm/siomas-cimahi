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
    
    <title>@yield('title', 'SIOMAS - Kota Cimahi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        /* ============================================ */
        /* MASKOT                                      */
        /* ============================================ */
        .maskot {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            cursor: pointer;
            animation: bounce 2s infinite;
            transition: transform 0.3s ease;
        }
        .maskot:hover {
            transform: scale(1.1);
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* ============================================ */
        /* POPUP                                       */
        /* ============================================ */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }
        .popup-overlay.active {
            display: flex;
        }
        .popup-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 400px;
            width: 90%;
            position: relative;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .popup-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        .popup-close:hover {
            color: #333;
        }
        
        /* ============================================ */
        /* NAVIGATION - DESKTOP                        */
        /* ============================================ */
        .nav-link {
            transition: color 0.3s ease;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            padding-bottom: 6px;
        }
        .nav-link:hover {
            color: #dc2626;
        }
        .nav-link.active {
            color: #dc2626 !important;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #dc2626;
            border-radius: 2px;
        }
        .dropdown-menu {
            transform-origin: top center;
        }
        .dropdown-btn.active {
            color: #dc2626 !important;
        }
        
        /* ============================================ */
        /* MOBILE NAVIGATION                           */
        /* ============================================ */
        .mobile-nav-link {
            display: block;
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
            color: #374151;
        }
        .mobile-nav-link:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        .mobile-nav-link.active {
            background: #fef2f2;
            color: #dc2626;
        }
        .mobile-nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
        }
        
        .mobile-dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #374151;
        }
        .mobile-dropdown-toggle:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        .mobile-dropdown-toggle.active {
            color: #dc2626;
        }
        .mobile-dropdown-toggle i:first-child {
            width: 24px;
            text-align: center;
            margin-right: 12px;
        }
        .mobile-dropdown-toggle .fa-chevron-down {
            transition: transform 0.3s ease;
        }
        .mobile-dropdown-toggle .fa-chevron-down.rotate-180 {
            transform: rotate(180deg);
        }
        
        .mobile-dropdown-item {
            display: block;
            padding: 10px 16px 10px 52px;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            color: #6b7280;
        }
        .mobile-dropdown-item:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        .mobile-dropdown-item.active {
            background: #fef2f2;
            color: #dc2626;
        }
        .mobile-dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
        }

        /* ============================================ */
        /* HAMBURGER MENU BUTTON                       */
        /* ============================================ */
        .hamburger {
            display: flex;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
            background: transparent;
            border: none;
        }
        .hamburger span {
            display: block;
            width: 25px;
            height: 3px;
            background: #374151;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        .hamburger.active span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* ============================================ */
        /* MOBILE NAV OVERLAY & PANEL                  */
        /* ============================================ */
        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .mobile-nav-panel {
            position: fixed;
            top: 0;
            left: -300px;
            width: 280px;
            max-width: 80%;
            height: 100%;
            background: white;
            z-index: 50;
            overflow-y: auto;
            transition: left 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }
        .mobile-nav-panel.active {
            left: 0;
        }
        .mobile-nav-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            background: white;
            position: sticky;
            top: 0;
            z-index: 5;
        }
        .mobile-nav-panel-body {
            padding: 8px 12px 20px;
        }
        .mobile-nav-panel-close {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            background: #f3f4f6;
            cursor: pointer;
            transition: background 0.2s ease;
            font-size: 18px;
            color: #6b7280;
        }
        .mobile-nav-panel-close:hover {
            background: #e5e7eb;
        }
        .mobile-nav-divider {
            border-top: 1px solid #f3f4f6;
            margin: 8px 0;
        }

        /* ============================================ */
        /* LOGO                                        */
        /* ============================================ */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0px;
            text-decoration: none;
            margin-left: 60px;
        }
        .logo-image {
            height: 65px;
            width: auto;
            object-fit: contain;
        }
        
        /* ============================================ */
        /* FOOTER                                      */
        /* ============================================ */
        .footer-phone-item {
            transition: all 0.2s ease;
        }
        .footer-phone-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }
        .footer-info-item {
            transition: all 0.2s ease;
            padding: 6px 12px;
            border-radius: 8px;
        }
        .footer-info-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .footer-bg {
            position: relative;
            background-image: url('{{ asset('images/bg-image1.webp') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .footer-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .footer-bg > * {
            position: relative;
            z-index: 1;
        }

        /* ============================================ */
        /* RESPONSIVE BREAKPOINTS                      */
        /* ============================================ */
        
        /* --- Tablet (768px - 1024px) --- */
        @media (max-width: 1024px) {
            .desktop-nav {
                display: none !important;
            }
            .mobile-nav-toggle {
                display: flex !important;
            }
            .mobile-auth {
                display: flex !important;
            }
            .navbar-auth-desktop {
                display: none !important;
            }
            
            .logo-container {
                margin-left: 8px;
            }
            .logo-image {
                height: 45px;
            }
        }

        /* --- Mobile (480px - 767px) --- */
        @media (max-width: 767px) {
            .logo-container {
                margin-left: 4px;
            }
            .logo-image {
                height: 38px;
            }
            .mobile-nav-panel {
                width: 260px;
            }
            .mobile-nav-link {
                padding: 10px 14px;
                font-size: 0.9rem;
            }
            .mobile-dropdown-toggle {
                padding: 10px 14px;
                font-size: 0.9rem;
            }
            .mobile-dropdown-item {
                padding: 8px 14px 8px 46px;
                font-size: 0.85rem;
            }
            .maskot {
                bottom: 15px;
                right: 15px;
            }
            .maskot img {
                width: 50px;
                height: 50px;
            }
            .popup-content {
                padding: 20px;
            }
        }

        /* --- Small Mobile (below 480px) --- */
        @media (max-width: 480px) {
            .logo-image {
                height: 32px;
            }
            .mobile-nav-panel {
                width: 240px;
            }
            .mobile-nav-link {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            .mobile-dropdown-toggle {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            .mobile-dropdown-item {
                padding: 6px 12px 6px 40px;
                font-size: 0.8rem;
            }
            .hamburger span {
                width: 22px;
                height: 2.5px;
            }
            .mobile-nav-panel-header {
                padding: 12px 16px;
            }
            .maskot {
                bottom: 12px;
                right: 12px;
            }
            .maskot img {
                width: 40px;
                height: 40px;
            }
        }

        /* --- Desktop (above 1024px) --- */
        @media (min-width: 1025px) {
            .desktop-nav {
                display: flex !important;
            }
            .mobile-nav-toggle {
                display: none !important;
            }
            .mobile-auth {
                display: none !important;
            }
            .navbar-auth-desktop {
                display: flex !important;
            }
        }
    </style>
</head>
<body>
    <!-- ============================================ -->
    <!-- NAVBAR                                       -->
    <!-- ============================================ -->
    <nav class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo SIOMAS -->
                <a href="{{ route('home') }}" class="logo-container">
                    @php
                        $logoPath = public_path('images/logo-siomas.png');
                        $logoUrl = file_exists($logoPath) ? asset('images/logo-siomas.png') : null;
                    @endphp
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="SIOMAS" class="logo-image">
                    @else
                        <div class="w-[65px] h-[65px] bg-blue-600 rounded-xl flex items-center justify-center">
                            <span class="text-3xl font-bold text-white">S</span>
                        </div>
                    @endif
                </a>

                <!-- ============================================ -->
                <!-- DESKTOP NAV                                  -->
                <!-- ============================================ -->
                <div class="desktop-nav hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" 
                       class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} text-gray-700 hover:text-red-600">
                        Beranda
                    </a>
                    <a href="{{ route('produk-hukum.index') }}" 
                       class="nav-link {{ request()->routeIs('produk-hukum.*') ? 'active' : '' }} text-gray-700 hover:text-red-600">
                        Produk Hukum
                    </a>
                    <a href="{{ route('ormas') }}" 
                       class="nav-link {{ request()->routeIs('ormas') ? 'active' : '' }} text-gray-700 hover:text-red-600">
                        Data ORMAS
                    </a>
                    <a href="{{ route('profil-kesbangpol') }}" 
                       class="nav-link {{ request()->routeIs('profil-kesbangpol') ? 'active' : '' }} text-gray-700 hover:text-red-600">
                        Profil Kesbangpol
                    </a>
                    <a href="{{ route('berita.semua') }}" 
                       class="nav-link {{ request()->routeIs('berita.semua') || request()->routeIs('berita.show') ? 'active' : '' }} text-gray-700 hover:text-red-600">
                        Berita
                    </a>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                class="nav-link dropdown-btn {{ request()->routeIs('alur-pelaporan') || request()->routeIs('pelaporan-ormas.create') || request()->routeIs('persyaratan-berbadan-hukum') || request()->routeIs('persyaratan-tidak-berbadan-hukum') ? 'active' : '' }} text-gray-700 hover:text-red-600 flex items-center gap-1">
                            Layanan
                            <i class="fas fa-chevron-down text-xs" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute left-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50 dropdown-menu"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2">
                            
                            <!-- Menu Existing -->
                            <a href="{{ route('alur-pelaporan') }}" 
                               class="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600 transition flex items-center gap-2 {{ request()->routeIs('alur-pelaporan') ? 'text-red-600 bg-red-50' : 'text-gray-700' }}">
                                <i class="fas fa-clipboard-list text-red-500 w-5"></i>
                                Alur Pelaporan ORMAS
                            </a>
                            <a href="{{ route('pelaporan-ormas.create') }}" 
                               class="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600 transition flex items-center gap-2 {{ request()->routeIs('pelaporan-ormas.create') ? 'text-red-600 bg-red-50' : 'text-gray-700' }}">
                                <i class="fas fa-file-alt text-red-500 w-5"></i>
                                Form Pelaporan ORMAS
                            </a>
                            
                            <!-- Divider -->
                            <div class="border-t border-gray-100 my-1"></div>
                            <div class="px-4 py-1 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                                Persyaratan Pendirian ORMAS
                            </div>
                            
                            <!-- Menu Baru 1 -->
                            <a href="{{ route('persyaratan-berbadan-hukum') }}" 
                               class="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600 transition flex items-start gap-2 {{ request()->routeIs('persyaratan-berbadan-hukum') ? 'text-red-600 bg-red-50' : 'text-gray-700' }}">
                                <i class="fas fa-balance-scale text-red-500 w-5 mt-0.5"></i>
                                <div>
                                    <div class="font-medium">Ormas Berbadan Hukum</div>
                                    <div class="text-[10px] text-gray-400 leading-tight">Persyaratan & Prosedur Pengesahan</div>
                                </div>
                            </a>
                            
                            <!-- Menu Baru 2 -->
                            <a href="{{ route('persyaratan-tidak-berbadan-hukum') }}" 
                               class="block px-4 py-2 text-sm hover:bg-red-50 hover:text-red-600 transition flex items-start gap-2 {{ request()->routeIs('persyaratan-tidak-berbadan-hukum') ? 'text-red-600 bg-red-50' : 'text-gray-700' }}">
                                <i class="fas fa-file-signature text-red-500 w-5 mt-0.5"></i>
                                <div>
                                    <div class="font-medium">Ormas Tidak Berbadan Hukum</div>
                                    <div class="text-[10px] text-gray-400 leading-tight">Persyaratan & Prosedur Pendaftaran</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('saran') }}" 
                       class="nav-link {{ request()->routeIs('saran*') ? 'active' : '' }} text-gray-700 hover:text-red-600">
                        Kotak Saran
                    </a>
                </div>

                <!-- ============================================ -->
                <!-- RIGHT SIDE - AUTH (Desktop)                 -->
                <!-- ============================================ -->
                <div class="navbar-auth-desktop hidden md:flex items-center space-x-3">
                    @auth
                        <span class="text-sm text-gray-600 hidden lg:inline font-medium">Halo, {{ Auth::user()->name }}</span>
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>

                <!-- ============================================ -->
                <!-- MOBILE: Hamburger Button + Auth (Mobile)    -->
                <!-- ============================================ -->
                <div class="flex items-center gap-2">
                    <!-- Auth buttons on mobile -->
                    @auth
                    <div class="mobile-auth hidden items-center gap-1">
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" 
                           class="px-2 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs font-medium">
                            <i class="fas fa-user"></i>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-2 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-xs font-medium">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                    @endauth

                    <!-- Hamburger Button -->
                    <button class="hamburger mobile-nav-toggle hidden" id="hamburgerBtn" aria-label="Toggle Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================ -->
    <!-- MOBILE NAV OVERLAY                          -->
    <!-- ============================================ -->
    <div class="mobile-nav-overlay" id="mobileOverlay"></div>

    <!-- ============================================ -->
    <!-- MOBILE NAV PANEL                            -->
    <!-- ============================================ -->
    <div class="mobile-nav-panel" id="mobilePanel">
        <!-- Header -->
        <div class="mobile-nav-panel-header">
            <div class="flex items-center gap-2">
                @php
                    $logoPathMobile = public_path('images/logo-siomas.png');
                    $logoUrlMobile = file_exists($logoPathMobile) ? asset('images/logo-siomas.png') : null;
                @endphp
                @if($logoUrlMobile)
                    <img src="{{ $logoUrlMobile }}" alt="SIOMAS" class="h-8 w-auto">
                @else
                    <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                        <span class="text-lg font-bold text-white">S</span>
                    </div>
                @endif
            </div>
            <button class="mobile-nav-panel-close" id="mobileCloseBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="mobile-nav-panel-body">
            <!-- 1. Beranda -->
            <a href="{{ route('home') }}" 
               class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home text-red-500"></i> Beranda
            </a>
            
            <!-- 2. Produk Hukum -->
            <a href="{{ route('produk-hukum.index') }}" 
               class="mobile-nav-link {{ request()->routeIs('produk-hukum.*') ? 'active' : '' }}">
                <i class="fas fa-gavel text-red-500"></i> Produk Hukum
            </a>
            
            <!-- 3. Data ORMAS -->
            <a href="{{ route('ormas') }}" 
               class="mobile-nav-link {{ request()->routeIs('ormas') ? 'active' : '' }}">
                <i class="fas fa-building text-red-500"></i> Data ORMAS
            </a>
            
            <!-- 4. Profil Kesbangpol -->
            <a href="{{ route('profil-kesbangpol') }}" 
               class="mobile-nav-link {{ request()->routeIs('profil-kesbangpol') ? 'active' : '' }}">
                <i class="fas fa-address-card text-red-500"></i> Profil Kesbangpol
            </a>
            
            <!-- 5. Berita -->
            <a href="{{ route('berita.semua') }}" 
               class="mobile-nav-link {{ request()->routeIs('berita.semua') || request()->routeIs('berita.show') ? 'active' : '' }}">
                <i class="fas fa-newspaper text-red-500"></i> Berita
            </a>
            
            <!-- 6. Layanan (Dropdown) -->
            <div x-data="{ open: {{ request()->routeIs('alur-pelaporan') || request()->routeIs('pelaporan-ormas.create') || request()->routeIs('persyaratan-berbadan-hukum') || request()->routeIs('persyaratan-tidak-berbadan-hukum') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="mobile-dropdown-toggle {{ request()->routeIs('alur-pelaporan') || request()->routeIs('pelaporan-ormas.create') || request()->routeIs('persyaratan-berbadan-hukum') || request()->routeIs('persyaratan-tidak-berbadan-hukum') ? 'active' : '' }}">
                    <span>
                        <i class="fas fa-concierge-bell text-red-500"></i> Layanan
                    </span>
                    <i class="fas fa-chevron-down" :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open" x-collapse>
                    <a href="{{ route('alur-pelaporan') }}" 
                       class="mobile-dropdown-item {{ request()->routeIs('alur-pelaporan') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> Alur Pelaporan ORMAS
                    </a>
                    <a href="{{ route('pelaporan-ormas.create') }}" 
                       class="mobile-dropdown-item {{ request()->routeIs('pelaporan-ormas.create') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Form Pelaporan ORMAS
                    </a>
                    
                    <div class="mobile-nav-divider"></div>
                    <div class="px-4 py-1 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">
                        Persyaratan Pendirian ORMAS
                    </div>
                    
                    <a href="{{ route('persyaratan-berbadan-hukum') }}" 
                       class="mobile-dropdown-item {{ request()->routeIs('persyaratan-berbadan-hukum') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale"></i> Ormas Berbadan Hukum
                    </a>
                    <a href="{{ route('persyaratan-tidak-berbadan-hukum') }}" 
                       class="mobile-dropdown-item {{ request()->routeIs('persyaratan-tidak-berbadan-hukum') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i> Ormas Tidak Berbadan Hukum
                    </a>
                </div>
            </div>
            
            <!-- 7. Kotak Saran -->
            <a href="{{ route('saran') }}" 
               class="mobile-nav-link {{ request()->routeIs('saran*') ? 'active' : '' }}">
                <i class="fas fa-envelope text-red-500"></i> Kotak Saran
            </a>

            <!-- Divider -->
            <div class="mobile-nav-divider"></div>

            <!-- User Info on Mobile -->
            @auth
                <div class="bg-gray-50 rounded-lg p-3 mb-2">
                    <p class="text-sm font-medium text-gray-700">
                        <i class="fas fa-user-circle text-red-500 mr-2"></i>
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Role: {{ ucfirst(Auth::user()->role) }}</p>
                </div>
            @endauth

            <!-- Contact Info -->
            <div class="bg-gray-50 rounded-lg p-3 mt-2">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-phone text-red-500 mr-2"></i> (022) 6654274
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-envelope text-red-500 mr-2"></i> bakesbangpol@cimahikota.go.id
                </p>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MAIN CONTENT                                 -->
    <!-- ============================================ -->
    <main class="mt-16">
        @yield('content')
    </main>

    <!-- ============================================ -->
    <!-- FOOTER                                       -->
    <!-- ============================================ -->
    <footer class="footer-bg text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center md:justify-start mb-6">
                <div class="flex items-center space-x-3">
                    @php
                        $logoPathFooter = public_path('images/logo-siomas1.png');
                        $logoUrlFooter = file_exists($logoPathFooter) ? asset('images/logo-siomas1.png') : null;
                    @endphp
                    @if($logoUrlFooter)
                        <img src="{{ $logoUrlFooter }}" alt="SIOMAS" class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                            <span class="text-lg font-bold text-white">S</span>
                        </div>
                    @endif
                    <div>
                        <div class="text-xl font-bold text-white">SIOMAS</div>
                        <div class="text-xs text-gray-300">Kota Cimahi</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="footer-info-item text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-1">
                        <i class="fas fa-map-marker-alt text-red-400"></i>
                        <span class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Alamat</span>
                    </div>
                    <p class="text-gray-200 text-sm leading-relaxed">
                        Jl. Raden Demang Hardjakusumah,<br>Gedung C Lt. 1, Cibabat,<br>Cimahi Utara, Kota Cimahi 40513
                    </p>
                </div>

                <div class="footer-info-item text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-1">
                        <i class="fas fa-envelope text-red-400"></i>
                        <span class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Email</span>
                    </div>
                    <p class="text-gray-200 text-sm">
                        bakesbangpol@cimahikota.go.id
                    </p>
                </div>

                <div class="footer-info-item text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-1">
                        <i class="fas fa-phone text-red-400"></i>
                        <span class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Nomor Telepon</span>
                    </div>
                    <p class="text-gray-200 text-sm">
                        (022) 6654274
                    </p>
                </div>

                <div class="footer-info-item text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-1">
                        <i class="fab fa-instagram text-red-400"></i>
                        <span class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Sosial Media</span>
                    </div>
                    <a href="https://www.instagram.com/bakesbangpol.cimahi?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" 
                       target="_blank" 
                       class="text-gray-200 text-sm hover:text-red-400 transition">
                        @bakesbangpol.cimahi
                    </a>
                </div>
            </div>

            <div class="border-t border-white/10 mb-5"></div>

            <div>
                <h3 class="text-base font-bold mb-3 flex items-center gap-2 text-white">
                    <i class="fas fa-phone-alt text-red-400"></i>
                    No TLP Penting
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-3 gap-y-1 max-w-3xl">
                    <div class="footer-phone-item flex items-start gap-2 p-1.5 rounded">
                        <i class="fas fa-phone text-red-400 mt-0.5 text-xs"></i>
                        <div>
                            <span class="text-white font-medium text-sm">112</span>
                            <span class="text-gray-300 text-xs block">Cimahi Campernik Layanan Darurat</span>
                        </div>
                    </div>
                    <div class="footer-phone-item flex items-start gap-2 p-1.5 rounded">
                        <i class="fas fa-phone text-red-400 mt-0.5 text-xs"></i>
                        <div>
                            <span class="text-white font-medium text-sm">(022) 6652095</span>
                            <span class="text-gray-300 text-xs block">Polres Cimahi</span>
                        </div>
                    </div>
                    <div class="footer-phone-item flex items-start gap-2 p-1.5 rounded">
                        <i class="fas fa-phone text-red-400 mt-0.5 text-xs"></i>
                        <div>
                            <span class="text-white font-medium text-sm">(022) 6652025</span>
                            <span class="text-gray-300 text-xs block">RSUD Cibabat</span>
                        </div>
                    </div>
                    <div class="footer-phone-item flex items-start gap-2 p-1.5 rounded">
                        <i class="fas fa-phone text-red-400 mt-0.5 text-xs"></i>
                        <div>
                            <span class="text-white font-medium text-sm">(022) 6658113</span>
                            <span class="text-gray-300 text-xs block">Damkar Cimahi</span>
                        </div>
                    </div>
                    <div class="footer-phone-item flex items-start gap-2 p-1.5 rounded">
                        <i class="fas fa-phone text-red-400 mt-0.5 text-xs"></i>
                        <div>
                            <span class="text-white font-medium text-sm">(022) 6045725</span>
                            <span class="text-gray-300 text-xs block">PLN Cimahi</span>
                        </div>
                    </div>
                    <div class="footer-phone-item flex items-start gap-2 p-1.5 rounded">
                        <i class="fas fa-phone text-red-400 mt-0.5 text-xs"></i>
                        <div>
                            <span class="text-white font-medium text-sm">(022) 20660899</span>
                            <span class="text-gray-300 text-xs block">BPBD Cimahi</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 mt-5 pt-4 text-center text-gray-400 text-xs">
                &copy; {{ date('Y') }} SIOMAS - Kota Cimahi. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- ============================================ -->
    <!-- MASKOT & POPUP                              -->
    <!-- ============================================ -->
    <div class="maskot" onclick="togglePopup()">
        @php
            $maskotPath = public_path('images/maskot.png');
            $maskotUrl = file_exists($maskotPath) ? asset('images/maskot.png') : null;
        @endphp
        @if($maskotUrl)
            <img src="{{ $maskotUrl }}" 
                 alt="Maskot" 
                 class="w-20 h-20 hover:scale-110 transition duration-300 object-contain drop-shadow-lg">
        @else
            <div class="w-14 h-14 bg-red-600 rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 transition cursor-pointer">
                <i class="fas fa-phone text-white text-2xl"></i>
            </div>
        @endif
    </div>

    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-content">
            <span class="popup-close" onclick="togglePopup()">&times;</span>
            <div class="text-center">
                @php
                    $popupLogoPath = public_path('images/logo-siomas.png');
                    $popupLogoUrl = file_exists($popupLogoPath) ? asset('images/logo-siomas.png') : null;
                @endphp
                @if($popupLogoUrl)
                    <img src="{{ $popupLogoUrl }}" alt="SIOMAS" class="w-32 h-32 object-contain mx-auto mb-4">
                @else
                    <div class="w-32 h-32 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-4xl text-red-600"></i>
                    </div>
                @endif
                <h3 class="text-xl font-bold text-gray-800 mb-2">Hubungi Kami</h3>
                <p class="text-gray-600 mb-4">Ada pertanyaan? Silakan hubungi kami melalui:</p>
                <div class="space-y-3 text-left">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-red-600 w-5"></i>
                        <span class="text-gray-700">(022) 6654274</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-red-600 w-5"></i>
                        <span class="text-gray-700">bakesbangpol@cimahikota.go.id</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-red-600 w-5"></i>
                        <span class="text-gray-700">Jl. Raden Demang Hardjakusumah, Gedung C Lt. 1 Cibabat, Cimahi Utara, Kota Cimahi 40513</span>
                    </div>
                </div>
                <button onclick="togglePopup()" class="mt-6 px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- SCRIPTS                                     -->
    <!-- ============================================ -->
    <script>
        function togglePopup() {
            var overlay = document.getElementById('popupOverlay');
            overlay.classList.toggle('active');
        }

        document.getElementById('popupOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                togglePopup();
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // ============================================
        // MOBILE NAV TOGGLE
        // ============================================
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const mobilePanel = document.getElementById('mobilePanel');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mobileCloseBtn = document.getElementById('mobileCloseBtn');

        function openMobileNav() {
            mobilePanel.classList.add('active');
            mobileOverlay.classList.add('active');
            hamburgerBtn.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileNav() {
            mobilePanel.classList.remove('active');
            mobileOverlay.classList.remove('active');
            hamburgerBtn.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', function() {
                if (mobilePanel.classList.contains('active')) {
                    closeMobileNav();
                } else {
                    openMobileNav();
                }
            });
        }

        if (mobileCloseBtn) {
            mobileCloseBtn.addEventListener('click', closeMobileNav);
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeMobileNav);
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobilePanel && mobilePanel.classList.contains('active')) {
                closeMobileNav();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>