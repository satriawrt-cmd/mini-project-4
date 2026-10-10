@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
<h1>Supplier</h1>
<p><a class="btn" href="{{ route('suppliers.create') }}">+ Supplier</a></p>

<table>
    <tr><th>Nama</th><th>Telepon</th><th>Jumlah Produk</th><th>Aksi</th></tr>
    @forelse ($suppliers as $s)
        <tr>
            <td>{{ $s->name }}</td>
            <td>{{ $s->phone }}</td>
            <td>{{ $s->products_count }}</td>
            <td>
                <a href="{{ route('suppliers.edit', $s) }}">Edit</a>
                <form class="inline" method="POST" action="{{ route('suppliers.destroy', $s) }}"
                      onsubmit="return confirm('Hapus supplier?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn danger" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="4">Belum ada supplier.</td></tr>
    @endforelse
</table>
@endsection
