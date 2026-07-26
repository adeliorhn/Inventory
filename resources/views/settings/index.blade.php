@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi - StockFlow')

@section('content')
<!-- Header Page -->
<div class="dashboard-header">
    <div class="header-titles">
        <h1>Pengaturan dan Profil Admin</h1>
        <p>Atur akun admin terdaftar, preferensi tampilan tema, dan batas ambang notifikasi sistem</p>
    </div>
</div>

<div class="grid-2-col">
    <!-- Profil Admin Card -->
    <div class="card-panel">
        <div class="panel-header">
            <h3>Informasi Profil Admin</h3>
        </div>
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
            @if ($user && $user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover;">
            @else
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #2563eb; color: #fff; font-size: 24px; font-weight: 800; display: flex; align-items: center; justify-content: center;">
                    {{ substr($user ? $user->name : 'A', 0, 1) }}
                </div>
            @endif
            <div>
                <strong style="font-size: 18px; display: block;">{{ $user ? $user->name : 'Administrator' }}</strong>
                <span class="text-muted" style="font-size: 13px;">{{ $user ? $user->email : 'admin@stockflow.com' }}</span>
                <div style="margin-top: 6px;">
                    <span class="pill-badge pill-success">Super Admin</span>
                </div>
            </div>
        </div>

        <form class="modal-body-form" style="padding: 0;">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="{{ $user ? $user->name : 'Admin' }}" readonly>
            </div>
            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" value="{{ $user ? $user->email : 'admin@stockflow.com' }}" readonly>
            </div>
            <div class="form-group">
                <label>Metode Login</label>
                <input type="text" value="{{ $user && $user->google_id ? 'Google OAuth 2.0' : 'Email & Password' }}" readonly>
            </div>
        </form>
    </div>

    <!-- System Preferences Card -->
    <div class="card-panel">
        <div class="panel-header">
            <h3>Preferensi Sistem dan Tema</h3>
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                <div>
                    <strong style="display: block; font-size: 14px;">Mode Tampilan (Theme)</strong>
                    <small class="text-muted">Pilih mode tampilan Terang (Light) atau Gelap (Dark)</small>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('theme-toggle').click();">
                    Ubah Tema
                </button>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                <div>
                    <strong style="display: block; font-size: 14px;">Cloud Storage Engine</strong>
                    <small class="text-muted">Penyimpanan media terintegrasi</small>
                </div>
                <span class="pill-badge pill-success">MinIO / Cloudinary</span>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <strong style="display: block; font-size: 14px;">Versi Aplikasi StockFlow</strong>
                    <small class="text-muted">Build SaaS Edition 2026.07</small>
                </div>
                <code class="sku-tag">v2.4.0-release</code>
            </div>
        </div>
    </div>
</div>
@endsection
