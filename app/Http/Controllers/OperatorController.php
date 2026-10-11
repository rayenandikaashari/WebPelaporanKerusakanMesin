<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use App\Models\Mesin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function dashboard()
    {
        $laporan = LaporanKerusakan::with('mesin')
            ->where('operator_id', Auth::id())
            ->latest()
            ->get();

        return view('operator.dashboard', compact('laporan'));
    }

    public function laporan()
    {
        $laporan = LaporanKerusakan::with('mesin')
            ->where('operator_id', Auth::id())
            ->latest()
            ->get();

        return view('operator.laporan.index', compact('laporan'));
    }

    public function buatLaporan()
    {
        $mesins = Mesin::where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('operator.laporan.create', compact('mesins'));
    }

    public function simpanLaporan(Request $request)
    {
        $validated = $request->validate([
            'mesin_id' => 'required|exists:mesins,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')
                ->store('laporan', 'public');
        }

        LaporanKerusakan::create([
            'mesin_id' => $validated['mesin_id'],
            'operator_id' => Auth::id(),
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'foto' => $foto,
            'status' => 'Menunggu Ditangani',
        ]);

        return redirect()
            ->route('operator.laporan')
            ->with('success', 'Laporan kerusakan berhasil disimpan.');
    }
}
