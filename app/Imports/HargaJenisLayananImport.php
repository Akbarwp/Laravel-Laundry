<?php

namespace App\Imports;

use App\Models\Cabang;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use App\Models\HargaJenisLayanan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HargaJenisLayananImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $cabang = Cabang::where('slug', $row['cabang'])->first();
        $namaLayanan = JenisLayanan::withTrashed()->where('nama', $row['nama_layanan'])->where('cabang_id', $cabang->id)->first();
        $namaPakaian = JenisPakaian::withTrashed()->where('nama', $row['nama_pakaian'])->where('cabang_id', $cabang->id)->first();
        $hargaJenisLayanan = HargaJenisLayanan::withTrashed()->where('jenis_layanan_id', $namaLayanan->id)->where('jenis_pakaian_id', $namaPakaian->id)->where('cabang_id', $cabang->id)->first();

        if (empty($hargaJenisLayanan) && ($row['jenis_satuan'] == 'Kg' || $row['jenis_satuan'] == 'Perjalanan')) {
            return new HargaJenisLayanan([
                'harga' => $row['harga'],
                'jenis_satuan' => $row['jenis_satuan'],
                'jenis_layanan_id' => $namaLayanan->id,
                'jenis_pakaian_id' => $namaPakaian->id,
                'cabang_id' => $cabang->id,
            ]);
        }
    }
}
