@extends('layouts.teknisi')

@section('title', 'Laporan Kerusakan')

@section('page-title', 'Laporan Kerusakan')

@section('page-description', 'Daftar laporan kerusakan mesin dari operator')

@section('content')

<div class="form-container">

    <div class="form-card">

        <div class="form-header">
            <div class="form-header-icon">
                🔧
            </div>

            <div>
                <h2>Daftar Laporan Kerusakan</h2>
                <p>Periksa laporan kerusakan mesin yang masuk dari operator.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(count($laporan) > 0)

            <div class="laporan-list">

                @foreach($laporan as $item)

                    <div class="laporan-item">

                        <div class="laporan-main">

                            <div class="laporan-code">
                                {{ $item['kode'] }}
                            </div>

                            <h3>
                                {{ $item['judul'] }}
                            </h3>

                            <div class="laporan-info">

                                <span>
                                    ⚙ {{ $item['mesin'] }}
                                </span>

                                <span>
                                    👤 {{ $item['operator'] }}
                                </span>

                                <span>
                                    📅 {{ $item['tanggal'] }}
                                </span>

                            </div>

                            <p class="laporan-description">
                                {{ $item['kerusakan'] }}
                            </p>

                            <div class="laporan-status">

                                @if($item['status'] == 'Menunggu Ditangani')
                                    <span class="status-badge waiting">
                                        Menunggu Ditangani
                                    </span>

                                @elseif($item['status'] == 'Sedang Diperbaiki')
                                    <span class="status-badge progress">
                                        Sedang Diperbaiki
                                    </span>

                                @elseif($item['status'] == 'Selesai Diperbaiki')
                                    <span class="status-badge completed">
                                        Selesai Diperbaiki
                                    </span>
                                @endif

                                <span class="priority-badge">
                                    Prioritas {{ $item['prioritas'] }}
                                </span>

                            </div>

                        </div>

                        <div class="laporan-action">

                            <a href="{{ route('teknisi.laporan.detail', $item['id']) }}"
                               class="btn-primary">
                                Lihat Detail
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-state">
                <div class="empty-icon">
                    📋
                </div>

                <h3>Belum Ada Laporan</h3>

                <p>
                    Belum terdapat laporan kerusakan mesin dari operator.
                </p>
            </div>

        @endif

    </div>

</div>

@endsection