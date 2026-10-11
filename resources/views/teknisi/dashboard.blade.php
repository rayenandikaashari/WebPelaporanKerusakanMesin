
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
            {{ auth()->user()->name }} 🔧
        </h2>

        <p>
            Berikut laporan kerusakan yang perlu ditangani.
        </p>
    </div>

    <a
        href="{{ route('teknisi.maintenance.index') }}"
        class="btn-primary"
    >
        + Laporan Maintenance
    </a>
</div>

<div class="stats">
    <div class="stat-card">
        <div class="stat-icon blue">▤</div>

        <div>
            <span>Total Laporan</span>
            <strong>{{ $totalLaporan }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">◷</div>

        <div>
            <span>Menunggu Ditangani</span>
            <strong>{{ $menunggu }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">✓</div>

        <div>
            <span>Selesai</span>
            <strong>{{ $selesai }}</strong>
        </div>
    </div>
</div>

<div class="section-header">
    <div>
        <h3>Laporan Kerusakan</h3>

        <p>
            Laporan dari operator yang perlu ditangani
        </p>
    </div>
</div>

<div class="table-card">

    @if($laporan->isEmpty())

        <div class="empty">
            <div class="empty-icon">✓</div>

            <h3>Tidak ada laporan</h3>

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
                @foreach($laporan as $item)
                    <tr>
                        <td>
                            <strong>
                                #{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                            </strong>
                        </td>

                        <td>
                            {{ $item->mesin->nama ?? '-' }}
                        </td>

                        <td>
                            {{ $item->judul }}
                        </td>

                        <td>
                            {{ $item->operator->name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}
                        </td>

                        <td>
                            @if($item->status === 'Selesai Diperbaiki')
                                <span class="status success-status">
                                    ✓ Selesai
                                </span>
                            @elseif($item->status === 'Sedang Diperbaiki')
                                <span
                                    class="status"
                                    style="background:#eff6ff;color:#2563eb;"
                                >
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
                                href="{{ route('teknisi.laporan.detail', $item->id) }}"
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
