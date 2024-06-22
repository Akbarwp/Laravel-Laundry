<?php

namespace App\Imports;

use App\Models\Cabang;
use App\Models\LayananPrioritas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LayananPrioritasImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $cabang = Cabang::where('slug', $row['cabang'])->first();
        $nama = LayananPrioritas::withTrashed()->where('nama', $row['layanan_prioritas'])->where('cabang_id', $cabang->id)->first();
        if (empty($nama)) {
            return new LayananPrioritas([
                'nama' => $row['layanan_prioritas'],
                'deskripsi' => $row['deskripsi'],
                'harga' => $row['harga'],
                'prioritas' => $row['prioritas'],
                'cabang_id' => $cabang->id,
            ]);
        }
    }
}
