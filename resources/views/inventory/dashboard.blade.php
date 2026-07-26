@extends('layouts.app')

@section('title', 'Dashboard Inventory - StockFlow')

@section('content')
@php
    $movementLabels = ['in' => 'Masuk', 'out' => 'Keluar', 'adjustment' => 'Penyesuaian'];
@endphp

<!-- Main Dashboard Header -->
<div class="dashboard-header">
    <div class="header-titles">
        <h1>Dashboard Inventory</h1>
        <p>Kelola seluruh produk, stok, dan aktivitas inventori secara real-time</p>
    </div>
    <div class="header-actions">
        <button type="button" class="btn btn-primary" onclick="openModal('modal-add-product')">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Tambah Produk</span>
        </button>
        <button type="button" class="btn btn-secondary" onclick="openModal('modal-stock-movement')">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            <span>Catat Mutasi</span>
        </button>
    </div>
</div>

<!-- 4 Metric Cards Section -->
<div class="metrics-grid">
    <!-- Total Produk (Highlighted Hero Card) -->
    <div class="metric-card metric-hero">
        <div class="metric-card-top">
            <span class="metric-label">Total Produk</span>
            <div class="metric-icon-badge">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7M17 7H7M17 7V17"/></svg>
            </div>
        </div>
        <div class="metric-value">{{ number_format($summary['items']) }}</div>
        <div class="metric-footer">
            <span class="pill-badge pill-success">
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"/></svg>
                Tersedia di sistem
            </span>
        </div>
    </div>

    <!-- Total Stok Unit -->
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-label">Total Stok Unit</span>
            <div class="metric-icon-badge text-blue">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
        </div>
        <div class="metric-value">{{ number_format($summary['totalStock']) }}</div>
        <div class="metric-footer">
            <span class="metric-subtext">Akumulasi seluruh produk</span>
        </div>
    </div>

    <!-- Stok Hampir Habis -->
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-label">Stok Hampir Habis</span>
            <div class="metric-icon-badge text-warning">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
        </div>
        <div class="metric-value">{{ number_format($summary['lowStock']) }}</div>
        <div class="metric-footer">
            <span class="pill-badge {{ $summary['lowStock'] > 0 ? 'pill-warning' : 'pill-neutral' }}">
                {{ $summary['lowStock'] > 0 ? 'Perlu Restok Segera' : 'Stok Aman' }}
            </span>
        </div>
    </div>

    <!-- Perubahan Stok Hari Ini -->
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-label">Perubahan Hari Ini</span>
            <div class="metric-icon-badge text-purple">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
        </div>
        <div class="metric-value">{{ number_format($summary['todayMovements']) }}</div>
        <div class="metric-footer">
            <span class="metric-subtext">Aktivitas mutasi hari ini</span>
        </div>
    </div>
</div>

<!-- Quick Actions Panel -->
<div class="card-panel quick-actions-panel" id="quick-actions">
    <div class="panel-header-simple">
        <h3>Aksi Cepat Admin</h3>
        <span class="sub-label">Akses pintas pengelolaan data produk dan stok</span>
    </div>
    <div class="quick-buttons-grid">
        <button type="button" class="quick-btn" onclick="openModal('modal-add-product')">
            <div class="quick-btn-icon bg-blue">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <div class="quick-btn-text">
                <strong>Tambah Produk</strong>
                <small>Buat produk baru</small>
            </div>
        </button>

        <button type="button" class="quick-btn" onclick="openModal('modal-stock-movement')">
            <div class="quick-btn-icon bg-green">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <div class="quick-btn-text">
                <strong>Catat Mutasi Stok</strong>
                <small>Stock In / Stock Out</small>
            </div>
        </button>

        <button type="button" class="quick-btn" onclick="openModal('modal-add-product')">
            <div class="quick-btn-icon bg-purple">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <div class="quick-btn-text">
                <strong>Upload Foto MinIO</strong>
                <small>Media produk Cloud</small>
            </div>
        </button>

        <a href="#mutasi-terbaru" class="quick-btn">
            <div class="quick-btn-icon bg-amber">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="quick-btn-text">
                <strong>Riwayat Stok</strong>
                <small>Log aktivitas mutasi</small>
            </div>
        </a>

        <a href="{{ route('reports.index') }}" class="quick-btn">
            <div class="quick-btn-icon bg-cyan">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <div class="quick-btn-text">
                <strong>Laporan & Export</strong>
                <small>Cetak PDF & Laporan</small>
            </div>
        </a>
    </div>
</div>

<!-- Middle Section: Chart & Activity Feed Grid -->
<div class="grid-2-col">
    <!-- Perubahan Stok 7 Hari Terakhir (Bar Chart) -->
    <div class="card-panel">
        <div class="panel-header">
            <div>
                <h3>Perubahan Stok (7 Hari Terakhir)</h3>
                <span class="sub-label">Grafik perbandingan Barang Masuk & Barang Keluar</span>
            </div>
            <div class="chart-legend">
                <span class="legend-item"><span class="legend-dot dot-in"></span> Stock In</span>
                <span class="legend-item"><span class="legend-dot dot-out"></span> Stock Out</span>
            </div>
        </div>
        <div class="chart-container" style="position: relative; height: 260px;">
            <canvas id="stockTrendChart"></canvas>
        </div>
    </div>

    <!-- Panel Aktivitas Terbaru (Timeline Log) -->
    <div class="card-panel" id="mutasi-terbaru">
        <div class="panel-header">
            <div>
                <h3>Aktivitas Terbaru</h3>
                <span class="sub-label">Riwayat mutasi & aktivitas admin internal</span>
            </div>
        </div>
        <div class="activity-feed">
            @forelse ($recentMovements as $movement)
                <div class="activity-item">
                    <div @class(['activity-icon', 'icon-in' => $movement->type === 'in', 'icon-out' => $movement->type === 'out', 'icon-adjust' => $movement->type === 'adjustment'])>
                        @if ($movement->type === 'in')
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="12 19 12 5 5 12"/><polyline points="19 12 12 5 12 19"/></svg>
                        @elseif ($movement->type === 'out')
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="12 5 12 19 19 12"/><polyline points="5 12 12 19 12 5"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/></svg>
                        @endif
                    </div>
                    <div class="activity-details">
                        <div class="activity-title">
                            <strong>{{ $movementLabels[$movement->type] ?? $movement->type }}</strong> - {{ $movement->item?->name ?? 'Produk' }}
                        </div>
                        <div class="activity-meta">
                            <span>{{ $movement->quantity }} {{ $movement->item?->unit ?? 'pcs' }} (Stok: {{ $movement->stock_before }} &rarr; {{ $movement->stock_after }})</span>
                            <span class="dot-separator">&bull;</span>
                            <span class="activity-time">{{ $movement->occurred_at->diffForHumans() }}</span>
                        </div>
                        @if ($movement->note)
                            <div class="activity-note">"{{ $movement->note }}"</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state-card">
                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
                    <p>Belum ada riwayat mutasi stok tercatat.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Tables Section: Low Stock Warning & Product Master -->
<div class="grid-1-2-col">
    <!-- Produk dengan Stok Rendah Table -->
    <div class="card-panel" id="stok-rendah">
        <div class="panel-header">
            <div>
                <h3>Stok Rendah (&le; Min Stok)</h3>
                <span class="sub-label">Produk yang membutuhkan tindakan restok</span>
            </div>
            <span class="badge badge-warning">{{ $lowStockItems->count() }} Alert</span>
        </div>
        <div class="table-responsive">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>SKU</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lowStockItems as $item)
                        <tr>
                            <td>
                                <div class="product-cell">
                                    @if ($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="product-img">
                                    @else
                                        <div class="product-img-placeholder">{{ substr($item->name, 0, 1) }}</div>
                                    @endif
                                    <div>
                                        <strong class="product-title">{{ $item->name }}</strong>
                                        <small class="product-category">{{ $item->category ?? 'Umum' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><code class="sku-tag">{{ $item->sku }}</code></td>
                            <td>
                                <strong class="text-danger">{{ $item->stock }} {{ $item->unit }}</strong>
                                <small class="text-muted" style="display: block; font-size: 11px;">Min: {{ $item->min_stock }}</small>
                            </td>
                            <td>
                                <span class="pill-badge pill-danger">Stok Kritis</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-xs btn-outline-primary" onclick="openStockModal('{{ $item->id }}')">
                                    Restok
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-table-cell">Semua stok produk dalam batas aman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Master Produk Terbaru Table -->
    <div class="card-panel" id="produk-terbaru">
        <div class="panel-header">
            <div>
                <h3>Daftar Produk Inventory</h3>
                <span class="sub-label">Total {{ $items->total() }} produk terdaftar</span>
            </div>
            <button type="button" class="btn btn-sm btn-primary" onclick="openModal('modal-add-product')">
                + Tambah Produk
            </button>
        </div>

        <div class="table-responsive">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>SKU / Barcode</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>
                                <div class="product-cell">
                                    @if ($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="product-img">
                                    @else
                                        <div class="product-img-placeholder">{{ substr($item->name, 0, 1) }}</div>
                                    @endif
                                    <div>
                                        <strong class="product-title">{{ $item->name }}</strong>
                                        <small class="product-category">{{ $item->location ? 'Lokasi: ' . $item->location : 'Lokasi -' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="sku-tag">{{ $item->sku }}</code>
                                @if ($item->barcode)
                                    <small class="text-muted" style="display: block; font-size: 11px;">BC: {{ $item->barcode }}</small>
                                @endif
                            </td>
                            <td><span class="category-chip">{{ $item->category ?? 'Umum' }}</span></td>
                            <td><strong>Rp {{ number_format($item->price, 0, ',', '.') }}</strong></td>
                            <td>
                                <strong>{{ number_format($item->stock) }} {{ $item->unit }}</strong>
                            </td>
                            <td>
                                <form action="{{ route('items.toggle-status', $item) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" @class(['pill-badge-btn', 'pill-success' => $item->is_active, 'pill-neutral' => ! $item->is_active]) title="Klik untuk mengubah status">
                                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="table-action-btns">
                                    <button type="button" class="action-btn text-blue" title="Edit Produk" onclick="openModal('modal-edit-{{ $item->id }}')">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>

                                    <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $item->name }} dari inventory?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn text-danger" title="Hapus Produk">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Product per Item -->
                        <div class="modal-backdrop" id="modal-edit-{{ $item->id }}" style="display: none;">
                            <div class="modal-card">
                                <div class="modal-header">
                                    <h3>Edit Produk - {{ $item->name }}</h3>
                                    <button type="button" class="modal-close-btn" onclick="closeModal('modal-edit-{{ $item->id }}')">&times;</button>
                                </div>
                                <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data" class="modal-body-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label>Kode SKU *</label>
                                            <input type="text" name="sku" value="{{ $item->sku }}" required maxlength="50">
                                        </div>
                                        <div class="form-group">
                                            <label>Barcode (Opsional)</label>
                                            <input type="text" name="barcode" value="{{ $item->barcode }}" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Produk *</label>
                                        <input type="text" name="name" value="{{ $item->name }}" required maxlength="150">
                                    </div>
                                    <div class="form-grid-3">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <input type="text" name="category" value="{{ $item->category }}" maxlength="100">
                                        </div>
                                        <div class="form-group">
                                            <label>Harga (Rp)</label>
                                            <input type="number" name="price" value="{{ (int)$item->price }}" min="0">
                                        </div>
                                        <div class="form-group">
                                            <label>Satuan *</label>
                                            <input type="text" name="unit" value="{{ $item->unit }}" required maxlength="30">
                                        </div>
                                    </div>
                                    <div class="form-grid-3">
                                        <div class="form-group">
                                            <label>Stok *</label>
                                            <input type="number" name="stock" value="{{ $item->stock }}" required min="0">
                                        </div>
                                        <div class="form-group">
                                            <label>Minimum Stok *</label>
                                            <input type="number" name="min_stock" value="{{ $item->min_stock }}" required min="0">
                                        </div>
                                        <div class="form-group">
                                            <label>Lokasi Gudang</label>
                                            <input type="text" name="location" value="{{ $item->location }}" maxlength="120">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Ganti Foto Produk (MinIO / Cloudinary)</label>
                                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi Produk</label>
                                        <textarea name="description" rows="2" maxlength="1000">{{ $item->description }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-{{ $item->id }}')">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-table-cell">Belum ada produk tercatat di sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $items->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Produk (Add Product) -->
<div class="modal-backdrop" id="modal-add-product" style="display: none;">
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <h3>Tambah Produk Baru</h3>
                <small class="text-muted">Masukkan rincian informasi produk inventori</small>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('modal-add-product')">&times;</button>
        </div>
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="modal-body-form">
            @csrf
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Kode SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required maxlength="50" placeholder="SKU-1001">
                </div>
                <div class="form-group">
                    <label>Barcode (Opsional)</label>
                    <input type="text" name="barcode" value="{{ old('barcode') }}" maxlength="100" placeholder="899123456789">
                </div>
            </div>
            <div class="form-group">
                <label>Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="150" placeholder="Contoh: Laptop ThinkPad T14">
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="category" value="{{ old('category') }}" maxlength="100" placeholder="Elektronik, ATK">
                </div>
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Satuan *</label>
                    <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" required maxlength="30">
                </div>
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label>Stok Awal *</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0">
                </div>
                <div class="form-group">
                    <label>Minimum Stok *</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" required min="0">
                </div>
                <div class="form-group">
                    <label>Lokasi Gudang</label>
                    <input type="text" name="location" value="{{ old('location') }}" maxlength="120" placeholder="Gudang Utama A">
                </div>
            </div>
            <div class="form-group">
                <label>Foto Produk (MinIO / Cloudinary)</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
            </div>
            <div class="form-group">
                <label>Deskripsi Produk</label>
                <textarea name="description" rows="2" maxlength="1000" placeholder="Keterangan singkat spesifikasi produk">{{ old('description') }}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-product')">Batal</button>
                <button type="submit" class="btn btn-primary">+ Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Catat Mutasi Stok (Stock Movement) -->
<div class="modal-backdrop" id="modal-stock-movement" style="display: none;">
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <h3>Catat Mutasi Stok</h3>
                <small class="text-muted">Pencatatan barang masuk (Stock In) atau barang keluar (Stock Out)</small>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeModal('modal-stock-movement')">&times;</button>
        </div>
        <form action="{{ route('stock-movements.store') }}" method="POST" class="modal-body-form">
            @csrf
            <div class="form-group">
                <label>Pilih Produk *</label>
                <select name="item_id" id="movement-item-select" required>
                    <option value="">-- Pilih Produk Inventory --</option>
                    @foreach ($allItems as $item)
                        <option value="{{ $item->id }}" @selected(old('item_id') == $item->id)>
                            {{ $item->sku }} - {{ $item->name }} (Stok Saat Ini: {{ $item->stock }} {{ $item->unit }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Jenis Mutasi *</label>
                    <select name="type" required>
                        <option value="in" @selected(old('type') === 'in')>Barang Masuk (Stock In)</option>
                        <option value="out" @selected(old('type') === 'out')>Barang Keluar (Stock Out)</option>
                        <option value="adjustment" @selected(old('type') === 'adjustment')>Penyesuaian Stok</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jumlah Quantity *</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                </div>
            </div>
            <div class="form-grid-2">
                <div class="form-group">
                    <label>Petugas / Actor</label>
                    <input type="text" name="actor" value="{{ old('actor', auth()->check() ? auth()->user()->name : '') }}" maxlength="100">
                </div>
                <div class="form-group">
                    <label>Waktu Kejadian</label>
                    <input type="datetime-local" name="occurred_at" value="{{ old('occurred_at', now()->format('Y-m-d\TH:i')) }}">
                </div>
            </div>
            <div class="form-group">
                <label>Keterangan / Catatan Mutasi</label>
                <textarea name="note" rows="2" maxlength="1000" placeholder="Alasan mutasi atau nomor referensi DO/PO">{{ old('note') }}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-stock-movement')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Mutasi Stok</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal Helpers
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function openStockModal(itemId) {
        const select = document.getElementById('movement-item-select');
        if (select) {
            select.value = itemId;
        }
        openModal('modal-stock-movement');
    }

    // Chart.js 7-Day Stock Trend Initialization
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('stockTrendChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartDays),
                    datasets: [
                        {
                            label: 'Stock In (Masuk)',
                            data: @json($stockInTrends),
                            backgroundColor: 'rgba(34, 197, 94, 0.85)',
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Stock Out (Keluar)',
                            data: @json($stockOutTrends),
                            backgroundColor: 'rgba(239, 68, 68, 0.85)',
                            borderRadius: 6,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226, 232, 240, 0.4)' },
                            ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b' }
                        }
                    }
                }
            });
        }

        // Global Search
        const searchInput = document.getElementById('global-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.saas-table tbody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection
