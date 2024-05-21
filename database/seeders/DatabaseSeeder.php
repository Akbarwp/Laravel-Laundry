<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\DetailGamis;
use App\Models\Gamis;
use App\Models\HargaJenisLayanan;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use App\Models\LayananPrioritas;
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
        $cabang2 = Cabang::create([
            'nama' => 'Cabang Kedua Uhuy',
            'lokasi' => 'Surabaya',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'deleted_at' => '2024-5-15 18:14:23',
        ]);
        Cabang::create([
            'nama' => 'Cabang Ketiga Spontan',
            'lokasi' => 'Surabaya',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
        ]);
        Cabang::create([
            'nama' => 'Cabang Keempat Luar',
            'lokasi' => 'Surabaya',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'deleted_at' => '2024-6-15 18:14:23',
        ]);

        //? Seeder --> create UMR
        UMR::create([
            'regional' => 'Surabaya',
            'upah' => 4725479,
            'tahun' => 2024,
            'is_used' => true,
        ]);
        UMR::create([
            'regional' => 'Surabaya',
            'upah' => 4525479,
            'tahun' => 2023,
            'is_used' => false,
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

        $manajer_laundry = User::factory()->create([
            'username' => 'Manajer Laundry 1',
            'email' => 'manajer@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $manajer_laundry2 = User::factory()->create([
            'username' => 'Manajer Laundry 2',
            'email' => 'manajer2@gmail.com',
            'cabang_id' => $cabang2->id,
            'deleted_at' => '2024-5-15 18:14:23',
        ]);
        $manajer_laundry->assignRole($roleManajer);
        $manajer_laundry2->assignRole($roleManajer);
        ManajerLaundry::create([
            'nama' => 'Manajer Laundry 1',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1997-01-01',
            'telepon' => '081234567891',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $manajer_laundry->id,
        ]);
        ManajerLaundry::create([
            'nama' => 'Manajer Laundry 2',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1997-01-01',
            'telepon' => '081234567892',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $manajer_laundry2->id,
        ]);

        $pegawai_laundry = User::factory()->create([
            'username' => 'Pegawai Laundry 1',
            'email' => 'pegawai@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $pegawai_laundry2 = User::factory()->create([
            'username' => 'Pegawai Laundry 2',
            'email' => 'pegawai2@gmail.com',
            'cabang_id' => $cabang2->id,
            'deleted_at' => '2024-5-15 18:14:23',
        ]);
        $pegawai_laundry->assignRole($rolePegawai);
        $pegawai_laundry2->assignRole($rolePegawai);
        PegawaiLaundry::create([
            'nama' => 'Pegawai Laundry 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1998-01-01',
            'telepon' => '082234567891',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pegawai_laundry->id,
        ]);
        PegawaiLaundry::create([
            'nama' => 'Pegawai Laundry 2',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1998-01-01',
            'telepon' => '082234567892',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pegawai_laundry2->id,
        ]);

        $rw = User::factory()->create([
            'username' => 'RW',
            'email' => 'rw@gmail.com',
        ]);
        $rw->assignRole($roleRW);
        RW::create([
            'nama' => 'RW 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1970-01-01',
            'telepon' => '083234567893',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $rw->id,
        ]);

        $gamis = User::factory()->create([
            'username' => 'Gamis 1',
            'email' => 'gamis@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $gamis2 = User::factory()->create([
            'username' => 'Gamis 2',
            'email' => 'gamis2@gmail.com',
            'cabang_id' => $cabang2->id,
            'deleted_at' => '2024-5-15 18:14:23',
        ]);
        $gamis->assignRole($roleGamis);
        $gamis2->assignRole($roleGamis);
        $keluargaGamis = Gamis::create([
            'kartu_keluarga' => '1234567890123456',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'rt' => 4,
            'rw' => 1,
        ]);
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

        //? Seeder --> create Jenis Layanan
        $jenisLayananCuci = JenisLayanan::create([
            'nama' => 'Cuci',
            'for_gamis' => false,
            'cabang_id' => $cabang->id,
        ]);
        $jenisLayananSetrika = JenisLayanan::create([
            'nama' => 'Setrika',
            'for_gamis' => true,
            'cabang_id' => $cabang->id,
        ]);
        $jenisLayananAntar = JenisLayanan::create([
            'nama' => 'Antar',
            'for_gamis' => true,
            'cabang_id' => $cabang->id,
        ]);

        //? Seeder --> create Jenis Pakaian
        $jenisPakaianKemeja = JenisPakaian::create([
            'nama' => 'Kemeja',
            'cabang_id' => $cabang->id,
        ]);
        $jenisPakaianKaos = JenisPakaian::create([
            'nama' => 'Kaos',
            'cabang_id' => $cabang->id,
        ]);
        $jenisPakaianJeans = JenisPakaian::create([
            'nama' => 'Jeans',
            'cabang_id' => $cabang->id,
        ]);

        //? Seeder --> make Harga Jenis Layanan Kaos
        HargaJenisLayanan::create([
            'harga' => 1000,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananCuci->id,
            'jenis_pakaian_id' => $jenisPakaianKaos->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 1500,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananSetrika->id,
            'jenis_pakaian_id' => $jenisPakaianKaos->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 1500,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananAntar->id,
            'jenis_pakaian_id' => $jenisPakaianKaos->id,
            'cabang_id' => $cabang->id,
        ]);

        //? Seeder --> make Harga Jenis Layanan Kemeja
        HargaJenisLayanan::create([
            'harga' => 1500,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananCuci->id,
            'jenis_pakaian_id' => $jenisPakaianKemeja->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 2000,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananSetrika->id,
            'jenis_pakaian_id' => $jenisPakaianKemeja->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 2000,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananAntar->id,
            'jenis_pakaian_id' => $jenisPakaianKemeja->id,
            'cabang_id' => $cabang->id,
        ]);

        //? Seeder --> make Harga Jenis Layanan Jeans
        HargaJenisLayanan::create([
            'harga' => 2000,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananCuci->id,
            'jenis_pakaian_id' => $jenisPakaianJeans->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 2500,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananSetrika->id,
            'jenis_pakaian_id' => $jenisPakaianJeans->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 2500,
            'jenis_satuan' => 'kg',
            'jenis_layanan_id' => $jenisLayananAntar->id,
            'jenis_pakaian_id' => $jenisPakaianJeans->id,
            'cabang_id' => $cabang->id,
        ]);

        //? Seeder --> create Layanan Prioritas
        LayananPrioritas::create([
            'nama' => 'Reguler',
            'jenis_satuan' => 'kg',
            'harga' => 0,
            'prioritas' => 1,
            'cabang_id' => $cabang->id,
        ]);
        LayananPrioritas::create([
            'nama' => 'Kilat',
            'jenis_satuan' => 'kg',
            'harga' => 1500,
            'prioritas' => 2,
            'cabang_id' => $cabang->id,
        ]);
        LayananPrioritas::create([
            'nama' => 'Cahaya',
            'jenis_satuan' => 'kg',
            'harga' => 2000,
            'prioritas' => 3,
            'cabang_id' => $cabang->id,
        ]);
    }
}
