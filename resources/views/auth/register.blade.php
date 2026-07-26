@php
    $theme = request()->cookie('theme', 'dark');
    if (!in_array($theme, ['light', 'dark'])) {
        $theme = 'dark';
    }
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="{{ $theme }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - StockFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body.auth-split-page {
            margin: 0;
            padding: 0;
            height: 100vh;
            max-height: 100vh;
            background-color: #06090e;
            color: #f8fafc;
            display: flex;
            overflow: hidden;
        }

        .auth-container {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            width: 100vw;
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
        }

        /* Left Hero Section */
        .hero-section {
            background: radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.14) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 40%),
                        #080c14;
            padding: 32px 44px 28px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            position: relative;
            height: 100%;
            overflow: hidden;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            color: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
            flex-shrink: 0;
        }

        .brand-icon svg {
            width: 22px;
            height: 22px;
            color: #ffffff;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .hero-content {
            margin-top: 18px;
            max-width: 560px;
        }

        .hero-title {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.8px;
            color: #ffffff;
            margin: 0 0 12px 0;
        }

        .hero-title .highlight {
            color: #38bdf8;
        }

        .hero-subtitle {
            font-size: 15px;
            line-height: 1.75;
            color: #94a3b8;
            margin: 0 0 16px 0;
        }

        .dashboard-preview-card {
            width: 100%;
            flex: 1;
            min-height: 0;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            margin-top: 8px;
        }

        .dashboard-preview-card img {
            width: 100%;
            height: 100%;
            max-height: 100%;
            display: block;
            object-fit: contain;
        }

        /* Right Form Section */
        .form-section {
            background-color: #04060a;
            padding: 24px 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .form-wrapper {
            width: 100%;
            max-width: 380px;
        }

        .form-header {
            margin-bottom: 16px;
        }

        .form-header h2 {
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }

        .form-header p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
            line-height: 1.3;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
        }

        .form-group input {
            width: 100%;
            padding: 9px 12px;
            background: #0d121d;
            border: 1px solid #1e293b;
            border-radius: 8px;
            color: #ffffff;
            font-size: 13px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-group input::placeholder {
            color: #334155;
        }

        .form-group input:focus {
            border-color: #38bdf8;
            background: #111827;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        .btn-primary {
            width: 100%;
            padding: 11px;
            background: #ffffff;
            color: #090d14;
            font-size: 14px;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
            margin-top: 4px;
        }

        .btn-primary:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.18);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 12px 0;
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #1e293b;
        }

        .divider span {
            padding: 0 12px;
        }

        .btn-google {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 10px;
            background: #0d121d;
            border: 1px solid #1e293b;
            border-radius: 8px;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-google:hover {
            background: #161e2e;
            border-color: #334155;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-google svg {
            width: 16px;
            height: 16px;
        }

        .form-footer {
            margin-top: 14px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }

        .form-footer a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .error-alert {
            margin-bottom: 12px;
            padding: 8px 12px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            font-size: 12px;
            line-height: 1.3;
        }

        /* Floating Theme Toggle */
        .theme-toggle-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #0d121d;
            border: 1px solid #1e293b;
            color: #94a3b8;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle-btn:hover {
            color: #ffffff;
            border-color: #334155;
        }

        /* Light Theme Overrides if user toggles */
        [data-theme="light"] body.auth-split-page {
            background-color: #f8fafc;
            color: #0f172a;
        }

        [data-theme="light"] .hero-section {
            background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.08) 0%, transparent 40%),
                        #0f172a;
        }

        [data-theme="light"] .form-section {
            background-color: #ffffff;
        }

        [data-theme="light"] .form-header h2 {
            color: #0f172a;
        }

        [data-theme="light"] .form-group input {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        [data-theme="light"] .form-group input::placeholder {
            color: #94a3b8;
        }

        [data-theme="light"] .btn-primary {
            background: #0f172a;
            color: #ffffff;
        }

        [data-theme="light"] .btn-primary:hover {
            background: #1e293b;
        }

        [data-theme="light"] .btn-google {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        [data-theme="light"] .btn-google:hover {
            background: #f8fafc;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991px) {
            html, body {
                overflow: auto;
            }
            body.auth-split-page {
                height: auto;
                max-height: none;
                overflow: auto;
            }
            .auth-container {
                grid-template-columns: 1fr;
                height: auto;
                max-height: none;
                overflow: auto;
            }
            .hero-section {
                display: none;
            }
            .form-section {
                min-height: 100vh;
                padding: 32px 24px;
                overflow: auto;
            }
        }
    </style>
</head>
<body class="auth-split-page" data-theme="{{ $theme }}">

    <div class="auth-container">
        <!-- Left Hero Column -->
        <div class="hero-section">
            <div>
                <a href="#" class="brand-logo">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                            <path d="m3.3 7 8.7 5 8.7-5"/>
                            <path d="M12 22V12"/>
                        </svg>
                    </div>
                    <span class="brand-name">StockFlow</span>
                </a>

                <div class="hero-content">
                    <h1 class="hero-title">
                        StockFlow <span class="highlight">Stok & Operasional</span><br>
                        Kelola Bisnis Anda.
                    </h1>
                    <p class="hero-subtitle">
                        Kelola inventori barang, pantau status mutasi stok secara real-time, dan bangun efisiensi kerja tim operasional Anda melalui dashboard terpadu.
                    </p>
                </div>
            </div>

            <div class="dashboard-preview-card">
                <img src="{{ asset('images/dashboard_preview.png') }}" alt="StockFlow Dashboard Preview">
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="form-section">
            <button id="theme-toggle" class="theme-toggle-btn" aria-label="Ubah Tema">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>

            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Buat Akun</h2>
                    <p>Daftarkan akun baru Anda untuk mulai mengelola inventori.</p>
                </div>

                @if ($errors->any())
                    <div class="error-alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="auth-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama Anda">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="******">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="******">
                    </div>

                    <button type="submit" class="btn-primary">
                        Daftar Akun
                    </button>
                </form>

                <div class="divider">
                    <span>ATAU LANJUT DENGAN</span>
                </div>

                <a href="{{ route('auth.google') }}" class="btn-google">
                    <svg viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                    </svg>
                    <span>Google</span>
                </a>

                <div class="form-footer">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        toggleBtn.addEventListener('click', () => {
            const htmlElement = document.documentElement;
            const currentTheme = htmlElement.getAttribute('data-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            htmlElement.setAttribute('data-theme', newTheme);
            document.body.setAttribute('data-theme', newTheme);
            document.cookie = "theme=" + newTheme + "; path=/; max-age=" + (60 * 60 * 24 * 365);
        });
    </script>
</body>
</html>
