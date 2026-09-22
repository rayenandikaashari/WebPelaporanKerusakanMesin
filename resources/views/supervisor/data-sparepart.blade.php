@extends('layouts.supervisor')

@section('title', 'Data Sparepart')

@section('page-title', 'Data Sparepart')

@section('page-description', 'Mengelola data master sparepart')


@section('content')

<div class="page-action">

    <div>

        <h2>
            Data Sparepart
        </h2>

        <p>
            Daftar sparepart yang terdaftar dalam sistem.
        </p>

    </div>


    <a
        href="{{ route('supervisor.sparepart.create') }}"
        class="btn-primary"
    >
        + Tambah Sparepart
    </a>

</div>


@if(session('success'))

    <div class="alert-success">

        {{ session('success') }}

    </div>

@endif


<div class="table-card">

    <table>

        <thead>

            <tr>

                <th>Kode</th>

                <th>Nama Sparepart</th>

                <th>Stok</th>

                <th>Aksi</th>

            </tr>

        </thead>


        <tbody>

            @forelse($sparepart as $item)

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
                        {{ $item['jumlah'] }}
                    </td>


                    <td>

                        <div class="action-buttons">

                            <a
                                href="{{ route('supervisor.sparepart.edit', $item['kode']) }}"
                                class="btn-edit"
                            >
                                ✏ Edit
                            </a>


                            <form
                                action="{{ route('supervisor.sparepart.destroy', $item['kode']) }}"
                                method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Yakin ingin menghapus sparepart ini?')"
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

                    <td
                        colspan="4"
                        style="text-align: center;"
                    >
                        Belum ada data sparepart.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection