<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    public function run(): void
    {
        $spareparts = [
            [
                'kode_sparepart' => 'SPR-001',
                'nama_sparepart' => 'Bearing 6204',
                'stok' => 20,
                'satuan' => 'pcs',
                'harga' => 35000,
                'aktif' => true,
            ],
            [
                'kode_sparepart' => 'SPR-002',
                'nama_sparepart' => 'V-Belt A-45',
                'stok' => 12,
                'satuan' => 'pcs',
                'harga' => 45000,
                'aktif' => true,
            ],
            [
                'kode_sparepart' => 'SPR-003',
                'nama_sparepart' => 'Baut M10',
                'stok' => 100,
                'satuan' => 'pcs',
                'harga' => 2000,
                'aktif' => true,
            ],
            [
                'kode_sparepart' => 'SPR-004',
                'nama_sparepart' => 'Limit Switch',
                'stok' => 8,
                'satuan' => 'pcs',
                'harga' => 65000,
                'aktif' => true,
            ],
            [
                'kode_sparepart' => 'SPR-005',
                'nama_sparepart' => 'Motor Coupling',
                'stok' => 5,
                'satuan' => 'pcs',
                'harga' => 120000,
                'aktif' => true,
            ],
            [
                'kode_sparepart' => 'SPR-006',
                'nama_sparepart' => 'Grease Mesin',
                'stok' => 15,
                'satuan' => 'tube',
                'harga' => 25000,
                'aktif' => true,
            ],
        ];

        foreach ($spareparts as $sparepart) {
            Sparepart::updateOrCreate(
                ['kode_sparepart' => $sparepart['kode_sparepart']],
                $sparepart
            );
        }
    }
}
