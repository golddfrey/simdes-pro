<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KepalaKeluarga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Str;

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        $totalTarget = 300; // desired total population (kepala + anggota)

        $jumlahKepala = KepalaKeluarga::count();
        $jumlahAnggotaExisting = AnggotaKeluarga::count();
        $currentTotal = $jumlahKepala + $jumlahAnggotaExisting;

        if ($currentTotal >= $totalTarget) {
            // nothing to do
            echo "Total penduduk sudah >= {$totalTarget}, tidak menambahkan anggota.\n";
            return;
        }

        $toAdd = $totalTarget - $currentTotal;

        // Preload kepala list and existing NIKs for fast uniqueness checks
        $kepalas = KepalaKeluarga::select('id','tempat_lahir','agama')->get()->toArray();
        if (count($kepalas) === 0) {
            echo "Tidak ada data kepala keluarga. Jalankan KepalaKeluargaSeeder terlebih dahulu.\n";
            return;
        }

        $existingNiks = array_flip(array_merge(
            KepalaKeluarga::pluck('nik')->toArray(),
            AnggotaKeluarga::pluck('nik')->toArray()
        ));

        $membersToInsert = [];

        // We'll iterate over kepala randomly and assign members until we reach target.
        $kepalaIds = array_column($kepalas, 'id');
        shuffle($kepalaIds);

        // Track whether a kepala already received a wife in this seeder run
        $assignedWife = [];

        // Safety guard to avoid infinite loops
        $maxIterations = 100;
        $iter = 0;

        while ($toAdd > 0 && $iter < $maxIterations) {
            $iter++;
            foreach ($kepalaIds as $kid) {
                if ($toAdd <= 0) break;

                // Randomly decide whether this kepala will receive members this pass
                // 60% chance to receive members (so some kepala have none)
                if (rand(1,100) > 60) continue;

                // possibly add wife (only one)
                if (!isset($assignedWife[$kid]) && $toAdd > 0 && rand(1,100) <= 50) {
                    $membersToInsert[] = $this->makeMemberArray($kid, 'P', $faker, $existingNiks, 'Istri');
                    $assignedWife[$kid] = true;
                    $toAdd--;
                    if ($toAdd <= 0) break;
                }

                // add children (0..min(4, toAdd))
                $maxChildren = min(4, $toAdd);
                if ($maxChildren > 0) {
                    $childCount = rand(0, $maxChildren);
                    for ($i=0;$i<$childCount && $toAdd>0;$i++) {
                        $gender = rand(0,1) ? 'L' : 'P';
                        $membersToInsert[] = $this->makeMemberArray($kid, $gender, $faker, $existingNiks, 'Anak');
                        $toAdd--;
                    }
                }

                if ($toAdd <= 0) break;

                // add other family members (0..min(2, toAdd))
                $maxOther = min(2, $toAdd);
                if ($maxOther > 0) {
                    $otherCount = rand(0, $maxOther);
                    for ($i=0;$i<$otherCount && $toAdd>0;$i++) {
                        $gender = rand(0,1) ? 'L' : 'P';
                        $membersToInsert[] = $this->makeMemberArray($kid, $gender, $faker, $existingNiks, 'Lainnya');
                        $toAdd--;
                    }
                }

                // If we've accumulated a large batch, flush to DB in batches for performance
                if (count($membersToInsert) >= 500) {
                    $this->batchInsert($membersToInsert);
                    $membersToInsert = [];
                }
            }
        }

        // Final flush
        if (count($membersToInsert) > 0) {
            $this->batchInsert($membersToInsert);
        }

        echo "Selesai menambahkan anggota: target terpenuhi.\n";
    }

    protected function makeMemberArray($kepalaId, $gender, $faker, & $existingNiks, $statusInFamily = 'Anggota')
    {
        // generate unique 17-digit NIK not present in existingNiks array
        do {
            $nik = '';
            for ($i=0;$i<17;$i++) {
                $nik .= (string) rand(0,9);
            }
        } while (isset($existingNiks[$nik]));
        // reserve it
        $existingNiks[$nik] = true;

        $name = $gender === 'P' ? $faker->name('female') : $faker->name('male');

        return [
            'kepala_keluarga_id' => $kepalaId,
            'nama' => $name,
            'nik' => $nik,
            'tempat_lahir' => $faker->city,
            'tanggal_lahir' => $faker->date('Y-m-d', '-30 years'),
            'jenis_kelamin' => $gender,
            'agama' => $faker->randomElement(['Islam','Kristen','Katolik','Hindu','Buddha']),
            'pendidikan' => $faker->randomElement(['SD','SMP','SMA','S1','S2','Tidak Sekolah']),
            'pekerjaan' => $faker->jobTitle,
            'status_perkawinan' => $gender === 'P' && rand(1,100)<=50 ? 'Kawin' : 'Belum Kawin',
            'status_dalam_keluarga' => $statusInFamily,
            'kewarganegaraan' => 'Indonesia',
            'alamat' => $faker->streetAddress,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    protected function batchInsert(array $rows)
    {
        // Insert in DB using chunked insert to keep memory and DB happy
        $chunks = array_chunk($rows, 500);
        foreach ($chunks as $chunk) {
            AnggotaKeluarga::insert($chunk);
        }
    }

}
