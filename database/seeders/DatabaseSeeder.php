<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\DetailGamis;
use App\Models\Gamis;
use App\Models\Lurah;
use App\Models\ManajerLaundry;
use App\Models\PegawaiLaundry;
use App\Models\RW;
use App\Models\UMR;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //? Seeder --> create Cabang
        $cabang = Cabang::create([
            'nama' => 'Cabang Pusat Pertama',
            'lokasi' => 'Surabaya',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
        ]);

        //? Seeder --> create UMR
        UMR::create([
            'regional' => 'Surabaya',
            'upah' => 4725479,
            'tahun' => 2024,
            'is_used' => true,
        ]);

        //? Seeder --> create User Role
        $roleLurah = Role::create(['name' => 'lurah']);
        $roleManajer = Role::create(['name' => 'manajer_laundry']);
        $rolePegawai = Role::create(['name' => 'pegawai_laundry']);
        $roleRW = Role::create(['name' => 'rw']);
        $roleGamis = Role::create(['name' => 'gamis']);

        //? Seeder --> make User
        $lurah = User::factory()->create([
            'username' => 'Lurah',
            'email' => 'lurah@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $lurah->assignRole($roleLurah);
        Lurah::create([
            'nama' => 'Lurah 1',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '081234567890',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $lurah->id,
        ]);

        $manajer_laundry = User::factory()->create([
            'username' => 'Manajer Laundry',
            'email' => 'manajer@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $manajer_laundry->assignRole($roleManajer);
        ManajerLaundry::create([
            'nama' => 'Manajer Laundry 1',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '081234567891',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $manajer_laundry->id,
        ]);

        $pegawai_laundry = User::factory()->create([
            'username' => 'Pegawai Laundry',
            'email' => 'pegawai@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $pegawai_laundry->assignRole($rolePegawai);
        PegawaiLaundry::create([
            'nama' => 'Pegawai Laundry 1',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '081234567892',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pegawai_laundry->id,
        ]);

        $rw = User::factory()->create([
            'username' => 'RW',
            'email' => 'rw@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $rw->assignRole($roleRW);
        RW::create([
            'nama' => 'RW 1',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '081234567893',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $rw->id,
        ]);

        $gamis = User::factory()->create([
            'username' => 'Gamis',
            'email' => 'gamis@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $gamis->assignRole($roleGamis);
        $keluargaGamis = Gamis::create([
            'kartu_keluarga' => '1234567890123456',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'rt' => 4,
            'rw' => 1,
        ]);
        DetailGamis::create([
            'nama_lengkap' => 'Gamis 1',
            'nik' => '1234567890123450',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'agama' => 'Islam',
            'pendidikan' => 'Diploma IV/Strata I',
            'golongan_darah' => 'O',
            'status_keluarga' => 'Kepala Keluarga',
            'telepon' => '081234567893',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'gamis_id' => $keluargaGamis->id,
            'user_id' => $gamis->id,
        ]);
    }
}
