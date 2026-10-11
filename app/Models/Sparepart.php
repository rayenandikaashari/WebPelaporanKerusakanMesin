<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sparepart extends Model
{
    protected $table = 'spareparts';

    protected $fillable = [
        'kode_sparepart',
        'nama_sparepart',
        'stok',
        'satuan',
        'harga',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'stok' => 'integer',
            'harga' => 'decimal:2',
            'aktif' => 'boolean',
        ];
    }

    public function penggunaan(): HasMany
    {
        return $this->hasMany(PenggunaanSparepart::class, 'sparepart_id');
    }
}
