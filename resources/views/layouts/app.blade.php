<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Adelio Inventory')</title>
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <script defer src="{{ asset('js/inventory.js') }}"></script>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Adelio Inventory">
            <span class="brand-mark">AI</span>
            <span>
                <strong>Adelio Inventory</strong>
                <small>Kontrol barang operasional</small>
            </span>
        </a>

        <nav class="nav" aria-label="Navigasi utama">
            <a @class(['nav-link', 'is-active' => request()->routeIs('dashboard')]) href="{{ route('dashboard') }}">Pencatatan</a>
            <a @class(['nav-link', 'is-active' => request()->routeIs('reports.*')]) href="{{ route('reports.index') }}">Laporan</a>
        </nav>
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
