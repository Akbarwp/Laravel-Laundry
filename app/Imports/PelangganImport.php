<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PelangganImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $pelanggan = Pelanggan::where('nama', $row['pelanggan'])->first();
        if (empty($pelanggan)) {
            return new Pelanggan([
                'nama' => $row['pelanggan'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'telepon' => $row['telepon'],
                'alamat' => $row['alamat'],
            ]);
        }
    }
}
