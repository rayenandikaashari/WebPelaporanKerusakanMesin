@extends('layouts.operator')

@section('title', 'Dashboard Operator')

@section('page-title', 'Dashboard')

@section('page-description', 'Ringkasan laporan kerusakan mesin Anda')

@section('content')

<div class="welcome">
    <div>
        <span class="welcome-label">Selamat datang kembali</span>

        <h2>{{ auth()->user()->name }} 👋</h2>

        <p>Pantau laporan kerusakan mesin yang telah Anda buat.</p>
    </div>

    <a href="{{ route('operator.buat-laporan') }}" class="btn-primary">
        + Buat Laporan
    </a>
</div>

<div class="stats">
    <div class="stat-card">
        <div class="stat-icon blue">▤</div>
        <div>
            <span>Total Laporan</span>
            <strong>{{ $laporan->count() }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">◷</div>
        <div>
            <span>Menunggu Ditangani</span>
            <strong>
                {{ $laporan->where('status', 'Menunggu Ditangani')->count() }}
            </strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">✓</div>
        <div>
            <span>Selesai Diperbaiki</span>
            <strong>
                {{ $laporan->where('status', 'Selesai Diperbaiki')->count() }}
            </strong>
        </div>
    </div>
</div>

<div class="section-header">
    <div>
        <h3>Laporan Terbaru</h3>
        <p>Laporan kerusakan yang Anda buat</p>
    </div>

    <a href="{{ route('operator.laporan') }}">
        Lihat Semua →
    </a>
</div>

<div class="table-card">
    @if($laporan->isEmpty())
        <div class="empty">
            <div class="empty-icon">▤</div>

            <h3>Belum ada laporan</h3>

            <p>Anda belum membuat laporan kerusakan mesin.</p>

            <a href="{{ route('operator.buat-laporan') }}" class="btn-primary">
                + Buat Laporan
            </a>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mesin</th>
                    <th>Kerusakan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($laporan->take(5) as $item)
                    <tr>
                        <td>
                            <strong>
                                #{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                            </strong>
                        </td>

                        <td>
                            {{ $item->mesin?->nama ?? '-' }}
                        </td>

                        <td>
                            {{ $item->judul ?? $item->deskripsi_kerusakan ?? '-' }}
                        </td>

                        <td>
                            {{ $item->created_at?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td>
                            @if($item->status === 'Selesai Diperbaiki')
                                <span class="status success-status">
                                    ✓ Selesai
                                </span>
                            @else
                                <span class="status waiting-status">
                                    ◷ {{ $item->status ?? 'Menunggu Ditangani' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection