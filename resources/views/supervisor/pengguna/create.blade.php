@extends('layouts.supervisor')

@section('title', 'Tambah Pengguna')

@section('page-title', 'Tambah Pengguna')

@section('page-description', 'Menambahkan pengguna baru ke dalam sistem')


@section('content')

<div class="form-card">

    <div class="form-header">

        <h2>
            Tambah Pengguna
        </h2>

        <p>
            Isi data pengguna baru yang akan ditambahkan.
        </p>

    </div>


    <form
        action="{{ route('supervisor.pengguna.store') }}"
        method="POST"
    >

        @csrf


        <div class="form-group">

            <label for="nama">
                Nama
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                value="{{ old('nama') }}"
                placeholder="Masukkan nama pengguna"
                required
            >

            @error('nama')
                <span class="error-message">
                    {{ $message }}
                </span>
            @enderror

        </div>


        <div class="form-group">

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username') }}"
                placeholder="Masukkan username"
                required
            >

            @error('username')
                <span class="error-message">
                    {{ $message }}
                </span>
            @enderror

        </div>


        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
            >

            @error('password')
                <span class="error-message">
                    {{ $message }}
                </span>
            @enderror

        </div>


        <div class="form-group">

            <label for="role">
                Role
            </label>

            <select
                id="role"
                name="role"
                required
            >

                <option value="">
                    -- Pilih Role --
                </option>

                <option
                    value="Operator"
                    {{ old('role') == 'Operator' ? 'selected' : '' }}
                >
                    Operator
                </option>

                <option
                    value="Teknisi"
                    {{ old('role') == 'Teknisi' ? 'selected' : '' }}
                >
                    Teknisi
                </option>

                <option
                    value="Supervisor"
                    {{ old('role') == 'Supervisor' ? 'selected' : '' }}
                >
                    Supervisor
                </option>

            </select>

            @error('role')
                <span class="error-message">
                    {{ $message }}
                </span>
            @enderror

        </div>


        <div class="form-group">

            <label for="status">
                Status
            </label>

            <select
                id="status"
                name="status"
                required
            >

                <option
                    value="Aktif"
                    {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}
                >
                    Aktif
                </option>

                <option
                    value="Tidak Aktif"
                    {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}
                >
                    Tidak Aktif
                </option>

            </select>

            @error('status')
                <span class="error-message">
                    {{ $message }}
                </span>
            @enderror

        </div>


        <div class="form-actions">

            <a
                href="{{ route('supervisor.data.pengguna') }}"
                class="btn-secondary"
            >
                Batal
            </a>

            <button
                type="submit"
                class="btn-primary"
            >
                Simpan Pengguna
            </button>

        </div>

    </form>

</div>

@endsection