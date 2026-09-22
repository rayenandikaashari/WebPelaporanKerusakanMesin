@extends('layouts.teknisi')

@section('title', 'Detail Laporan')

@section('page-title', 'Detail Laporan Kerusakan')

@section('page-description', 'Periksa dan tangani laporan kerusakan mesin')

@section('content')

<div class="form-container">

    <div class="form-card">

        {{-- HEADER --}}
        <div class="form-header">

            <div class="form-header-icon">
                🔧
            </div>

            <div>
                <h2>
                    #{{ str_pad($laporan['id'], 3, '0', STR_PAD_LEFT) }}
                    - {{ $laporan['mesin'] }}
                </h2>

                <p>
                    Laporan dibuat oleh
                    {{ $laporan['operator'] ?? '-' }}
                </p>
            </div>

        </div>


        {{-- ISI DETAIL --}}
        <div style="padding: 25px;">

            {{-- INFORMASI LAPORAN --}}
            <div class="detail-grid">

                <div class="detail-item">
                    <span>Kode Laporan</span>

                    <strong>
                        {{ $laporan['kode'] ?? '-' }}
                    </strong>
                </div>


                <div class="detail-item">
                    <span>Mesin</span>

                    <strong>
                        {{ $laporan['mesin'] ?? '-' }}
                    </strong>
                </div>


                <div class="detail-item">
                    <span>Operator</span>

                    <strong>
                        {{ $laporan['operator'] ?? '-' }}
                    </strong>
                </div>


                <div class="detail-item">
                    <span>Tanggal Laporan</span>

                    <strong>
                        {{ $laporan['tanggal'] ?? '-' }}
                    </strong>
                </div>


                <div class="detail-item">
                    <span>Prioritas</span>

                    <strong>
                        {{ $laporan['prioritas'] ?? '-' }}
                    </strong>
                </div>


                <div class="detail-item">
                    <span>Status</span>

                    <strong>
                        {{ $laporan['status'] ?? '-' }}
                    </strong>
                </div>

            </div>


            {{-- JUDUL KERUSAKAN --}}
            <div class="detail-description">

                <span>
                    Judul Kerusakan
                </span>

                <h3>
                    {{ $laporan['judul'] ?? '-' }}
                </h3>

            </div>


            {{-- DESKRIPSI / KERUSAKAN --}}
            <div class="detail-description">

                <span>
                    Deskripsi Kerusakan
                </span>

                <p>
                    {{ $laporan['kerusakan'] ?? '-' }}
                </p>

            </div>


            {{-- HASIL PERBAIKAN SEBELUMNYA --}}
            <div class="detail-description">

                <span>
                    Hasil Perbaikan
                </span>

                <p>
                    {{ $laporan['hasil'] ?? '-' }}
                </p>

            </div>


            {{-- SPAREPART --}}
            <div class="detail-description">

                <span>
                    Sparepart yang Digunakan
                </span>

                <p>
                    {{ $laporan['sparepart'] ?? '-' }}
                </p>

            </div>


            <hr class="divider">


            {{-- UPDATE STATUS --}}
            <h3 class="form-section-title">
                Perbarui Status
            </h3>


            <form
                action="{{ route('teknisi.laporan.status', $laporan['id']) }}"
                method="POST"
            >

                @csrf


                <div class="form-group">

                    <label>
                        Status Perbaikan
                    </label>

                    <select name="status" required>

                        <option
                            value="Menunggu Ditangani"
                            {{ ($laporan['status'] ?? '') == 'Menunggu Ditangani' ? 'selected' : '' }}
                        >
                            Menunggu Ditangani
                        </option>


                        <option
                            value="Sedang Diperbaiki"
                            {{ ($laporan['status'] ?? '') == 'Sedang Diperbaiki' ? 'selected' : '' }}
                        >
                            Sedang Diperbaiki
                        </option>


                        <option
                            value="Selesai Diperbaiki"
                            {{ ($laporan['status'] ?? '') == 'Selesai Diperbaiki' ? 'selected' : '' }}
                        >
                            Selesai Diperbaiki
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>
                        Hasil Perbaikan
                    </label>

                    <textarea
                        name="hasil"
                        rows="4"
                        placeholder="Jelaskan tindakan dan hasil perbaikan..."
                    >{{ $laporan['hasil'] != '-' ? $laporan['hasil'] : '' }}</textarea>

                </div>


                <div class="form-group">

                    <label>
                        Sparepart yang Digunakan
                    </label>

                    <textarea
                        name="sparepart"
                        rows="3"
                        placeholder="Contoh: Bearing 6204 - 2 pcs&#10;V-Belt A-45 - 1 pcs"
                    >{{ $laporan['sparepart'] != '-' ? $laporan['sparepart'] : '' }}</textarea>

                </div>


                <button
                    type="submit"
                    class="btn-primary"
                >
                    ✓ Simpan Perubahan
                </button>

            </form>


            {{-- HASIL JIKA SUDAH SELESAI --}}
            @if(($laporan['status'] ?? '') == 'Selesai Diperbaiki')

                <hr class="divider">


                <div class="alert success">

                    <strong>
                        ✓ Perbaikan sudah selesai.
                    </strong>

                    <br><br>

                    <strong>
                        Hasil Perbaikan:
                    </strong>

                    {{ $laporan['hasil'] ?? '-' }}

                    <br>

                    <strong>
                        Sparepart:
                    </strong>

                    {{ $laporan['sparepart'] ?? '-' }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection