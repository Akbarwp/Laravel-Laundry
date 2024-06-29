<?php

namespace Database\Seeders\akun;

use App\Models\Lurah;
use App\Models\User;
use Illuminate\Database\Seeder;

class LurahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleLurah = 'lurah';

        $lurah = User::factory()->create([
            'username' => 'Lurah',
            'email' => 'lurah@gmail.com',
        ]);
        $lurah->assignRole($roleLurah);
        Lurah::create([
            'nama' => 'Lurah 1',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '081234567890',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $lurah->id,
        ]);
    }
}
