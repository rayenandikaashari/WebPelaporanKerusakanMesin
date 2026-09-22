@extends('layouts.supervisor')

@section('title', 'Data Mesin')

@section('page-title', 'Data Mesin')

@section('page-description', 'Mengelola data master mesin')


@section('content')

<div class="page-action">

    <div>

        <h2>
            Data Mesin
        </h2>

        <p>
            Daftar mesin yang terdaftar dalam sistem.
        </p>

    </div>

    <a href="{{ route('supervisor.mesin.create') }}" class="btn-primary">
        + Tambah Mesin
    </a>

</div>


<div class="table-card">

    <table>

        <thead>

            <tr>

                <th>Kode</th>
                <th>Nama Mesin</th>
                <th>Jenis</th>
                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($mesin as $item)

                <tr>

                    <td>
                        {{ $item['kode'] }}
                    </td>

                    <td>
                        <strong>
                            {{ $item['nama'] }}
                        </strong>
                    </td>

                    <td>
                        {{ $item['jenis'] }}
                    </td>

                    <td>

                        <div class="action-buttons">

                            <a
                                href="{{ route('supervisor.mesin.edit', $item['kode']) }}"
                                class="btn-edit"
                            >
                                ✏ Edit
                            </a>

                            <form
                                action="{{ route('supervisor.mesin.destroy', $item['kode']) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus mesin ini?')"
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

            @empty

                <tr>

                    <td colspan="4" style="text-align: center;">
                        Belum ada data mesin.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection