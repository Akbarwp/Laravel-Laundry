<?php

namespace Database\Seeders;

use App\Models\UMR;
use Illuminate\Database\Seeder;

class UMRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UMR::create([
            'regional' => 'Surabaya',
            'upah' => 4725479,
            'tahun' => 2024,
            'is_used' => true,
        ]);
        UMR::create([
            'regional' => 'Surabaya',
            'upah' => 4525479,
            'tahun' => 2023,
            'is_used' => false,
        ]);
    }
}
