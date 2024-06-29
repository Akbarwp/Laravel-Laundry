<?php

namespace Database\Seeders\akun;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Gamis;
use App\Models\Cabang;
use App\Models\DetailGamis;
use Illuminate\Database\Seeder;

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
        $this->createGamis($cabang, $keluargaGamis, $roleGamis, 1, ['nama' => 'Jual Pecel','gaji' => 1000000]);
        $this->createGamis($cabang, $keluargaGamis, $roleGamis, 3, ['nama' => 'Antar Jemput','gaji' => 1000000]);
        $this->createGamis($cabang, $keluargaGamis, $roleGamis, 4, ['nama' => '-','gaji' => 0]);

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

    public function createGamis($cabang, $keluargaGamis, $roleGamis, $angka, $pemasukkan)
    {
        $gamis = User::factory()->create([
            'username' => 'Gamis '.$angka,
            'email' => 'gamis'.$angka.'@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $gamis->assignRole($roleGamis);
        DetailGamis::create([
            'nama' => 'Gamis '.$angka,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1999-01-01',
            'telepon' => '08423456789'.$angka,
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'nama_pemasukkan' => $pemasukkan['nama'],
            'pemasukkan' => $pemasukkan['gaji'],
            'gamis_id' => $keluargaGamis->id,
            'user_id' => $gamis->id,
        ]);
    }
}
