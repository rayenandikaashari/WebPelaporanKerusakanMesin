<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use App\Models\LaporanMaintenance;
use App\Models\Mesin;
use App\Models\Sparepart;
use App\Models\PenggunaanSparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeknisiController extends Controller
{
    /**
     * Dashboard teknisi.
     */
    public function dashboard()
    {
        $laporan = LaporanKerusakan::with(['mesin', 'operator'])
            ->latest()
            ->get();

        $totalLaporan = $laporan->count();

        $menunggu = $laporan->where(
            'status',
            'Menunggu Ditangani'
        )->count();

        $selesai = $laporan->where(
            'status',
            'Selesai Diperbaiki'
        )->count();

        return view('teknisi.dashboard', compact(
            'laporan',
            'totalLaporan',
            'menunggu',
            'selesai'
        ));
    }

    /**
     * Daftar seluruh laporan kerusakan.
     */
    public function laporan()
    {
        $laporan = LaporanKerusakan::with(['mesin', 'operator'])
            ->latest()
            ->get();

        return view('teknisi.laporan.index', compact('laporan'));
    }

    /**
     * Detail laporan beserta spare part yang tersedia
     * dan spare part yang sudah digunakan.
     */
    public function detailLaporan($id)
    {
        $laporan = LaporanKerusakan::with([
            'mesin',
            'operator',
            'teknisi',
            'penggunaanSpareparts.sparepart',
        ])->findOrFail($id);

        $spareparts = Sparepart::where('aktif', true)
            ->orderBy('nama_sparepart')
            ->get();

        $penggunaanSpareparts = $laporan->penggunaanSpareparts
            ->keyBy('sparepart_id');

        return view('teknisi.laporan.show', compact(
            'laporan',
            'spareparts',
            'penggunaanSpareparts'
        ));
    }

    /**
     * Menampilkan foto kerusakan.
     * Mendukung BLOB di database dan path foto lama.
     */
    public function foto($id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        if (empty($laporan->foto)) {
            abort(404, 'Foto tidak tersedia.');
        }

        $foto = $laporan->foto;

        // Mendukung foto lama yang disimpan sebagai path.
        if (
            is_string($foto) &&
            preg_match('/\.(jpe?g|png)$/i', $foto) &&
            !str_starts_with($foto, "\xFF\xD8\xFF") &&
            !str_starts_with($foto, "\x89PNG")
        ) {
            $path = ltrim($foto, '/');

            if (!Storage::disk('public')->exists($path)) {
                abort(404, 'File foto lama tidak ditemukan.');
            }

            $foto = Storage::disk('public')->get($path);
        }

        // Memeriksa tipe gambar berdasarkan isi file.
        $imageInfo = @getimagesizefromstring($foto);

        if ($imageInfo === false) {
            abort(404, 'Data foto tidak valid.');
        }

        $mime = $imageInfo['mime'];

        if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
            abort(415, 'Format foto tidak didukung.');
        }

        return response($foto, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Memperbarui status perbaikan dan penggunaan spare part.
     * Perubahan stok dan laporan dilakukan dalam satu transaksi.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:Menunggu Ditangani,Sedang Diperbaiki,Selesai Diperbaiki',
            ],
            'hasil' => ['nullable', 'string', 'max:2000'],
            'penggunaan_spareparts' => ['sometimes', 'array'],
            'penggunaan_spareparts.*.jumlah' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        DB::transaction(function () use ($validated, $id) {
            $laporan = LaporanKerusakan::findOrFail($id);

            $laporan->status = $validated['status'];
            $laporan->teknisi_id = Auth::id();
            $laporan->hasil_perbaikan = $validated['hasil'] ?? null;

            $laporan->selesai_pada =
                $validated['status'] === 'Selesai Diperbaiki'
                    ? ($laporan->selesai_pada ?? now())
                    : null;

            $laporan->save();

            /*
             * Jika form belum mengirim data spare part,
             * jangan mengubah penggunaan atau stok yang sudah ada.
             */
            if (!array_key_exists('penggunaan_spareparts', $validated)) {
                return;
            }

            $lama = PenggunaanSparepart::where(
                'laporan_kerusakan_id',
                $laporan->id
            )
                ->lockForUpdate()
                ->get()
                ->keyBy('sparepart_id');

            $input = $validated['penggunaan_spareparts'] ?? [];
            $jumlahBaru = [];

            foreach ($input as $sparepartId => $data) {
                $jumlah = (int) ($data['jumlah'] ?? 0);

                if ($jumlah > 0) {
                    $jumlahBaru[(int) $sparepartId] = $jumlah;
                }
            }

            $ids = array_unique(array_merge(
                $lama->keys()
                    ->map(fn ($sparepartId) => (int) $sparepartId)
                    ->all(),
                array_keys($jumlahBaru)
            ));

            sort($ids);

            $stokSpareparts = Sparepart::whereIn('id', $ids)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Validasi spare part yang dipilih.
            foreach ($jumlahBaru as $sparepartId => $jumlah) {
                $part = $stokSpareparts->get($sparepartId);

                if (!$part || !$part->aktif) {
                    throw ValidationException::withMessages([
                        'penggunaan_spareparts' =>
                            'Spare part tidak ditemukan atau sudah tidak aktif.',
                    ]);
                }
            }

            foreach ($ids as $sparepartId) {
                $part = $stokSpareparts->get($sparepartId);

                if (!$part) {
                    continue;
                }

                $jumlahLama = (int) (
                    $lama->get($sparepartId)?->jumlah ?? 0
                );

                $jumlahSekarang = (int) (
                    $jumlahBaru[$sparepartId] ?? 0
                );

                /*
                 * Selisih positif berarti ada tambahan penggunaan.
                 * Selisih negatif berarti jumlah penggunaan dikurangi.
                 */
                $selisih = $jumlahSekarang - $jumlahLama;

                if ($selisih > 0 && $part->stok < $selisih) {
                    throw ValidationException::withMessages([
                        'penggunaan_spareparts' =>
                            "Stok {$part->nama_sparepart} tidak mencukupi. " .
                            "Stok tersedia: {$part->stok} {$part->satuan}.",
                    ]);
                }

                if ($selisih !== 0) {
                    $part->stok -= $selisih;
                    $part->save();
                }

                // Jumlah nol menghapus penggunaan dan mengembalikan stok.
                if ($jumlahSekarang === 0) {
                    $lama->get($sparepartId)?->delete();
                    continue;
                }

                PenggunaanSparepart::updateOrCreate(
                    [
                        'laporan_kerusakan_id' => $laporan->id,
                        'sparepart_id' => $sparepartId,
                    ],
                    [
                        'jumlah' => $jumlahSekarang,
                    ]
                );
            }
        });

        return redirect()
            ->route('teknisi.laporan.detail', $id)
            ->with(
                'success',
                'Status dan penggunaan spare part berhasil disimpan.'
            );
    }

    /**
     * Menampilkan pengecekan maintenance harian.
     */
    public function maintenance(Request $request)
    {
        $mesins = Mesin::where('aktif', true)
            ->orderBy('id')
            ->get();

        $tanggal = $request->query(
            'tanggal',
            now()->format('Y-m-d')
        );

        $maintenance = LaporanMaintenance::whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('mesin_id');

        $dataMesin = $mesins->map(function ($mesin) use ($maintenance) {
            $catatan = $maintenance->get($mesin->id);

            return [
                'id' => $mesin->id,
                'nama' => $mesin->nama,
                'status' => $catatan?->status ?? 'Belum Dicek',
                'waktu' => $catatan?->waktu,
                'keterangan' => $catatan?->keterangan,
            ];
        });

        $totalMesin = $dataMesin->count();

        $sudahDicek = $dataMesin
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

    /**
     * Menyimpan hasil maintenance harian.
     */
    public function simpanMaintenance(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'mesin_id' => ['required', 'exists:mesins,id'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        LaporanMaintenance::updateOrCreate(
            [
                'mesin_id' => $validated['mesin_id'],
                'tanggal' => $validated['tanggal'],
            ],
            [
                'teknisi_id' => Auth::id(),
                'status' => 'Sudah Dicek',
                'waktu' => now()->format('H:i:s'),
                'keterangan' => $validated['keterangan']
                    ?: 'Mesin telah diperiksa dan dalam kondisi normal.',
            ]
        );

        return redirect()
            ->route('teknisi.maintenance.index', [
                'tanggal' => $validated['tanggal'],
            ])
            ->with('success', 'Pengecekan mesin berhasil dicatat.');
    }
}
