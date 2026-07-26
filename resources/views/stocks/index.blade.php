@extends('layouts.app')

@section('title', 'Manajemen Stok - StockFlow')

@section('content')
<!-- Header Page -->
<div class="dashboard-header">
    <div class="header-titles">
        <h1>Stok Barang Inventory</h1>
        <p>Pantau jumlah stok saat ini, batas minimum stok, lokasi gudang, dan aksi pencatatan mutasi</p>
    </div>
    <div class="header-actions">
        <button type="button" class="btn btn-primary" onclick="openModal('modal-stock-movement')">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            <span>Catat Mutasi Stok</span>
        </button>
    </div>
</div>

<!-- Stats Mini Row -->
<div class="metrics-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 24px;">
    <div class="metric-card">
        <span class="metric-label">Total Jenis Produk</span>
        <div class="metric-value">{{ number_format($totalItems) }}</div>
        <small class="text-muted">Produk terdaftar</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Total Akumulasi Stok</span>
        <div class="metric-value text-blue">{{ number_format($totalStockSum) }}</div>
        <small class="text-muted">Unit di seluruh gudang</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Stok Hampir Habis</span>
        <div class="metric-value text-warning">{{ number_format($lowStockCount) }}</div>
        <small class="text-muted">&le; Min Stok</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Stok Habis (Kosong)</span>
        <div class="metric-value text-danger">{{ number_format($outOfStockCount) }}</div>
        <small class="text-muted">0 unit</small>
    </div>
</div>

<!-- Table Panel -->
<div class="card-panel">
    <div class="panel-header" style="flex-wrap: wrap; gap: 16px;">
        <form action="{{ route('stocks.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%;">
            <div style="flex: 1; min-width: 240px;">
                <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari barang, SKU, lokasi gudang..." style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-app); color: var(--text-main); font-size: 13px;">
            </div>
            <select name="filter" style="padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-app); color: var(--text-main); font-size: 13px;">
                <option value="">-- Semua Status Stok --</option>
                <option value="low_stock" @selected($filters['filter'] === 'low_stock')>Stok Rendah</option>
                <option value="out_of_stock" @selected($filters['filter'] === 'out_of_stock')>Stok Habis (0)</option>
                <option value="safe" @selected($filters['filter'] === 'safe')>Stok Aman</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            <a href="{{ route('stocks.index') }}" class="btn btn-xs btn-outline-primary" style="display: inline-flex; align-items: center;">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="saas-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kode SKU</th>
                    <th>Lokasi Gudang</th>
                    <th>Stok Saat Ini</th>
                    <th>Batas Min Stok</th>
                    <th>Status Stok</th>
                    <th>Aksi Mutasi</th>
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
                                    <small class="product-category">{{ $item->category ?? 'Umum' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><code class="sku-tag">{{ $item->sku }}</code></td>
                        <td>{{ $item->location ?? 'Gudang Utama' }}</td>
                        <td>
                            <strong @class(['text-danger' => $item->stock === 0, 'text-warning' => $item->is_low_stock && $item->stock > 0]) style="font-size: 15px;">
                                {{ number_format($item->stock) }} {{ $item->unit }}
                            </strong>
                        </td>
                        <td>{{ number_format($item->min_stock) }} {{ $item->unit }}</td>
                        <td>
                            @if ($item->stock === 0)
                                <span class="pill-badge pill-danger">Kosong</span>
                            @elseif ($item->is_low_stock)
                                <span class="pill-badge pill-warning">Hampir Habis</span>
                            @else
                                <span class="pill-badge pill-success">Stok Aman</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-xs btn-outline-primary" onclick="openStockModal('{{ $item->id }}')">
                                + Mutasi Stok
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-table-cell">Tidak ada data stok produk ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 20px;">
        {{ $items->links() }}
    </div>
</div>

<!-- Modal Catat Mutasi Stok -->
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
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.style.display = 'flex';
    }
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.style.display = 'none';
    }
    function openStockModal(itemId) {
        const select = document.getElementById('movement-item-select');
        if (select) select.value = itemId;
        openModal('modal-stock-movement');
    }
</script>
@endsection
