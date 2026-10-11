<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Maintenance')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @include('layouts.partials.sidebar')

    <div class="main-content">
        @include('layouts.partials.navbar')
        @include('layouts.partials.alert')

        <main class="page-content">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>