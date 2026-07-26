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
    <title>@yield('title', 'StockFlow')</title>
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <script defer src="{{ asset('js/inventory.js') }}"></script>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="StockFlow">
            <span class="brand-mark">SF</span>
            <span>
                <strong>StockFlow</strong>
                <small>Kontrol barang operasional</small>
            </span>
        </a>

        <nav class="nav" aria-label="Navigasi utama">
            <a @class(['nav-link', 'is-active' => request()->routeIs('dashboard')]) href="{{ route('dashboard') }}">Pencatatan</a>
            <a @class(['nav-link', 'is-active' => request()->routeIs('reports.*')]) href="{{ route('reports.index') }}">Laporan</a>
        </nav>

        @auth
            <div class="user-menu">
                <div class="user-profile">
                    @if (auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                    @else
                        <div class="user-avatar-placeholder">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    @endif
                    <span class="user-name">{{ auth()->user()->name }}</span>
                </div>
                <button id="theme-toggle" class="button button-secondary button-small theme-toggle-btn" aria-label="Ubah Tema" style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; padding: 0; min-height: 34px;">
                    <!-- Moon Icon -->
                    <svg class="theme-icon moon-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                    <!-- Sun Icon -->
                    <svg class="theme-icon sun-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="button button-secondary button-small">Logout</button>
                </form>
            </div>
        @endauth
    </header>

    <main class="page">
        @if (session('status'))
            <div class="flash" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="error-box" role="alert">
                <strong>Periksa input:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
