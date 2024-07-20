<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\LayananTambahanTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        $title = "Laundry Simokerto";
        return view('pelanggan.index', compact('title'));
    }

    public function cekTransaksi(Request $request)
    {
        $transaksi = Transaksi::query()
            ->join('cabang as c', 'transaksi.cabang_id', '=', 'c.id')
            ->join('pelanggan as p', 'transaksi.pelanggan_id', '=', 'p.id')
            ->where('nota_pelanggan', $request->nota)
            ->select(
                'transaksi.id',
                'transaksi.nota_pelanggan',
                DB::raw('DATE(transaksi.waktu) as tanggal'),
                'c.nama as cabang_nama',
                'p.nama as pelanggan_nama',
                'transaksi.jenis_pembayaran',
                'transaksi.total_bayar_akhir',
                'transaksi.bayar',
                'transaksi.kembalian',
                'transaksi.status')
            ->first();
        $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
        $layananTambahan = LayananTambahanTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();

        $detailLayanan = [];
        foreach ($detailTransaksi as $value => $item) {
            $detailLayanan[$value] = [
                'pakaian' => $item->detailLayananTransaksi[0]->hargaJenisLayanan->jenisPakaian->nama,
                'total' => $item->total_pakaian,
            ];
            foreach ($item->detailLayananTransaksi as $layanan) {
                $detailLayanan[$value]['layanan'][] = $layanan->hargaJenisLayanan->jenisLayanan->nama;
            }
        }

        $detailLayananTambahan = [];
        foreach ($layananTambahan as $value => $item) {
            $detailLayananTambahan[$value] = [
                'layanan' => $item->layananTambahan->nama,
            ];
        }

        if ($transaksi) {
            return [$transaksi, $detailLayanan, $detailLayananTambahan];
        } else {
            return abort(400);
        }
    }
}
