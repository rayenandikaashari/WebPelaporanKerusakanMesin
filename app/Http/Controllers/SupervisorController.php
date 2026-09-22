<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view('supervisor.login');
    }


    public function authenticate(Request $request)
    {
        $nama = $request->nama ?: 'Supervisor';

        session([
            'supervisor' => [
                'nama' => $nama
            ]
        ]);

        return redirect()->route('supervisor.dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $laporan = $this->dummyLaporan();

        /*
        |--------------------------------------------------------------------------
        | Gunakan data mesin dari session
        |--------------------------------------------------------------------------
        */

        if (!session()->has('data_mesin')) {

            session([
                'data_mesin' => $this->dummyMesin()
            ]);

        }

        $mesin = session('data_mesin');


        $totalMesin = count($mesin);

        $mesinNormal = collect($mesin)
            ->where('status', 'Normal')
            ->count();

        $mesinBermasalah = $totalMesin - $mesinNormal;


        $totalLaporan = count($laporan);

        $laporanMenunggu = collect($laporan)
            ->where('status', 'Menunggu Ditangani')
            ->count();

        $laporanSelesai = collect($laporan)
            ->where('status', 'Selesai Diperbaiki')
            ->count();


        return view(
            'supervisor.dashboard',
            compact(
                'laporan',
                'mesin',
                'totalMesin',
                'mesinNormal',
                'mesinBermasalah',
                'totalLaporan',
                'laporanMenunggu',
                'laporanSelesai'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS MESIN
    |--------------------------------------------------------------------------
    */

    public function mesin()
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data mesin dari session
        |--------------------------------------------------------------------------
        */

        if (!session()->has('data_mesin')) {

            session([
                'data_mesin' => $this->dummyMesin()
            ]);

        }

        $mesin = session('data_mesin');


        return view(
            'supervisor.mesin',
            compact('mesin')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN KERUSAKAN
    |--------------------------------------------------------------------------
    */

    public function laporan()
    {
        $laporan = $this->dummyLaporan();

        return view(
            'supervisor.laporan',
            compact('laporan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN MAINTENANCE
    |--------------------------------------------------------------------------
    */

    public function maintenance()
    {
        $maintenance = $this->dummyMaintenance();

        return view(
            'supervisor.maintenance',
            compact('maintenance')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT KERUSAKAN
    |--------------------------------------------------------------------------
    */

    public function riwayatKerusakan()
    {
        $laporan = $this->dummyLaporan();

        return view(
            'supervisor.riwayat-kerusakan',
            compact('laporan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT PERBAIKAN
    |--------------------------------------------------------------------------
    */

    public function riwayatPerbaikan()
    {
        $laporan = $this->dummyLaporan();

        $laporan = collect($laporan)
            ->where('status', 'Selesai Diperbaiki')
            ->values()
            ->all();

        return view(
            'supervisor.riwayat-perbaikan',
            compact('laporan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SPAREPART
    |--------------------------------------------------------------------------
    */

    public function sparepart()
    {
        if (!session()->has('data_sparepart')) {

            session([
                'data_sparepart' => $this->dummySparepart()
            ]);

        }

        $sparepart = session('data_sparepart');

        return view(
            'supervisor.sparepart',
            compact('sparepart')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATA MASTER
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | DATA MESIN
    |--------------------------------------------------------------------------
    */

    public function dataMesin()
    {
        /*
        |--------------------------------------------------------------------------
        | Jika session data mesin belum ada,
        | gunakan dummy data sebagai data awal.
        |--------------------------------------------------------------------------
        */

        if (!session()->has('data_mesin')) {

            session([
                'data_mesin' => $this->dummyMesin()
            ]);

        }

        $mesin = session('data_mesin');


        return view(
            'supervisor.data-mesin',
            compact('mesin')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH MESIN
    |--------------------------------------------------------------------------
    */

    public function createMesin()
    {
        return view('supervisor.mesin.create');
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN MESIN BARU
    |--------------------------------------------------------------------------
    */

    public function storeMesin(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'jenis' => 'required|string|max:100',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Ambil data mesin dari session
        |--------------------------------------------------------------------------
        */

        $mesin = session(
            'data_mesin',
            $this->dummyMesin()
        );


        /*
        |--------------------------------------------------------------------------
        | Cek apakah kode sudah digunakan
        |--------------------------------------------------------------------------
        */

        $kodeSudahAda = collect($mesin)
            ->contains(function ($item) use ($request) {

                return $item['kode'] === $request->kode;

            });


        if ($kodeSudahAda) {

            return back()
                ->withInput()
                ->withErrors([
                    'kode' => 'Kode mesin sudah digunakan.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Buat ID baru
        |--------------------------------------------------------------------------
        */

        $idTerakhir = collect($mesin)
            ->max('id');

        $idBaru = $idTerakhir
            ? $idTerakhir + 1
            : 1;


        /*
        |--------------------------------------------------------------------------
        | Tambahkan mesin
        |--------------------------------------------------------------------------
        */

        $mesin[] = [

            'id' => $idBaru,

            'kode' => $request->kode,

            'nama' => $request->nama,

            'jenis' => $request->jenis,

            /*
            |--------------------------------------------------------------------------
            | Status awal mesin
            |--------------------------------------------------------------------------
            */

            'status' => 'Normal',
        ];


        /*
        |--------------------------------------------------------------------------
        | Simpan ke session
        |--------------------------------------------------------------------------
        */

        session([
            'data_mesin' => $mesin
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke Data Mesin
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('supervisor.data.mesin')
            ->with(
                'success',
                'Data mesin berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT MESIN
    |--------------------------------------------------------------------------
    */

    public function editMesin($kode)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data mesin
        |--------------------------------------------------------------------------
        */

        $mesin = session(
            'data_mesin',
            $this->dummyMesin()
        );


        /*
        |--------------------------------------------------------------------------
        | Cari mesin berdasarkan kode
        |--------------------------------------------------------------------------
        */

        $dataMesin = collect($mesin)
            ->firstWhere('kode', $kode);


        /*
        |--------------------------------------------------------------------------
        | Jika tidak ditemukan
        |--------------------------------------------------------------------------
        */

        if (!$dataMesin) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Tampilkan halaman edit
        |--------------------------------------------------------------------------
        */

        return view(
            'supervisor.mesin.edit',
            [
                'mesin' => $dataMesin
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE MESIN
    |--------------------------------------------------------------------------
    */

    public function updateMesin(
        Request $request,
        $kode
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'jenis' => 'required|string|max:100',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Ambil data mesin
        |--------------------------------------------------------------------------
        */

        $mesin = session(
            'data_mesin',
            $this->dummyMesin()
        );


        /*
        |--------------------------------------------------------------------------
        | Cek kode baru
        |
        | Kode boleh sama dengan kode mesin yang sedang diedit.
        | Tetapi tidak boleh sama dengan mesin lain.
        |--------------------------------------------------------------------------
        */

        $kodeSudahDigunakan = collect($mesin)
            ->contains(function ($item) use ($request, $kode) {

                return $item['kode'] !== $kode
                    && $item['kode'] === $request->kode;

            });


        if ($kodeSudahDigunakan) {

            return back()
                ->withInput()
                ->withErrors([
                    'kode' => 'Kode mesin sudah digunakan.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update data
        |--------------------------------------------------------------------------
        */

        foreach ($mesin as &$item) {

            if ($item['kode'] === $kode) {

                $item['kode'] = $request->kode;

                $item['nama'] = $request->nama;

                $item['jenis'] = $request->jenis;

                /*
                |--------------------------------------------------------------------------
                | Status tidak diubah di halaman Data Mesin.
                | Status tetap mengikuti data sebelumnya.
                |--------------------------------------------------------------------------
                */

                break;
            }
        }

        unset($item);


        /*
        |--------------------------------------------------------------------------
        | Simpan kembali ke session
        |--------------------------------------------------------------------------
        */

        session([
            'data_mesin' => $mesin
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke Data Mesin
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('supervisor.data.mesin')
            ->with(
                'success',
                'Data mesin berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS MESIN
    |--------------------------------------------------------------------------
    */

    public function destroyMesin($kode)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data mesin
        |--------------------------------------------------------------------------
        */

        $mesin = session(
            'data_mesin',
            $this->dummyMesin()
        );


        /*
        |--------------------------------------------------------------------------
        | Hapus mesin berdasarkan kode
        |--------------------------------------------------------------------------
        */

        $mesin = collect($mesin)
            ->reject(function ($item) use ($kode) {

                return $item['kode'] === $kode;

            })
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | Simpan kembali
        |--------------------------------------------------------------------------
        */

        session([
            'data_mesin' => $mesin
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke Data Mesin
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('supervisor.data.mesin')
            ->with(
                'success',
                'Data mesin berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DATA SPAREPART
    |--------------------------------------------------------------------------
    */

    public function dataSparepart()
    {
        /*
        |--------------------------------------------------------------------------
        | Jika session data sparepart belum ada,
        | gunakan dummy data sebagai data awal.
        |--------------------------------------------------------------------------
        */

        if (!session()->has('data_sparepart')) {

            session([
                'data_sparepart' => $this->dummySparepart()
            ]);

        }

        $sparepart = session('data_sparepart');


        return view(
            'supervisor.data-sparepart',
            compact('sparepart')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH SPAREPART
    |--------------------------------------------------------------------------
    */

    public function createSparepart()
    {
        return view('supervisor.sparepart.create');
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN SPAREPART BARU
    |--------------------------------------------------------------------------
    */

    public function storeSparepart(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:0',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Ambil data sparepart
        |--------------------------------------------------------------------------
        */

        $sparepart = session(
            'data_sparepart',
            $this->dummySparepart()
        );


        /*
        |--------------------------------------------------------------------------
        | Cek kode sudah digunakan
        |--------------------------------------------------------------------------
        */

        $kodeSudahAda = collect($sparepart)
            ->contains(function ($item) use ($request) {

                return $item['kode'] === $request->kode;

            });


        if ($kodeSudahAda) {

            return back()
                ->withInput()
                ->withErrors([
                    'kode' => 'Kode sparepart sudah digunakan.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Buat ID baru
        |--------------------------------------------------------------------------
        */

        $idTerakhir = collect($sparepart)
            ->max('id');

        $idBaru = $idTerakhir
            ? $idTerakhir + 1
            : 1;


        /*
        |--------------------------------------------------------------------------
        | Tambahkan sparepart
        |--------------------------------------------------------------------------
        */

        $sparepart[] = [

            'id' => $idBaru,

            'kode' => $request->kode,

            'nama' => $request->nama,

            'jumlah' => $request->jumlah,

            'digunakan' => 0,

        ];


        /*
        |--------------------------------------------------------------------------
        | Simpan ke session
        |--------------------------------------------------------------------------
        */

        session([
            'data_sparepart' => $sparepart
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke Data Sparepart
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('supervisor.data.sparepart')
            ->with(
                'success',
                'Data sparepart berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT SPAREPART
    |--------------------------------------------------------------------------
    */

    public function editSparepart($kode)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data sparepart
        |--------------------------------------------------------------------------
        */

        $sparepart = session(
            'data_sparepart',
            $this->dummySparepart()
        );


        /*
        |--------------------------------------------------------------------------
        | Cari sparepart berdasarkan kode
        |--------------------------------------------------------------------------
        */

        $dataSparepart = collect($sparepart)
            ->firstWhere('kode', $kode);


        /*
        |--------------------------------------------------------------------------
        | Jika tidak ditemukan
        |--------------------------------------------------------------------------
        */

        if (!$dataSparepart) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Tampilkan halaman edit
        |--------------------------------------------------------------------------
        */

        return view(
            'supervisor.sparepart.edit',
            [
                'sparepart' => $dataSparepart
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SPAREPART
    |--------------------------------------------------------------------------
    */

    public function updateSparepart(
        Request $request,
        $kode
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:0',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Ambil data sparepart
        |--------------------------------------------------------------------------
        */

        $sparepart = session(
            'data_sparepart',
            $this->dummySparepart()
        );


        /*
        |--------------------------------------------------------------------------
        | Cek kode baru
        |
        | Kode boleh sama dengan kode sparepart yang sedang diedit.
        | Tetapi tidak boleh sama dengan sparepart lain.
        |--------------------------------------------------------------------------
        */

        $kodeSudahDigunakan = collect($sparepart)
            ->contains(function ($item) use ($request, $kode) {

                return $item['kode'] !== $kode
                    && $item['kode'] === $request->kode;

            });


        if ($kodeSudahDigunakan) {

            return back()
                ->withInput()
                ->withErrors([
                    'kode' => 'Kode sparepart sudah digunakan.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update data
        |--------------------------------------------------------------------------
        */

        foreach ($sparepart as &$item) {

            if ($item['kode'] === $kode) {

                $item['kode'] = $request->kode;

                $item['nama'] = $request->nama;

                $item['jumlah'] = $request->jumlah;

                break;
            }
        }

        unset($item);


        /*
        |--------------------------------------------------------------------------
        | Simpan kembali
        |--------------------------------------------------------------------------
        */

        session([
            'data_sparepart' => $sparepart
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke Data Sparepart
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('supervisor.data.sparepart')
            ->with(
                'success',
                'Data sparepart berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS SPAREPART
    |--------------------------------------------------------------------------
    */

    public function destroySparepart($kode)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data sparepart
        |--------------------------------------------------------------------------
        */

        $sparepart = session(
            'data_sparepart',
            $this->dummySparepart()
        );


        /*
        |--------------------------------------------------------------------------
        | Hapus berdasarkan kode
        |--------------------------------------------------------------------------
        */

        $sparepart = collect($sparepart)
            ->reject(function ($item) use ($kode) {

                return $item['kode'] === $kode;

            })
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | Simpan kembali
        |--------------------------------------------------------------------------
        */

        session([
            'data_sparepart' => $sparepart
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke Data Sparepart
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('supervisor.data.sparepart')
            ->with(
                'success',
                'Data sparepart berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DATA PENGGUNA
    |--------------------------------------------------------------------------
    */

    public function dataPengguna()
    {
        $pengguna = [
            [
                'id' => 1,
                'nama' => 'Budi',
                'username' => 'budi',
                'role' => 'Operator',
                'status' => 'Aktif',
            ],

            [
                'id' => 2,
                'nama' => 'Andi',
                'username' => 'andi',
                'role' => 'Operator',
                'status' => 'Aktif',
            ],

            [
                'id' => 3,
                'nama' => 'Deni',
                'username' => 'deni',
                'role' => 'Operator',
                'status' => 'Aktif',
            ],

            [
                'id' => 4,
                'nama' => 'Teknisi 1',
                'username' => 'teknisi1',
                'role' => 'Teknisi',
                'status' => 'Aktif',
            ],

            [
                'id' => 5,
                'nama' => 'Supervisor',
                'username' => 'supervisor',
                'role' => 'Supervisor',
                'status' => 'Aktif',
            ],
        ];

        return view(
            'supervisor.data-pengguna',
            compact('pengguna')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT PENGGUNA
    |--------------------------------------------------------------------------
    */

    public function editPengguna($id)
    {
        $pengguna = [
            [
                'id' => 1,
                'nama' => 'Budi',
                'username' => 'budi',
                'role' => 'Operator',
                'status' => 'Aktif',
            ],

            [
                'id' => 2,
                'nama' => 'Andi',
                'username' => 'andi',
                'role' => 'Operator',
                'status' => 'Aktif',
            ],

            [
                'id' => 3,
                'nama' => 'Deni',
                'username' => 'deni',
                'role' => 'Operator',
                'status' => 'Aktif',
            ],

            [
                'id' => 4,
                'nama' => 'Teknisi 1',
                'username' => 'teknisi1',
                'role' => 'Teknisi',
                'status' => 'Aktif',
            ],

            [
                'id' => 5,
                'nama' => 'Supervisor',
                'username' => 'supervisor',
                'role' => 'Supervisor',
                'status' => 'Aktif',
            ],
        ];

        $dataPengguna = collect($pengguna)
            ->firstWhere('id', (int) $id);

        if (!$dataPengguna) {
            abort(404);
        }

        return view(
            'supervisor.pengguna.edit',
            [
                'pengguna' => $dataPengguna
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request->session()->forget('supervisor');

        return redirect()
            ->route('supervisor.login')
            ->with(
                'success',
                'Anda berhasil logout.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DUMMY DATA MESIN
    |--------------------------------------------------------------------------
    */

    private function dummyMesin()
    {
        return [
            [
                'id' => 1,
                'kode' => 'MSN-001',
                'nama' => 'Mesin Produksi 01',
                'jenis' => 'Produksi',
                'status' => 'Normal',
            ],
            [
                'id' => 2,
                'kode' => 'MSN-002',
                'nama' => 'Mesin Produksi 02',
                'jenis' => 'Produksi',
                'status' => 'Normal',
            ],
            [
                'id' => 3,
                'kode' => 'MSN-003',
                'nama' => 'Mesin Produksi 03',
                'jenis' => 'Produksi',
                'status' => 'Perlu Perbaikan',
            ],
            [
                'id' => 4,
                'kode' => 'MSN-004',
                'nama' => 'Mesin Produksi 04',
                'jenis' => 'Produksi',
                'status' => 'Normal',
            ],
            [
                'id' => 5,
                'kode' => 'MSN-005',
                'nama' => 'Mesin Produksi 05',
                'jenis' => 'Produksi',
                'status' => 'Normal',
            ],
            [
                'id' => 6,
                'kode' => 'MSN-006',
                'nama' => 'Mesin Packing 01',
                'jenis' => 'Packing',
                'status' => 'Normal',
            ],
            [
                'id' => 7,
                'kode' => 'MSN-007',
                'nama' => 'Mesin Packing 02',
                'jenis' => 'Packing',
                'status' => 'Normal',
            ],
            [
                'id' => 8,
                'kode' => 'MSN-008',
                'nama' => 'Mesin Packing 03',
                'jenis' => 'Packing',
                'status' => 'Perlu Perbaikan',
            ],
            [
                'id' => 9,
                'kode' => 'MSN-009',
                'nama' => 'Mesin Conveyor 01',
                'jenis' => 'Conveyor',
                'status' => 'Normal',
            ],
            [
                'id' => 10,
                'kode' => 'MSN-010',
                'nama' => 'Mesin Conveyor 02',
                'jenis' => 'Conveyor',
                'status' => 'Normal',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DUMMY DATA LAPORAN
    |--------------------------------------------------------------------------
    */

    private function dummyLaporan()
    {
        return [
            [
                'id' => 1,
                'kode' => 'LPR-001',
                'judul' => 'Mesin Tidak Dapat Menyala',
                'mesin' => 'Mesin Produksi 01',
                'operator' => 'Budi',
                'tanggal' => '22 September 2026',
                'kerusakan' => 'Mesin tidak dapat menyala saat akan digunakan.',
                'prioritas' => 'Tinggi',
                'status' => 'Menunggu Ditangani',
                'hasil' => '-',
                'sparepart' => '-',
            ],
            [
                'id' => 2,
                'kode' => 'LPR-002',
                'judul' => 'Suara Mesin Terlalu Berisik',
                'mesin' => 'Mesin Produksi 02',
                'operator' => 'Andi',
                'tanggal' => '22 September 2026',
                'kerusakan' => 'Terdengar suara berisik dari bagian mesin ketika beroperasi.',
                'prioritas' => 'Sedang',
                'status' => 'Sedang Diperbaiki',
                'hasil' => 'Pemeriksaan bearing sedang dilakukan.',
                'sparepart' => 'Bearing 6204',
            ],
            [
                'id' => 3,
                'kode' => 'LPR-003',
                'judul' => 'Motor Mengalami Panas Berlebih',
                'mesin' => 'Mesin Produksi 03',
                'operator' => 'Deni',
                'tanggal' => '21 September 2026',
                'kerusakan' => 'Motor mesin mengalami panas berlebih setelah digunakan.',
                'prioritas' => 'Tinggi',
                'status' => 'Selesai Diperbaiki',
                'hasil' => 'Motor telah diganti dan mesin kembali normal.',
                'sparepart' => 'Motor 2HP',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DUMMY DATA MAINTENANCE
    |--------------------------------------------------------------------------
    */

    private function dummyMaintenance()
    {
        return [
            [
                'tanggal' => '22 September 2026',
                'teknisi' => 'Teknisi 1',
                'total' => 10,
                'dicek' => 10,
                'status' => 'Selesai',
            ],
            [
                'tanggal' => '21 September 2026',
                'teknisi' => 'Teknisi 1',
                'total' => 10,
                'dicek' => 10,
                'status' => 'Selesai',
            ],
            [
                'tanggal' => '20 September 2026',
                'teknisi' => 'Teknisi 1',
                'total' => 10,
                'dicek' => 8,
                'status' => 'Belum Selesai',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DUMMY DATA SPAREPART
    |--------------------------------------------------------------------------
    */

    private function dummySparepart()
    {
        return [
            [
                'id' => 1,
                'kode' => 'SP-001',
                'nama' => 'Bearing 6204',
                'jumlah' => 12,
                'digunakan' => 3,
            ],
            [
                'id' => 2,
                'kode' => 'SP-002',
                'nama' => 'Motor 2HP',
                'jumlah' => 5,
                'digunakan' => 1,
            ],
            [
                'id' => 3,
                'kode' => 'SP-003',
                'nama' => 'V-Belt A42',
                'jumlah' => 20,
                'digunakan' => 5,
            ],
            [
                'id' => 4,
                'kode' => 'SP-004',
                'nama' => 'Oli Mesin',
                'jumlah' => 25,
                'digunakan' => 8,
            ],
        ];
    }


}