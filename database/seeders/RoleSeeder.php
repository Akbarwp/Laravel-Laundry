<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'lurah']);
        Role::create(['name' => 'pic']);
        Role::create(['name' => 'rw']);
        Role::create(['name' => 'manajer_laundry']);
        Role::create(['name' => 'pegawai_laundry']);
        Role::create(['name' => 'gamis']);
    }
}
