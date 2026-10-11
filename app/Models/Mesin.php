<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mesin extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'lokasi',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function laporanKerusakans(): HasMany
    {
        return $this->hasMany(LaporanKerusakan::class);
    }

    public function laporanMaintenances(): HasMany
    {
        return $this->hasMany(LaporanMaintenance::class);
    }
}
