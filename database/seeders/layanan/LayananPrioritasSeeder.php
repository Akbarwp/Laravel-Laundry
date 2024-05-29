<?php

namespace Database\Seeders\layanan;

use Carbon\Carbon;
use App\Models\Cabang;
use Illuminate\Database\Seeder;
use App\Models\LayananPrioritas;

class LayananPrioritasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang2 = Cabang::where('id', 2)->onlyTrashed()->first();

        //? Cabang 1
        LayananPrioritas::create([
            'nama' => 'Reguler',
            'harga' => 0,
            'prioritas' => 1,
            'cabang_id' => $cabang->id,
        ]);
        LayananPrioritas::create([
            'nama' => 'Kilat',
            'harga' => 1500,
            'prioritas' => 2,
            'cabang_id' => $cabang->id,
        ]);
        LayananPrioritas::create([
            'nama' => 'Cahaya',
            'harga' => 2000,
            'prioritas' => 3,
            'cabang_id' => $cabang->id,
        ]);
        LayananPrioritas::create([
            'nama' => 'Raja Ratu',
            'harga' => 10000,
            'prioritas' => 99,
            'cabang_id' => $cabang->id,
            'deleted_at' => Carbon::now(),
        ]);

        //? Cabang 2
        LayananPrioritas::create([
            'nama' => 'Reguler',
            'harga' => 0,
            'prioritas' => 1,
            'cabang_id' => $cabang2->id,
        ]);
        LayananPrioritas::create([
            'nama' => 'Raja Ratu',
            'harga' => 10000,
            'prioritas' => 99,
            'cabang_id' => $cabang2->id,
            'deleted_at' => Carbon::now(),
        ]);
    }
}
