<?php

namespace App\Exports;

use App\Models\LayananTambahan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LayananTambahanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        return LayananTambahan::query()
            ->join('cabang as c', 'c.id', '=', 'layanan_tambahan.cabang_id')
            ->where('c.slug', $this->cabang)
            ->orderBy('layanan_tambahan.id', 'asc')->get(['layanan_tambahan.*', 'c.slug']);
    }

    public function map($data): array
    {
        return [
            $data->nama,
            $data->harga,
            $data->slug,
        ];
    }

    public function headings(): array
    {
        return [
            'nama_layanan',
            'harga',
            'cabang',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}
