<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\KepalaKeluarga;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KepalaKeluarga>
 */
class KepalaKeluargaFactory extends Factory
{
    protected $model = KepalaKeluarga::class;

    public function definition(): array
    {
        $kota = [
            'Makassar',
            'Tamalate (Makassar)',
            'Biringkanaya (Makassar)',
            'Panakkukang (Makassar)',
            'Sungguminasa (Gowa)',
            'Somba Opu (Gowa)',
            'Bajeng (Gowa)',
            'Pallangga (Gowa)'
        ];

        return [
            // 17 numeric characters as requested
            'nik' => $this->faker->unique()->numerify('#################'),
            'nama' => $this->faker->name('male'),
            // We'll treat `tempat_lahir` as the requested domicile (near Makassar/Gowa)
            'tempat_lahir' => $this->faker->randomElement($kota),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2005-12-31'),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'jenis_kelamin' => 'L',
            'nomor_telepon' => $this->faker->numerify('08##########'),
        ];
    }
}
