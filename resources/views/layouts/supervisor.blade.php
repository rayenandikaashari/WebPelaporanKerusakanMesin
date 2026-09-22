<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Supervisor') - Sistem Maintenance
    </title>

    <link rel="stylesheet"
          href="{{ asset('css/operator.css') }}">

</head>

<body>

<div class="app-container">

    {{-- SIDEBAR --}}

    <aside class="sidebar">

        <div class="sidebar-header">

            <div class="sidebar-logo">
                🔧
            </div>

            <div>
                <strong>
                    Sistem Maintenance
                </strong>

                <span>
                    Supervisor
                </span>
            </div>

        </div>


        <nav class="sidebar-menu">

            <a href="{{ route('supervisor.dashboard') }}"
               class="{{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">

                <span>▦</span>
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

                <span>🔧</span>
                Laporan Maintenance

            </a>


            <div class="sidebar-section">
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


            <div class="sidebar-section">
                DATA MASTER
            </div>


            <a href="{{ route('supervisor.data.mesin') }}"
               class="{{ request()->routeIs('supervisor.data.mesin') ? 'active' : '' }}">

                <span>⚙</span>
                Data Mesin

            </a>


            <a href="{{ route('supervisor.data.sparepart') }}"
               class="{{ request()->routeIs('supervisor.data.sparepart') ? 'active' : '' }}">

                <span>▣</span>
                Data Sparepart

            </a>


            <a href="{{ route('supervisor.data.pengguna') }}"
               class="{{ request()->routeIs('supervisor.data.pengguna') ? 'active' : '' }}">

                <span>♙</span>
                Data Pengguna

            </a>

        </nav>


        <div class="sidebar-footer">

            <div class="user-info">

                <div class="user-avatar">
                    S
                </div>

                <div>

                    <strong>
                        {{ session('supervisor.nama', 'Supervisor') }}
                    </strong>

                    <span>
                        Supervisor
                    </span>

                </div>

            </div>


            <form action="{{ route('supervisor.logout') }}"
                  method="POST">

                @csrf

                <button type="submit"
                        class="logout-button">

                    ↪ Logout

                </button>

            </form>

        </div>

    </aside>


    {{-- MAIN CONTENT --}}

    <main class="main-content">

        <header class="top-header">

            <div>

                <h1>
                    @yield('page-title', 'Dashboard')
                </h1>

                <p>
                    @yield(
                        'page-description',
                        'Sistem Maintenance Mesin'
                    )
                </p>

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
                    ⚠ {{ session('error') }}
                </div>

            @endif


            @if($errors->any())

                <div class="alert error">

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            @yield('content')

        </section>

    </main>

</div>

</body>

</html>