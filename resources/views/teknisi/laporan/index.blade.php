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

        @if($laporan->isNotEmpty())

            <div class="laporan-list">

                @foreach($laporan as $item)

                    <div class="laporan-item">

                        <div class="laporan-main">

                            <div class="laporan-code">
                                #{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                            </div>

                            <h3>
                                {{ $item->judul }}
                            </h3>

                            <div class="laporan-info">

                                <span>
                                    ⚙ {{ $item->mesin->nama ?? '-' }}
                                </span>

                                <span>
                                    👤 {{ $item->operator->name ?? '-' }}
                                </span>

                                <span>
                                    📅 {{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}
                                </span>

                            </div>

                            <p class="laporan-description">
                                {{ $item->deskripsi }}
                            </p>

                            <div class="laporan-status">

                                @if($item->status === 'Menunggu Ditangani')
                                    <span class="status-badge waiting">
                                        Menunggu Ditangani
                                    </span>

                                @elseif($item->status === 'Sedang Diperbaiki')
                                    <span class="status-badge progress">
                                        Sedang Diperbaiki
                                    </span>

                                @elseif($item->status === 'Selesai Diperbaiki')
                                    <span class="status-badge completed">
                                        Selesai Diperbaiki
                                    </span>
                                @endif

                            </div>

                        </div>

                        <div class="laporan-action">

                            <a
                                href="{{ route('teknisi.laporan.detail', $item->id) }}"
                                class="btn-primary"
                            >
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