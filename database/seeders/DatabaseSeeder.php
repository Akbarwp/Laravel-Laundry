<?php

namespace Database\Seeders;

use App\Enums\StatusTransaksi;
use App\Models\DetailLayananTransaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Database\Seeders\akun\GamisSeeder;
use Database\Seeders\akun\LurahSeeder;
use Database\Seeders\akun\ManajerSeeder;
use Database\Seeders\akun\PegawaiSeeder;
use Database\Seeders\akun\RWSeeder;
use Database\Seeders\layanan\HargaJenisLayananSeeder;
use Database\Seeders\layanan\JenisLayananSeeder;
use Database\Seeders\layanan\JenisPakaianSeeder;
use Database\Seeders\layanan\LayananPrioritasSeeder;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //? Data Master
            CabangSeeder::class,
            UMRSeeder::class,

            //? Data Akun
            RoleSeeder::class,
            LurahSeeder::class,
            RWSeeder::class,
            ManajerSeeder::class,
            PegawaiSeeder::class,
            GamisSeeder::class,

            //? Data Layanan
            JenisLayananSeeder::class,
            JenisPakaianSeeder::class,
            HargaJenisLayananSeeder::class,
            LayananPrioritasSeeder::class,
        ]);

        $pelanggan = Pelanggan::create([
            'nama' => 'Pelanggan 1',
            'jenis_kelamin' => 'L',
            'telepon' => '081',
            'alamat' => '-',
        ]);

        $nota1 = Carbon::now()->format('His') . '-' . Carbon::now()->format('dmY') . '-' . str()->uuid();
        $transaksi = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota1,
            'nota_pelanggan' => 'pelanggan-' . $nota1,
            'waktu' => Carbon::now(),
            'total_biaya_layanan' => 17000,
            'total_biaya_prioritas' => 9000,
            'total_bayar_akhir' => 26000,
            'jenis_pembayaran' => 'tunai',
            'bayar' => 30000,
            'kembalian' => 4000,
            'status' => StatusTransaksi::PROSES,
            'layanan_prioritas_id' => 2,
            'pelanggan_id' => $pelanggan->id,
            'pegawai_id' => 7,
            'gamis_id' => 1,
            'cabang_id' => 1,
        ]);

        $detail1 = DetailTransaksi::create([
            'total_pakaian' => 4,
            'harga_layanan_akhir' => 2500,
            'total_biaya_layanan' => 10000,
            'total_biaya_prioritas' => 6000,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 1,
            'detail_transaksi_id' => $detail1->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 2,
            'detail_transaksi_id' => $detail1->id,
        ]);

        $detail2 = DetailTransaksi::create([
            'total_pakaian' => 2,
            'harga_layanan_akhir' => 3500,
            'total_biaya_layanan' => 7000,
            'total_biaya_prioritas' => 3000,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 4,
            'detail_transaksi_id' => $detail2->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 5,
            'detail_transaksi_id' => $detail2->id,
        ]);


        $nota2 = Carbon::now()->format('His') . '-' . Carbon::now()->format('dmY') . '-' . str()->uuid();
        $transaksi2 = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota2,
            'nota_pelanggan' => 'pelanggan-' . $nota2,
            'waktu' => Carbon::now(),
            'total_biaya_layanan' => 29000,
            'total_biaya_prioritas' => 15000,
            'total_bayar_akhir' => 44000,
            'jenis_pembayaran' => 'tunai',
            'bayar' => 50000,
            'kembalian' => 6000,
            'status' => StatusTransaksi::BARU,
            'layanan_prioritas_id' => 2,
            'pelanggan_id' => $pelanggan->id,
            'pegawai_id' => 7,
            'gamis_id' => 1,
            'cabang_id' => 1,
        ]);

        $detail3 = DetailTransaksi::create([
            'total_pakaian' => 4,
            'harga_layanan_akhir' => 4500,
            'total_biaya_layanan' => 18000,
            'total_biaya_prioritas' => 6000,
            'transaksi_id' => $transaksi2->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 7,
            'detail_transaksi_id' => $detail3->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 8,
            'detail_transaksi_id' => $detail3->id,
        ]);

        $detail4 = DetailTransaksi::create([
            'total_pakaian' => 2,
            'harga_layanan_akhir' => 3500,
            'total_biaya_layanan' => 7000,
            'total_biaya_prioritas' => 3000,
            'transaksi_id' => $transaksi2->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 4,
            'detail_transaksi_id' => $detail4->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 5,
            'detail_transaksi_id' => $detail4->id,
        ]);

        $detail5 = DetailTransaksi::create([
            'total_pakaian' => 4,
            'harga_layanan_akhir' => 1000,
            'total_biaya_layanan' => 4000,
            'total_biaya_prioritas' => 6000,
            'transaksi_id' => $transaksi2->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 1,
            'detail_transaksi_id' => $detail5->id,
        ]);
    }
}
