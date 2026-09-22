@extends('layouts.teknisi')

@section('title', 'Laporan Maintenance')

@section('page-title', 'Laporan Maintenance')

@section('page-description', 'Pengecekan maintenance harian seluruh mesin')


@section('content')

<div class="maintenance-container">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert success">
            ✓ {{ session('success') }}
        </div>

    @endif


    {{-- HEADER --}}
    <div class="maintenance-header">

        <div>

            <div class="maintenance-title">

                <div class="maintenance-icon">
                    🛠
                </div>

                <div>

                    <h2>
                        Maintenance Harian
                    </h2>

                    <p>
                        Periksa seluruh mesin setelah kegiatan operasional selesai.
                    </p>

                </div>

            </div>

        </div>


        {{-- PILIH TANGGAL --}}
        <form
            action="{{ route('teknisi.maintenance') }}"
            method="GET"
            class="date-filter"
        >

            <label>
                Tanggal Maintenance
            </label>

            <input
                type="date"
                name="tanggal"
                value="{{ $tanggal }}"
                onchange="this.form.submit()"
            >

        </form>

    </div>


    {{-- RINGKASAN --}}
    <div class="maintenance-summary">

        <div class="summary-card">

            <div class="summary-icon">
                ⚙
            </div>

            <div>

                <span>Total Mesin</span>

                <strong>
                    {{ $totalMesin }}
                </strong>

            </div>

        </div>


        <div class="summary-card waiting">

            <div class="summary-icon">
                ◷
            </div>

            <div>

                <span>Belum Dicek</span>

                <strong>
                    {{ $belumDicek }}
                </strong>

            </div>

        </div>


        <div class="summary-card complete">

            <div class="summary-icon">
                ✓
            </div>

            <div>

                <span>Sudah Dicek</span>

                <strong>
                    {{ $sudahDicek }}
                </strong>

            </div>

        </div>

    </div>


    {{-- PROGRESS --}}
    <div class="maintenance-progress">

        <div class="progress-header">

            <div>

                <strong>
                    Progress Maintenance
                </strong>

                <span>
                    {{ $sudahDicek }} dari {{ $totalMesin }} mesin telah diperiksa
                </span>

            </div>

            <strong>
                {{ $totalMesin > 0 ? round(($sudahDicek / $totalMesin) * 100) : 0 }}%
            </strong>

        </div>


        <div class="progress-bar">

            <div
                class="progress-fill"
                style="width: {{ $totalMesin > 0 ? ($sudahDicek / $totalMesin) * 100 : 0 }}%;"
            ></div>

        </div>

    </div>


    {{-- DAFTAR MESIN --}}
    <div class="machine-card">

        <div class="machine-card-header">

            <div>

                <h2>
                    Daftar Mesin
                </h2>

                <p>
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                </p>

            </div>

            @if($sudahDicek == $totalMesin)

                <div class="all-complete">
                    ✓ Semua Mesin Sudah Dicek
                </div>

            @else

                <div class="not-complete">
                    ⚠ Masih Ada Mesin Belum Dicek
                </div>

            @endif

        </div>


        <div class="machine-list">

            @foreach($dataMesin as $item)

                <div
                    class="machine-row {{ $item['status'] == 'Sudah Dicek' ? 'checked' : 'unchecked' }}"
                >

                    {{-- STATUS --}}
                    <div class="machine-status">

                        @if($item['status'] == 'Sudah Dicek')

                            <div class="status-circle checked-circle">
                                ✓
                            </div>

                        @else

                            <div class="status-circle unchecked-circle">
                            </div>

                        @endif

                    </div>


                    {{-- NOMOR --}}
                    <div class="machine-number">

                        {{ str_pad($item['id'], 2, '0', STR_PAD_LEFT) }}

                    </div>


                    {{-- INFORMASI --}}
                    <div class="machine-info">

                        <strong>
                            {{ $item['nama'] }}
                        </strong>

                        @if($item['status'] == 'Sudah Dicek')

                            <span class="status-text success-text">
                                ✓ Sudah Dicek
                            </span>

                            @if($item['waktu'])

                                <small>
                                    Dicek pukul {{ $item['waktu'] }}
                                </small>

                            @endif

                        @else

                            <span class="status-text waiting-text">
                                Belum Dicek
                            </span>

                            <small>
                                Menunggu pemeriksaan teknisi
                            </small>

                        @endif

                    </div>


                    {{-- ACTION --}}
                    <div class="machine-action">

                        @if($item['status'] == 'Sudah Dicek')

                            <button
                                type="button"
                                class="btn-checked"
                                disabled
                            >
                                ✓ Sudah Dicek
                            </button>

                        @else

                            <button
                                type="button"
                                class="btn-check-machine"
                                onclick="openMaintenanceModal(
                                    {{ $item['id'] }},
                                    '{{ $item['nama'] }}'
                                )"
                            >
                                Cek Mesin
                            </button>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>


{{-- MODAL --}}
<div
    id="maintenanceModal"
    class="maintenance-modal"
>

    <div class="modal-overlay" onclick="closeMaintenanceModal()"></div>


    <div class="modal-content">

        <div class="modal-header">

            <div>

                <span>
                    Pengecekan Mesin
                </span>

                <h2 id="modalMachineName">
                    -
                </h2>

            </div>

            <button
                type="button"
                class="modal-close"
                onclick="closeMaintenanceModal()"
            >
                ×
            </button>

        </div>


        <form
            action="{{ route('teknisi.maintenance.simpan') }}"
            method="POST"
        >

            @csrf

            <input
                type="hidden"
                name="tanggal"
                value="{{ $tanggal }}"
            >

            <input
                type="hidden"
                name="mesin_id"
                id="modalMachineId"
            >


            <div class="modal-body">

                <div class="check-info">

                    <div class="check-icon">
                        🔧
                    </div>

                    <div>

                        <strong>
                            Periksa kondisi mesin
                        </strong>

                        <p>
                            Pastikan mesin telah diperiksa secara menyeluruh
                            sebelum menandai sebagai selesai.
                        </p>

                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Keterangan Pemeriksaan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="4"
                        placeholder="Contoh: Kondisi mesin normal, tidak ditemukan kerusakan..."
                    ></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-secondary"
                    onclick="closeMaintenanceModal()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    ✓ Tandai Sudah Dicek
                </button>

            </div>

        </form>

    </div>

</div>


<script>

function openMaintenanceModal(id, nama)
{
    document.getElementById('modalMachineId').value = id;

    document.getElementById('modalMachineName').innerText = nama;

    document.getElementById('maintenanceModal')
        .classList.add('show');
}


function closeMaintenanceModal()
{
    document.getElementById('maintenanceModal')
        .classList.remove('show');
}

</script>

@endsection