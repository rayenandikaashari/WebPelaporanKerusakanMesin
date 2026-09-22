@extends('layouts.supervisor')

@section('title', 'Riwayat Kerusakan')

@section('page-title', 'Riwayat Kerusakan')

@section('page-description', 'Riwayat seluruh kerusakan mesin')


@section('content')

<div class="table-card">

    <table>

        <thead>

            <tr>
                <th>ID</th>
                <th>Mesin</th>
                <th>Kerusakan</th>
                <th>Operator</th>
                <th>Tanggal</th>
                <th>Status</th>
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
                        {{ $item['operator'] }}
                    </td>

                    <td>
                        {{ $item['tanggal'] }}
                    </td>

                    <td>

                        @if($item['status'] == 'Selesai Diperbaiki')

                            <span class="status success-status">
                                ✓ Selesai Diperbaiki
                            </span>

                        @else

                            <span class="status waiting-status">
                                ◷ {{ $item['status'] }}
                            </span>

                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection