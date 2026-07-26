<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan Inventory</title>
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <script defer src="{{ asset('js/inventory.js') }}"></script>
</head>
<body class="print-body">
@php
    $movementLabels = ['in' => 'Masuk', 'out' => 'Keluar', 'adjustment' => 'Penyesuaian'];
@endphp

<main class="print-sheet">
    <section class="print-header">
        <div>
            <p class="eyebrow">StockFlow</p>
            <h1>Laporan Inventory Barang</h1>
            <p>{{ $periodLabel }} - Dicetak {{ now()->format('d M Y H:i') }}</p>
        </div>
        <button class="button no-print" type="button" data-print-now>
            <span aria-hidden="true">P</span>
            Cetak
        </button>
    </section>

    <section class="print-summary">
        <div><span>Total barang</span><strong>{{ number_format($summary['totalItems']) }}</strong></div>
        <div><span>Stok rendah</span><strong>{{ number_format($summary['lowStock']) }}</strong></div>
        <div><span>Masuk</span><strong>{{ number_format($summary['incoming']) }}</strong></div>
        <div><span>Keluar</span><strong>{{ number_format($summary['outgoing']) }}</strong></div>
        <div><span>Mutasi</span><strong>{{ number_format($summary['movementCount']) }}</strong></div>
    </section>

    <h2>Rekap Stok</h2>
    <table class="print-table">
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
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category ?? '-' }}</td>
                    <td>{{ $item->location ?? '-' }}</td>
                    <td>{{ number_format($item->stock) }} {{ $item->unit }}</td>
                    <td>{{ number_format($item->min_stock) }} {{ $item->unit }}</td>
                    <td>{{ $item->is_low_stock ? 'Stok rendah' : 'Aman' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Riwayat Mutasi</h2>
    <table class="print-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Stok</th>
                <th>Petugas</th>
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
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada mutasi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</main>
</body>
</html>
