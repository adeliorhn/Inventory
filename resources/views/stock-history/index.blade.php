@extends('layouts.app')

@section('title', 'Riwayat Mutasi Stok - StockFlow')

@section('content')
@php
    $movementLabels = ['in' => 'Barang Masuk', 'out' => 'Barang Keluar', 'adjustment' => 'Penyesuaian Stok'];
@endphp

<!-- Header Page -->
<div class="dashboard-header">
    <div class="header-titles">
        <h1>Riwayat Mutasi Stok</h1>
        <p>Laporan seluruh aktivitas pergerakan stok (Stock In & Stock Out) beserta tanggal, petugas, dan catatan</p>
    </div>
</div>

<!-- Stats Mini Row -->
<div class="metrics-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
    <div class="metric-card">
        <span class="metric-label">Total Transaksi Mutasi</span>
        <div class="metric-value">{{ number_format($totalMovements) }}</div>
        <small class="text-muted">Transaksi tercatat</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Total Unit Masuk (Stock In)</span>
        <div class="metric-value text-blue">+{{ number_format($totalIn) }}</div>
        <small class="text-muted">Unit barang masuk</small>
    </div>
    <div class="metric-card">
        <span class="metric-label">Total Unit Keluar (Stock Out)</span>
        <div class="metric-value text-danger">-{{ number_format($totalOut) }}</div>
        <small class="text-muted">Unit barang keluar</small>
    </div>
</div>

<!-- Table Panel -->
<div class="card-panel">
    <div class="panel-header" style="flex-wrap: wrap; gap: 16px;">
        <form action="{{ route('stock-history.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari nama barang, SKU, petugas, atau catatan..." style="width: 100%; padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-app); color: var(--text-main); font-size: 13px;">
            </div>
            <select name="type" style="padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-app); color: var(--text-main); font-size: 13px;">
                <option value="">-- Semua Jenis Mutasi --</option>
                <option value="in" @selected($filters['type'] === 'in')>Barang Masuk (Stock In)</option>
                <option value="out" @selected($filters['type'] === 'out')>Barang Keluar (Stock Out)</option>
                <option value="adjustment" @selected($filters['type'] === 'adjustment')>Penyesuaian Stok</option>
            </select>
            <select name="item_id" style="padding: 9px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-app); color: var(--text-main); font-size: 13px;">
                <option value="">-- Semua Produk --</option>
                @foreach ($items as $itm)
                    <option value="{{ $itm->id }}" @selected($filters['item_id'] == $itm->id)>{{ $itm->sku }} - {{ $itm->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            <a href="{{ route('stock-history.index') }}" class="btn btn-xs btn-outline-primary" style="display: inline-flex; align-items: center;">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="saas-table">
            <thead>
                <tr>
                    <th>Waktu Kejadian</th>
                    <th>Produk</th>
                    <th>Jenis Mutasi</th>
                    <th>Jumlah Quantity</th>
                    <th>Perubahan Stok</th>
                    <th>Petugas / Actor</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $movement)
                    <tr>
                        <td><strong>{{ $movement->occurred_at->format('d M Y H:i') }}</strong></td>
                        <td>
                            <div class="product-cell">
                                <div>
                                    <strong class="product-title">{{ $movement->item?->name ?? 'Produk Dihapus' }}</strong>
                                    <code class="sku-tag">{{ $movement->item?->sku ?? '-' }}</code>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($movement->type === 'in')
                                <span class="pill-badge pill-success">Stock In</span>
                            @elseif ($movement->type === 'out')
                                <span class="pill-badge pill-danger">Stock Out</span>
                            @else
                                <span class="pill-badge pill-warning">Penyesuaian</span>
                            @endif
                        </td>
                        <td>
                            <strong @class(['text-blue' => $movement->type === 'in', 'text-danger' => $movement->type === 'out'])>
                                {{ $movement->type === 'in' ? '+' : ($movement->type === 'out' ? '-' : '') }}{{ number_format($movement->quantity) }} {{ $movement->item?->unit ?? 'pcs' }}
                            </strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ $movement->stock_before }} &rarr; {{ $movement->stock_after }}</small>
                        </td>
                        <td><strong>{{ $movement->actor ?? 'Admin' }}</strong></td>
                        <td>{{ $movement->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-table-cell">Belum ada riwayat mutasi stok ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 20px;">
        {{ $movements->links() }}
    </div>
</div>
@endsection
