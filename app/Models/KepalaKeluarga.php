<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaKeluarga extends Model
{
    use HasFactory;

    protected $table = 'kepala_keluargas';

    protected $fillable = [
        'nik',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'jenis_kelamin',
        'nomor_telepon',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function anggota()
    {
        return $this->hasMany(\App\Models\AnggotaKeluarga::class, 'kepala_keluarga_id');
    }
}
