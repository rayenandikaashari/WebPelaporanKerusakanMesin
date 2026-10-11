<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    
    public function penggunaanSpareparts(): HasMany
    {
        return $this->hasMany(
            PenggunaanSparepart::class,
            'laporan_kerusakan_id'
        )->with('sparepart');
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
