@extends('layouts.app')

@section('title', 'Histori Stok')

@section('content')
<h1>Histori Barang Masuk / Keluar</h1>

<table>
    <tr><th>Waktu</th><th>Produk</th><th>Tipe</th><th>Jumlah</th><th>User</th><th>Catatan</th></tr>
    @forelse ($movements as $m)
        <tr>
            <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
            <td>{{ $m->product->sku }} - {{ $m->product->name }}</td>
            <td>{{ $m->type === 'IN' ? 'Masuk' : 'Keluar' }}</td>
            <td>{{ $m->quantity }}</td>
            <td>{{ $m->user->username }}</td>
            <td>{{ $m->note }}</td>
        </tr>
    @empty
        <tr><td colspan="6">Belum ada transaksi.</td></tr>
    @endforelse
</table>

{{ $movements->links() }}
@endsection
