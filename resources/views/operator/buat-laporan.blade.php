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

                <p>
                    Isi informasi kerusakan mesin dengan lengkap.
                </p>

            </div>

        </div>


        <form
            action="{{ route('operator.laporan.simpan') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <div class="form-group">

                <label>
                    Mesin
                    <span>*</span>
                </label>

                <select name="mesin" required>

                    <option value="">
                        Pilih mesin
                    </option>

                    <option value="Mesin Produksi 01">
                        Mesin Produksi 01
                    </option>

                    <option value="Mesin Produksi 02">
                        Mesin Produksi 02
                    </option>

                    <option value="Mesin Produksi 03">
                        Mesin Produksi 03
                    </option>

                    <option value="Mesin Packing 01">
                        Mesin Packing 01
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Judul Kerusakan
                    <span>*</span>
                </label>

                <input
                    type="text"
                    name="judul"
                    placeholder="Contoh: Mesin tidak dapat menyala"
                    value="{{ old('judul') }}"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Deskripsi Kerusakan
                    <span>*</span>
                </label>

                <textarea
                    name="deskripsi"
                    rows="6"
                    placeholder="Jelaskan kondisi atau kerusakan mesin secara detail..."
                    required
                >{{ old('deskripsi') }}</textarea>

            </div>


            <div class="form-group">

                <label>
                    Foto Kerusakan
                </label>

                <div class="upload-box">

                    <div class="upload-icon">
                        📷
                    </div>

                    <strong>
                        Upload foto kerusakan
                    </strong>

                    <span>
                        JPG, JPEG, PNG maksimal 2 MB
                    </span>

                    <input
                        type="file"
                        name="foto"
                        accept=".jpg,.jpeg,.png"
                    >

                </div>

            </div>


            <div class="form-footer">

                <a
                    href="{{ route('operator.laporan') }}"
                    class="btn-secondary"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Kirim Laporan
                </button>

            </div>

        </form>

    </div>


    <div class="info-card">

        <div class="info-icon">
            ℹ
        </div>

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