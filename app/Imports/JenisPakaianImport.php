<?php

namespace App\Imports;

use App\Models\Cabang;
use App\Models\JenisPakaian;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JenisPakaianImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $cabang = Cabang::where('slug', $row['cabang'])->first();
        $nama = JenisPakaian::withTrashed()->where('nama', $row['nama_pakaian'])->where('cabang_id', $cabang->id)->first();
        if (empty($nama)) {
            return new JenisPakaian([
                'nama' => $row['nama_pakaian'],
                'deskripsi' => $row['deskripsi'],
                'cabang_id' => $cabang->id,
            ]);
        }
    }
}
