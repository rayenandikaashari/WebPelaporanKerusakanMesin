@extends('layouts.teknisi')

@section('title', 'Dashboard Teknisi')

@section('page-title', 'Dashboard Teknisi')

@section('page-description', 'Kelola laporan kerusakan dan maintenance mesin')


@section('content')

<div class="welcome">

    <div>

        <span class="welcome-label">
            Selamat datang
        </span>

        <h2>
            {{ session('teknisi.nama') }} 🔧
        </h2>

        <p>
            Berikut laporan kerusakan yang perlu ditangani.
        </p>

    </div>

    <a
        href="{{ route('teknisi.maintenance') }}"
        class="btn-primary"
    >
        + Laporan Maintenance
    </a>

</div>


<div class="stats">

    <div class="stat-card">

        <div class="stat-icon blue">
            ▤
        </div>

        <div>

            <span>Total Laporan</span>

            <strong>
                {{ count($laporan) }}
            </strong>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon orange">
            ◷
        </div>

        <div>

            <span>Menunggu Ditangani</span>

            <strong>
                {{ collect($laporan)->where('status', 'Menunggu Ditangani')->count() }}
            </strong>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon green">
            ✓
        </div>

        <div>

            <span>Selesai</span>

            <strong>
                {{ collect($laporan)->where('status', 'Selesai Diperbaiki')->count() }}
            </strong>

        </div>

    </div>

</div>


<div class="section-header">

    <div>

        <h3>
            Laporan Kerusakan
        </h3>

        <p>
            Laporan dari operator yang menunggu penanganan
        </p>

    </div>

</div>


<div class="table-card">

@if(count($laporan) == 0)

    <div class="empty">

        <div class="empty-icon">
            ✓
        </div>

        <h3>
            Tidak ada laporan
        </h3>

        <p>
            Belum ada laporan kerusakan dari operator.
        </p>

    </div>

@else

<table>

<thead>

<tr>

    <th>ID</th>

    <th>Mesin</th>

    <th>Kerusakan</th>

    <th>Operator</th>

    <th>Tanggal</th>

    <th>Status</th>

    <th>Aksi</th>

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
        {{ $item['judul'] }}
    </td>

    <td>
        {{ $item['operator'] ?? '-' }}
    </td>

    <td>
        {{ $item['tanggal'] }}
    </td>

    <td>

        @if($item['status'] == 'Selesai Diperbaiki')

            <span class="status success-status">
                ✓ Selesai
            </span>

        @elseif($item['status'] == 'Sedang Diperbaiki')

            <span class="status"
                  style="background:#eff6ff;color:#2563eb;">
                🔧 Sedang Diperbaiki
            </span>

        @else

            <span class="status waiting-status">
                ◷ Menunggu
            </span>

        @endif

    </td>

    <td>

        <a
            href="{{ route('teknisi.laporan.detail', $item['id']) }}"
            class="btn-secondary"
        >
            Detail
        </a>

    </td>

</tr>

@endforeach

</tbody>

</table>

@endif

</div>

@endsection