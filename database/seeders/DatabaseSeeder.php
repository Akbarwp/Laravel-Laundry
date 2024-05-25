<?php

namespace Database\Seeders;

use Database\Seeders\akun\GamisSeeder;
use Database\Seeders\akun\LurahSeeder;
use Database\Seeders\akun\ManajerSeeder;
use Database\Seeders\akun\PegawaiSeeder;
use Database\Seeders\akun\RWSeeder;
use Database\Seeders\layanan\HargaJenisLayananSeeder;
use Database\Seeders\layanan\JenisLayananSeeder;
use Database\Seeders\layanan\JenisPakaianSeeder;
use Database\Seeders\layanan\LayananPrioritasSeeder;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //? Data Master
            CabangSeeder::class,
            UMRSeeder::class,

            //? Data Akun
            LurahSeeder::class,
            RWSeeder::class,
            ManajerSeeder::class,
            PegawaiSeeder::class,
            GamisSeeder::class,

            //? Data Layanan
            JenisLayananSeeder::class,
            JenisPakaianSeeder::class,
            HargaJenisLayananSeeder::class,
            LayananPrioritasSeeder::class,
        ]);
    }
}
