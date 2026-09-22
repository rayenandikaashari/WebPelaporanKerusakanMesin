<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function login()
    {
        if (session()->has('operator')) {
            return redirect()->route('operator.dashboard');
        }

        return view('operator.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (
            $request->username === 'operator' &&
            $request->password === '123456'
        ) {
            session([
                'operator' => [
                    'id' => 1,
                    'nama' => 'Budi Operator',
                    'username' => 'operator',
                    'role' => 'operator',
                ]
            ]);

            return redirect()->route('operator.dashboard');
        }

        return back()
            ->withInput()
            ->with('error', 'Username atau password salah.');
    }

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

        return view('operator.laporan', compact('laporan'));
    }

    public function buatLaporan()
    {
        $this->checkLogin();

        return view('operator.buat-laporan');
    }

    public function simpanLaporan(Request $request)
    {
        $this->checkLogin();

        $request->validate([
            'mesin' => 'required',
            'judul' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {

            $folder = public_path('uploads/laporan');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
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
            'operator' => session('operator.nama'),
        ];

        session([
            'laporan_operator' => $laporan
        ]);

        return redirect()
            ->route('operator.laporan')
            ->with('success', 'Laporan kerusakan berhasil dibuat.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('operator');

        return redirect()
            ->route('operator.login')
            ->with('success', 'Anda berhasil logout.');
    }

    private function checkLogin()
    {
        if (!session()->has('operator')) {
            abort(
                redirect()->route('operator.login')
            );
        }
    }
}