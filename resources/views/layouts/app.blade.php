@php
    $theme = auth()->check() ? auth()->user()->theme : request()->cookie('theme', 'light');
    if (!in_array($theme, ['light', 'dark'])) {
        $theme = 'light';
    }
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="{{ $theme }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Inventory - StockFlow')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="{{ asset('js/inventory.js') }}"></script>
</head>
<body class="app-body">
    <div class="app-layout" id="app-layout">
        <!-- Collapsible Left Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a class="sidebar-brand" href="{{ route('dashboard') }}" aria-label="StockFlow">
                    <span class="brand-badge">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                            <path d="m3.3 7 8.7 5 8.7-5"/>
                            <path d="M12 22V12"/>
                        </svg>
                    </span>
                    <span class="brand-text">StockFlow</span>
                </a>
                <button id="sidebar-toggle" class="sidebar-toggle-btn" aria-label="Collapse Sidebar" title="Ciutkan Sidebar">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-label">UTAMA</div>
                
                <!-- Dashboard -->
                <a @class(['nav-item', 'active' => request()->routeIs('dashboard')]) href="{{ route('dashboard') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Produk -->
                <a @class(['nav-item', 'active' => request()->routeIs('products.*')]) href="{{ route('products.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                    <span>Produk</span>
                </a>

                <!-- Stok -->
                <a @class(['nav-item', 'active' => request()->routeIs('stocks.*')]) href="{{ route('stocks.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span>Stok</span>
                </a>

                <!-- Riwayat Stok -->
                <a @class(['nav-item', 'active' => request()->routeIs('stock-history.*')]) href="{{ route('stock-history.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        <polyline points="17 6 23 6 23 12"/>
                    </svg>
                    <span>Riwayat Stok</span>
                </a>

                <!-- Kategori -->
                <a @class(['nav-item', 'active' => request()->routeIs('categories.*')]) href="{{ route('categories.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                        <line x1="4" y1="22" x2="4" y2="15"/>
                    </svg>
                    <span>Kategori</span>
                </a>

                <!-- Upload Gambar -->
                <a @class(['nav-item', 'active' => request()->routeIs('uploads.*')]) href="{{ route('uploads.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    <span>Upload Gambar</span>
                </a>

                <div class="nav-section-label">MANAJEMEN</div>

                <!-- Laporan -->
                <a @class(['nav-item', 'active' => request()->routeIs('reports.*')]) href="{{ route('reports.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    <span>Laporan</span>
                </a>

                <!-- Pengaturan -->
                <a @class(['nav-item', 'active' => request()->routeIs('settings.*')]) href="{{ route('settings.index') }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span>Pengaturan</span>
                </a>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
                    @csrf
                    <button type="submit" class="nav-item logout-btn" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-card">
                    @if (auth()->check() && auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="admin-avatar">
                    @else
                        <div class="admin-avatar-placeholder">{{ substr(auth()->check() ? auth()->user()->name : 'A', 0, 1) }}</div>
                    @endif
                    <div class="admin-info">
                        <strong>{{ auth()->check() ? auth()->user()->name : 'Admin StockFlow' }}</strong>
                        <small>{{ auth()->check() ? auth()->user()->email : 'admin@stockflow.com' }}</small>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Viewport Area -->
        <div class="main-wrapper">
            <!-- Top Navigation Bar Header -->
            <header class="main-topbar">
                <div class="topbar-search">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" placeholder="Cari produk, SKU, atau barang..." id="global-search-input">
                    <span class="search-shortcut">⌘F</span>
                </div>

                <div class="topbar-actions">
                    <a href="{{ route('settings.index') }}" class="topbar-icon-btn" title="Notifikasi / Pengaturan" aria-label="Notifikasi">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span class="badge-dot"></span>
                    </a>

                    <button id="theme-toggle" class="topbar-icon-btn" title="Ubah Tema" aria-label="Ubah Tema">
                        <!-- Moon Icon -->
                        <svg class="theme-icon moon-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <!-- Sun Icon -->
                        <svg class="theme-icon sun-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </button>

                    <div class="topbar-user">
                        @if (auth()->check() && auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="topbar-avatar">
                        @else
                            <div class="topbar-avatar-placeholder">{{ substr(auth()->check() ? auth()->user()->name : 'A', 0, 1) }}</div>
                        @endif
                        <div class="topbar-user-details">
                            <span class="user-display-name">{{ auth()->check() ? auth()->user()->name : 'Admin StockFlow' }}</span>
                            <small class="user-role">Administrator</small>
                        </div>
                    </div>
                </div>
            </header>

            <main class="content-container">
                @if (session('status'))
                    <div class="alert-banner alert-success">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-banner alert-error">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <div>
                            <strong>Periksa kesalahan berikut:</strong>
                            <ul style="margin: 4px 0 0 16px; padding: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Collapsible Sidebar Script
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const appLayout = document.getElementById('app-layout');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                appLayout.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', appLayout.classList.contains('sidebar-collapsed'));
            });

            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                appLayout.classList.add('sidebar-collapsed');
            }
        }
    </script>
</body>
</html>
