<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaNotification extends Model
{
    use HasFactory;

    protected $table = 'kepala_notifications';

    protected $fillable = [
        'kepala_keluarga_id',
        'title',
        'body',
        'url',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function kepala()
    {
        return $this->belongsTo(KepalaKeluarga::class, 'kepala_keluarga_id');
    }
}
