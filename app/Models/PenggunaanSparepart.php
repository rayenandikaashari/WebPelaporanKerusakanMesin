<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggunaanSparepart extends Model
{
    protected $table = 'penggunaan_spareparts';

    protected $fillable = [
        'laporan_kerusakan_id',
        'sparepart_id',
        'jumlah',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
        ];
    }

    public function laporanKerusakan(): BelongsTo
    {
        return $this->belongsTo(
            LaporanKerusakan::class,
            'laporan_kerusakan_id'
        );
    }

    public function sparepart(): BelongsTo
    {
        return $this->belongsTo(
            Sparepart::class,
            'sparepart_id'
        );
    }
}
