@extends('layouts.supervisor')

@section('title', 'Edit Pengguna')

@section('page-title', 'Edit Pengguna')

@section('page-description', 'Mengubah data pengguna sistem')


@section('content')

<div class="page-action">

    <div>

        <h2>
            Edit Pengguna
        </h2>

        <p>
            Ubah data pengguna yang dipilih.
        </p>

    </div>

    <a href="{{ route('supervisor.data.pengguna') }}" class="btn-secondary">
        ← Kembali
    </a>

</div>


<div class="form-card">

    <form
        action="{{ route('supervisor.pengguna.update', $pengguna['id']) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        {{-- Nama --}}

        <div class="form-group">

            <label for="nama">
                Nama
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                value="{{ old('nama', $pengguna['nama']) }}"
                required
            >

        </div>


        {{-- Username --}}

        <div class="form-group">

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username', $pengguna['username']) }}"
                required
            >

        </div>


        {{-- Password --}}

        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Kosongkan jika tidak ingin mengubah password"
            >

            <small>
                Kosongkan jika password tidak ingin diubah.
            </small>

        </div>


        {{-- Role --}}

        <div class="form-group">

            <label for="role">
                Role
            </label>

            <select
                id="role"
                name="role"
                required
            >

                <option
                    value="Operator"
                    {{ old('role', $pengguna['role']) == 'Operator' ? 'selected' : '' }}
                >
                    Operator
                </option>

                <option
                    value="Teknisi"
                    {{ old('role', $pengguna['role']) == 'Teknisi' ? 'selected' : '' }}
                >
                    Teknisi
                </option>

                <option
                    value="Supervisor"
                    {{ old('role', $pengguna['role']) == 'Supervisor' ? 'selected' : '' }}
                >
                    Supervisor
                </option>

            </select>

        </div>


        {{-- Status --}}

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
                    {{ old('status', $pengguna['status']) == 'Aktif' ? 'selected' : '' }}
                >
                    Aktif
                </option>

                <option
                    value="Tidak Aktif"
                    {{ old('status', $pengguna['status']) == 'Tidak Aktif' ? 'selected' : '' }}
                >
                    Tidak Aktif
                </option>

            </select>

        </div>


        {{-- Tombol --}}

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
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection