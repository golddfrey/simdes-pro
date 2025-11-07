<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKeluargaChangeRequest extends Model
{
    use HasFactory;

    protected $table = 'anggota_change_requests';

    protected $fillable = [
        'kepala_keluarga_id',
        'anggota_keluarga_id',
        'action', // add|update|delete|death
        'payload',
        'status', // pending|approved|rejected
        'reviewed_by',
        'reviewed_at',
        'reason',
    ];

    protected $casts = [
        'payload' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function kepala()
    {
        return $this->belongsTo(KepalaKeluarga::class, 'kepala_keluarga_id');
    }

    public function anggota()
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }
}
