@extends('layouts.supervisor')

@section('title', 'Data Pengguna')

@section('page-title', 'Data Pengguna')

@section('page-description', 'Mengelola data pengguna sistem')


@section('content')

<div class="page-action">

    <div>

        <h2>
            Data Pengguna
        </h2>

        <p>
            Daftar pengguna yang terdaftar dalam sistem.
        </p>

    </div>

    <a href="{{ route('supervisor.pengguna.create') }}" class="btn-primary">
        + Tambah Pengguna
    </a>

</div>


<div class="table-card">

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

            @foreach($pengguna as $item)

                <tr>

                    <td>
                        #{{ str_pad($item['id'], 3, '0', STR_PAD_LEFT) }}
                    </td>

                    <td>
                        <strong>
                            {{ $item['nama'] }}
                        </strong>
                    </td>

                    <td>
                        {{ $item['username'] }}
                    </td>

                    <td>
                        {{ $item['role'] }}
                    </td>

                    <td>

                        @if($item['status'] == 'Aktif')

                            <span class="status success-status">
                                ✓ Aktif
                            </span>

                        @else

                            <span class="status waiting-status">
                                ⚠ Tidak Aktif
                            </span>

                        @endif

                    </td>

                    <td>

                        <div class="action-buttons">

                            <a
                                href="{{ route('supervisor.pengguna.edit', $item['id']) }}"
                                class="btn-edit"
                            >
                                ✏ Edit
                            </a>


                            <form
                                action="{{ route('supervisor.pengguna.destroy', $item['id']) }}"
                                method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn-delete"
                                >
                                    🗑 Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection