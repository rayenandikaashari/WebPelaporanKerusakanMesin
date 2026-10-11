<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanMaintenance extends Model
{
    protected $fillable = [
        'mesin_id',
        'teknisi_id',
        'tanggal',
        'status',
        'waktu',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function mesin(): BelongsTo
    {
        return $this->belongsTo(Mesin::class);
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}
