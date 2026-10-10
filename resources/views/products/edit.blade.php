@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<h1>Edit Produk</h1>
@include('products._form', ['action' => route('products.update', $product), 'method' => 'PUT'])
@endsection
