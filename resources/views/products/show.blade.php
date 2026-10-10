@extends('layouts.app')

@section('title', $product->name)

@section('content')
<h1>{{ $product->name }}</h1>

<div class="card">
    <p><strong>SKU:</strong> {{ $product->sku }}</p>
    <p><strong>Kategori:</strong> {{ $product->category->name }}</p>
    <p><strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
    <p><strong>Stok:</strong> <span @class(['low' => $product->isLowStock()])>{{ $product->stock }}</span> (minimum {{ $product->minimum_stock }})</p>
    <p><strong>Supplier:</strong></p>
    <ul>
        @forelse ($product->suppliers as $s)
            <li>{{ $s->name }} @if ($s->pivot->supplier_price !== null) — Rp {{ number_format($s->pivot->supplier_price, 0, ',', '.') }} @endif</li>
        @empty
            <li>Belum ada supplier.</li>
        @endforelse
    </ul>
</div>

<h2>10 Transaksi Terakhir</h2>
<table>
    <tr><th>Waktu</th><th>Tipe</th><th>Jumlah</th><th>User</th><th>Catatan</th></tr>
    @forelse ($movements as $m)
        <tr>
            <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
            <td>{{ $m->type === 'IN' ? 'Masuk' : 'Keluar' }}</td>
            <td>{{ $m->quantity }}</td>
            <td>{{ $m->user->username }}</td>
            <td>{{ $m->note }}</td>
        </tr>
    @empty
        <tr><td colspan="5">Belum ada transaksi.</td></tr>
    @endforelse
</table>

<p><a class="btn gray" href="{{ route('products.index') }}">Kembali</a></p>
@endsection
