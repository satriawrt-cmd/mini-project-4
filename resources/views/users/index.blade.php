@extends('layouts.app')

@section('title', 'User')

@section('content')
<h1>Manajemen User</h1>
<p><a class="btn" href="{{ route('users.create') }}">+ User</a></p>

<table>
    <tr><th>Username</th><th>Nama</th><th>Email</th><th>Role</th></tr>
    @foreach ($users as $u)
        <tr>
            <td>{{ $u->username }}</td><td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td><td>{{ $u->role }}</td>
        </tr>
    @endforeach
</table>
@endsection
