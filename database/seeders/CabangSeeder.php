<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cabang::create([
            'nama' => 'Cabang Pusat Pertama',
            'lokasi' => 'Surabaya',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
        ]);
        Cabang::create([
            'nama' => 'Cabang Kedua Uhuy',
            'lokasi' => 'Surabaya',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'deleted_at' => '2024-5-15 18:14:23',
        ]);
        Cabang::create([
            'nama' => 'Cabang Ketiga Spontan',
            'lokasi' => 'Surabaya',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
        ]);
        Cabang::create([
            'nama' => 'Cabang Keempat Luar',
            'lokasi' => 'Surabaya',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'deleted_at' => '2024-6-15 18:14:23',
        ]);
    }
}
