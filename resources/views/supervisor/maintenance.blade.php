@extends('layouts.supervisor')

@section('title', 'Laporan Maintenance')

@section('page-title', 'Laporan Maintenance')

@section('page-description', 'Melihat laporan maintenance harian seluruh mesin')


@section('content')

<div class="table-card">

    <div class="page-action">

        <div>

            <h2>
                Laporan Maintenance
            </h2>

            <p>
                Riwayat pemeriksaan maintenance yang dilakukan teknisi.
            </p>

        </div>

    </div>


    <table>

        <thead>

            <tr>

                <th>Tanggal</th>
                <th>Teknisi</th>
                <th>Total Mesin</th>
                <th>Sudah Dicek</th>
                <th>Status</th>

            </tr>

        </thead>

        <tbody>

            @foreach($maintenance as $item)

                <tr>

                    <td>
                        {{ $item['tanggal'] }}
                    </td>

                    <td>
                        {{ $item['teknisi'] }}
                    </td>

                    <td>
                        {{ $item['total'] }}
                    </td>

                    <td>
                        {{ $item['dicek'] }}
                    </td>

                    <td>

                        @if($item['status'] == 'Selesai')

                            <span class="status success-status">
                                ✓ Selesai
                            </span>

                        @else

                            <span class="status waiting-status">
                                ◷ Belum Selesai
                            </span>

                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection