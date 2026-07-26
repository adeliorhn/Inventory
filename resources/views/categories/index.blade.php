@extends('layouts.app')

@section('title', 'Manajemen Kategori - StockFlow')

@section('content')
<!-- Header Page -->
<div class="dashboard-header">
    <div class="header-titles">
        <h1>Kategori Produk</h1>
        <p>Kelola pengelompokan kategori produk inventori dan pantau distribusi stok per kategori</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <span>+ Kelola Produk per Kategori</span>
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="metrics-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
    <div class="metric-card">
        <span class="metric-label">Total Kategori Unik</span>
        <div class="metric-value text-purple">{{ number_format($totalCategoriesCount) }}</div>
        <small class="text-muted">Kategori aktif</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Total Produk Terkategori</span>
        <div class="metric-value text-blue">{{ number_format($totalItemsCount - $uncategorizedCount) }}</div>
        <small class="text-muted">Item terkategori</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Produk Tanpa Kategori</span>
        <div class="metric-value text-warning">{{ number_format($uncategorizedCount) }}</div>
        <small class="text-muted">Perlu dikategorikan</small>
    </div>
</div>

<!-- Category Grid -->
<div class="card-panel">
    <div class="panel-header">
        <h3>Daftar Kategori Produk</h3>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        @forelse ($categories as $cat)
            <div style="background: var(--bg-app); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; transition: transform 0.2s;" class="category-card">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <span class="category-chip" style="font-size: 13px; padding: 6px 12px;">{{ $cat->category }}</span>
                    <a href="{{ route('products.index', ['category' => $cat->category]) }}" class="btn btn-xs btn-outline-primary">Lihat Produk &rarr;</a>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px;">
                    <div>
                        <span class="text-muted" style="font-size: 12px; display: block;">Jumlah Produk</span>
                        <strong style="font-size: 18px; color: var(--text-main);">{{ number_format($cat->total_items) }} Item</strong>
                    </div>
                    <div>
                        <span class="text-muted" style="font-size: 12px; display: block;">Total Stok</span>
                        <strong style="font-size: 18px; color: var(--primary-blue);">{{ number_format($cat->total_stock) }} Unit</strong>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state-card" style="grid-column: 1 / -1;">
                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
                <p>Belum ada kategori produk tercatat. Tambahkan kategori saat mengisi data produk.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
