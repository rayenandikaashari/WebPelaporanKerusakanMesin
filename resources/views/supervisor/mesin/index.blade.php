@extends('layouts.supervisor')

@section('title', 'Status Mesin')

@section('page-title', 'Status Seluruh Mesin')

@section('page-description', 'Pantau kondisi dan status seluruh mesin')


@section('content')

<div class="page-action">

    <div>

        <h2>
            Kondisi Mesin
        </h2>

        <p>
            Kondisi terkini dari seluruh mesin yang terdaftar.
        </p>

    </div>

</div>


<div class="table-card">

    <table>

        <thead>

            <tr>

                <th>Kode</th>
                <th>Nama Mesin</th>
                <th>Jenis</th>
                <th>Status</th>

            </tr>

        </thead>

        <tbody>

            @foreach($mesin as $item)

                <tr>

                    <td>
                        <strong>
                            {{ $item['kode'] }}
                        </strong>
                    </td>

                    <td>
                        {{ $item['nama'] }}
                    </td>

                    <td>
                        {{ $item['jenis'] }}
                    </td>

                    <td>

                        @if($item['status'] == 'Normal')

                            <span class="status success-status">
                                ✓ Normal
                            </span>

                        @else

                            <span class="status waiting-status">
                                ⚠ Perlu Perbaikan
                            </span>

                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection