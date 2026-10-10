@extends('layouts.app')

@section('title', 'Barang Keluar')

@section('content')
<h1>Barang Keluar</h1>

<form method="POST" action="{{ route('stock.out.store') }}" class="card">
    @csrf

    <label for="product_id">Produk</label>
    <select id="product_id" name="product_id" required>
        <option value="">-- pilih produk --</option>
        @foreach ($products as $p)
            <option value="{{ $p->id }}" @selected((int) old('product_id') === (int) $p->id)>
                {{ $p->sku }} - {{ $p->name }} (stok {{ $p->stock }})
            </option>
        @endforeach
    </select>
    @error('product_id') <small class="error">{{ $message }}</small> @enderror

    <label for="quantity">Jumlah</label>
    <input id="quantity" type="number" name="quantity" min="1" value="{{ old('quantity') }}" required>
    @error('quantity') <small class="error">{{ $message }}</small> @enderror

    <label for="note">Catatan</label>
    <input id="note" name="note" maxlength="255" value="{{ old('note') }}">
    @error('note') <small class="error">{{ $message }}</small> @enderror

    <p><button type="submit">Simpan</button></p>
</form>
@endsection
