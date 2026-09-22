<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Operator') - Sistem Maintenance</title>

    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">
</head>

<body>

    <div class="app">

        <!-- SIDEBAR -->
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
                MENU OPERATOR
            </div>

            <nav>

                <a href="{{ route('operator.dashboard') }}"
                   class="{{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
                    <span>▣</span>
                    Dashboard
                </a>

                <a href="{{ route('operator.laporan') }}"
                   class="{{ request()->routeIs('operator.laporan*') ? 'active' : '' }}">
                    <span>▤</span>
                    Laporan Kerusakan
                </a>

                <a href="{{ route('operator.laporan.buat') }}"
                   class="{{ request()->routeIs('operator.laporan.buat') ? 'active' : '' }}">
                    <span>＋</span>
                    Buat Laporan
                </a>

            </nav>

            <div class="sidebar-bottom">

                <div class="user-mini">

                    <div class="avatar">
                        {{ strtoupper(substr(session('operator.nama', 'O'), 0, 1)) }}
                    </div>

                    <div class="user-info">
                        <strong>
                            {{ session('operator.nama', 'Operator') }}
                        </strong>

                        <span>Operator</span>
                    </div>

                </div>

                <form action="{{ route('operator.logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="logout-btn">
                        <span>↪</span>
                        Logout
                    </button>
                </form>

            </div>

        </aside>


        <!-- CONTENT -->
        <main class="main">

            <header class="topbar">

                <div>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-description', 'Sistem Maintenance Mesin')</p>
                </div>

                <div class="topbar-user">

                    <div class="notification">
                        🔔
                        <span></span>
                    </div>

                    <div class="top-user">
                        <div class="avatar small">
                            {{ strtoupper(substr(session('operator.nama', 'O'), 0, 1)) }}
                        </div>

                        <div>
                            <strong>
                                {{ session('operator.nama', 'Operator') }}
                            </strong>

                            <small>Operator</small>
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