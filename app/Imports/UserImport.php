<?php

namespace App\Imports;

use App\Models\Cabang;
use App\Models\DetailGamis;
use App\Models\Gamis;
use App\Models\ManajerLaundry;
use App\Models\PegawaiLaundry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $data = User::withTrashed()->where('email', $row['email'])->first();
            if (empty($data)) {
                $cabang = Cabang::where('slug', $row['cabang'])->first();
                $user = User::create([
                    'email' => $row['email'],
                    'username' => $row['username'],
                    'password' => Hash::make($row['password']),
                    'cabang_id' => $cabang->id,
                ]);
                $user->assignRole($row['role']);
                $validatedProfile = [
                    'nama' => $row['nama_lengkap'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tanggal_lahir' => Carbon::parse($row['tanggal_lahir'])->format('Y-m-d'),
                    'telepon' => $row['telepon'],
                    'alamat' => $row['alamat'],
                    'mulai_kerja' => $row['mulai_kerja'],
                    'user_id' => $user->id,
                ];

                switch ($row['role']) {
                    case 'manajer_laundry':
                        ManajerLaundry::create($validatedProfile);
                        break;
                    case 'pegawai_laundry':
                        PegawaiLaundry::create($validatedProfile);
                        break;
                    case 'gamis':
                        $gamis = Gamis::where('kartu_keluarga', $row['kartu_keluarga'])->first();
                        $validatedProfile['gamis_id'] = $gamis->id;
                        $validatedProfile['nama_pemasukkan'] = $row['nama_pemasukkan'];
                        $validatedProfile['pemasukkan'] = $row['pemasukkan'];
                        DetailGamis::create($validatedProfile);
                        break;
                }
            }
        }
    }
}
