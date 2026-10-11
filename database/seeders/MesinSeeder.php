<?php

namespace Database\Seeders;

use App\Models\Mesin;
use Illuminate\Database\Seeder;

class MesinSeeder extends Seeder
{
    public function run(): void
    {
        $mesins = [
            ['kode' => 'PROD-01', 'nama' => 'Mesin Produksi 01'],
            ['kode' => 'PROD-02', 'nama' => 'Mesin Produksi 02'],
            ['kode' => 'PROD-03', 'nama' => 'Mesin Produksi 03'],
            ['kode' => 'PROD-04', 'nama' => 'Mesin Produksi 04'],
            ['kode' => 'PROD-05', 'nama' => 'Mesin Produksi 05'],
            ['kode' => 'PACK-01', 'nama' => 'Mesin Packing 01'],
            ['kode' => 'PACK-02', 'nama' => 'Mesin Packing 02'],
            ['kode' => 'PACK-03', 'nama' => 'Mesin Packing 03'],
            ['kode' => 'CONV-01', 'nama' => 'Conveyor 01'],
            ['kode' => 'CONV-02', 'nama' => 'Conveyor 02'],
        ];

        foreach ($mesins as $mesin) {
            Mesin::updateOrCreate(
                ['kode' => $mesin['kode']],
                [
                    'nama' => $mesin['nama'],
                    'aktif' => true,
                ]
            );
        }
    }
}
