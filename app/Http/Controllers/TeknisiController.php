<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller
{
    public function dashboard()
    {
        $this->checkLogin();

        $laporan = session('laporan_operator', []);

        return view('teknisi.dashboard', compact('laporan'));
    }

    public function laporan()
    {
        $this->checkLogin();

        $laporan = session('laporan_operator', []);

        return view('teknisi.laporan.index', compact('laporan'));
    }

    public function detailLaporan($id)
    {
        $this->checkLogin();

        $laporan = collect(session('laporan_operator', []))
            ->firstWhere('id', (int) $id);

        if (!$laporan) {
            abort(404, 'Laporan kerusakan tidak ditemukan.');
        }

        return view('teknisi.laporan.show', compact('laporan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $this->checkLogin();

        $request->validate([
            'status' => ['required', 'in:Menunggu Ditangani,Sedang Diperbaiki,Selesai Diperbaiki'],
            'hasil' => ['nullable', 'string', 'max:2000'],
            'sparepart' => ['nullable', 'string', 'max:1000'],
        ]);

        $laporan = session('laporan_operator', []);
        $index = null;

        foreach ($laporan as $key => $item) {
            if ((int) $item['id'] === (int) $id) {
                $index = $key;
                break;
            }
        }

        if ($index === null) {
            abort(404, 'Laporan kerusakan tidak ditemukan.');
        }

        $laporan[$index]['status'] = $request->status;

        if ($request->filled('hasil')) {
            $laporan[$index]['hasil'] = $request->hasil;
        }

        if ($request->filled('sparepart')) {
            $laporan[$index]['sparepart'] = $request->sparepart;
        }

        session([
            'laporan_operator' => $laporan,
        ]);

        return redirect()
            ->route('teknisi.laporan.detail', $id)
            ->with('success', 'Status perbaikan berhasil diperbarui.');
    }

    public function maintenance(Request $request)
    {
        $this->checkLogin();

        $mesin = [
            1 => 'Mesin Produksi 01',
            2 => 'Mesin Produksi 02',
            3 => 'Mesin Produksi 03',
            4 => 'Mesin Produksi 04',
            5 => 'Mesin Produksi 05',
            6 => 'Mesin Packing 01',
            7 => 'Mesin Packing 02',
            8 => 'Mesin Packing 03',
            9 => 'Mesin Conveyor 01',
            10 => 'Mesin Conveyor 02',
        ];

        $tanggal = $request->query('tanggal', now()->format('Y-m-d'));

        $maintenance = session('maintenance.' . $tanggal, []);

        $dataMesin = [];

        foreach ($mesin as $id => $nama) {
            $dataMesin[] = [
                'id' => $id,
                'nama' => $nama,
                'status' => $maintenance[$id]['status'] ?? 'Belum Dicek',
                'waktu' => $maintenance[$id]['waktu'] ?? null,
                'keterangan' => $maintenance[$id]['keterangan'] ?? null,
            ];
        }

        $totalMesin = count($dataMesin);

        $sudahDicek = collect($dataMesin)
            ->where('status', 'Sudah Dicek')
            ->count();

        $belumDicek = $totalMesin - $sudahDicek;

        return view(
            'teknisi.maintenance.index',
            compact(
                'dataMesin',
                'tanggal',
                'totalMesin',
                'sudahDicek',
                'belumDicek'
            )
        );
    }

    public function simpanMaintenance(Request $request)
    {
        $this->checkLogin();

        $request->validate([
            'tanggal' => ['required', 'date'],
            'mesin_id' => ['required', 'integer', 'min:1', 'max:10'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $tanggal = $request->tanggal;
        $mesinId = (int) $request->mesin_id;

        $maintenance = session('maintenance.' . $tanggal, []);

        $maintenance[$mesinId] = [
            'status' => 'Sudah Dicek',
            'waktu' => now()->format('H:i'),
            'keterangan' => $request->keterangan
                ?: 'Mesin telah diperiksa dan dalam kondisi normal.',
        ];

        session([
            'maintenance.' . $tanggal => $maintenance,
        ]);

        return redirect()
            ->route('teknisi.maintenance.index', [
                'tanggal' => $tanggal,
            ])
            ->with('success', 'Pengecekan mesin berhasil dicatat.');
    }

    private function checkLogin()
    {
        if (!Auth::check()) {
            abort(redirect()->route('login'));
        }

        if (Auth::user()->role !== 'teknisi') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}
