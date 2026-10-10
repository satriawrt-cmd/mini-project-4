@extends('layouts.app')

@section('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<h1>{{ $category->exists ? 'Edit Kategori' : 'Tambah Kategori' }}</h1>

<form method="POST" class="card"
      action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}">
    @csrf
    @if ($category->exists)
        @method('PUT')
    @endif

    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name', $category->name) }}" required>
    @error('name') <small class="error">{{ $message }}</small> @enderror

    <p>
        <button type="submit">Simpan</button>
        <a class="btn gray" href="{{ route('categories.index') }}">Batal</a>
    </p>
</form>
@endsection
