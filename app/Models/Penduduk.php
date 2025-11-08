<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Penduduk extends Model
{
    use HasFactory;

    // Table name used by this model
    protected $table = 'penduduks';

    // Fields that may be mass-assigned
    protected $fillable = [
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'nomor_telepon',
        'alamat',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Hitung usia berdasarkan tanggal_lahir, atau kembalikan null bila tidak ada.
     */
    public function getAgeAttribute()
    {
        if (!$this->tanggal_lahir) return null;
        return Carbon::parse($this->tanggal_lahir)->age;
    }
}
