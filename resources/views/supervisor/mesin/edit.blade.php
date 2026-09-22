@extends('layouts.supervisor')

@section('title', 'Edit Mesin')

@section('page-title', 'Edit Mesin')

@section('page-description', 'Mengubah data master mesin')


@section('content')

<div class="form-card">

    <form
        action="{{ route('supervisor.mesin.update', $mesin['kode']) }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        <div class="form-group">

            <label for="kode">
                Kode Mesin
            </label>

            <input
                type="text"
                id="kode"
                name="kode"
                value="{{ $mesin['kode'] }}"
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
                value="{{ $mesin['nama'] }}"
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

                <option value="Produksi"
                    {{ $mesin['jenis'] == 'Produksi' ? 'selected' : '' }}>
                    Produksi
                </option>

                <option value="Packing"
                    {{ $mesin['jenis'] == 'Packing' ? 'selected' : '' }}>
                    Packing
                </option>

                <option value="Conveyor"
                    {{ $mesin['jenis'] == 'Conveyor' ? 'selected' : '' }}>
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
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection