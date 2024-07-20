<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Database\Seeders\akun\RWSeeder;
use Database\Seeders\TransaksiSeeder;
use Database\Seeders\akun\GamisSeeder;
use Database\Seeders\akun\LurahSeeder;
use Database\Seeders\akun\PICSeeder;
use Database\Seeders\akun\ManajerSeeder;
use Database\Seeders\akun\PegawaiSeeder;
use Database\Seeders\layanan\JenisLayananSeeder;
use Database\Seeders\layanan\JenisPakaianSeeder;
use Database\Seeders\layanan\LayananTambahanSeeder;
use Database\Seeders\layanan\LayananPrioritasSeeder;
use Database\Seeders\layanan\HargaJenisLayananSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Pelanggan::factory(10)->create();
        $this->call([
            //? Data Master
            CabangSeeder::class,
            UMRSeeder::class,

            //? Data Akun
            RoleSeeder::class,
            LurahSeeder::class,
            PICSeeder::class,
            RWSeeder::class,
            ManajerSeeder::class,
            PegawaiSeeder::class,
            GamisSeeder::class,

            //? Data Layanan
            JenisLayananSeeder::class,
            LayananTambahanSeeder::class,
            JenisPakaianSeeder::class,
            HargaJenisLayananSeeder::class,
            LayananPrioritasSeeder::class,

            //? Data Transaksi
            TransaksiSeeder::class,
        ]);
    }
}
