<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view('teknisi.login');
    }

    public function authenticate(Request $request)
    {
        $nama = $request->nama ?: 'Teknisi';

        session([
            'teknisi' => [
                'nama' => $nama
            ]
        ]);

        return redirect()->route('teknisi.dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $laporan = $this->dummyLaporan();

        return view('teknisi.dashboard', compact('laporan'));
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN KERUSAKAN
    |--------------------------------------------------------------------------
    */

    public function laporan()
    {
        $laporan = $this->dummyLaporan();

        return view('teknisi.laporan', compact('laporan'));
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL LAPORAN
    |--------------------------------------------------------------------------
    */

    public function detailLaporan($id)
    {
        $laporan = collect($this->dummyLaporan())
            ->firstWhere('id', (int) $id);

        if (!$laporan) {
            abort(404);
        }

        return view('teknisi.detail-laporan', compact('laporan'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, $id)
    {
        return redirect()
            ->route('teknisi.laporan.detail', $id)
            ->with('success', 'Status perbaikan berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | MAINTENANCE HARIAN
    |--------------------------------------------------------------------------
    */

    public function maintenance(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Daftar 10 mesin
        |--------------------------------------------------------------------------
        */

        $mesin = [
            1  => 'Mesin Produksi 01',
            2  => 'Mesin Produksi 02',
            3  => 'Mesin Produksi 03',
            4  => 'Mesin Produksi 04',
            5  => 'Mesin Produksi 05',
            6  => 'Mesin Packing 01',
            7  => 'Mesin Packing 02',
            8  => 'Mesin Packing 03',
            9  => 'Mesin Conveyor 01',
            10 => 'Mesin Conveyor 02',
        ];


        /*
        |--------------------------------------------------------------------------
        | Tanggal yang dipilih
        |--------------------------------------------------------------------------
        */

        $tanggal = $request->tanggal
            ?? now()->format('Y-m-d');


        /*
        |--------------------------------------------------------------------------
        | Ambil data maintenance berdasarkan tanggal
        |--------------------------------------------------------------------------
        */

        $maintenance = session(
            'maintenance.' . $tanggal,
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Gabungkan 10 mesin dengan status pengecekan
        |--------------------------------------------------------------------------
        */

        $dataMesin = [];

        foreach ($mesin as $id => $nama) {

            $dataMesin[] = [

                'id' => $id,

                'nama' => $nama,

                'status' => $maintenance[$id]['status']
                    ?? 'Belum Dicek',

                'waktu' => $maintenance[$id]['waktu']
                    ?? null,

                'keterangan' => $maintenance[$id]['keterangan']
                    ?? null,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $totalMesin = count($dataMesin);

        $sudahDicek = collect($dataMesin)
            ->where('status', 'Sudah Dicek')
            ->count();

        $belumDicek = $totalMesin - $sudahDicek;


        return view(
            'teknisi.maintenance',
            compact(
                'dataMesin',
                'tanggal',
                'totalMesin',
                'sudahDicek',
                'belumDicek'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PENGECEKAN MESIN
    |--------------------------------------------------------------------------
    */

    public function simpanMaintenance(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'mesin_id' => 'required|integer|min:1|max:10',
            'keterangan' => 'nullable|string|max:1000',
        ]);


        $tanggal = $request->tanggal;
        $mesinId = (int) $request->mesin_id;


        /*
        |--------------------------------------------------------------------------
        | Ambil maintenance pada tanggal tersebut
        |--------------------------------------------------------------------------
        */

        $maintenance = session(
            'maintenance.' . $tanggal,
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Simpan hasil pengecekan
        |--------------------------------------------------------------------------
        */

        $maintenance[$mesinId] = [

            'status' => 'Sudah Dicek',

            'waktu' => now()->format('H:i'),

            'keterangan' => $request->keterangan
                ?: 'Mesin telah diperiksa dan dalam kondisi normal.',

        ];


        /*
        |--------------------------------------------------------------------------
        | Simpan kembali ke session
        |--------------------------------------------------------------------------
        */

        session([
            'maintenance.' . $tanggal => $maintenance
        ]);


        return redirect()
            ->route('teknisi.maintenance', [
                'tanggal' => $tanggal
            ])
            ->with(
                'success',
                'Pengecekan mesin berhasil dicatat.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request->session()->forget('teknisi');

        return redirect()
            ->route('teknisi.login')
            ->with('success', 'Anda berhasil logout.');
    }


    /*
    |--------------------------------------------------------------------------
    | DATA DUMMY LAPORAN KERUSAKAN
    |--------------------------------------------------------------------------
    */

    private function dummyLaporan()
    {
        return [

            [
                'id' => 1,
                'kode' => 'LPR-001',
                'judul' => 'Mesin Tidak Dapat Menyala',
                'mesin' => 'Mesin Produksi A',
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
                'mesin' => 'Mesin Produksi B',
                'operator' => 'Andi',
                'tanggal' => '22 September 2026',
                'kerusakan' => 'Terdengar suara berisik dari bagian mesin ketika sedang beroperasi.',
                'prioritas' => 'Sedang',
                'status' => 'Sedang Diperbaiki',
                'hasil' => 'Pemeriksaan bearing sedang dilakukan.',
                'sparepart' => 'Bearing 6204',
            ],

            [
                'id' => 3,
                'kode' => 'LPR-003',
                'judul' => 'Motor Mengalami Panas Berlebih',
                'mesin' => 'Mesin Produksi C',
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
}