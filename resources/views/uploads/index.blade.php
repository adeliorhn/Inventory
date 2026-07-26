@extends('layouts.app')

@section('title', 'Media Manager dan Upload Gambar - StockFlow')

@section('content')
<!-- Header Page -->
<div class="dashboard-header">
    <div class="header-titles">
        <h1>Upload Gambar dan Media (MinIO / Cloudinary)</h1>
        <p>Kelola file gambar dan video produk yang disimpan secara aman di cloud storage MinIO / Cloudinary</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <span>+ Unggah Media via Produk</span>
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="metrics-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 24px;">
    <div class="metric-card">
        <span class="metric-label">Total Produk Ber-Media</span>
        <div class="metric-value text-blue">{{ number_format($totalMediaCount) }}</div>
        <small class="text-muted">Memiliki foto/video</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Status Cloud Storage</span>
        <div class="metric-value text-success" style="font-size: 22px; display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Terkoneksi (Active)
        </div>
        <small class="text-muted">MinIO / Cloudinary Engine Active</small>
    </div>
</div>

<!-- Gallery Grid -->
<div class="card-panel">
    <div class="panel-header">
        <h3>Galeri Media Produk Inventory</h3>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
        @forelse ($itemsWithMedia as $item)
            <div style="background: var(--bg-app); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;">
                <div style="height: 160px; width: 100%; background: #0f172a; position: relative;">
                    @if ($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #fff; font-weight: 800;">No Image</div>
                    @endif
                </div>
                <div style="padding: 14px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <strong style="font-size: 14px; display: block; margin-bottom: 4px;">{{ $item->name }}</strong>
                        <code class="sku-tag">{{ $item->sku }}</code>
                    </div>
                    <div style="margin-top: 12px; display: flex; gap: 8px;">
                        @if ($item->image_url)
                            <a href="{{ $item->image_url }}" target="_blank" class="btn btn-xs btn-outline-primary" style="flex: 1; text-align: center;">Lihat HD &nearr;</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state-card" style="grid-column: 1 / -1;">
                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
                <p>Belum ada media produk yang diunggah ke storage.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
