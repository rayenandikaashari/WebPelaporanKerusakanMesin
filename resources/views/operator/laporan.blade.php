@extends('layouts.operator')

@section('title', 'Laporan Kerusakan')

@section('page-title', 'Laporan Kerusakan')

@section('page-description', 'Daftar laporan kerusakan yang Anda buat')


@section('content')

<div class="page-action">

    <div>

        <h2>Daftar Laporan</h2>

        <p>
            Anda hanya dapat melihat laporan yang Anda buat sendiri.
        </p>

    </div>

    <a href="{{ route('operator.laporan.buat') }}"
       class="btn-primary">

        + Buat Laporan

    </a>

</div>


<div class="table-card">

    @if(count($laporan) == 0)

        <div class="empty">

            <div class="empty-icon">
                ▤
            </div>

            <h3>Belum ada laporan</h3>

            <p>
                Silakan buat laporan kerusakan jika menemukan
                masalah pada mesin.
            </p>

            <a href="{{ route('operator.laporan.buat') }}"
               class="btn-primary">

                Buat Laporan

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
                                {{ Str::limit($item['deskripsi'], 60) }}
                            </small>

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
                                    ◷ Menunggu Ditangani
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