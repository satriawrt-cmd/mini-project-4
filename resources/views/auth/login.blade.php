@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card login">
    <h1>Login</h1>
    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <label for="username">Username</label>
        <input id="username" name="username" value="{{ old('username') }}" required autofocus>
        @error('username')
            <small class="error">{{ $message }}</small>
        @enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @error('password')
            <small class="error">{{ $message }}</small>
        @enderror

        <p><button type="submit">Masuk</button></p>
    </form>
</div>
@endsection
