@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<h1>Tambah Produk</h1>
@include('products._form', ['action' => route('products.store'), 'method' => 'POST'])
@endsection
