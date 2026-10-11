<header class="navbar">
    <div class="navbar-title">
        @yield('navbar-title', 'Sistem Maintenance Mesin')
    </div>

    @auth
        <div class="navbar-user">
            {{ auth()->user()->name ?? 'Pengguna' }}
        </div>
    @endauth
</header>