<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KepalaFromPendudukSeeder extends Seeder
{
    /**
     * Create 100 kepala keluarga from existing penduduk using DB queries (low memory)
     */
    public function run(): void
    {
        $needed = 100;

        // Select eligible males and females using query builder to avoid Eloquent overhead
        $malePool = DB::table('penduduks')
            ->where('jenis_kelamin', 'L')
            ->where('tanggal_lahir', '<=', now()->subYears(17)->toDateString())
            ->inRandomOrder()
            ->limit($needed)
            ->get();

        $femalePool = DB::table('penduduks')
            ->where('jenis_kelamin', 'P')
            ->where('tanggal_lahir', '<=', now()->subYears(15)->toDateString())
            ->inRandomOrder()
            ->limit($needed)
            ->get();

        if ($malePool->count() < $needed || $femalePool->count() < $needed) {
            $this->command->warn('Not enough eligible penduduk to create 100 kepala with spouse. Adjust your penduduk seed or reduce target.');
        }

        DB::transaction(function () use ($malePool, $femalePool) {
            $i = 0;
            foreach ($malePool as $male) {
                $spouse = $femalePool->get($i);
                if (!$spouse) break;

                // skip if kepala already exists for this nik
                $exists = DB::table('kepala_keluargas')->where('nik', $male->nik)->exists();
                if ($exists) {
                    $i++;
                    continue;
                }

                $kepalaId = DB::table('kepala_keluargas')->insertGetId([
                    'nik' => $male->nik,
                    'nama' => $male->nama,
                    'tempat_lahir' => $male->tempat_lahir,
                    'tanggal_lahir' => $male->tanggal_lahir,
                    'jenis_kelamin' => 'L',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert spouse as anggota
                DB::table('anggota_keluargas')->insert([
                    'kepala_keluarga_id' => $kepalaId,
                    'nama' => $spouse->nama,
                    'nik' => $spouse->nik,
                    'tempat_lahir' => $spouse->tempat_lahir,
                    'tanggal_lahir' => $spouse->tanggal_lahir,
                    'jenis_kelamin' => $spouse->jenis_kelamin,
                    'status_perkawinan' => 'Kawin',
                    'status_dalam_keluarga' => 'Istri',
                    'alamat' => $spouse->alamat,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $i++;
            }
        });

        $this->command->info('KepalaFromPendudukSeeder: selesai.');
    }
}
