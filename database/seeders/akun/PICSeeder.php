<?php

namespace Database\Seeders\akun;

use App\Models\PIC;
use App\Models\User;
use Illuminate\Database\Seeder;

class PICSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePIC = 'pic';

        $pic = User::factory()->create([
            'username' => 'PIC',
            'email' => 'pic@gmail.com',
        ]);
        $pic->assignRole($rolePIC);
        PIC::create([
            'nama' => 'PIC 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '081234567891',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pic->id,
        ]);
    }
}
