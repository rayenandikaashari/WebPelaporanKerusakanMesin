<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Supervisor') - Sistem Maintenance</title>

    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/supervisor-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
</head>

@php
    $supervisorName = auth()->user()->name
        ?? session('supervisor.nama', 'Supervisor');
@endphp

<body>

<div class="app supervisor-layout">

    <aside class="sidebar">

        <div class="logo">
            <div class="logo-icon">⚙</div>

            <div>
                <h2>Maintenance</h2>
                <span>System</span>
            </div>
        </div>

        <div class="menu-title">
            MENU SUPERVISOR
        </div>

        <nav>

            <a href="{{ route('supervisor.dashboard') }}"
               class="{{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
                <span>▣</span>
                Dashboard
            </a>

            <a href="{{ route('supervisor.mesin') }}"
               class="{{ request()->routeIs('supervisor.mesin') ? 'active' : '' }}">
                <span>⚙</span>
                Status Mesin
            </a>

            <a href="{{ route('supervisor.laporan') }}"
               class="{{ request()->routeIs('supervisor.laporan') ? 'active' : '' }}">
                <span>▤</span>
                Laporan Kerusakan
            </a>

            <a href="{{ route('supervisor.maintenance') }}"
               class="{{ request()->routeIs('supervisor.maintenance') ? 'active' : '' }}">
                <span>⚒</span>
                Laporan Maintenance
            </a>

            <div class="menu-title">
                RIWAYAT
            </div>

            <a href="{{ route('supervisor.riwayat.kerusakan') }}"
               class="{{ request()->routeIs('supervisor.riwayat.kerusakan') ? 'active' : '' }}">
                <span>◷</span>
                Riwayat Kerusakan
            </a>

            <a href="{{ route('supervisor.riwayat.perbaikan') }}"
               class="{{ request()->routeIs('supervisor.riwayat.perbaikan') ? 'active' : '' }}">
                <span>✓</span>
                Riwayat Perbaikan
            </a>

            <a href="{{ route('supervisor.sparepart') }}"
               class="{{ request()->routeIs('supervisor.sparepart') ? 'active' : '' }}">
                <span>▣</span>
                Penggunaan Sparepart
            </a>

            <div class="menu-title">
                DATA MASTER
            </div>

            <a href="{{ route('supervisor.data.mesin') }}"
               class="{{ request()->routeIs('supervisor.data.mesin', 'supervisor.mesin.create', 'supervisor.mesin.edit') ? 'active' : '' }}">
                <span>⚙</span>
                Data Mesin
            </a>

            <a href="{{ route('supervisor.data.sparepart') }}"
               class="{{ request()->routeIs('supervisor.data.sparepart', 'supervisor.sparepart.create', 'supervisor.sparepart.edit') ? 'active' : '' }}">
                <span>▣</span>
                Data Sparepart
            </a>

            <a href="{{ route('supervisor.data.pengguna') }}"
               class="{{ request()->routeIs('supervisor.data.pengguna', 'supervisor.pengguna.create', 'supervisor.pengguna.edit') ? 'active' : '' }}">
                <span>♙</span>
                Data Pengguna
            </a>

        </nav>

        <div class="sidebar-bottom">

            <div class="user-mini">
                <div class="avatar">
                    {{ strtoupper(substr($supervisorName, 0, 1)) }}
                </div>

                <div class="user-info">
                    <strong>{{ $supervisorName }}</strong>
                    <span>Supervisor</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-btn">
                    <span>↪</span>
                    Logout
                </button>
            </form>

        </div>

    </aside>

    <main class="main">

        <header class="topbar">

            <div>
                <h1>@yield('page-title', 'Dashboard')</h1>
                <p>@yield('page-description', 'Sistem Maintenance Mesin')</p>
            </div>

            <div class="topbar-user">
                @include('partials.notification-bell')

                <div class="top-user">
                    <div class="avatar small">
                        {{ strtoupper(substr($supervisorName, 0, 1)) }}
                    </div>

                    <div>
                        <strong>{{ $supervisorName }}</strong>
                        <small>Supervisor</small>
                    </div>
                </div>
            </div>

        </header>

        <section class="content">

            @if(session('success'))
                <div class="alert success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert error">
                    ! {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert error">
                    <strong>Terjadi kesalahan:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </section>

    </main>

</div>

<script src="{{ asset('js/notifications.js') }}" defer></script>

</body>
</html>
