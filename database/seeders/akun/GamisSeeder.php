<?php

namespace Database\Seeders\akun;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Gamis;
use App\Models\Cabang;
use App\Models\DetailGamis;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class GamisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang2 = Cabang::where('id', 2)->onlyTrashed()->first();
        $roleGamis = 'gamis';

        $keluargaGamis = Gamis::create([
            'kartu_keluarga' => '1234567890123456',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'rt' => 4,
            'rw' => 1,
        ]);

        //? Cabang 1
        $gamis = User::factory()->create([
            'username' => 'Gamis 1',
            'email' => 'gamis@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $gamis->assignRole($roleGamis);
        DetailGamis::create([
            'nama' => 'Gamis 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1999-01-01',
            'telepon' => '084234567891',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'gamis_id' => $keluargaGamis->id,
            'user_id' => $gamis->id,
        ]);

        //? Cabang 2
        $gamis2 = User::factory()->create([
            'username' => 'Gamis 2',
            'email' => 'gamis2@gmail.com',
            'cabang_id' => $cabang2->id,
            'deleted_at' => Carbon::now(),
        ]);
        $gamis2->assignRole($roleGamis);
        DetailGamis::create([
            'nama' => 'Gamis 2',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1999-01-01',
            'telepon' => '084234567892',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'gamis_id' => $keluargaGamis->id,
            'user_id' => $gamis2->id,
        ]);
    }
}
