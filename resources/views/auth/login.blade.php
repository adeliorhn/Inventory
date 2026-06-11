@php
    $theme = request()->cookie('theme', 'light');
    if (!in_array($theme, ['light', 'dark'])) {
        $theme = 'light';
    }
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="{{ $theme }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Adelio Inventory</title>
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <style>
        /* Modern Glassmorphic Login Styles */
        body.login-page {
            display: grid;
            place-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f4f7f6 0%, #d8e0dd 100%);
            margin: 0;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(23, 32, 29, 0.08);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 48px rgba(23, 32, 29, 0.12);
        }

        .login-header {
            margin-bottom: 32px;
        }

        .login-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            background: var(--primary);
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            border-radius: 12px;
            margin-bottom: 16px;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.2);
        }

        .login-header h1 {
            font-size: 24px;
            margin: 0 0 8px;
            color: var(--text);
            font-weight: 800;
        }

        .login-header p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .btn-google {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            min-height: 48px;
            background: #ffffff;
            border: 1px solid #dadce0;
            border-radius: 8px;
            color: #3c4043;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.25s, box-shadow 0.25s, border-color 0.25s;
        }

        .btn-google:hover {
            background-color: #f8f9fa;
            border-color: #d2e3fc;
            box-shadow: 0 1px 3px 0 rgba(60,64,67,0.3), 0 4px 8px 3px rgba(60,64,67,0.15);
        }

        .btn-google:active {
            background-color: #eeeeee;
        }

        .btn-google svg {
            width: 20px;
            height: 20px;
        }

        .login-footer {
            margin-top: 32px;
            font-size: 12px;
            color: var(--muted);
        }

        .error-alert {
            margin-bottom: 20px;
            padding: 12px 14px;
            border-radius: 8px;
            background: var(--danger-soft);
            border: 1px solid #fca5a5;
            color: var(--danger);
            font-size: 13px;
            text-align: left;
        }

        /* Floating Theme Toggle on Login Page */
        .theme-toggle-container {
            position: absolute;
            top: 24px;
            right: 24px;
            z-index: 50;
        }

        .theme-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: var(--text);
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(23, 32, 29, 0.05);
            transition: all 0.3s ease;
        }

        .theme-toggle-btn:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 6px 16px rgba(23, 32, 29, 0.1);
        }

        [data-theme="dark"] .theme-toggle-btn {
            background: rgba(30, 41, 59, 0.85);
            border-color: rgba(255, 255, 255, 0.1);
            color: #f8fafc;
        }

        [data-theme="dark"] .theme-toggle-btn:hover {
            background: rgba(30, 41, 59, 0.95);
        }

        /* Show/hide icons based on active theme */
        .theme-icon {
            display: none;
        }
        [data-theme="dark"] .sun-icon {
            display: block;
        }
        [data-theme="dark"] .moon-icon {
            display: none;
        }
        [data-theme="light"] .sun-icon {
            display: none;
        }
        [data-theme="light"] .moon-icon {
            display: block;
        }

        /* Theme variations for Login Page */
        [data-theme="dark"] .login-page {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        [data-theme="dark"] .login-card {
            background: rgba(30, 41, 59, 0.85);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        [data-theme="dark"] .btn-google {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }

        [data-theme="dark"] .btn-google:hover {
            background-color: #334155;
            border-color: #475569;
        }
    </style>
</head>
<body class="login-page" data-theme="{{ $theme }}">

    <div class="theme-toggle-container">
        <button id="theme-toggle" class="theme-toggle-btn" aria-label="Ubah Tema">
            <!-- Moon Icon -->
            <svg class="theme-icon moon-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
            <!-- Sun Icon -->
            <svg class="theme-icon sun-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
    </div>

    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">AI</div>
            <h1>Adelio Inventory</h1>
            <p>Kontrol barang operasional & mutasi stok</p>
        </div>

        @if ($errors->any())
            <div class="error-alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="flash" style="margin-bottom: 20px; padding: 12px; border-radius: 8px; text-align: left; font-size: 13px;">
                {{ session('status') }}
            </div>
        @endif

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg viewBox="0 0 24 24" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
            </svg>
            <span>Masuk dengan Google</span>
        </a>

        <div class="login-footer">
            &copy; {{ date('Y') }} Adelio Inventory. Semua Hak Dilindungi.
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        toggleBtn.addEventListener('click', () => {
            const htmlElement = document.documentElement;
            const currentTheme = htmlElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            htmlElement.setAttribute('data-theme', newTheme);
            document.body.setAttribute('data-theme', newTheme);
            document.cookie = "theme=" + newTheme + "; path=/; max-age=" + (60 * 60 * 24 * 365);
        });
    </script>
</body>
</html>
