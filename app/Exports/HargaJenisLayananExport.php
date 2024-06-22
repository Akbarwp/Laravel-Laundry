<?php

namespace App\Exports;

use App\Models\HargaJenisLayanan;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HargaJenisLayananExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        return HargaJenisLayanan::query()
            ->join('cabang as c', 'c.id', '=', 'harga_jenis_layanan.cabang_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'harga_jenis_layanan.jenis_layanan_id')
            ->join('jenis_pakaian as jp', 'jp.id', '=', 'harga_jenis_layanan.jenis_pakaian_id')
            ->where('c.slug', $this->cabang)
            ->orderBy('harga_jenis_layanan.jenis_layanan_id', 'asc')
            ->orderBy('harga_jenis_layanan.jenis_pakaian_id', 'asc')
            ->get(['harga_jenis_layanan.*', 'c.slug', 'jl.nama as jenis_layanan', 'jp.nama as jenis_pakaian']);
    }

    public function map($data): array
    {
        return [
            $data->jenis_layanan,
            $data->jenis_pakaian,
            $data->harga,
            $data->jenis_satuan,
            $data->slug,
        ];
    }

    public function headings(): array
    {
        return [
            'jenis_layanan',
            'jenis_pakaian',
            'harga',
            'jenis_satuan',
            'cabang',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}
