<?php

namespace App\Exports;

use App\Models\JenisPakaian;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JenisPakaianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $cabang;

    public function __construct($cabang)
    {
        $this->cabang = $cabang;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return JenisPakaian::query()
            ->join('cabang as c', 'c.id', '=', 'jenis_pakaian.cabang_id')
            ->where('c.slug', $this->cabang)
            ->orderBy('jenis_pakaian.id', 'asc')->get(['jenis_pakaian.*', 'c.slug']);
    }

    public function map($data): array
    {
        return [
            $data->nama,
            $data->deskripsi,
            $data->slug,
        ];
    }

    public function headings(): array
    {
        return [
            'nama_pakaian',
            'deskripsi',
            'cabang',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}
