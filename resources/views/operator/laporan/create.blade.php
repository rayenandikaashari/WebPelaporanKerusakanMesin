
@extends('layouts.operator')

@section('title', 'Buat Laporan')

@section('page-title', 'Buat Laporan Kerusakan')

@section('page-description', 'Laporkan kerusakan mesin kepada teknisi')

@section('content')

<div class="form-container">

    <div class="form-card">

        <div class="form-header">
            <div class="form-header-icon">
                ⚠
            </div>

            <div>
                <h2>Form Laporan Kerusakan</h2>
                <p>Isi informasi kerusakan mesin dengan lengkap.</p>
            </div>
        </div>

        <form
            action="{{ route('operator.simpan-laporan') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="form-group">
                <label for="mesin_id">
                    Mesin <span>*</span>
                </label>

                <select name="mesin_id" id="mesin_id" required>
                    <option value="">Pilih mesin</option>

                    @foreach ($mesins as $mesin)
                        <option
                            value="{{ $mesin->id }}"
                            {{ old('mesin_id') == $mesin->id ? 'selected' : '' }}
                        >
                            {{ $mesin->nama }}
                        </option>
                    @endforeach
                </select>

                @error('mesin_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="judul">
                    Judul Kerusakan <span>*</span>
                </label>

                <input
                    type="text"
                    id="judul"
                    name="judul"
                    placeholder="Contoh: Mesin tidak dapat menyala"
                    value="{{ old('judul') }}"
                    maxlength="255"
                    required
                >

                @error('judul')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi">
                    Deskripsi Kerusakan <span>*</span>
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="6"
                    placeholder="Jelaskan kondisi atau kerusakan mesin secara detail..."
                    required
                >{{ old('deskripsi') }}</textarea>

                @error('deskripsi')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="foto">Foto Kerusakan</label>

                <div class="upload-box">
                    <div class="upload-icon">📷</div>

                    <strong>Upload foto kerusakan</strong>

                    <span>JPG, JPEG, PNG maksimal 2 MB</span>

                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept=".jpg,.jpeg,.png"
                    >
                </div>

                @error('foto')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-footer">
                <a
                    href="{{ route('operator.laporan') }}"
                    class="btn-secondary"
                >
                    Batal
                </a>

                <button type="submit" class="btn-primary">
                    Kirim Laporan
                </button>
            </div>

        </form>

    </div>

    <div class="info-card">
        <div class="info-icon">ℹ</div>

        <div>
            <h3>Informasi</h3>

            <p>
                Setelah laporan dikirim, teknisi akan menerima
                laporan dan melakukan pemeriksaan terhadap mesin.
            </p>

            <p>
                Status laporan dapat Anda pantau melalui menu
                <strong>Laporan Kerusakan</strong>.
            </p>
        </div>
    </div>

</div>

@endsection
