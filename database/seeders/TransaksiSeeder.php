<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Database\Seeder;
use App\Models\DetailLayananTransaksi;
use App\Models\LayananTambahanTransaksi;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jumlah = 10;
        $jmlTransaksi = 10;
        $tanggalAwal = 'now';
        $tanggalAkhir = '2024-12-31';

        for ($i = 1; $i <= $jmlTransaksi; $i++) {
            $this->createTransaksiReguler(
                fake()->numberBetween(1, $jumlah),
                fake()->randomElement([1,2]),
                fake()->randomElement(['Baru', 'Proses']),
                fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta')->format('dmY'),
                fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta')
            );
        }
        for ($i = 1; $i <= $jmlTransaksi; $i++) {
            $this->createTransaksiKilat(
                fake()->numberBetween(1, $jumlah),
                fake()->randomElement([1,2]),
                fake()->randomElement(['Baru', 'Proses']),
                fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta')->format('dmY'),
                fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta')
            );
        }
        for ($i = 1; $i <= $jmlTransaksi; $i++) {
            $this->createTransaksiCahaya(
                fake()->numberBetween(1, $jumlah),
                fake()->randomElement([1,2]),
                fake()->randomElement(['Baru', 'Proses']),
                fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta')->format('dmY'),
                fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta')
            );
        }
        for ($i = 1; $i <= $jmlTransaksi; $i++) {
            $this->createTransaksiRegulerTambahan(
                fake()->numberBetween(1, $jumlah),
                fake()->randomElement([1,2]),
                fake()->randomElement(['Baru', 'Proses']),
                fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta')->format('dmY'),
                fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta')
            );
        }
    }

    public function createTransaksiReguler($pelanggan, $gamis, $status, $tanggalNota, $tanggal)
    {
        $nota1 = Carbon::now()->format('His') . '-' . $tanggalNota . '-' . str()->uuid();
        $transaksi = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota1,
            'nota_pelanggan' => 'pelanggan-' . $nota1,
            'waktu' => $tanggal,
            'total_biaya_layanan' => 138000,
            'total_biaya_prioritas' => 0,
            'total_bayar_akhir' => 138000,
            'total_biaya_layanan_tambahan' => 0,
            'jenis_pembayaran' => 'Tunai',
            'bayar' => 150000,
            'kembalian' => 12000,
            'status' => $status,
            'layanan_prioritas_id' => 1,
            'pelanggan_id' => $pelanggan,
            'pegawai_id' => 7,
            'gamis_id' => $gamis,
            'cabang_id' => 1,
        ]);

        $detail1 = DetailTransaksi::create([
            'total_pakaian' => 24,
            'harga_layanan_akhir' => 3500,
            'total_biaya_layanan' => 84000,
            'total_biaya_prioritas' => 0,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 3,
            'detail_transaksi_id' => $detail1->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 4,
            'detail_transaksi_id' => $detail1->id,
        ]);

        $detail2 = DetailTransaksi::create([
            'total_pakaian' => 12,
            'harga_layanan_akhir' => 4500,
            'total_biaya_layanan' => 54000,
            'total_biaya_prioritas' => 0,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 5,
            'detail_transaksi_id' => $detail2->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 6,
            'detail_transaksi_id' => $detail2->id,
        ]);
    }

    public function createTransaksiKilat($pelanggan, $gamis, $status, $tanggalNota, $tanggal)
    {
        $nota1 = Carbon::now()->format('His') . '-' . $tanggalNota . '-' . str()->uuid();
        $transaksi = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota1,
            'nota_pelanggan' => 'pelanggan-' . $nota1,
            'waktu' => $tanggal,
            'total_biaya_layanan' => 138000,
            'total_biaya_prioritas' => 54000,
            'total_bayar_akhir' => 192000,
            'total_biaya_layanan_tambahan' => 0,
            'jenis_pembayaran' => 'Tunai',
            'bayar' => 200000,
            'kembalian' => 8000,
            'status' => $status,
            'layanan_prioritas_id' => 2,
            'pelanggan_id' => $pelanggan,
            'pegawai_id' => 7,
            'gamis_id' => $gamis,
            'cabang_id' => 1,
        ]);

        $detail1 = DetailTransaksi::create([
            'total_pakaian' => 24,
            'harga_layanan_akhir' => 3500,
            'total_biaya_layanan' => 84000,
            'total_biaya_prioritas' => 36000,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 3,
            'detail_transaksi_id' => $detail1->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 4,
            'detail_transaksi_id' => $detail1->id,
        ]);

        $detail2 = DetailTransaksi::create([
            'total_pakaian' => 12,
            'harga_layanan_akhir' => 4500,
            'total_biaya_layanan' => 54000,
            'total_biaya_prioritas' => 18000,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 5,
            'detail_transaksi_id' => $detail2->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 6,
            'detail_transaksi_id' => $detail2->id,
        ]);
    }

    public function createTransaksiCahaya($pelanggan, $gamis, $status, $tanggalNota, $tanggal)
    {
        $nota1 = Carbon::now()->format('His') . '-' . $tanggalNota . '-' . str()->uuid();
        $transaksi = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota1,
            'nota_pelanggan' => 'pelanggan-' . $nota1,
            'waktu' => $tanggal,
            'total_biaya_layanan' => 138000,
            'total_biaya_prioritas' => 72000,
            'total_bayar_akhir' => 210000,
            'total_biaya_layanan_tambahan' => 0,
            'jenis_pembayaran' => 'Tunai',
            'bayar' => 220000,
            'kembalian' => 10000,
            'status' => $status,
            'layanan_prioritas_id' => 3,
            'pelanggan_id' => $pelanggan,
            'pegawai_id' => 7,
            'gamis_id' => $gamis,
            'cabang_id' => 1,
        ]);

        $detail1 = DetailTransaksi::create([
            'total_pakaian' => 24,
            'harga_layanan_akhir' => 3500,
            'total_biaya_layanan' => 84000,
            'total_biaya_prioritas' => 48000,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 3,
            'detail_transaksi_id' => $detail1->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 4,
            'detail_transaksi_id' => $detail1->id,
        ]);

        $detail2 = DetailTransaksi::create([
            'total_pakaian' => 12,
            'harga_layanan_akhir' => 4500,
            'total_biaya_layanan' => 54000,
            'total_biaya_prioritas' => 24000,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 5,
            'detail_transaksi_id' => $detail2->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 6,
            'detail_transaksi_id' => $detail2->id,
        ]);
    }

    public function createTransaksiRegulerTambahan($pelanggan, $gamis, $status, $tanggalNota, $tanggal)
    {
        $nota1 = Carbon::now()->format('His') . '-' . $tanggalNota . '-' . str()->uuid();
        $transaksi = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota1,
            'nota_pelanggan' => 'pelanggan-' . $nota1,
            'waktu' => $tanggal,
            'total_biaya_layanan' => 138000,
            'total_biaya_prioritas' => 0,
            'total_bayar_akhir' => 158000,
            'total_biaya_layanan_tambahan' => 20000,
            'jenis_pembayaran' => 'Tunai',
            'bayar' => 200000,
            'kembalian' => 42000,
            'status' => $status,
            'layanan_prioritas_id' => 1,
            'pelanggan_id' => $pelanggan,
            'pegawai_id' => 7,
            'gamis_id' => $gamis,
            'cabang_id' => 1,
        ]);

        $detail1 = DetailTransaksi::create([
            'total_pakaian' => 24,
            'harga_layanan_akhir' => 3500,
            'total_biaya_layanan' => 84000,
            'total_biaya_prioritas' => 0,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 3,
            'detail_transaksi_id' => $detail1->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 4,
            'detail_transaksi_id' => $detail1->id,
        ]);

        $detail2 = DetailTransaksi::create([
            'total_pakaian' => 12,
            'harga_layanan_akhir' => 4500,
            'total_biaya_layanan' => 54000,
            'total_biaya_prioritas' => 0,
            'transaksi_id' => $transaksi->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 5,
            'detail_transaksi_id' => $detail2->id,
        ]);
        DetailLayananTransaksi::create([
            'harga_jenis_layanan_id' => 6,
            'detail_transaksi_id' => $detail2->id,
        ]);

        LayananTambahanTransaksi::create([
            'layanan_tambahan_id' => 1,
            'transaksi_id' => $transaksi->id,
        ]);
        LayananTambahanTransaksi::create([
            'layanan_tambahan_id' => 2,
            'transaksi_id' => $transaksi->id,
        ]);
    }
}
