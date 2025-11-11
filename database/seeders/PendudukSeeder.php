<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Penduduk;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $total = 300;
        $batch = [];
        $existing = [];

        // Ensure at least 120 males age 19-50 (to be eligible as kepala)
        for ($i = 0; $i < 120; $i++) {
            $age = $faker->numberBetween(19, 50);
            $tanggal_lahir = Carbon::now()->subYears($age)->subDays($faker->numberBetween(0, 365))->format('Y-m-d');
            do { $nik = $faker->numerify(str_repeat('#', 16)); } while (isset($existing[$nik]));
            $existing[$nik] = true;
            $batch[] = [
                'nik' => $nik,
                'nama' => $faker->name('male'),
                'jenis_kelamin' => 'L',
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $tanggal_lahir,
                    'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                    'status_perkawinan' => $faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai']),
                    'pekerjaan' => $faker->randomElement(['Petani', 'Wiraswasta', 'PNS', 'Karyawan Swasta']),
                    'nomor_telepon' => $faker->numerify('08##########'),
                    'alamat' => $faker->streetAddress(),
                    'provinsi' => 'Sulawesi Selatan',
                    'kota' => $faker->city(),
                    'kecamatan' => $faker->word(),
                    'kelurahan' => $faker->word(),
                    'kode_pos' => $faker->postcode(),
                    'pendidikan' => $faker->randomElement(['SD','SMP','SMA','Sarjana','Belum Sekolah']),
                    'kewarganegaraan' => 'WNI',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($batch) >= 100) { DB::table('penduduks')->insert($batch); $batch = []; }
        }

        // Ensure at least 100 females age 18-50 (to use as potential istri)
        for ($i = 0; $i < 100; $i++) {
            $age = $faker->numberBetween(18, 50);
            $tanggal_lahir = Carbon::now()->subYears($age)->subDays($faker->numberBetween(0, 365))->format('Y-m-d');
            do { $nik = $faker->numerify(str_repeat('#', 16)); } while (isset($existing[$nik]));
            $existing[$nik] = true;
            $batch[] = [
                'nik' => $nik,
                'nama' => $faker->name('female'),
                'jenis_kelamin' => 'P',
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                'status_perkawinan' => $faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai']),
                'pekerjaan' => $faker->randomElement(['Ibu Rumah Tangga', 'Wiraswasta', 'Karyawan Swasta']),
                'nomor_telepon' => $faker->numerify('08##########'),
                'alamat' => $faker->streetAddress(),
                'provinsi' => 'Sulawesi Selatan',
                'kota' => $faker->city(),
                'kecamatan' => $faker->word(),
                'kelurahan' => $faker->word(),
                'kode_pos' => $faker->postcode(),
                'pendidikan' => $faker->randomElement(['SD','SMP','SMA','Sarjana','Belum Sekolah']),
                'kewarganegaraan' => 'WNI',
                'kecamatan' => $faker->word(),
                'kelurahan' => $faker->word(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($batch) >= 100) { DB::table('penduduks')->insert($batch); $batch = []; }
        }

        // Fill the rest to reach 300 with mixed ages 1..50
        $remaining = $total - 120 - 100; // 80
        for ($i = 0; $i < $remaining; $i++) {
            $age = $faker->numberBetween(1, 50);
            $tanggal_lahir = Carbon::now()->subYears($age)->subDays($faker->numberBetween(0, 365))->format('Y-m-d');
            $gender = $faker->randomElement(['L','P']);
            do { $nik = $faker->numerify(str_repeat('#', 16)); } while (isset($existing[$nik]));
            $existing[$nik] = true;
            $batch[] = [
                'nik' => $nik,
                'nama' => $gender === 'L' ? $faker->name('male') : $faker->name('female'),
                'jenis_kelamin' => $gender,
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                'status_perkawinan' => $faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai']),
                'pekerjaan' => $faker->randomElement(['Petani', 'Wiraswasta', 'PNS', 'Pelajar', 'Karyawan Swasta']),
                'nomor_telepon' => $faker->numerify('08##########'),
                'alamat' => $faker->streetAddress(),
                'provinsi' => 'Sulawesi Selatan',
                'kota' => $faker->city(),
                'kecamatan' => $faker->word(),
                'kelurahan' => $faker->word(),
                'kode_pos' => $faker->postcode(),
                'pendidikan' => $faker->randomElement(['SD','SMP','SMA','Sarjana','Belum Sekolah']),
                'kewarganegaraan' => 'WNI',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (count($batch) >= 100) { DB::table('penduduks')->insert($batch); $batch = []; }
        }

        if (!empty($batch)) DB::table('penduduks')->insert($batch);

        $this->command->info("Selesai menambahkan {$total} penduduk ke tabel penduduks.");
    }
}
