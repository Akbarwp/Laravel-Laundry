<?php

namespace App\Imports;

use App\Models\Gamis;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GamisImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $kk = Gamis::where('kartu_keluarga', $row['kartu_keluarga'])->first();
        if (empty($kk)) {
            return new Gamis([
                'kartu_keluarga' => $row['kartu_keluarga'],
                'rt' => $row['rt'],
                'rw' => $row['rw'],
            ]);
        }
    }
}
