<?php

namespace App\Exports;

use App\Models\LayananPrioritas;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LayananPrioritasExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        return LayananPrioritas::query()
            ->join('cabang as c', 'c.id', '=', 'layanan_prioritas.cabang_id')
            ->where('c.slug', $this->cabang)
            ->orderBy('layanan_prioritas.prioritas', 'asc')->get(['layanan_prioritas.*', 'c.slug']);
    }

    public function map($data): array
    {
        return [
            $data->nama,
            $data->harga,
            $data->prioritas,
            $data->deskripsi,
            $data->slug,
        ];
    }

    public function headings(): array
    {
        return [
            'layanan_prioritas',
            'harga',
            'prioritas',
            'deskripsi',
            'cabang',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}
