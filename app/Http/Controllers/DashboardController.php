<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Transaksi;
use App\Models\UMR;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";
        $userRole = auth()->user()->roles[0]->name;
        $umr = UMR::where('is_used', 1)->first();

        if ($userRole != 'lurah') {
            $jmlUser = User::where('cabang_id', auth()->user()->cabang_id)->count();
            $jmlCabang = '';

        } else {
            $jmlCabang = Cabang::count();
            $jmlUser = User::count();
        }

        //? Mencari detail transaksi yang jenis layanannya untuk Gamis
        $transaksi = Transaksi::query()
            ->join('detail_transaksi as dt', 'dt.transaksi_id', '=', 'transaksi.id')
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'dt.layanan_prioritas_id')
            ->join('detail_layanan_transaksi as dlt', 'dlt.detail_transaksi_id', '=', 'dt.id')
            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
            ->select(
                'transaksi.nota_layanan',
                'dt.total_pakaian',
                'lp.nama as layanan_prioritas',
                'lp.harga as harga_layanan_prioritas',
                'dt.total_biaya_prioritas',
                'jl.nama as jenis_layanan',
                'jp.nama as jenis_pakaian',
                'hjl.harga as harga_layanan',
            )
            ->where('jl.for_gamis', true)
            ->where('transaksi.gamis_id', 1)
            ->where('transaksi.cabang_id', 1)
            ->orderBy('transaksi.nota_layanan', 'asc')->orderBy('dt.id', 'asc')->get();

        //? Mencari jenis layanan yang untuk Gamis && Menghitung total_biaya_layanan per detail transaksi
        $transaksi2 = Transaksi::query()
            ->join('detail_transaksi as dt', 'dt.transaksi_id', '=', 'transaksi.id')
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'dt.layanan_prioritas_id')
            ->join('detail_layanan_transaksi as dlt', 'dlt.detail_transaksi_id', '=', 'dt.id')
            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
            ->select(
                'transaksi.nota_layanan',
                DB::raw('dt.total_pakaian * hjl.harga as total_biaya_layanan'),
            )
            ->where('jl.for_gamis', true)
            ->where('transaksi.gamis_id', 1)
            ->where('transaksi.cabang_id', 1)
            ->groupBy(
                'transaksi.nota_layanan',
                'dt.total_pakaian',
                'hjl.harga',
            )
            ->orderBy('transaksi.nota_layanan', 'asc')->orderBy('dt.id', 'asc')->get();

        //? Menghitung total_pendapatan_gamis per transaksi
        $transaksi3 = Transaksi::query()
            ->join('detail_transaksi as dt', 'dt.transaksi_id', '=', 'transaksi.id')
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'dt.layanan_prioritas_id')
            ->join('detail_layanan_transaksi as dlt', 'dlt.detail_transaksi_id', '=', 'dt.id')
            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
            ->select(
                'transaksi.nota_layanan',
                DB::raw('SUM(dt.total_pakaian * hjl.harga) as total_pendapatan_gamis'),
            )
            ->where('jl.for_gamis', true)
            ->where('transaksi.gamis_id', 1)
            ->where('transaksi.cabang_id', 1)
            ->groupBy(
                'transaksi.nota_layanan',
            )
            ->orderBy('transaksi.nota_layanan', 'asc')->orderBy('dt.id', 'asc')->get();

        //? Menghitung total_pendapatan_gamis secara keseluruhan
        $totalPendapatanGamis1 = 0;
        foreach ($transaksi3 as $item) {
            $totalPendapatanGamis1 += $item->total_pendapatan_gamis;
        }
        $totalPendapatanGamis1 = 'Rp' . number_format($totalPendapatanGamis1, 2, ',', '.');

        // dd($transaksi, $transaksi2, $transaksi3, $totalPendapatanGamis1);

        return view('dashboard.index', compact('title', 'userRole', 'jmlCabang', 'jmlUser', 'umr'));
    }
}
