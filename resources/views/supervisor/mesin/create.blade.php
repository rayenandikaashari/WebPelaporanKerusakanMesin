@extends('layouts.supervisor')

@section('title', 'Tambah Mesin')

@section('page-title', 'Tambah Mesin')

@section('page-description', 'Menambahkan data master mesin')


@section('content')

<div class="form-card">

    <form action="{{ route('supervisor.mesin.store') }}" method="POST">

        @csrf

        <div class="form-group">

            <label for="kode">
                Kode Mesin
            </label>

            <input
                type="text"
                id="kode"
                name="kode"
                placeholder="Contoh: MSN-010"
                value="{{ old('kode') }}"
                required
            >

        </div>


        <div class="form-group">

            <label for="nama">
                Nama Mesin
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                placeholder="Contoh: Mesin Produksi 10"
                value="{{ old('nama') }}"
                required
            >

        </div>


        <div class="form-group">

            <label for="jenis">
                Jenis Mesin
            </label>

            <select
                id="jenis"
                name="jenis"
                required
            >

                <option value="">
                    -- Pilih Jenis --
                </option>

                <option value="Produksi">
                    Produksi
                </option>

                <option value="Packing">
                    Packing
                </option>

                <option value="Conveyor">
                    Conveyor
                </option>

            </select>

        </div>


        <div class="form-actions">

            <a
                href="{{ route('supervisor.mesin') }}"
                class="btn-secondary"
            >
                Batal
            </a>

            <button
                type="submit"
                class="btn-primary"
            >
                Simpan Mesin
            </button>

        </div>

    </form>

</div>

@endsection