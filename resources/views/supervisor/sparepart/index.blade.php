@extends('layouts.supervisor')

@section('title', 'Penggunaan Sparepart')

@section('page-title', 'Penggunaan Sparepart')

@section('page-description', 'Pantau penggunaan sparepart untuk perbaikan mesin')


@section('content')

<div class="table-card">

    <table>

        <thead>

            <tr>

                <th>Kode</th>
                <th>Sparepart</th>
                <th>Stok</th>
                <th>Digunakan</th>

            </tr>

        </thead>

        <tbody>

            @foreach($sparepart as $item)

                <tr>

                    <td>
                        <strong>
                            {{ $item['kode'] }}
                        </strong>
                    </td>

                    <td>
                        {{ $item['nama'] }}
                    </td>

                    <td>
                        {{ $item['jumlah'] }}
                    </td>

                    <td>
                        {{ $item['digunakan'] }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection