
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Teknisi') - Sistem Maintenance</title>

    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">
</head>

<body>

<div class="app">

    <aside class="sidebar">

        <div class="logo">
            <div class="logo-icon">
                ⚙
            </div>

            <div>
                <h2>Maintenance</h2>
                <span>System</span>
            </div>
        </div>

        <div class="menu-title">
            MENU TEKNISI
        </div>

        <nav>

            <a href="{{ route('teknisi.dashboard') }}"
               class="{{ request()->routeIs('teknisi.dashboard') ? 'active' : '' }}">
                <span>▣</span>
                Dashboard
            </a>

            <a href="{{ route('teknisi.laporan.index') }}"
               class="{{ request()->routeIs('teknisi.laporan.index', 'teknisi.laporan.detail', 'teknisi.laporan.status') ? 'active' : '' }}">
                <span>▤</span>
                Laporan Kerusakan
            </a>

            <a href="{{ route('teknisi.maintenance.index') }}"
               class="{{ request()->routeIs('teknisi.maintenance.index', 'teknisi.maintenance.simpan') ? 'active' : '' }}">
                <span>⚒</span>
                Laporan Maintenance
            </a>

        </nav>

        <div class="sidebar-bottom">

            <div class="user-mini">

                <div class="avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'T', 0, 1)) }}
                </div>

                <div class="user-info">
                    <strong>
                        {{ auth()->user()->name ?? 'Teknisi' }}
                    </strong>

                    <span>Teknisi</span>
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

                <p>
                    @yield('page-description', 'Sistem Maintenance Mesin')
                </p>
            </div>

            <div class="topbar-user">

                <div class="notification">
                    🔔
                    <span></span>
                </div>

                <div class="top-user">

                    <div class="avatar small">
                        {{ strtoupper(substr(auth()->user()->name ?? 'T', 0, 1)) }}
                    </div>

                    <div>
                        <strong>
                            {{ auth()->user()->name ?? 'Teknisi' }}
                        </strong>

                        <small>Teknisi</small>
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

</body>

</html>
