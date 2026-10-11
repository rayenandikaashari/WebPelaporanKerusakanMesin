@extends('layouts.teknisi')

@section('title', 'Detail Laporan')

@section('page-title', 'Detail Laporan Kerusakan')

@section('page-description', 'Periksa dan tangani laporan kerusakan mesin')

@section('content')

<div class="form-container">

    <div class="form-card">

        <div class="form-header">

            <div class="form-header-icon">
                🔧
            </div>

            <div>
                <h2>
                    #{{ str_pad($laporan->id, 3, '0', STR_PAD_LEFT) }}
                    - {{ $laporan->mesin->nama ?? '-' }}
                </h2>

                <p>
                    Laporan dibuat oleh
                    {{ $laporan->operator->name ?? '-' }}
                </p>
            </div>

        </div>

        <div style="padding: 25px;">

            @if(session('success'))
                <div class="alert success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="detail-grid">

                <div class="detail-item">
                    <span>Kode Laporan</span>
                    <strong>
                        #{{ str_pad($laporan->id, 3, '0', STR_PAD_LEFT) }}
                    </strong>
                </div>

                <div class="detail-item">
                    <span>Mesin</span>
                    <strong>
                        {{ $laporan->mesin->nama ?? '-' }}
                    </strong>
                </div>

                <div class="detail-item">
                    <span>Operator</span>
                    <strong>
                        {{ $laporan->operator->name ?? '-' }}
                    </strong>
                </div>

                <div class="detail-item">
                    <span>Tanggal Laporan</span>
                    <strong>
                        {{ $laporan->created_at?->format('d-m-Y H:i') ?? '-' }}
                    </strong>
                </div>

                <div class="detail-item">
                    <span>Prioritas</span>
                    <strong>-</strong>
                </div>

                <div class="detail-item">
                    <span>Status</span>
                    <strong>{{ $laporan->status }}</strong>
                </div>

            </div>

            <div class="detail-description">

                <span>Judul Kerusakan</span>

                <h3>{{ $laporan->judul }}</h3>

            </div>

            <div class="detail-description">

                <span>Deskripsi Kerusakan</span>

                <p>{{ $laporan->deskripsi }}</p>

            </div>

            @if($laporan->foto)
                <div class="detail-description">
                    <span>Foto Kerusakan</span>

                    <div style="margin-top: 10px;">
                        <img
                            src="{{ route('teknisi.laporan.foto', $laporan->id) }}"
                            alt="Foto Kerusakan Mesin"
                            class="damage-photo"
                            style="display: block; max-width: 100%; height: auto;"
                        >
                    </div>
                </div>
            @else
                <div class="detail-description">
                    <span>Foto Kerusakan</span>
                    <p>Foto tidak tersedia.</p>
                </div>
            @endif

            <div class="detail-description">

                <span>Hasil Perbaikan</span>

                <p>
                    {{ $laporan->hasil_perbaikan ?: '-' }}
                </p>

            </div>

            <div class="detail-description">

                <span>Sparepart yang Digunakan</span>

                <p>
                    {{ $laporan->sparepart ?: '-' }}
                </p>

            </div>

            <hr class="divider">

            <h3 class="form-section-title">
                Perbarui Status
            </h3>

            <form
                action="{{ route('teknisi.laporan.status', $laporan->id) }}"
                method="POST"
            >

                @csrf

                <div class="form-group">

                    <label for="status">
                        Status Perbaikan
                    </label>

                    <select name="status" id="status" required>

                        <option
                            value="Menunggu Ditangani"
                            {{ old('status', $laporan->status) === 'Menunggu Ditangani' ? 'selected' : '' }}
                        >
                            Menunggu Ditangani
                        </option>

                        <option
                            value="Sedang Diperbaiki"
                            {{ old('status', $laporan->status) === 'Sedang Diperbaiki' ? 'selected' : '' }}
                        >
                            Sedang Diperbaiki
                        </option>

                        <option
                            value="Selesai Diperbaiki"
                            {{ old('status', $laporan->status) === 'Selesai Diperbaiki' ? 'selected' : '' }}
                        >
                            Selesai Diperbaiki
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label for="hasil">
                        Hasil Perbaikan
                    </label>

                    <textarea
                        name="hasil"
                        id="hasil"
                        rows="4"
                        placeholder="Jelaskan tindakan dan hasil perbaikan..."
                    >{{ old('hasil', $laporan->hasil_perbaikan) }}</textarea>

                </div>

                
            <div class="form-group">
                <label>Sparepart yang Digunakan</label>

                @forelse ($spareparts as $part)
                    @php
                        $jumlahTerpakai = (int) (
                            $penggunaanSpareparts->get($part->id)?->jumlah ?? 0
                        );

                        $jumlahInput = old(
                            'penggunaan_spareparts.' . $part->id . '.jumlah',
                            $jumlahTerpakai
                        );
                    @endphp

                    <div style="display: grid; grid-template-columns: minmax(0, 1fr) 120px; gap: 12px; align-items: center; margin-bottom: 14px; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <div>
                            <label for="sparepart_{{ $part->id }}" style="display: block; margin-bottom: 4px;">
                                {{ $part->nama_sparepart }}
                            </label>

                            <small style="color: #6b7280;">
                                {{ $part->kode_sparepart }}
                                · Stok tersedia: {{ $part->stok }} {{ $part->satuan }}
                            </small>
                        </div>

                        <div>
                            <input
                                type="number"
                                id="sparepart_{{ $part->id }}"
                                name="penggunaan_spareparts[{{ $part->id }}][jumlah]"
                                min="0"
                                max="{{ $part->stok + $jumlahTerpakai }}"
                                value="{{ $jumlahInput }}"
                                style="width: 100%; box-sizing: border-box;"
                            >
                        </div>
                    </div>
                @empty
                    <p>Belum ada spare part aktif di database.</p>
                @endforelse

                <small style="color: #6b7280;">
                    Isi jumlah spare part yang digunakan. Isi 0 jika tidak digunakan.
                </small>
            </div>


                <button type="submit" class="btn-primary">
                    ✓ Simpan Perubahan
                </button>

            </form>

            @if($laporan->status === 'Selesai Diperbaiki')

                <hr class="divider">

                <div class="alert success">

                    <strong>✓ Perbaikan sudah selesai.</strong>

                    <br><br>

                    <strong>Hasil Perbaikan:</strong>

                    {{ $laporan->hasil_perbaikan ?: '-' }}

                    <br>

                    <strong>Sparepart:</strong>

                    {{ $laporan->sparepart ?: '-' }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
