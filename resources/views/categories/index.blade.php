@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<h1>Kategori</h1>
<p><a class="btn" href="{{ route('categories.create') }}">+ Kategori</a></p>

<table>
    <tr><th>Nama</th><th>Jumlah Produk</th><th>Aksi</th></tr>
    @forelse ($categories as $c)
        <tr>
            <td>{{ $c->name }}</td>
            <td>{{ $c->products_count }}</td>
            <td>
                <a href="{{ route('categories.edit', $c) }}">Edit</a>
                <form class="inline" method="POST" action="{{ route('categories.destroy', $c) }}"
                      onsubmit="return confirm('Hapus kategori?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn danger" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="3">Belum ada kategori.</td></tr>
    @endforelse
</table>
@endsection
