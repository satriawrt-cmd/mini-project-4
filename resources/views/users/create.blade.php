@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<h1>Tambah User</h1>

<form method="POST" action="{{ route('users.store') }}" class="card">
    @csrf

    <label for="name">Nama lengkap</label>
    <input id="name" name="name" value="{{ old('name') }}" required>
    @error('name') <small class="error">{{ $message }}</small> @enderror

    <label for="username">Username</label>
    <input id="username" name="username" value="{{ old('username') }}" required>
    @error('username') <small class="error">{{ $message }}</small> @enderror

    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @error('email') <small class="error">{{ $message }}</small> @enderror

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @error('password') <small class="error">{{ $message }}</small> @enderror

    <label for="role">Role</label>
    <select id="role" name="role">
        <option value="staff" @selected(old('role') === 'staff')>staff</option>
        <option value="admin" @selected(old('role') === 'admin')>admin</option>
    </select>
    @error('role') <small class="error">{{ $message }}</small> @enderror

    <p>
        <button type="submit">Simpan</button>
        <a class="btn gray" href="{{ route('users.index') }}">Batal</a>
    </p>
</form>
@endsection
