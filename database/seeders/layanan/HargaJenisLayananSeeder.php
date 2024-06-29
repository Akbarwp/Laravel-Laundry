<?php

namespace Database\Seeders\layanan;

use App\Enums\JenisSatuanLayanan;
use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use Illuminate\Database\Seeder;
use App\Models\HargaJenisLayanan;

class HargaJenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang2 = Cabang::where('id', 2)->onlyTrashed()->first();

        //? Cabang 1
        $jenisLayananCuci = JenisLayanan::where(['nama' => 'Cuci', 'cabang_id' => $cabang->id])->first();
        $jenisLayananSetrika = JenisLayanan::where(['nama' => 'Setrika', 'cabang_id' => $cabang->id])->first();

        $jenisPakaianKaos = JenisPakaian::where(['nama' => 'Kaos', 'cabang_id' => $cabang->id])->first();
        $jenisPakaianKemeja = JenisPakaian::where(['nama' => 'Kemeja', 'cabang_id' => $cabang->id])->first();
        $jenisPakaianJeans = JenisPakaian::where(['nama' => 'Jeans', 'cabang_id' => $cabang->id])->first();

        //? Seeder --> make Harga Jenis Layanan Kaos
        HargaJenisLayanan::create([
            'harga' => 1000,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'jenis_layanan_id' => $jenisLayananCuci->id,
            'jenis_pakaian_id' => $jenisPakaianKaos->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 1500,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'jenis_layanan_id' => $jenisLayananSetrika->id,
            'jenis_pakaian_id' => $jenisPakaianKaos->id,
            'cabang_id' => $cabang->id,
        ]);

        //? Seeder --> make Harga Jenis Layanan Kemeja
        HargaJenisLayanan::create([
            'harga' => 1500,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'jenis_layanan_id' => $jenisLayananCuci->id,
            'jenis_pakaian_id' => $jenisPakaianKemeja->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 2000,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'jenis_layanan_id' => $jenisLayananSetrika->id,
            'jenis_pakaian_id' => $jenisPakaianKemeja->id,
            'cabang_id' => $cabang->id,
        ]);

        //? Seeder --> make Harga Jenis Layanan Jeans
        HargaJenisLayanan::create([
            'harga' => 2000,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'jenis_layanan_id' => $jenisLayananCuci->id,
            'jenis_pakaian_id' => $jenisPakaianJeans->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 2500,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'jenis_layanan_id' => $jenisLayananSetrika->id,
            'jenis_pakaian_id' => $jenisPakaianJeans->id,
            'cabang_id' => $cabang->id,
        ]);

        //? Cabang 2
        $jenisLayananCuci2 = JenisLayanan::where(['nama' => 'Cuci', 'cabang_id' => $cabang2->id])->first();
        $jenisLayananSetrika2 = JenisLayanan::where(['nama' => 'Setrika', 'cabang_id' => $cabang2->id])->first();

        $jenisPakaianKemeja2 = JenisPakaian::where(['nama' => 'Kemeja', 'cabang_id' => $cabang2->id])->first();

        //? Seeder --> make Harga Jenis Layanan Kemeja
        HargaJenisLayanan::create([
            'harga' => 1000,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'jenis_layanan_id' => $jenisLayananCuci2->id,
            'jenis_pakaian_id' => $jenisPakaianKemeja2->id,
            'cabang_id' => $cabang2->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 1000,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'jenis_layanan_id' => $jenisLayananCuci2->id,
            'jenis_pakaian_id' => $jenisLayananSetrika2->id,
            'cabang_id' => $cabang2->id,
        ]);
    }
}
