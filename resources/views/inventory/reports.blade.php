@extends('layouts.app')

@section('title', 'Laporan Inventory')

@section('content')
@php
    $movementLabels = ['in' => 'Masuk', 'out' => 'Keluar', 'adjustment' => 'Penyesuaian'];
@endphp

<section class="page-heading">
    <div>
        <p class="eyebrow">Cetak laporan</p>
        <h1>Laporan stok dan pergerakan barang</h1>
        <p class="subtle">{{ $periodLabel }}</p>
    </div>
    <a class="button" href="{{ route('reports.print', request()->query()) }}" target="_blank" rel="noopener">
        <span aria-hidden="true">P</span>
        Cetak laporan
    </a>
</section>

<section class="panel">
    <form class="filter-bar" action="{{ route('reports.index') }}" method="GET">
        <label>
            Dari
            <input type="date" name="from" value="{{ $filters['from'] ?? '' }}">
        </label>
        <label>
            Sampai
            <input type="date" name="to" value="{{ $filters['to'] ?? '' }}">
        </label>
        <button class="button" type="submit">Terapkan</button>
        <a class="button button-secondary" href="{{ route('reports.index') }}">Reset</a>
    </form>
</section>

<section class="metrics" aria-label="Ringkasan laporan">
    <article class="metric">
        <span>Total barang</span>
        <strong>{{ number_format($summary['totalItems']) }}</strong>
    </article>
    <article class="metric metric-warning">
        <span>Stok rendah</span>
        <strong>{{ number_format($summary['lowStock']) }}</strong>
    </article>
    <article class="metric metric-success">
        <span>Masuk</span>
        <strong>{{ number_format($summary['incoming']) }}</strong>
    </article>
    <article class="metric metric-danger">
        <span>Keluar</span>
        <strong>{{ number_format($summary['outgoing']) }}</strong>
    </article>
    <article class="metric metric-info">
        <span>Penyesuaian</span>
        <strong>{{ number_format($summary['adjustments']) }}</strong>
    </article>
</section>

<section class="panel wide-panel">
    <div class="panel-heading">
        <h2>Rekap Stok</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Stok</th>
                    <th>Minimum</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category ?? '-' }}</td>
                        <td>{{ $item->location ?? '-' }}</td>
                        <td>{{ number_format($item->stock) }} {{ $item->unit }}</td>
                        <td>{{ number_format($item->min_stock) }} {{ $item->unit }}</td>
                        <td>
                            <span @class(['badge', 'badge-warning' => $item->is_low_stock, 'badge-ok' => ! $item->is_low_stock])>
                                {{ $item->is_low_stock ? 'Stok rendah' : 'Aman' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="panel wide-panel">
    <div class="panel-heading">
        <h2>Riwayat Mutasi</h2>
        <span class="muted">{{ number_format($summary['movementCount']) }} transaksi</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Stok</th>
                    <th>Petugas</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $movement)
                    <tr>
                        <td>{{ $movement->occurred_at->format('d M Y H:i') }}</td>
                        <td>{{ $movement->item?->name ?? '-' }}</td>
                        <td>{{ $movementLabels[$movement->type] ?? $movement->type }}</td>
                        <td>{{ number_format($movement->quantity) }}</td>
                        <td>{{ $movement->stock_before }} -> {{ $movement->stock_after }}</td>
                        <td>{{ $movement->actor ?? '-' }}</td>
                        <td>{{ $movement->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">Tidak ada mutasi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $movements->links() }}
</section>
@endsection
