<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // Allowed NIK prefixes per requirement
        $prefixes = ['737111', '737112', '737113', '737114', '737115'];

        // Small list of kecamatan/kelurahan in Makassar to make addresses realistic
        $kecamatans = ['Ujung Pandang', 'Tamalate', 'Tamalanrea', 'Biringkanaya', 'Mamajang', 'Mariso', 'Tallo', 'Panakkukang', 'Rappocini'];
        $kelurahans = ['Pabayangan', 'Maccini Sombala', 'Paotere', 'Lhimpo', 'Pannampu', 'Tidung', 'Manggala', 'Pongtiku'];

        $total = 500;
        $existing = [];
        $batch = [];

        for ($i = 0; $i < $total; $i++) {
            // ensure unique NIK
            do {
                $prefix = $prefixes[array_rand($prefixes)];
                // remaining digits to reach 16
                $remaining = 16 - strlen($prefix);
                $suffix = '';
                for ($j = 0; $j < $remaining; $j++) {
                    $suffix .= mt_rand(0, 9);
                }
                $nik = $prefix . $suffix;
            } while (isset($existing[$nik]));

            $existing[$nik] = true;

            // Random gender, Indonesian names
            $gender = (mt_rand(0, 1) === 0) ? 'L' : 'P';
            $name = $faker->name($gender === 'L' ? 'male' : 'female');

            // realistic DOB between 1 and 80 years old
            $date = $faker->dateTimeBetween('-80 years', '-1 years')->format('Y-m-d');

            $kec = $kecamatans[array_rand($kecamatans)];
            $kel = $kelurahans[array_rand($kelurahans)];

            $alamat = sprintf("Jl. %s No.%s, Kel. %s, Kec. %s, Kota Makassar", $faker->streetName(), $faker->buildingNumber(), $kel, $kec);

            $religions = ['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'];

            $batch[] = [
                'nik' => $nik,
                'nama' => $name,
                'jenis_kelamin' => $gender,
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $date,
                'agama' => $religions[array_rand($religions)],
                'nomor_telepon' => $faker->phoneNumber(),
                'alamat' => $alamat,
                'kota' => 'Kota Makassar',
                'kecamatan' => $kec,
                'kelurahan' => $kel,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // insert in chunks to avoid large single insert
            if (count($batch) >= 100) {
                DB::table('penduduks')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('penduduks')->insert($batch);
        }

        $this->command->info("Selesai menambahkan {$total} penduduk ke tabel penduduks.");
    }
}
