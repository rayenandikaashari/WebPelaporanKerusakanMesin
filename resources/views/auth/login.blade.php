@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-container">
    <h1>Login</h1>
    <p>Sistem Manajemen Maintenance dan Pelaporan Kerusakan Mesin</p>

    @if ($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
    @endif

   <form method="POST" action="{{ route('login.process') }}">
    @csrf

        <label for="email">Email</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
        >

        <label for="password">Password</label>
        <input
            id="password"
            type="password"
            name="password"
            required
        >

        <button type="submit">Masuk</button>
    </form>
</div>
@endsection
