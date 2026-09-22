<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login Teknisi</title>

    <link rel="stylesheet"
          href="{{ asset('css/operator.css') }}">

</head>

<body class="login-page">

<div class="login-container">

    <div class="login-left">

        <div class="login-brand">

            <div class="logo-icon large">
                ⚙
            </div>

            <h1>
                Maintenance<br>
                Management System
            </h1>

            <p>
                Sistem pengelolaan perbaikan
                dan maintenance mesin.
            </p>

        </div>

    </div>


    <div class="login-right">

        <div class="login-box">

            <div class="login-heading">

                <span class="badge">
                    TEKNISI
                </span>

                <h2>Login Teknisi 🔧</h2>

                <p>
                    Masuk untuk menangani laporan kerusakan.
                </p>

            </div>


            @if(session('error'))

                <div class="alert error">
                    {{ session('error') }}
                </div>

            @endif


            <form
                action="{{ route('teknisi.authenticate') }}"
                method="POST"
            >

                @csrf

                <div class="form-group">

                    <label>
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        placeholder="Masukkan username"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn-primary full"
                >
                    Login
                </button>

            </form>


            <div class="demo-account">

                <strong>Akun Prototype</strong>

                <span>
                    Username: teknisi
                </span>

                <span>
                    Password: 123456
                </span>

            </div>

        </div>

    </div>

</div>

</body>

</html>