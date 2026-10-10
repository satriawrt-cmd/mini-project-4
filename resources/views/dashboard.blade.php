@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1>Dashboard</h1>

<div class="grid">
    <div class="card stat">Total produk<b>{{ $stats['total_products'] }}</b></div>
    <div class="card stat">Nilai inventory<b>Rp {{ number_format($stats['inventory_value'], 0, ',', '.') }}</b></div>
    <div class="card stat">Stok rendah<b @class(['low' => $stats['low_count'] > 0])>{{ $stats['low_count'] }}</b></div>
    <div class="card stat">Barang masuk<b>{{ $stats['total_in'] }}</b></div>
    <div class="card stat">Barang keluar<b>{{ $stats['total_out'] }}</b></div>
</div>

<h2>Produk Stok Rendah</h2>
<table>
    <tr><th>SKU</th><th>Nama</th><th>Stok</th><th>Minimum</th></tr>
    @forelse ($lowStock as $p)
        <tr>
            <td>{{ $p->sku }}</td><td>{{ $p->name }}</td>
            <td class="low">{{ $p->stock }}</td><td>{{ $p->minimum_stock }}</td>
        </tr>
    @empty
        <tr><td colspan="4">Tidak ada produk stok rendah.</td></tr>
    @endforelse
</table>

<h2>Ringkasan per Kategori</h2>
<table>
    <tr><th>Kategori</th><th>Jumlah Produk</th><th>Total Stok</th><th>Rata-rata Harga</th></tr>
    @foreach ($categories as $c)
        <tr>
            <td>{{ $c->name }}</td>
            <td>{{ $c->products_count }}</td>
            <td>{{ $c->total_stock ?? 0 }}</td>
            <td>Rp {{ number_format($c->average_price ?? 0, 0, ',', '.') }}</td>
        </tr>
    @endforeach
</table>

<h2>Laporan Inventory</h2>
<table>
    <tr><th>SKU</th><th>Nama</th><th>Total Masuk</th><th>Total Keluar</th><th>Stok Saat Ini</th></tr>
    @foreach ($report as $p)
        <tr>
            <td>{{ $p->sku }}</td><td>{{ $p->name }}</td>
            <td>{{ $p->total_in ?? 0 }}</td><td>{{ $p->total_out ?? 0 }}</td><td>{{ $p->stock }}</td>
        </tr>
    @endforeach
</table>
@endsection
