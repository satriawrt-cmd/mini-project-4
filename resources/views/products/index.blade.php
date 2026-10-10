@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<h1>Produk</h1>

<form class="filters" method="GET" action="{{ route('products.index') }}">
    <input name="search" placeholder="Cari nama / SKU" value="{{ $search }}">
    <select name="category_id">
        <option value="">Semua kategori</option>
        @foreach ($categories as $c)
            <option value="{{ $c->id }}" @selected((int) $categoryId === (int) $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
    <select name="sort">
        @foreach (['name' => 'Nama', 'sku' => 'SKU', 'price' => 'Harga', 'stock' => 'Stok'] as $key => $label)
            <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="dir">
        <option value="asc" @selected($dir === 'asc')>A → Z / Kecil → Besar</option>
        <option value="desc" @selected($dir === 'desc')>Z → A / Besar → Kecil</option>
    </select>
    <button type="submit">Terapkan</button>
    @if (auth()->user()->isAdmin())
        <a class="btn gray" href="{{ route('products.create') }}">+ Produk</a>
    @endif
</form>

<table>
    <tr>
        <th><a href="{{ $sortUrls['sku'] }}">SKU</a></th>
        <th><a href="{{ $sortUrls['name'] }}">Nama</a></th>
        <th>Kategori</th>
        <th><a href="{{ $sortUrls['price'] }}">Harga</a></th>
        <th><a href="{{ $sortUrls['stock'] }}">Stok</a></th>
        <th>Min</th>
        <th>Aksi</th>
    </tr>
    @forelse ($products as $product)
        <tr>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name }}</td>
            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
            <td @class(['low' => $product->isLowStock()])>{{ $product->stock }}</td>
            <td>{{ $product->minimum_stock }}</td>
            <td>
                <a href="{{ route('products.show', $product) }}">Detail</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('products.edit', $product) }}">Edit</a>
                    <form class="inline" method="POST" action="{{ route('products.destroy', $product) }}"
                          onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn danger" type="submit">Hapus</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7">Data tidak ditemukan.</td></tr>
    @endforelse
</table>

{{ $products->links() }}
<p>Total: {{ $products->total() }} produk</p>
@endsection
