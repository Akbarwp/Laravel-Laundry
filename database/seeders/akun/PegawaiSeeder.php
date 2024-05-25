<?php

namespace Database\Seeders\akun;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cabang;
use App\Models\PegawaiLaundry;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang2 = Cabang::where('id', 2)->onlyTrashed()->first();
        $rolePegawai = Role::create(['name' => 'pegawai_laundry']);

        //? Cabang 1
        $pegawai_laundry = User::factory()->create([
            'username' => 'Pegawai Laundry 1',
            'email' => 'pegawai@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $pegawai_laundry->assignRole($rolePegawai);
        PegawaiLaundry::create([
            'nama' => 'Pegawai Laundry 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1998-01-01',
            'telepon' => '082234567891',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pegawai_laundry->id,
        ]);

        //? Cabang 2
        $pegawai_laundry2 = User::factory()->create([
            'username' => 'Pegawai Laundry 2',
            'email' => 'pegawai2@gmail.com',
            'cabang_id' => $cabang2->id,
        ]);
        $pegawai_laundry2->assignRole($rolePegawai);
        PegawaiLaundry::create([
            'nama' => 'Pegawai Laundry 2',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1998-01-01',
            'telepon' => '082234567892',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pegawai_laundry2->id,
        ]);

        $pegawai_laundry3 = User::factory()->create([
            'username' => 'Pegawai Laundry 3',
            'email' => 'pegawai3@gmail.com',
            'cabang_id' => $cabang2->id,
            'deleted_at' => Carbon::now(),
        ]);
        $pegawai_laundry3->assignRole($rolePegawai);
        PegawaiLaundry::create([
            'nama' => 'Pegawai Laundry 3',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1998-01-01',
            'telepon' => '082234567892',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pegawai_laundry3->id,
        ]);
    }
}
