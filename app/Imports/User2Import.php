<?php

namespace App\Imports;

use App\Models\RW;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PIC;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class User2Import implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $data = User::withTrashed()->where('email', $row['email'])->first();
            $nomorRW = RW::where('nomor_rw', $row['nomor_rw'])->first();

            if (empty($data) && empty($nomorRW)) {
                $user = User::create([
                    'email' => $row['email'],
                    'username' => $row['username'],
                    'password' => Hash::make($row['password']),
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
                    case 'pic':
                        PIC::create($validatedProfile);
                        break;
                    case 'rw':
                        $validatedProfile['nomor_rw'] = $row['nomor_rw'];
                        RW::create($validatedProfile);
                        break;
                }
            }
        }
    }
}
