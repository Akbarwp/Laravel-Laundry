<?php

namespace Database\Seeders\layanan;

use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\JenisPakaian;
use Illuminate\Database\Seeder;

class JenisPakaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang2 = Cabang::where('id', 2)->onlyTrashed()->first();

        //? Cabang 1
        JenisPakaian::create([
            'nama' => 'Kemeja',
            'cabang_id' => $cabang->id,
        ]);
        JenisPakaian::create([
            'nama' => 'Kaos',
            'cabang_id' => $cabang->id,
        ]);
        JenisPakaian::create([
            'nama' => 'Jeans',
            'cabang_id' => $cabang->id,
        ]);
        JenisPakaian::create([
            'nama' => 'Jas',
            'cabang_id' => $cabang->id,
            'deleted_at' => Carbon::now(),
        ]);

        //? Cabang 2
        JenisPakaian::create([
            'nama' => 'Kemeja',
            'cabang_id' => $cabang2->id,
        ]);
        JenisPakaian::create([
            'nama' => 'Kaos',
            'cabang_id' => $cabang2->id,
        ]);
        JenisPakaian::create([
            'nama' => 'Jas',
            'cabang_id' => $cabang2->id,
            'deleted_at' => Carbon::now(),
        ]);
    }
}
