<?php

namespace App\Imports;

use App\Models\Cabang;
use App\Models\LayananTambahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LayananTambahanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $cabang = Cabang::where('slug', $row['cabang'])->first();
        $nama = LayananTambahan::withTrashed()->where('nama', $row['nama_layanan'])->where('cabang_id', $cabang->id)->first();
        if (empty($nama)) {
            return new LayananTambahan([
                'nama' => $row['nama_layanan'],
                'harga' => $row['harga'],
                'cabang_id' => $cabang->id,
            ]);
        }
    }
}
