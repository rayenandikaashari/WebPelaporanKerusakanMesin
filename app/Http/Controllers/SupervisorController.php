<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use App\Models\LaporanMaintenance;
use App\Models\Mesin;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SupervisorController extends Controller
{
    public function login()
    {
        return view('supervisor.login');
    }

    public function authenticate(Request $request)
    {
        // Mempertahankan alur login lama.
        $nama = $request->nama ?: 'Supervisor';

        session(['supervisor' => ['nama' => $nama]]);

        return redirect()->route('supervisor.dashboard');
    }

    public function dashboard()
    {
        $laporan = $this->laporanUntukView();
        $mesin = $this->mesinUntukView();

        $totalMesin = Mesin::count();

        $mesinNormal = Mesin::where('aktif', true)
            ->whereDoesntHave('laporanKerusakans', function ($query) {
                $query->whereIn('status', [
                    'Menunggu Ditangani',
                    'Sedang Diperbaiki',
                ]);
            })
            ->count();

        $mesinBermasalah = max(0, $totalMesin - $mesinNormal);

        $totalLaporan = LaporanKerusakan::count();

        $laporanMenunggu = LaporanKerusakan::where(
            'status',
            'Menunggu Ditangani'
        )->count();

        $laporanSelesai = LaporanKerusakan::where(
            'status',
            'Selesai Diperbaiki'
        )->count();

        return view('supervisor.dashboard', compact(
            'laporan',
            'mesin',
            'totalMesin',
            'mesinNormal',
            'mesinBermasalah',
            'totalLaporan',
            'laporanMenunggu',
            'laporanSelesai'
        ));
    }

    public function mesin()
    {
        $mesin = $this->mesinUntukView();

        return view('supervisor.mesin.index', compact('mesin'));
    }

    public function laporan()
    {
        $laporan = $this->laporanUntukView();

        return view('supervisor.laporan.index', compact('laporan'));
    }

    public function maintenance()
    {
        $maintenance = LaporanMaintenance::with(['teknisi', 'mesin'])
            ->orderByDesc('tanggal')
            ->get()
            ->groupBy(fn ($item) => $item->tanggal->format('Y-m-d'))
            ->map(function ($items) {
                $total = $items->count();

                $dicek = $items
                    ->where('status', 'Sudah Dicek')
                    ->count();

                return [
                    'tanggal' => $items->first()
                        ->tanggal
                        ->translatedFormat('d F Y'),

                    'teknisi' => $items
                        ->pluck('teknisi.name')
                        ->filter()
                        ->unique()
                        ->implode(', ') ?: '-',

                    'total' => $total,
                    'dicek' => $dicek,
                    'status' => $dicek >= $total
                        ? 'Selesai'
                        : 'Belum Selesai',
                ];
            })
            ->values()
            ->all();

        return view(
            'supervisor.maintenance.index',
            compact('maintenance')
        );
    }

    public function riwayatKerusakan()
    {
        $laporan = $this->laporanUntukView();

        return view(
            'supervisor.riwayat.kerusakan.index',
            compact('laporan')
        );
    }

    public function riwayatPerbaikan()
    {
        $laporan = $this->laporanUntukView('Selesai Diperbaiki');

        return view(
            'supervisor.riwayat.perbaikan.index',
            compact('laporan')
        );
    }

    public function sparepart()
    {
        $sparepart = $this->sparepartUntukView();

        return view(
            'supervisor.sparepart.index',
            compact('sparepart')
        );
    }

    public function dataMesin()
    {
        $mesin = $this->mesinUntukView();

        return view(
            'supervisor.data.mesin.index',
            compact('mesin')
        );
    }

    public function createMesin()
    {
        return view('supervisor.mesin.create');
    }

    public function storeMesin(Request $request)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                'unique:mesins,kode',
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'jenis' => [
                'nullable',
                'string',
                'max:255',
            ],
            'lokasi' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        Mesin::create([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'lokasi' => $validated['lokasi']
                ?? $validated['jenis']
                ?? null,
            'aktif' => true,
        ]);

        return redirect()
            ->route('supervisor.data.mesin')
            ->with('success', 'Data mesin berhasil ditambahkan.');
    }

    public function editMesin($kode)
    {
        $record = Mesin::where('kode', $kode)->firstOrFail();

        $mesin = [
            'id' => $record->id,
            'kode' => $record->kode,
            'nama' => $record->nama,
            'jenis' => $record->lokasi,
            'lokasi' => $record->lokasi,
            'status' => $record->aktif
                ? 'Normal'
                : 'Nonaktif',
        ];

        return view('supervisor.mesin.edit', compact('mesin'));
    }

    public function updateMesin(Request $request, $kode)
    {
        $mesin = Mesin::where('kode', $kode)->firstOrFail();

        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('mesins', 'kode')->ignore($mesin->id),
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'jenis' => [
                'nullable',
                'string',
                'max:255',
            ],
            'lokasi' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $mesin->update([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'lokasi' => $validated['lokasi']
                ?? $validated['jenis']
                ?? null,
        ]);

        return redirect()
            ->route('supervisor.data.mesin')
            ->with('success', 'Data mesin berhasil diperbarui.');
    }

    public function destroyMesin($kode)
    {
        $mesin = Mesin::where('kode', $kode)->firstOrFail();

        if (
            $mesin->laporanKerusakans()->exists()
            || $mesin->laporanMaintenances()->exists()
        ) {
            return redirect()
                ->route('supervisor.data.mesin')
                ->with(
                    'error',
                    'Mesin tidak dapat dihapus karena sudah memiliki riwayat laporan atau maintenance.'
                );
        }

        $mesin->delete();

        return redirect()
            ->route('supervisor.data.mesin')
            ->with('success', 'Data mesin berhasil dihapus.');
    }

    public function dataSparepart()
    {
        $sparepart = $this->sparepartUntukView();

        return view(
            'supervisor.data.sparepart.index',
            compact('sparepart')
        );
    }

    public function createSparepart()
    {
        return view('supervisor.sparepart.create');
    }

    public function storeSparepart(Request $request)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                'unique:spareparts,kode_sparepart',
            ],
            'nama' => [
                'required',
                'string',
                'max:150',
            ],
            'jumlah' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        Sparepart::create([
            'kode_sparepart' => $validated['kode'],
            'nama_sparepart' => $validated['nama'],
            'stok' => $validated['jumlah'],
            'satuan' => 'pcs',
            'harga' => 0,
            'aktif' => true,
        ]);

        return redirect()
            ->route('supervisor.data.sparepart')
            ->with('success', 'Data sparepart berhasil ditambahkan.');
    }

    public function editSparepart($kode)
    {
        $record = Sparepart::where(
            'kode_sparepart',
            $kode
        )->firstOrFail();

        $sparepart = [
            'id' => $record->id,
            'kode' => $record->kode_sparepart,
            'nama' => $record->nama_sparepart,
            'jumlah' => $record->stok,
            'digunakan' => $record->penggunaan()->sum('jumlah'),
        ];

        return view(
            'supervisor.sparepart.edit',
            compact('sparepart')
        );
    }

    public function updateSparepart(Request $request, $kode)
    {
        $sparepart = Sparepart::where(
            'kode_sparepart',
            $kode
        )->firstOrFail();

        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'spareparts',
                    'kode_sparepart'
                )->ignore($sparepart->id),
            ],
            'nama' => [
                'required',
                'string',
                'max:150',
            ],
            'jumlah' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $sparepart->update([
            'kode_sparepart' => $validated['kode'],
            'nama_sparepart' => $validated['nama'],
            'stok' => $validated['jumlah'],
        ]);

        return redirect()
            ->route('supervisor.data.sparepart')
            ->with('success', 'Data sparepart berhasil diperbarui.');
    }

    public function destroySparepart($kode)
    {
        $sparepart = Sparepart::where(
            'kode_sparepart',
            $kode
        )->firstOrFail();

        if ($sparepart->penggunaan()->exists()) {
            return redirect()
                ->route('supervisor.data.sparepart')
                ->with(
                    'error',
                    'Sparepart tidak dapat dihapus karena sudah digunakan pada laporan perbaikan.'
                );
        }

        $sparepart->delete();

        return redirect()
            ->route('supervisor.data.sparepart')
            ->with('success', 'Data sparepart berhasil dihapus.');
    }

    public function dataPengguna()
    {
        $pengguna = User::orderBy('id')
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'nama' => $user->name,
                'username' => $user->email,
                'role' => ucfirst($user->role ?? 'operator'),
                'status' => 'Aktif',
            ])
            ->all();

        return view(
            'supervisor.data.pengguna.index',
            compact('pengguna')
        );
    }

    public function createPengguna()
    {
        return view('supervisor.pengguna.create');
    }

    public function storePengguna(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'username' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'role' => [
                'required',
                Rule::in(['operator', 'teknisi', 'supervisor']),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ]);

        $user = new User();

        $user->forceFill([
            'name' => $validated['nama'],
            'email' => $validated['username'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ])->save();

        return redirect()
            ->route('supervisor.data.pengguna')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editPengguna($id)
    {
        $user = User::findOrFail($id);

        $pengguna = [
            'id' => $user->id,
            'nama' => $user->name,
            'username' => $user->email,
            'role' => ucfirst($user->role ?? 'operator'),
            'status' => 'Aktif',
        ];

        return view(
            'supervisor.pengguna.edit',
            compact('pengguna')
        );
    }

    public function updatePengguna(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'username' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => [
                'required',
                Rule::in(['operator', 'teknisi', 'supervisor']),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
            ],
        ]);

        $data = [
            'name' => $validated['nama'],
            'email' => $validated['username'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->forceFill($data)->save();

        return redirect()
            ->route('supervisor.data.pengguna')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroyPengguna($id)
    {
        $user = User::findOrFail($id);

        $punyaRiwayatKerusakan = LaporanKerusakan::where(
            'operator_id',
            $user->id
        )
            ->orWhere('teknisi_id', $user->id)
            ->exists();

        $punyaRiwayatMaintenance = LaporanMaintenance::where(
            'teknisi_id',
            $user->id
        )->exists();

        if ($punyaRiwayatKerusakan || $punyaRiwayatMaintenance) {
            return redirect()
                ->route('supervisor.data.pengguna')
                ->with(
                    'error',
                    'Pengguna tidak dapat dihapus karena sudah memiliki riwayat laporan atau maintenance.'
                );
        }

        if (auth()->id() === $user->id) {
            return redirect()
                ->route('supervisor.data.pengguna')
                ->with(
                    'error',
                    'Akun yang sedang digunakan tidak dapat dihapus.'
                );
        }

        $user->delete();

        return redirect()
            ->route('supervisor.data.pengguna')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('supervisor');

        return redirect()
            ->route('supervisor.login')
            ->with('success', 'Anda berhasil logout.');
    }

    private function mesinUntukView()
    {
        return Mesin::orderBy('kode')
            ->get()
            ->map(function ($mesin) {
                $laporanAktif = $mesin->laporanKerusakans()
                    ->whereIn('status', [
                        'Menunggu Ditangani',
                        'Sedang Diperbaiki',
                    ])
                    ->exists();

                return [
                    'id' => $mesin->id,
                    'kode' => $mesin->kode,
                    'nama' => $mesin->nama,
                    'jenis' => $mesin->lokasi ?? '-',
                    'lokasi' => $mesin->lokasi ?? '-',
                    'status' => !$mesin->aktif
                        ? 'Nonaktif'
                        : ($laporanAktif ? 'Perlu Perbaikan' : 'Normal'),
                ];
            })
            ->all();
    }

    private function laporanUntukView(?string $status = null)
    {
        $query = LaporanKerusakan::with([
            'mesin',
            'operator',
            'teknisi',
            'penggunaanSpareparts.sparepart',
        ])->latest();

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->get()
            ->map(function ($item) {
                $tanggal = $item->created_at
                    ? $item->created_at->translatedFormat('d F Y')
                    : '-';

                $namaSparepart = $item->penggunaanSpareparts
                    ->map(fn ($usage) => $usage->sparepart?->nama_sparepart)
                    ->filter()
                    ->unique()
                    ->implode(', ');

                return [
                    'id' => $item->id,
                    'kode' => 'LPR-' . str_pad(
                        (string) $item->id,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'judul' => $item->judul,
                    'mesin' => $item->mesin?->nama ?? '-',
                    'operator' => $item->operator?->name ?? '-',
                    'tanggal' => $tanggal,
                    'kerusakan' => $item->deskripsi,
                    'prioritas' => '-',
                    'status' => $item->status,
                    'hasil' => $item->hasil_perbaikan ?? '-',
                    'sparepart' => $namaSparepart
                        ?: ($item->sparepart ?: '-'),
                ];
            })
            ->all();
    }

    private function sparepartUntukView()
    {
        return Sparepart::withSum('penggunaan', 'jumlah')
            ->orderBy('kode_sparepart')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'kode' => $item->kode_sparepart,
                'nama' => $item->nama_sparepart,
                'jumlah' => $item->stok,
                'digunakan' => (int) ($item->penggunaan_sum_jumlah ?? 0),
            ])
            ->all();
    }
}