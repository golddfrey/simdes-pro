<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KepalaKeluarga;

class KepalaKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create one fixed tester kepala keluarga for your tests if not exists
        if (!KepalaKeluarga::where('nik', '12345678901234567')->exists()) {
            KepalaKeluarga::factory()->create([
                'nik' => '12345678901234567', // 17 chars
                'nama' => 'Tester Kepala',
                'tempat_lahir' => 'Makassar',
                'tanggal_lahir' => '1980-01-01',
                'agama' => 'Islam',
                'jenis_kelamin' => 'L',
                'nomor_telepon' => '081234567890',
            ]);

            // create the remaining 99 random kepala keluarga
            KepalaKeluarga::factory()->count(99)->create();
        }
    }
}
