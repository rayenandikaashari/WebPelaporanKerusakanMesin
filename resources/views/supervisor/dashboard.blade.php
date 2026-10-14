@extends('layouts.supervisor')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Supervisor')

@section('page-description', 'Pantau kondisi mesin dan aktivitas maintenance')


@section('content')

<div class="page-action">

    <div>

        <h2>
            Ringkasan Sistem
        </h2>

        <p>
            Informasi keseluruhan kondisi mesin dan laporan maintenance.
        </p>

    </div>

</div>


<div class="maintenance-summary">

    <div class="summary-card">

        <div class="summary-icon">
            ⚙
        </div>

        <div>

            <span>
                Total Mesin
            </span>

            <strong>
                {{ $totalMesin }}
            </strong>

        </div>

    </div>


    <div class="summary-card complete">

        <div class="summary-icon">
            ✓
        </div>

        <div>

            <span>
                Mesin Normal
            </span>

            <strong>
                {{ $mesinNormal }}
            </strong>

        </div>

    </div>


    <div class="summary-card waiting">

        <div class="summary-icon">
            ⚠
        </div>

        <div>

            <span>
                Perlu Perbaikan
            </span>

            <strong>
                {{ $mesinBermasalah }}
            </strong>

        </div>

    </div>


    <div class="summary-card">

        <div class="summary-icon">
            ▤
        </div>

        <div>

            <span>
                Total Laporan
            </span>

            <strong>
                {{ $totalLaporan }}
            </strong>

        </div>

    </div>

</div>


<div class="table-card">

    <div class="page-action">

        <div>

            <h2>
                Laporan Terbaru
            </h2>

            <p>
                Laporan kerusakan mesin terbaru.
            </p>

        </div>

        <a href="{{ route('supervisor.laporan') }}"
           class="btn-primary">

            Lihat Semua

        </a>

    </div>


    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Mesin</th>
                <th>Kerusakan</th>
                <th>Operator</th>
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

                        <strong>
                            {{ $item['judul'] }}
                        </strong>

                        <small class="table-description">
                            {{ Str::limit($item['kerusakan'], 50) }}
                        </small>

                    </td>

                    <td>
                        {{ $item['operator'] }}
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