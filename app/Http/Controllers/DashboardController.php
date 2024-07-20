<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\DetailGamis;
use App\Models\Transaksi;
use App\Models\UMR;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";
        $userRole = auth()->user()->roles[0]->name;
        $umr = UMR::where('is_used', true)->first();

        if ($userRole == 'lurah' || $userRole == 'pic') {
            $cabang = null;
            $jmlCabang = Cabang::count();
            $jmlUser = User::count();
            $jmlGamis = DetailGamis::join('users as u', 'u.id', '=', 'detail_gamis.user_id')->count();

            $transaksiBaru = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('status', 'Baru')->count();
            $transaksiProses = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('status', 'Proses')->count();
            $transaksiSiapDiambil = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('status', 'Siap Diambil')->count();
            $transaksiPengantaran = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('status', 'Antar')->count();
            $transaksiPenjemputan = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('status', 'Jemput')->count();
            $transaksiSelesai = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('status', 'Selesai')->count();
            $transaksiBatal = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('status', 'Batal')->count();

            $jadwalLayanan = Transaksi::query()
                ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
                ->join('cabang as c', 'c.id', '=', 'transaksi.cabang_id')
                ->where('transaksi.status', '!=', 'Selesai')
                ->where('transaksi.status', '!=', 'Batal')
                ->where(DB::raw('DATE(transaksi.waktu)'), Carbon::now()->format('Y-m-d'))
                ->orderBy('lp.prioritas', 'desc')
                ->orderBy('transaksi.waktu', 'asc')
                ->select('transaksi.*', 'c.slug as cabang_slug', 'c.nama as cabang_nama')
                ->get();

            $pendapatanHari = Transaksi::query()
                ->where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))
                ->where('status', 'Selesai')
                ->sum('total_bayar_akhir');

            $transaksiBulanan = Transaksi::query()
                ->where(DB::raw('YEAR(waktu)'), Carbon::now()->format('Y'))
                ->where('status', 'Selesai')
                ->groupBy(DB::raw('MONTH(waktu)'))
                ->select(DB::raw('MONTH(waktu) as bulan'), DB::raw('SUM(total_bayar_akhir) as hasil'))
                ->get()
                ->keyBy('bulan');
            $pendapatanBulanan = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $pendapatanBulanan[$bulan] = [
                    'bulan' => $bulan,
                    'hasil' => isset($transaksiBulanan[$bulan]) ? $transaksiBulanan[$bulan]->hasil : 0,
                ];
            }

            $transaksiTahunan = Transaksi::query()
                ->where('status', 'Selesai')
                ->groupBy(DB::raw('YEAR(waktu)'))
                ->select(DB::raw('YEAR(waktu) as tahun'), DB::raw('SUM(total_bayar_akhir) as hasil'))
                ->get()
                ->keyBy('tahun');
            $pendapatanTahunan = [];
            foreach ($transaksiTahunan as $item => $value) {
                $pendapatanTahunan[$item] = [
                    'tahun' => $item,
                    'hasil' => isset($transaksiTahunan[$item]) ? $transaksiTahunan[$item]->hasil : 0,
                ];
            }

            return view('dashboard.index', compact('title', 'userRole', 'umr', 'cabang', 'jmlCabang', 'jmlUser', 'jmlGamis', 'transaksiBaru', 'transaksiProses', 'transaksiSiapDiambil', 'transaksiPengantaran', 'transaksiPenjemputan', 'transaksiSelesai', 'transaksiBatal', 'jadwalLayanan', 'pendapatanHari', 'pendapatanBulanan', 'pendapatanTahunan'));

        } else if ($userRole == 'manajer_laundry' || $userRole == 'pegawai_laundry') {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $jmlUser = User::where('cabang_id', $cabang->id)->count();
            $jmlCabang = null;
            $jmlGamis = DetailGamis::join('users as u', 'u.id', '=', 'detail_gamis.user_id')->where('u.cabang_id', $cabang->id)->select('detail_gamis.*')->count();

            $transaksiBaru = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('status', 'Baru')->count();
            $transaksiProses = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('status', 'Proses')->count();
            $transaksiSiapDiambil = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('status', 'Siap Diambil')->count();
            $transaksiPengantaran = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('status', 'Antar')->count();
            $transaksiPenjemputan = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('status', 'Jemput')->count();
            $transaksiSelesai = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('status', 'Selesai')->count();
            $transaksiBatal = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('status', 'Batal')->count();

            $jadwalLayanan = Transaksi::query()
                ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
                ->join('cabang as c', 'c.id', '=', 'transaksi.cabang_id')
                ->where('transaksi.cabang_id', $cabang->id)
                ->where('transaksi.status', '!=', 'Selesai')
                ->where('transaksi.status', '!=', 'Batal')
                ->where(DB::raw('DATE(transaksi.waktu)'), Carbon::now()->format('Y-m-d'))
                ->orderBy('lp.prioritas', 'desc')
                ->orderBy('transaksi.waktu', 'asc')
                ->select('transaksi.*', 'c.nama as cabang_nama')
                ->get();

            $pendapatanHari = Transaksi::query()
                ->where('cabang_id', $cabang->id)
                ->where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))
                ->where('status', 'Selesai')
                ->sum('total_bayar_akhir');

            $transaksiBulanan = Transaksi::query()
                ->where('cabang_id', $cabang->id)
                ->where(DB::raw('YEAR(waktu)'), Carbon::now()->format('Y'))
                ->where('status', 'Selesai')
                ->groupBy(DB::raw('MONTH(waktu)'))
                ->select(DB::raw('MONTH(waktu) as bulan'), DB::raw('SUM(total_bayar_akhir) as hasil'))
                ->get()
                ->keyBy('bulan');
            $pendapatanBulanan = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $pendapatanBulanan[$bulan] = [
                    'bulan' => $bulan,
                    'hasil' => isset($transaksiBulanan[$bulan]) ? $transaksiBulanan[$bulan]->hasil : 0,
                ];
            }

            $transaksiTahunan = Transaksi::query()
                ->where('cabang_id', $cabang->id)
                ->where('status', 'Selesai')
                ->groupBy(DB::raw('YEAR(waktu)'))
                ->select(DB::raw('YEAR(waktu) as tahun'), DB::raw('SUM(total_bayar_akhir) as hasil'))
                ->get()
                ->keyBy('tahun');
            $pendapatanTahunan = [];
            foreach ($transaksiTahunan as $item => $value) {
                $pendapatanTahunan[$item] = [
                    'tahun' => $item,
                    'hasil' => isset($transaksiTahunan[$item]) ? $transaksiTahunan[$item]->hasil : 0,
                ];
            }

            return view('dashboard.index', compact('title', 'userRole', 'umr', 'cabang', 'jmlCabang', 'jmlUser', 'jmlGamis', 'transaksiBaru', 'transaksiProses', 'transaksiSiapDiambil', 'transaksiPengantaran', 'transaksiPenjemputan', 'transaksiSelesai', 'transaksiBatal', 'jadwalLayanan', 'pendapatanHari', 'pendapatanBulanan', 'pendapatanTahunan'));

        } else if ($userRole == 'gamis') {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $jmlGamis = DetailGamis::join('users as u', 'u.id', '=', 'detail_gamis.user_id')->where('u.cabang_id', $cabang->id)->select('detail_gamis.*')->count();

            $transaksiBaru = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('gamis_id', auth()->user()->gamis[0]->id)->where('status', 'Baru')->count();
            $transaksiProses = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('gamis_id', auth()->user()->gamis[0]->id)->where('status', 'Proses')->count();
            $transaksiSiapDiambil = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('gamis_id', auth()->user()->gamis[0]->id)->where('status', 'Siap Diambil')->count();
            $transaksiPengantaran = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('gamis_id', auth()->user()->gamis[0]->id)->where('status', 'Antar')->count();
            $transaksiPenjemputan = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('gamis_id', auth()->user()->gamis[0]->id)->where('status', 'Jemput')->count();
            $transaksiSelesai = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('gamis_id', auth()->user()->gamis[0]->id)->where('status', 'Selesai')->count();
            $transaksiBatal = Transaksi::where(DB::raw('DATE(waktu)'), Carbon::now()->format('Y-m-d'))->where('cabang_id', $cabang->id)->where('gamis_id', auth()->user()->gamis[0]->id)->where('status', 'Batal')->count();

            $transaksiBulanan = Transaksi::query()
                ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                ->where('transaksi.cabang_id', $cabang->id)
                ->where('jl.for_gamis', true)
                ->where('transaksi.status', 'Selesai')
                ->where('dg.user_id', auth()->user()->id)
                ->where(DB::raw('YEAR(transaksi.waktu)'), Carbon::now()->format('Y'))
                ->groupBy(DB::raw('MONTH(transaksi.waktu)'))
                ->select(DB::raw('MONTH(transaksi.waktu) as bulan'), DB::raw('SUM(total_bayar_akhir) as hasil'))
                ->orderBy('transaksi.waktu', 'asc')
                ->orderBy('transaksi.gamis_id', 'asc')
                ->get()
                ->keyBy('bulan');
            $pendapatanBulanan = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $pendapatanBulanan[$bulan] = [
                    'bulan' => $bulan,
                    'hasil' => isset($transaksiBulanan[$bulan]) ? $transaksiBulanan[$bulan]->hasil : 0,
                ];
            }

            $transaksiTahunan = Transaksi::query()
                ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                ->where('transaksi.cabang_id', $cabang->id)
                ->where('jl.for_gamis', true)
                ->where('transaksi.status', 'Selesai')
                ->where('dg.user_id', auth()->user()->id)
                ->groupBy(DB::raw('YEAR(waktu)'))
                ->select(DB::raw('YEAR(waktu) as tahun'), DB::raw('SUM(total_bayar_akhir) as hasil'))
                ->orderBy('transaksi.waktu', 'asc')
                ->orderBy('transaksi.gamis_id', 'asc')
                ->get()
                ->keyBy('tahun');
            $pendapatanTahunan = [];
            foreach ($transaksiTahunan as $item => $value) {
                $pendapatanTahunan[$item] = [
                    'tahun' => $item,
                    'hasil' => isset($transaksiTahunan[$item]) ? $transaksiTahunan[$item]->hasil : 0,
                ];
            }

            return view('dashboard.index', compact('title', 'userRole', 'umr', 'cabang', 'jmlGamis', 'transaksiBaru', 'transaksiProses', 'transaksiSiapDiambil', 'transaksiPengantaran', 'transaksiPenjemputan', 'transaksiSelesai', 'transaksiBatal', 'pendapatanBulanan', 'pendapatanTahunan'));

        } else if ($userRole == 'rw') {
            $rw = User::join('rw', 'rw.user_id', '=', 'users.id')->where('users.id', '=', auth()->user()->id)->first();
            $jmlGamis = DetailGamis::join('gamis as g', 'g.id', '=', 'detail_gamis.gamis_id')->where('g.rw', $rw->nomor_rw)->count();
            return view('dashboard.index', compact('title', 'userRole', 'umr', 'rw', 'jmlGamis'));
        }
    }
}
