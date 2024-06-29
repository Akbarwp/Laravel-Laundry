<?php

namespace Database\Seeders\akun;

use App\Models\RW;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;

class RWSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleRW = 'rw';

        $rw = User::factory()->create([
            'username' => 'RW',
            'email' => 'rw@gmail.com',
        ]);
        $rw->assignRole($roleRW);
        RW::create([
            'nomor_rw' => '1',
            'nama' => 'RW 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '083234567893',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $rw->id,
        ]);

        $rw2 = User::factory()->create([
            'username' => 'RW 2',
            'email' => 'rw2@gmail.com',
            'deleted_at' => Carbon::now(),
        ]);
        $rw2->assignRole($roleRW);
        RW::create([
            'nomor_rw' => '2',
            'nama' => 'RW 2',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '083234567893',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $rw2->id,
        ]);
    }
}
