@extends('layouts.supervisor')

@section('title', 'Riwayat Perbaikan')

@section('page-title', 'Riwayat Perbaikan')

@section('page-description', 'Riwayat perbaikan mesin yang telah selesai')


@section('content')

<div class="table-card">

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Mesin</th>
                <th>Kerusakan</th>
                <th>Hasil Perbaikan</th>
                <th>Sparepart</th>

            </tr>

        </thead>

        <tbody>

            @foreach($laporan as $item)

                <tr>

                    <td>
                        #{{ str_pad($item['id'], 3, '0', STR_PAD_LEFT) }}
                    </td>

                    <td>
                        {{ $item['mesin'] }}
                    </td>

                    <td>
                        {{ $item['judul'] }}
                    </td>

                    <td>
                        {{ $item['hasil'] }}
                    </td>

                    <td>
                        {{ $item['sparepart'] }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection