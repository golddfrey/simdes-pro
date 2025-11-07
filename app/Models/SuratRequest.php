<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratRequest extends Model
{
    use HasFactory;

    protected $table = 'surat_requests';

    protected $fillable = [
        'kepala_keluarga_id',
        'jenis_surat',
        'tujuan',
        'keterangan',
        'payload',
        'status', // pending|approved|rejected
        'reviewed_by',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function kepala()
    {
        return $this->belongsTo(KepalaKeluarga::class, 'kepala_keluarga_id');
    }
}
