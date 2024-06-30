<?php

namespace App\Exports;

use App\Models\DetailGamis;
use App\Models\ManajerLaundry;
use App\Models\PegawaiLaundry;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $cabang;

    public function __construct($cabang)
    {
        $this->cabang = $cabang;
    }

    public function collection()
    {
        if ($this->cabang) {
            $user = User::query()
                ->join('cabang as c', 'c.id', '=', 'users.cabang_id')
                ->where('c.slug', $this->cabang)
                ->orderBy('users.cabang_id', 'asc')->get(['users.*', 'c.slug']);
        } else {
            $user = User::join('cabang as c', 'c.id', '=', 'users.cabang_id')->orderBy('cabang_id', 'asc')->get(['users.*', 'c.slug']);
        }

        $roleUser = auth()->user()->roles[0]->name;
        foreach ($user as $key => $item) {
            switch ($item->roles[0]->name) {
                case 'manajer_laundry':
                    if ($roleUser == 'lurah') {
                        $profile = ManajerLaundry::where('user_id', $item->id)->first();

                        $user[$key]['role'] = $item->roles[0]->name;
                        $user[$key]['nama_lengkap'] = $profile->nama;
                        $user[$key]['jenis_kelamin'] = $profile->jenis_kelamin;
                        $user[$key]['tempat_lahir'] = $profile->tempat_lahir;
                        $user[$key]['tanggal_lahir'] = $profile->tanggal_lahir;
                        $user[$key]['telepon'] = $profile->telepon;
                        $user[$key]['alamat'] = $profile->alamat;
                        $user[$key]['mulai_kerja'] = $profile->mulai_kerja;
                        $user[$key]['selesai_kerja'] = $profile->selesai_kerja;

                    } elseif ($roleUser == 'manajer_laundry') {
                        break;
                    }
                    break;
                case 'pegawai_laundry':
                    $profile = PegawaiLaundry::where('user_id', $item->id)->first();

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
                case 'gamis':
                    $profile = DetailGamis::where('user_id', $item->id)->first();

                    $user[$key]['role'] = $item->roles[0]->name;
                    $user[$key]['nama_lengkap'] = $profile->nama;
                    $user[$key]['jenis_kelamin'] = $profile->jenis_kelamin;
                    $user[$key]['tempat_lahir'] = $profile->tempat_lahir;
                    $user[$key]['tanggal_lahir'] = $profile->tanggal_lahir;
                    $user[$key]['telepon'] = $profile->telepon;
                    $user[$key]['alamat'] = $profile->alamat;
                    $user[$key]['mulai_kerja'] = $profile->mulai_kerja;
                    $user[$key]['selesai_kerja'] = $profile->selesai_kerja;
                    $user[$key]['kartu_keluarga'] = $profile->gamis->kartu_keluarga;
                    $user[$key]['nama_pemasukkan'] = $profile->nama_pemasukkan;
                    $user[$key]['pemasukkan'] = $profile->pemasukkan;

                    break;
            }
        }
        return $user;
    }

    public function map($user): array
    {
        return [
            $user->role,
            $user->slug,
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
            $user->kartu_keluarga,
            $user->nama_pemasukkan,
            $user->pemasukkan,
        ];
    }

    public function headings(): array
    {
        return [
            'role',
            'cabang',
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
            'kartu_keluarga',
            'nama_pemasukkan',
            'pemasukkan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
