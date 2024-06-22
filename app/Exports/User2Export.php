<?php

namespace App\Exports;

use App\Models\RW;
use App\Models\User;
use App\Models\Lurah;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class User2Export implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    public function collection()
    {
        $user = User::role(['lurah', 'rw'])->orderBy('created_at', 'asc')->get();

        foreach ($user as $key => $item) {
            switch ($item->roles[0]->name) {
                case 'lurah':
                    $profile = Lurah::where('user_id', $item->id)->first();

                    $user[$key]['role'] = $item->roles[0]->name;
                    $user[$key]['nama_lengkap'] = $profile->nama;
                    $user[$key]['jenis_kelamin'] = $profile->jenis_kelamin;
                    $user[$key]['tempat_lahir'] = $profile->tempat_lahir;
                    $user[$key]['tanggal_lahir'] = $profile->tanggal_lahir;
                    $user[$key]['telepon'] = $profile->telepon;
                    $user[$key]['alamat'] = $profile->alamat;
                    $user[$key]['mulai_kerja'] = $profile->mulai_kerja;
                    $user[$key]['selesai_kerja'] = $profile->selesai_kerja;

                    break;
                case 'rw':
                    $profile = RW::where('user_id', $item->id)->first();

                    $user[$key]['role'] = $item->roles[0]->name;
                    $user[$key]['nama_lengkap'] = $profile->nama;
                    $user[$key]['jenis_kelamin'] = $profile->jenis_kelamin;
                    $user[$key]['tempat_lahir'] = $profile->tempat_lahir;
                    $user[$key]['tanggal_lahir'] = $profile->tanggal_lahir;
                    $user[$key]['telepon'] = $profile->telepon;
                    $user[$key]['alamat'] = $profile->alamat;
                    $user[$key]['mulai_kerja'] = $profile->mulai_kerja;
                    $user[$key]['selesai_kerja'] = $profile->selesai_kerja;
                    $user[$key]['nomor_rw'] = $profile->nomor_rw;

                    break;
            }
        }
        return $user;
    }

    public function map($user): array
    {
        return [
            $user->role,
            $user->username,
            $user->email,
            $user->nama_lengkap,
            $user->jenis_kelamin,
            $user->tempat_lahir,
            $user->tanggal_lahir,
            $user->telepon,
            $user->alamat,
            $user->mulai_kerja,
            $user->selesai_kerja,
            $user->nomor_rw,
        ];
    }

    public function headings(): array
    {
        return [
            'role',
            'username',
            'email',
            'nama_lengkap',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'telepon',
            'alamat',
            'mulai_kerja',
            'selesai_kerja',
            'nomor_rw',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
