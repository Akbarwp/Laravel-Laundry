<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailLayananTransaksi;
use App\Models\DetailTransaksi;

class DetailLayananTransaksiController extends Controller
{
    public function viewDetailLayanan(Request $request)
    {
        $title = "Detail Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::withTrashed()->where('slug', $request->cabang)->first();
            $transaksi = Transaksi::where('id', $request->transaksi)->first();
            $detailTransaksi = DetailTransaksi::where('id', $request->detailTransaksi)->orderBy('id', 'asc')->first();
            $detailLayananTransaksi = DetailLayananTransaksi::where('detail_transaksi_id', $request->detailTransaksi)->orderBy('id', 'asc')->get();
            return view('dashboard.transaksi.lurah.layanan', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'detailLayananTransaksi'));

        } else {
            $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = Transaksi::where('id', $request->transaksi)->first();
            $detailTransaksi = DetailTransaksi::where('id', $request->detailTransaksi)->orderBy('id', 'asc')->first();
            $detailLayananTransaksi = DetailLayananTransaksi::where('detail_transaksi_id', $request->detailTransaksi)->orderBy('id', 'asc')->get();
            return view('dashboard.transaksi.layanan', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'detailLayananTransaksi'));
        }
    }

    public function viewDetailLayananGamis(Request $request)
    {
        $title = "Detail Transaksi Layanan";

        $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
        $transaksi = Transaksi::where('id', $request->transaksi)->first();
        $detailTransaksi = DetailTransaksi::where('id', $request->detailTransaksi)->orderBy('id', 'asc')->first();
        $detailLayananTransaksi = DetailLayananTransaksi::where('detail_transaksi_id', $request->detailTransaksi)->orderBy('id', 'asc')->get();
        return view('dashboard.transaksi.gamis.layanan', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'detailLayananTransaksi'));
    }
}
