<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function dashboard()
    {
        $this->checkLogin();

        $laporan = session('laporan_operator', []);

        return view('operator.dashboard', compact('laporan'));
    }

    public function laporan()
    {
        $this->checkLogin();

        $laporan = session('laporan_operator', []);

        return view('operator.laporan.index', compact('laporan'));
    }

    public function buatLaporan()
    {
        $this->checkLogin();

        return view('operator.laporan.create');
    }

    public function simpanLaporan(Request $request)
    {
        $this->checkLogin();

        $request->validate([
            'mesin' => ['required'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $folder = public_path('uploads/laporan');

            if (!is_dir($folder)) {
                mkdir($folder, 0775, true);
            }

            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();

            $file->move($folder, $namaFile);

            $foto = $namaFile;
        }

        $laporan = session('laporan_operator', []);

        $laporan[] = [
            'id' => count($laporan) + 1,
            'mesin' => $request->mesin,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'status' => 'Menunggu Ditangani',
            'tanggal' => now()->format('d-m-Y H:i'),
            'operator' => Auth::user()->name,
        ];

        session([
            'laporan_operator' => $laporan,
        ]);

        return redirect()
            ->route('operator.laporan')
            ->with('success', 'Laporan kerusakan berhasil dibuat.');
    }

    private function checkLogin()
    {
        if (!Auth::check()) {
            abort(redirect()->route('login'));
        }

        if (Auth::user()->role !== 'operator') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}
