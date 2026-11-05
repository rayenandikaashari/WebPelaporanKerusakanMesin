
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Operator') - Sistem Maintenance</title>

    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>

<body>

    <div class="app">

        <aside class="sidebar" id="appSidebar">

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
                   class="{{ request()->routeIs('operator.laporan') ? 'active' : '' }}">
                    <span>▤</span>
                    Laporan Kerusakan
                </a>

                <a href="{{ route('operator.buat-laporan') }}"
                   class="{{ request()->routeIs('operator.buat-laporan') ? 'active' : '' }}">
                    <span>＋</span>
                    Buat Laporan
                </a>

            </nav>

            <div class="sidebar-bottom">

                <div class="user-mini">

                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'O', 0, 1)) }}
                    </div>

                    <div class="user-info">
                        <strong>
                            {{ auth()->user()->name ?? 'Operator' }}
                        </strong>

                        <span>Operator</span>
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

        <div class="mobile-sidebar-overlay"
             id="mobileSidebarOverlay"
             aria-hidden="true"></div>

        <main class="main">

            <header class="topbar">

                <div class="topbar-heading">
                    <button type="button"
                            class="mobile-menu-toggle"
                            id="mobileMenuToggle"
                            aria-label="Buka menu"
                            aria-controls="appSidebar"
                            aria-expanded="false">
                        ☰
                    </button>

                    <div>
                        <h1>@yield('page-title', 'Dashboard')</h1>
                        <p>@yield('page-description', 'Sistem Maintenance Mesin')</p>
                    </div>
                </div>

                <div class="topbar-user">

                    @include('partials.notification-bell')

                    <div class="top-user">

                        <div class="avatar small">
                            {{ strtoupper(substr(auth()->user()->name ?? 'O', 0, 1)) }}
                        </div>

                        <div>
                            <strong>
                                {{ auth()->user()->name ?? 'Operator' }}
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
<script src="{{ asset('js/notifications.js') }}" defer></script>
<script src="{{ asset('js/responsive-layout.js') }}" defer></script>

</body>

</html>
