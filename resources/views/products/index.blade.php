@extends('layouts.app')

@section('title', 'Manajemen Produk - StockFlow')

@section('content')
<!-- Header Page -->
<div class="dashboard-header">
    <div class="header-titles">
        <h1>Master Produk</h1>
        <p>Kelola daftar produk, data SKU, barcode, harga, dan media produk MinIO</p>
    </div>
    <div class="header-actions">
        <button type="button" class="btn btn-primary" onclick="openModal('modal-add-product')">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>+ Tambah Produk</span>
        </button>
    </div>
</div>

<!-- Stats Mini Row -->
<div class="metrics-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
    <div class="metric-card">
        <span class="metric-label">Total Produk</span>
        <div class="metric-value">{{ number_format($totalCount) }}</div>
        <small class="text-muted">Item terdaftar</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Produk Aktif</span>
        <div class="metric-value text-blue">{{ number_format($activeCount) }}</div>
        <small class="text-muted">Siap dipasarkan</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Stok Kritis</span>
        <div class="metric-value text-danger">{{ number_format($lowStockCount) }}</div>
        <small class="text-muted">Perlu restok</small>
    </div>
</div>

<!-- Filter Bar & Table Panel -->
<div class="card-panel">
    <div class="panel-header" style="flex-wrap: wrap; gap: 16px;">
        <form action="{{ route('products.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%;">
            <div style="flex: 1; min-width: 240px;">
                <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari nama produk, SKU, barcode..." style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-app); color: var(--text-main); font-size: 13px;">
            </div>
            <select name="category" style="padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-app); color: var(--text-main); font-size: 13px;">
                <option value="">-- Semua Kategori --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" @selected($filters['category'] === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="status" style="padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-app); color: var(--text-main); font-size: 13px;">
                <option value="">-- Semua Status --</option>
                <option value="active" @selected($filters['status'] === 'active')>Aktif</option>
                <option value="inactive" @selected($filters['status'] === 'inactive')>Nonaktif</option>
                <option value="low_stock" @selected($filters['status'] === 'low_stock')>Stok Rendah</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            <a href="{{ route('products.index') }}" class="btn btn-xs btn-outline-primary" style="display: inline-flex; align-items: center;">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="saas-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>SKU / Barcode</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok Saat Ini</th>
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
                            <strong @class(['text-danger' => $item->is_low_stock])>{{ number_format($item->stock) }} {{ $item->unit }}</strong>
                            <small class="text-muted" style="display: block; font-size: 11px;">Min: {{ $item->min_stock }}</small>
                        </td>
                        <td>
                            <form action="{{ route('items.toggle-status', $item) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" @class(['pill-badge-btn', 'pill-success' => $item->is_active, 'pill-neutral' => ! $item->is_active]) title="Klik untuk mengaktifkan / menonaktifkan">
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

                    <!-- Modal Edit Product -->
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
                        <td colspan="7" class="empty-table-cell">Tidak ada produk ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 20px;">
        {{ $items->links() }}
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

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.style.display = 'flex';
    }
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.style.display = 'none';
    }
</script>
@endsection
