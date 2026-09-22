@extends('layouts.supervisor')

@section('title', 'Tambah Sparepart')

@section('page-title', 'Tambah Sparepart')

@section('page-description', 'Menambahkan data sparepart baru')


@section('content')

<div class="form-card">

    <div class="form-header">

        <h2>
            Tambah Sparepart
        </h2>

        <p>
            Masukkan data sparepart yang akan ditambahkan.
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
        action="{{ route('supervisor.sparepart.store') }}"
        method="POST"
    >

        @csrf


        <div class="form-group">

            <label for="kode">
                Kode Sparepart
            </label>

            <input
                type="text"
                id="kode"
                name="kode"
                value="{{ old('kode') }}"
                placeholder="Contoh: SP-005"
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
                value="{{ old('nama') }}"
                placeholder="Contoh: Bearing 6205"
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
                value="{{ old('jumlah', 0) }}"
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
                Simpan Sparepart
            </button>

        </div>

    </form>

</div>

@endsection