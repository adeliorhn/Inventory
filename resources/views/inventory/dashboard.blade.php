@extends('layouts.app')

@section('title', 'Pencatatan Inventory')

@section('content')
@php
    $movementLabels = ['in' => 'Masuk', 'out' => 'Keluar', 'adjustment' => 'Penyesuaian'];
@endphp

<section class="page-heading">
    <div>
        <p class="eyebrow">Dashboard inventory</p>
        <h1>Pencatatan barang, stok, notifikasi, dan komunikasi</h1>
    </div>
    <a class="button button-secondary" href="{{ route('reports.index') }}">
        <span aria-hidden="true">[]</span>
        Lihat laporan
    </a>
</section>

<section class="metrics" aria-label="Ringkasan inventory">
    <article class="metric">
        <span>Total barang</span>
        <strong>{{ number_format($summary['items']) }}</strong>
    </article>
    <article class="metric metric-warning">
        <span>Stok rendah</span>
        <strong>{{ number_format($summary['lowStock']) }}</strong>
    </article>
    <article class="metric">
        <span>Mutasi hari ini</span>
        <strong>{{ number_format($summary['todayMovements']) }}</strong>
    </article>
    <article class="metric metric-info">
        <span>Pesan terbuka</span>
        <strong>{{ number_format($summary['openMessages']) }}</strong>
    </article>
</section>

<div class="dashboard-grid" id="pencatatan">
    <section class="panel">
        <div class="panel-heading">
            <h2>Tambah Barang</h2>
        </div>
        <form class="form-grid" action="{{ route('items.store') }}" method="POST">
            @csrf
            <label>
                Kode SKU
                <input name="sku" value="{{ old('sku') }}" required maxlength="50" placeholder="BRG-004">
            </label>
            <label>
                Nama barang
                <input name="name" value="{{ old('name') }}" required maxlength="150" placeholder="Nama barang">
            </label>
            <label>
                Kategori
                <input name="category" value="{{ old('category') }}" maxlength="100" placeholder="ATK, Elektronik">
            </label>
            <label>
                Satuan
                <input name="unit" value="{{ old('unit', 'pcs') }}" required maxlength="30">
            </label>
            <label>
                Lokasi
                <input name="location" value="{{ old('location') }}" maxlength="120" placeholder="Gudang A">
            </label>
            <label>
                Stok awal
                <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0">
            </label>
            <label>
                Minimum stok
                <input type="number" name="min_stock" value="{{ old('min_stock', 0) }}" required min="0">
            </label>
            <label class="span-2">
                Catatan
                <textarea name="description" rows="3" maxlength="1000" placeholder="Detail singkat barang">{{ old('description') }}</textarea>
            </label>
            <button class="button span-2" type="submit">
                <span aria-hidden="true">+</span>
                Simpan barang
            </button>
        </form>
    </section>

    <section class="panel">
        <div class="panel-heading">
            <h2>Catat Mutasi Stok</h2>
        </div>
        <form class="form-grid" action="{{ route('stock-movements.store') }}" method="POST">
            @csrf
            <label class="span-2">
                Barang
                <select name="item_id" required>
                    <option value="">Pilih barang</option>
                    @foreach ($allItems as $item)
                        <option value="{{ $item->id }}" @selected(old('item_id') == $item->id)>
                            {{ $item->sku }} - {{ $item->name }} ({{ $item->stock }} {{ $item->unit }})
                        </option>
                    @endforeach
                </select>
            </label>
            <label>
                Jenis
                <select name="type" required>
                    <option value="in" @selected(old('type') === 'in')>Barang masuk</option>
                    <option value="out" @selected(old('type') === 'out')>Barang keluar</option>
                    <option value="adjustment" @selected(old('type') === 'adjustment')>Penyesuaian stok</option>
                </select>
            </label>
            <label>
                Jumlah
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
            </label>
            <label>
                Petugas
                <input name="actor" value="{{ old('actor') }}" maxlength="100" placeholder="Nama petugas">
            </label>
            <label>
                Waktu
                <input type="datetime-local" name="occurred_at" value="{{ old('occurred_at', now()->format('Y-m-d\TH:i')) }}">
            </label>
            <label class="span-2">
                Keterangan
                <textarea name="note" rows="3" maxlength="1000" placeholder="Alasan mutasi stok">{{ old('note') }}</textarea>
            </label>
            <button class="button span-2" type="submit">
                <span aria-hidden="true">&lt;&gt;</span>
                Simpan mutasi
            </button>
        </form>
    </section>
</div>

<section class="panel wide-panel">
    <div class="panel-heading">
        <h2>Daftar Barang</h2>
        <span class="muted">{{ $items->total() }} item tercatat</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            <small>{{ $item->sku }}</small>
                        </td>
                        <td>{{ $item->category ?? '-' }}</td>
                        <td>{{ $item->location ?? '-' }}</td>
                        <td>{{ number_format($item->stock) }} {{ $item->unit }}</td>
                        <td>
                            <span @class(['badge', 'badge-warning' => $item->is_low_stock, 'badge-ok' => ! $item->is_low_stock])>
                                {{ $item->is_low_stock ? 'Stok rendah' : 'Aman' }}
                            </span>
                        </td>
                        <td>
                            <details class="row-actions">
                                <summary>Ubah</summary>
                                <form class="edit-form" action="{{ route('items.update', $item) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input name="sku" value="{{ $item->sku }}" required maxlength="50">
                                    <input name="name" value="{{ $item->name }}" required maxlength="150">
                                    <input name="category" value="{{ $item->category }}" maxlength="100" placeholder="Kategori">
                                    <input name="unit" value="{{ $item->unit }}" required maxlength="30">
                                    <input name="location" value="{{ $item->location }}" maxlength="120" placeholder="Lokasi">
                                    <input type="number" name="stock" value="{{ $item->stock }}" required min="0">
                                    <input type="number" name="min_stock" value="{{ $item->min_stock }}" required min="0">
                                    <textarea name="description" rows="2" maxlength="1000">{{ $item->description }}</textarea>
                                    <button class="button button-small" type="submit">Simpan</button>
                                </form>
                                <form action="{{ route('items.destroy', $item) }}" method="POST" data-confirm="Hapus barang ini dari inventory?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button button-danger button-small" type="submit">Hapus</button>
                                </form>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">Belum ada barang yang dicatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $items->links() }}
</section>

<div class="dashboard-grid dashboard-grid-secondary">
    <section class="panel">
        <div class="panel-heading">
            <h2>Mutasi Terbaru</h2>
        </div>
        <div class="activity-list">
            @forelse ($recentMovements as $movement)
                <article class="activity">
                    <span @class(['dot', 'dot-in' => $movement->type === 'in', 'dot-out' => $movement->type === 'out', 'dot-adjust' => $movement->type === 'adjustment'])></span>
                    <div>
                        <strong>{{ $movementLabels[$movement->type] ?? $movement->type }} - {{ $movement->item?->name }}</strong>
                        <small>{{ $movement->quantity }} {{ $movement->item?->unit }} dari {{ $movement->stock_before }} ke {{ $movement->stock_after }} - {{ $movement->occurred_at->format('d M Y H:i') }}</small>
                    </div>
                </article>
            @empty
                <p class="empty-state">Belum ada mutasi stok.</p>
            @endforelse
        </div>
    </section>

    <section class="panel" id="notifikasi">
        <div class="panel-heading">
            <h2>Notif & Komunikasi</h2>
        </div>
        <div class="split-stack">
            <div>
                <h3>Notifikasi stok</h3>
                <div class="notice-list">
                    @forelse ($alerts as $alert)
                        <article @class(['notice', 'notice-critical' => $alert->severity === 'critical'])>
                            <div>
                                <strong>{{ $alert->title }}</strong>
                                <p>{{ $alert->body }}</p>
                                <small>{{ $alert->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <form action="{{ route('alerts.read', $alert) }}" method="POST">
                                @csrf
                                <button class="icon-button" type="submit" title="Tandai dibaca">OK</button>
                            </form>
                        </article>
                    @empty
                        <p class="empty-state">Tidak ada notifikasi aktif.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3>Kirim pesan tim</h3>
                <form class="form-grid compact" action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <label>
                        Pengirim
                        <input name="sender_name" value="{{ old('sender_name') }}" required maxlength="100">
                    </label>
                    <label>
                        Tujuan
                        <input name="recipient_team" value="{{ old('recipient_team') }}" required maxlength="100" placeholder="Pembelian">
                    </label>
                    <label>
                        Prioritas
                        <select name="priority" required>
                            <option value="normal" @selected(old('priority') === 'normal')>Normal</option>
                            <option value="urgent" @selected(old('priority') === 'urgent')>Urgent</option>
                        </select>
                    </label>
                    <label>
                        Subjek
                        <input name="subject" value="{{ old('subject') }}" required maxlength="150">
                    </label>
                    <label class="span-2">
                        Pesan
                        <textarea name="body" rows="3" required maxlength="1200">{{ old('body') }}</textarea>
                    </label>
                    <button class="button span-2" type="submit">
                        <span aria-hidden="true">></span>
                        Kirim pesan
                    </button>
                </form>
            </div>

            <div>
                <h3>Pesan terbaru</h3>
                <div class="message-list">
                    @forelse ($messages as $message)
                        <article @class(['message', 'message-urgent' => $message->priority === 'urgent'])>
                            <div>
                                <strong>{{ $message->subject }}</strong>
                                <p>{{ $message->body }}</p>
                                <small>{{ $message->sender_name }} ke {{ $message->recipient_team }} - {{ $message->created_at->format('d M Y H:i') }}</small>
                            </div>
                            @if ($message->status === 'open')
                                <form action="{{ route('messages.resolve', $message) }}" method="POST">
                                    @csrf
                                    <button class="icon-button" type="submit" title="Tandai selesai">OK</button>
                                </form>
                            @else
                                <span class="badge badge-ok">Selesai</span>
                            @endif
                        </article>
                    @empty
                        <p class="empty-state">Belum ada pesan tim.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
