<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id',
        'laporan_kerusakan_id',
        'type',
        'title',
        'message',
        'url',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(
            LaporanKerusakan::class,
            'laporan_kerusakan_id'
        );
    }
}
