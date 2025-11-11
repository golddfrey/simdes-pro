<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Penduduk;
use App\Models\KepalaKeluarga;
use App\Models\AnggotaKeluarga;
use Faker\Factory as Faker;
use Carbon\Carbon;

class KepalaFromPendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua penduduk yang tersedia
        $all = Penduduk::all();

        // Pools
        $malePool = $all->filter(function ($p) {
            return $p->jenis_kelamin === 'L' && $p->tanggal_lahir && Carbon::parse($p->tanggal_lahir)->age > 18;
        })->values();

        $femalePool = $all->filter(function ($p) {
            return $p->jenis_kelamin === 'P' && $p->tanggal_lahir && Carbon::parse($p->tanggal_lahir)->age >= 18;
        })->values();

        $childPool = $all->filter(function ($p) {
            return $p->tanggal_lahir && Carbon::parse($p->tanggal_lahir)->age <= 7;
        })->values();

        // Track used penduduk IDs so we don't assign same penduduk twice
        $used = [];

        if ($malePool->count() < 1) {
            $this->command->warn('Tidak ada kandidat pria >18 untuk dibuat kepala. Proses dihentikan.');
            return;
        }

        $take = min(100, $malePool->count());
        $selected = $malePool->random($take);

        foreach ($selected as $penduduk) {
            // buat kepala keluarga berdasarkan data penduduk
            $kepala = KepalaKeluarga::create([
                'nik' => $penduduk->nik,
                'nama' => $penduduk->nama,
                'tempat_lahir' => $penduduk->tempat_lahir,
                'tanggal_lahir' => $penduduk->tanggal_lahir,
                'agama' => $penduduk->agama,
                'jenis_kelamin' => $penduduk->jenis_kelamin,
                'nomor_telepon' => $penduduk->nomor_telepon,
            ]);

            $used[] = $penduduk->id;

            $kepalaAge = Carbon::parse($penduduk->tanggal_lahir)->age;

            // buat istri: cari dari femalePool yang belum dipakai dan memenuhi rentang usia
            $minWifeAge = max(18, $kepalaAge - 10);
            $wifeCandidate = $femalePool->filter(function ($p) use ($used, $minWifeAge, $kepalaAge) {
                $age = Carbon::parse($p->tanggal_lahir)->age;
                return !in_array($p->id, $used) && $age >= $minWifeAge && $age <= $kepalaAge;
            })->values();

            if ($wifeCandidate->isEmpty()) {
                // fallback: any unused female >=18
                $wifeCandidate = $femalePool->filter(function ($p) use ($used) {
                    return !in_array($p->id, $used);
                })->values();
            }

            if ($wifeCandidate->isNotEmpty()) {
                $wife = $wifeCandidate->random();
                // mark used
                $used[] = $wife->id;

                AnggotaKeluarga::create([
                    'kepala_keluarga_id' => $kepala->id,
                    'nama' => $wife->nama,
                    'nik' => $wife->nik,
                    'tempat_lahir' => $wife->tempat_lahir,
                    'tanggal_lahir' => $wife->tanggal_lahir,
                    'jenis_kelamin' => 'P',
                    'agama' => $wife->agama,
                    'pendidikan' => $wife->pendidikan ?? null,
                    'pekerjaan' => $wife->pekerjaan ?? null,
                    'status_perkawinan' => 'Kawin',
                    'status_dalam_keluarga' => 'Istri',
                    'kewarganegaraan' => $wife->kewarganegaraan ?? 'WNI',
                    'alamat' => $wife->alamat,
                    'provinsi' => $wife->provinsi ?? 'Sulawesi Selatan',
                    'kota' => $wife->kota,
                    'kecamatan' => $wife->kecamatan,
                    'kelurahan' => $wife->kelurahan,
                    'kode_pos' => $wife->kode_pos ?? null,
                    'is_deceased' => false,
                ]);
            }

            // anak: 0..3 anak, usia maksimal 7 tahun
                $numChildren = $faker->numberBetween(0, 3);
                $children = [];
                for ($c = 0; $c < $numChildren; $c++) {
                    // cari anak dari childPool yang belum dipakai dan sesuai umur
                    $childCandidate = $childPool->filter(function ($p) use ($used) {
                        return !in_array($p->id, $used);
                    })->values();

                    if ($childCandidate->isEmpty()) {
                        // fallback: cari anak apapun yang belum dipakai dan usia <=7
                        $childCandidate = $childPool->filter(function ($p) use ($used) {
                            return !in_array($p->id, $used);
                        })->values();
                    }

                    if ($childCandidate->isNotEmpty()) {
                        $childP = $childCandidate->random();
                        $used[] = $childP->id;
                        $child = AnggotaKeluarga::create([
                            'kepala_keluarga_id' => $kepala->id,
                            'nama' => $childP->nama,
                            'nik' => $childP->nik,
                            'tempat_lahir' => $childP->tempat_lahir,
                            'tanggal_lahir' => $childP->tanggal_lahir,
                            'jenis_kelamin' => $childP->jenis_kelamin,
                            'agama' => $childP->agama,
                            'pendidikan' => $childP->pendidikan ?? null,
                            'pekerjaan' => $childP->pekerjaan ?? null,
                            'status_perkawinan' => 'Belum Kawin',
                            'status_dalam_keluarga' => 'Anak',
                            'kewarganegaraan' => $childP->kewarganegaraan ?? 'WNI',
                            'alamat' => $childP->alamat,
                            'provinsi' => $childP->provinsi ?? 'Sulawesi Selatan',
                            'kota' => $childP->kota,
                            'kecamatan' => $childP->kecamatan,
                            'kelurahan' => $childP->kelurahan,
                            'kode_pos' => $childP->kode_pos ?? null,
                            'is_deceased' => false,
                        ]);
                        $children[] = $child;
                    }
                }

            // keluarga lainnya: 0..2 anggota lain (mis. orangtua atau saudara), diurutkan
            $numOthers = $faker->numberBetween(0, 2);
            for ($o = 0; $o < $numOthers; $o++) {
                // pilih anggota lain dari pool penduduk yang belum dipakai dan dewasa
                $otherCandidate = $all->filter(function ($p) use ($used) {
                    return !in_array($p->id, $used) && $p->tanggal_lahir && Carbon::parse($p->tanggal_lahir)->age >= 18;
                })->values();

                if ($otherCandidate->isNotEmpty()) {
                    $other = $otherCandidate->random();
                    $used[] = $other->id;
                    AnggotaKeluarga::create([
                        'kepala_keluarga_id' => $kepala->id,
                        'nama' => $other->nama,
                        'nik' => $other->nik,
                        'tempat_lahir' => $other->tempat_lahir,
                        'tanggal_lahir' => $other->tanggal_lahir,
                        'jenis_kelamin' => $other->jenis_kelamin,
                        'agama' => $other->agama,
                        'pendidikan' => $other->pendidikan ?? null,
                        'pekerjaan' => $other->pekerjaan ?? null,
                        'status_perkawinan' => $other->status_perkawinan ?? 'Belum Kawin',
                        'status_dalam_keluarga' => 'Lainnya',
                        'kewarganegaraan' => $other->kewarganegaraan ?? 'WNI',
                        'alamat' => $other->alamat,
                        'provinsi' => $other->provinsi ?? 'Sulawesi Selatan',
                        'kota' => $other->kota,
                        'kecamatan' => $other->kecamatan,
                        'kelurahan' => $other->kelurahan,
                        'kode_pos' => $other->kode_pos ?? null,
                        'is_deceased' => false,
                    ]);
                }
            }

            // done for this kepala
        }

        $this->command->info('Selesai membuat kepala keluarga beserta anggota dari data penduduk.');
    }
}
