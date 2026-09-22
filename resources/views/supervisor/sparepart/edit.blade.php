@extends('layouts.supervisor')

@section('title', 'Edit Sparepart')

@section('page-title', 'Edit Sparepart')

@section('page-description', 'Mengubah data master sparepart')


@section('content')

<div class="form-card">

    <div class="form-header">

        <h2>
            Edit Sparepart
        </h2>

        <p>
            Ubah informasi sparepart yang dipilih.
        </p>

    </div>


    @if($errors->any())

        <div class="alert-error">

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('supervisor.sparepart.update', $sparepart['kode']) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        <div class="form-group">

            <label for="kode">
                Kode Sparepart
            </label>

            <input
                type="text"
                id="kode"
                name="kode"
                value="{{ old('kode', $sparepart['kode']) }}"
                required
            >

        </div>


        <div class="form-group">

            <label for="nama">
                Nama Sparepart
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                value="{{ old('nama', $sparepart['nama']) }}"
                required
            >

        </div>


        <div class="form-group">

            <label for="jumlah">
                Stok
            </label>

            <input
                type="number"
                id="jumlah"
                name="jumlah"
                value="{{ old('jumlah', $sparepart['jumlah']) }}"
                min="0"
                required
            >

        </div>


        <div class="form-actions">

            <a
                href="{{ route('supervisor.data.sparepart') }}"
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