<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKerusakan extends Model
{
    protected $fillable = [
        'mesin_id',
        'operator_id',
        'teknisi_id',
        'judul',
        'deskripsi',
        'foto',
        'status',
        'hasil_perbaikan',
        'sparepart',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'selesai_pada' => 'datetime',
        ];
    }

    public function mesin(): BelongsTo
    {
        return $this->belongsTo(Mesin::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}
