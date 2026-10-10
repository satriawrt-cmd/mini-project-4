@extends('layouts.app')

@section('title', $supplier->exists ? 'Edit Supplier' : 'Tambah Supplier')

@section('content')
<h1>{{ $supplier->exists ? 'Edit Supplier' : 'Tambah Supplier' }}</h1>

<form method="POST" class="card"
      action="{{ $supplier->exists ? route('suppliers.update', $supplier) : route('suppliers.store') }}">
    @csrf
    @if ($supplier->exists)
        @method('PUT')
    @endif

    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
    @error('name') <small class="error">{{ $message }}</small> @enderror

    <label for="phone">Telepon</label>
    <input id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
    @error('phone') <small class="error">{{ $message }}</small> @enderror

    <p>
        <button type="submit">Simpan</button>
        <a class="btn gray" href="{{ route('suppliers.index') }}">Batal</a>
    </p>
</form>
@endsection
