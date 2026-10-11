<aside class="sidebar">
    <div class="sidebar-brand">Maintenance Mesin</div>

    @auth
        @php($role = auth()->user()->role ?? '')

        <nav class="sidebar-menu">
            @if ($role === 'operator')
                <a href="{{ url('/operator/dashboard') }}">Dashboard</a>
                <a href="{{ url('/operator/laporan') }}">Laporan Kerusakan</a>
                <a href="{{ url('/operator/buat-laporan') }}">Buat Laporan</a>
            @elseif ($role === 'teknisi')
                <a href="{{ url('/teknisi/dashboard') }}">Dashboard</a>
                <a href="{{ url('/teknisi/laporan') }}">Laporan Kerusakan</a>
                <a href="{{ url('/teknisi/maintenance') }}">Laporan Maintenance</a>
            @elseif ($role === 'supervisor')
                <a href="{{ url('/supervisor/dashboard') }}">Dashboard</a>
                <a href="{{ url('/supervisor/laporan') }}">Laporan Kerusakan</a>
                <a href="{{ url('/supervisor/maintenance') }}">Laporan Maintenance</a>
                <a href="{{ url('/supervisor/data/pengguna') }}">Data Pengguna</a>
            @endif

            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </nav>
    @endauth
</aside>