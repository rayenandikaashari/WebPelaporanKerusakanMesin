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
            MENU TEKNISI
        </div>


        <nav>

            <!-- Dashboard -->
            <a href="{{ route('teknisi.dashboard') }}"
               class="{{ request()->routeIs('teknisi.dashboard') ? 'active' : '' }}">

                <span>▣</span>

                Dashboard

            </a>


            <!-- Laporan Kerusakan -->
            <a href="{{ route('teknisi.laporan') }}"
               class="{{ request()->routeIs('teknisi.laporan*') ? 'active' : '' }}">

                <span>▤</span>

                Laporan Kerusakan

            </a>


            <!-- Maintenance -->
            <a href="{{ route('teknisi.maintenance') }}"
               class="{{ request()->routeIs('teknisi.maintenance*') ? 'active' : '' }}">

                <span>⚒</span>

                Laporan Maintenance

            </a>

        </nav>


        <!-- SIDEBAR BOTTOM -->
        <div class="sidebar-bottom">

            <div class="user-mini">

                <div class="avatar">

                    {{ strtoupper(substr(session('teknisi.nama', 'T'), 0, 1)) }}

                </div>


                <div class="user-info">

                    <strong>
                        {{ session('teknisi.nama', 'Teknisi') }}
                    </strong>

                    <span>
                        Teknisi
                    </span>

                </div>

            </div>


            <!-- Logout -->
            <form action="{{ route('teknisi.logout') }}" method="POST">

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


        <!-- TOPBAR -->
        <header class="topbar">

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


            <div class="topbar-user">

                <!-- Notification -->
                <div class="notification">

                    🔔

                    <span></span>

                </div>


                <!-- User -->
                <div class="top-user">

                    <div class="avatar small">

                        {{ strtoupper(substr(session('teknisi.nama', 'T'), 0, 1)) }}

                    </div>


                    <div>

                        <strong>
                            {{ session('teknisi.nama', 'Teknisi') }}
                        </strong>

                        <small>
                            Teknisi
                        </small>

                    </div>

                </div>

            </div>

        </header>



        <!-- PAGE CONTENT -->
        <section class="content">


            <!-- SUCCESS -->
            @if(session('success'))

                <div class="alert success">

                    ✓ {{ session('success') }}

                </div>

            @endif



            <!-- ERROR -->
            @if(session('error'))

                <div class="alert error">

                    ! {{ session('error') }}

                </div>

            @endif



            <!-- VALIDATION ERROR -->
            @if($errors->any())

                <div class="alert error">

                    <strong>
                        Terjadi kesalahan:
                    </strong>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif



            <!-- CHILD CONTENT -->
            @yield('content')


        </section>

    </main>

</div>

</body>

</html>