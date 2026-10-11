@extends('layouts.supervisor')

@section('title', 'Laporan Kerusakan')

@section('page-title', 'Laporan Kerusakan')

@section('page-description', 'Melihat seluruh laporan kerusakan mesin')


@section('content')

<div class="page-action">

    <div>

        <h2>
            Seluruh Laporan Kerusakan
        </h2>

        <p>
            Supervisor dapat melihat seluruh laporan kerusakan dari operator.
        </p>

    </div>

</div>


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

            @foreach(array_reverse($laporan) as $item)

                <tr>

                    <td>
                        <strong>
                            #{{ str_pad($item['id'], 3, '0', STR_PAD_LEFT) }}
                        </strong>
                    </td>

                    <td>
                        {{ $item['mesin'] }}
                    </td>

                    <td>

                        <strong>
                            {{ $item['judul'] }}
                        </strong>

                        <small class="table-description">
                            {{ Str::limit($item['kerusakan'], 60) }}
                        </small>

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

                        @elseif($item['status'] == 'Sedang Diperbaiki')

                            <span class="status"
                                  style="background:#dbeafe;color:#1d4ed8;">

                                ⚙ Sedang Diperbaiki

                            </span>

                        @else

                            <span class="status waiting-status">
                                ◷ Menunggu Ditangani
                            </span>

                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection