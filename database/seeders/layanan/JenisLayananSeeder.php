<?php

namespace Database\Seeders\layanan;

use App\Models\Cabang;
use App\Models\JenisLayanan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang2 = Cabang::where('id', 2)->onlyTrashed()->first();

        //? Cabang 1
        JenisLayanan::create([
            'nama' => 'Cuci',
            'for_gamis' => false,
            'cabang_id' => $cabang->id,
        ]);
        JenisLayanan::create([
            'nama' => 'Setrika',
            'for_gamis' => true,
            'cabang_id' => $cabang->id,
        ]);
        JenisLayanan::create([
            'nama' => 'Antar',
            'for_gamis' => true,
            'cabang_id' => $cabang->id,
        ]);
        JenisLayanan::create([
            'nama' => 'Parfum',
            'for_gamis' => true,
            'cabang_id' => $cabang->id,
            'deleted_at' => Carbon::now(),
        ]);

        //? Cabang 2
        JenisLayanan::create([
            'nama' => 'Cuci',
            'for_gamis' => false,
            'cabang_id' => $cabang2->id,
        ]);
        JenisLayanan::create([
            'nama' => 'Setrika',
            'for_gamis' => true,
            'cabang_id' => $cabang2->id,
        ]);
        JenisLayanan::create([
            'nama' => 'Antar',
            'for_gamis' => true,
            'cabang_id' => $cabang2->id,
        ]);
        JenisLayanan::create([
            'nama' => 'Parfum',
            'for_gamis' => true,
            'cabang_id' => $cabang2->id,
            'deleted_at' => Carbon::now(),
        ]);
    }
}
