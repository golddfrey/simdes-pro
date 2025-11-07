<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\KepalaKeluargaSeeder;
use Database\Seeders\AnggotaSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create an admin user for testing (password: password)
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
        ]);
        // ensure admin flag is set
        if ($admin && !$admin->is_admin) {
            $admin->is_admin = true;
            $admin->save();
        }

        // Also keep a general test user
        User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

    // Seed base penduduk data first (500 penduduk)
    $this->call(\Database\Seeders\PendudukSeeder::class);

    // From penduduk data, create 100 kepala keluarga (each with at least one spouse anggota)
    $this->call(\Database\Seeders\KepalaFromPendudukSeeder::class);
    }
}
