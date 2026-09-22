<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Operator - Maintenance System</title>

    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">

</head>

<body class="login-page">

    <div class="login-container">

        <div class="login-left">

            <div class="login-brand">

                <div class="logo-icon large">
                    ⚙
                </div>

                <h1>Maintenance<br>Management System</h1>

                <p>
                    Sistem pelaporan dan pemantauan
                    maintenance mesin.
                </p>

            </div>

        </div>


        <div class="login-right">

            <div class="login-box">

                <div class="login-heading">

                    <span class="badge">
                        OPERATOR
                    </span>

                    <h2>Selamat Datang 👋</h2>

                    <p>
                        Silakan login untuk melanjutkan.
                    </p>

                </div>


                @if(session('error'))

                    <div class="alert error">
                        {{ session('error') }}
                    </div>

                @endif


                <form
                    action="{{ route('operator.authenticate') }}"
                    method="POST"
                >

                    @csrf

                    <div class="form-group">

                        <label>Username</label>

                        <input
                            type="text"
                            name="username"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Password</label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                        >

                    </div>


                    <button class="btn-primary full">
                        Login
                    </button>

                </form>


                <div class="demo-account">

                    <strong>Akun Prototype</strong>

                    <span>Username: operator</span>
                    <span>Password: 123456</span>

                </div>

            </div>

        </div>

    </div>

</body>

</html>