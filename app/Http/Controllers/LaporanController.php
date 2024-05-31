<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\MonitoringGamis;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanPendapatanLaundry(Request $request)
    {
        $title = "Laporan Pendapatan Laundry";
        $cabang = Cabang::withTrashed()->get();
        $nama_cabang = null;

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m-') . '01';
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m-d');

        $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->join('cabang as c', 'c.id', '=', 'transaksi.cabang_id')
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
            ->select('transaksi.nota_layanan', DB::raw("DATE(transaksi.waktu) as tanggal"), 'transaksi.layanan_prioritas_id', 'transaksi.total_bayar_akhir', 'transaksi.pelanggan_id', 'transaksi.pegawai_id', DB::raw("SUM((dt.total_pakaian * hjl.harga) + (dt.total_pakaian * lp.harga)) as pendapatan_laundry"), 'transaksi.gamis_id as gamis_id', 'c.nama as nama_cabang', 'c.id as cabang_id')
            ->where('jl.for_gamis', false)
            ->where(DB::raw("DATE(transaksi.waktu)"), '>=', $tanggalAwal)
            ->where(DB::raw("DATE(transaksi.waktu)"), '<=', $tanggalAkhir)
            ->where('transaksi.status', 'Selesai')
            ->groupBy('transaksi.nota_layanan', DB::raw("DATE(transaksi.waktu)"), 'transaksi.layanan_prioritas_id', 'transaksi.total_bayar_akhir', 'transaksi.pelanggan_id', 'transaksi.pegawai_id', 'transaksi.gamis_id', 'c.nama', 'c.id')
            ->orderBy('transaksi.waktu', 'asc')
            ->get();

        if ($request->cabang_id) {
            $transaksi = $transaksi->where('cabang_id', $request->cabang_id);
            $nama_cabang = $cabang->where('id', $request->cabang_id)->first();
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry') {
            $cabangId = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = $transaksi->where('cabang_id', $cabangId->id);
            $nama_cabang = $cabangId;
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry' && $request->cabang_id) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        return view('dashboard.laporan.pendapatan-laundry', compact('title', 'transaksi', 'cabang', 'nama_cabang'));
    }

    public function pdfLaporanPendapatanLaundry(Request $request)
    {
        $title = "Laporan Pendapatan Laundry";
        $nama_cabang = null;

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m-') . '01';
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m-d');

        $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->join('cabang as c', 'c.id', '=', 'transaksi.cabang_id')
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
            ->select('transaksi.nota_layanan', DB::raw("DATE(transaksi.waktu) as tanggal"), 'transaksi.layanan_prioritas_id', 'transaksi.total_bayar_akhir', 'transaksi.pelanggan_id', 'transaksi.pegawai_id', DB::raw("SUM((dt.total_pakaian * hjl.harga) + (dt.total_pakaian * lp.harga)) as pendapatan_laundry"), 'transaksi.gamis_id as gamis_id', 'c.nama as nama_cabang', 'c.id as cabang_id')
            ->where('jl.for_gamis', false)
            ->where(DB::raw("DATE(transaksi.waktu)"), '>=', $tanggalAwal)
            ->where(DB::raw("DATE(transaksi.waktu)"), '<=', $tanggalAkhir)
            ->where('transaksi.status', 'Selesai')
            ->groupBy('transaksi.nota_layanan', DB::raw("DATE(transaksi.waktu)"), 'transaksi.layanan_prioritas_id', 'transaksi.total_bayar_akhir', 'transaksi.pelanggan_id', 'transaksi.pegawai_id', 'transaksi.gamis_id', 'c.nama', 'c.id')
            ->orderBy('transaksi.waktu', 'asc')
            ->get();

        if ($request->cabang_id) {
            $transaksi = $transaksi->where('cabang_id', $request->cabang_id);
            $nama_cabang = Cabang::withTrashed()->where('id', $request->cabang_id)->first();
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry') {
            $cabangId = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = $transaksi->where('cabang_id', $cabangId->id);
            $nama_cabang = $cabangId;
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry' && $request->cabang_id) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $view = view()->share($title, $transaksi);
        $pdf = Pdf::loadView('dashboard.laporan.pdf.pendapatan-laundry', [
            'judul' => $title,
            'judulTabel' => $title,
            'transaksi' => $transaksi,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'nama_cabang' => $nama_cabang,
            'footer' => $title
        ])
        ->setPaper('a4', 'landscape');
        // return $pdf->download();
        return $pdf->stream();
    }

    public function laporanPendapatanGamis(Request $request)
    {
        $title = "Laporan Pendapatan Gamis";
        $cabang = Cabang::withTrashed()->get();
        $nama_cabang = null;

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m');
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m');

        $transaksi = MonitoringGamis::query()
            ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
            ->join('users as u', 'u.id', '=', 'dg.user_id')
            ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
            ->where('bulan', '>=', Carbon::parse($tanggalAwal)->format('m'))
            ->where('tahun', '>=', Carbon::parse($tanggalAwal)->format('Y'))
            ->where('bulan', '<=', Carbon::parse($tanggalAkhir)->format('m'))
            ->where('tahun', '<=', Carbon::parse($tanggalAkhir)->format('Y'))
            ->select('monitoring_gamis.*', 'dg.nama as  nama_gamis', 'c.nama as nama_cabang', 'c.id as cabang_id')
            ->orderBy('c.id', 'asc')->orderBy('monitoring_gamis.detail_gamis_id', 'asc')->orderBy('monitoring_gamis.bulan', 'asc')->orderBy('monitoring_gamis.tahun', 'asc')->get();

        if ($request->cabang_id) {
            $transaksi = $transaksi->where('cabang_id', $request->cabang_id);
            $nama_cabang = Cabang::where('id', $request->cabang_id)->first();
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry') {
            $cabangId = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = $transaksi->where('cabang_id', $cabangId->id);
            $nama_cabang = $cabangId;
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry' && $request->cabang_id) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        return view('dashboard.laporan.pendapatan-gamis', compact('title', 'transaksi', 'cabang', 'nama_cabang'));
    }

    public function pdfLaporanPendapatanGamis(Request $request)
    {
        $title = "Laporan Pendapatan Gamis";
        $nama_cabang = null;

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m');
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m');

        $transaksi = MonitoringGamis::query()
            ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
            ->join('users as u', 'u.id', '=', 'dg.user_id')
            ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
            ->where('bulan', '>=', Carbon::parse($tanggalAwal)->format('m'))
            ->where('tahun', '>=', Carbon::parse($tanggalAwal)->format('Y'))
            ->where('bulan', '<=', Carbon::parse($tanggalAkhir)->format('m'))
            ->where('tahun', '<=', Carbon::parse($tanggalAkhir)->format('Y'))
            ->select('monitoring_gamis.*', 'dg.nama as  nama_gamis', 'c.nama as nama_cabang', 'c.id as cabang_id')
            ->orderBy('c.id', 'asc')->orderBy('monitoring_gamis.detail_gamis_id', 'asc')->orderBy('monitoring_gamis.bulan', 'asc')->orderBy('monitoring_gamis.tahun', 'asc')->get();

        if ($request->cabang_id) {
            $transaksi = $transaksi->where('cabang_id', $request->cabang_id);
            $nama_cabang = Cabang::withTrashed()->where('id', $request->cabang_id)->first();
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry') {
            $cabangId = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = $transaksi->where('cabang_id', $cabangId->id);
            $nama_cabang = $cabangId;
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry' && $request->cabang_id) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $view = view()->share($title, $transaksi);
        $pdf = Pdf::loadView('dashboard.laporan.pdf.pendapatan-gamis', [
            'judul' => $title,
            'judulTabel' => $title,
            'transaksi' => $transaksi,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'nama_cabang' => $nama_cabang,
            'footer' => $title
        ])
        ->setPaper('a4', 'landscape');
        // return $pdf->download();
        return $pdf->stream();
    }

    public function laporanPelanggan(Request $request)
    {
        $title = "Laporan Pelanggan";
        $cabang = Cabang::withTrashed()->get();
        $nama_cabang = null;

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m');
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m');

        $transaksi = Transaksi::query()
            ->with(['cabang' => function($query) {
                $query->withTrashed();
            }])
            ->join('cabang as c', 'c.id', '=', 'transaksi.cabang_id')
            ->join('pelanggan as p', 'p.id', '=', 'transaksi.pelanggan_id')
            ->select('transaksi.pelanggan_id', 'p.nama as nama_pelanggan', DB::raw("COUNT(transaksi.id) as total_transaksi"), DB::raw("SUM(transaksi.total_bayar_akhir) as total_pengeluaran"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"), 'c.id as cabang_id', 'c.nama as nama_cabang')
            ->where(DB::raw("MONTH(transaksi.waktu)"), '>=', Carbon::parse($tanggalAwal)->format('m'))
            ->where(DB::raw("YEAR(transaksi.waktu)"), '>=', Carbon::parse($tanggalAwal)->format('Y'))
            ->where(DB::raw("MONTH(transaksi.waktu)"), '<=', Carbon::parse($tanggalAkhir)->format('m'))
            ->where(DB::raw("YEAR(transaksi.waktu)"), '<=', Carbon::parse($tanggalAkhir)->format('Y'))
            ->where('transaksi.status', 'Selesai')
            ->groupBy('transaksi.pelanggan_id', 'p.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"), 'c.id', 'c.nama')
            ->orderBy('transaksi.waktu', 'asc')
            ->get();

        if ($request->cabang_id) {
            $transaksi = $transaksi->where('cabang_id', $request->cabang_id);
            $nama_cabang = $cabang->where('id', $request->cabang_id)->first();
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry') {
            $cabangId = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = $transaksi->where('cabang_id', $cabangId->id);
            $nama_cabang = $cabangId;
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry' && $request->cabang_id) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        return view('dashboard.laporan.pelanggan', compact('title', 'transaksi', 'cabang', 'nama_cabang'));
    }

    public function pdfLaporanPelanggan(Request $request)
    {
        $title = "Laporan Pelanggan";
        $cabang = Cabang::withTrashed()->get();
        $nama_cabang = null;

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m');
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m');

        $transaksi = Transaksi::query()
            ->with(['cabang' => function($query) {
                $query->withTrashed();
            }])
            ->join('cabang as c', 'c.id', '=', 'transaksi.cabang_id')
            ->join('pelanggan as p', 'p.id', '=', 'transaksi.pelanggan_id')
            ->select('transaksi.pelanggan_id', 'p.nama as nama_pelanggan', DB::raw("COUNT(transaksi.id) as total_transaksi"), DB::raw("SUM(transaksi.total_bayar_akhir) as total_pengeluaran"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"), 'c.id as cabang_id', 'c.nama as nama_cabang')
            ->where(DB::raw("MONTH(transaksi.waktu)"), '>=', Carbon::parse($tanggalAwal)->format('m'))
            ->where(DB::raw("YEAR(transaksi.waktu)"), '>=', Carbon::parse($tanggalAwal)->format('Y'))
            ->where(DB::raw("MONTH(transaksi.waktu)"), '<=', Carbon::parse($tanggalAkhir)->format('m'))
            ->where(DB::raw("YEAR(transaksi.waktu)"), '<=', Carbon::parse($tanggalAkhir)->format('Y'))
            ->where('transaksi.status', 'Selesai')
            ->groupBy('transaksi.pelanggan_id', 'p.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"), 'c.id', 'c.nama')
            ->orderBy('transaksi.waktu', 'asc')
            ->get();

        if ($request->cabang_id) {
            $transaksi = $transaksi->where('cabang_id', $request->cabang_id);
            $nama_cabang = $cabang->where('id', $request->cabang_id)->first();
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry') {
            $cabangId = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = $transaksi->where('cabang_id', $cabangId->id);
            $nama_cabang = $cabangId;
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry' && $request->cabang_id) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $view = view()->share($title, $transaksi);
        $pdf = Pdf::loadView('dashboard.laporan.pdf.pelanggan', [
            'judul' => $title,
            'judulTabel' => $title,
            'transaksi' => $transaksi,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'nama_cabang' => $nama_cabang,
            'footer' => $title
        ])
        ->setPaper('a4', 'landscape');
        // return $pdf->download();
        return $pdf->stream();
    }

    public function laporanGamis(Request $request)
    {
        $title = "Laporan Gamis";
        $cabang = Cabang::withTrashed()->get();
        $nama_cabang = null;

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m');
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m');

        $transaksi = Transaksi::query()
            ->with(['cabang' => function($query) {
                $query->withTrashed();
            }])
            ->join('cabang as c', 'c.id', '=', 'transaksi.cabang_id')
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->select('transaksi.gamis_id', 'dg.nama as nama_gamis', DB::raw("COUNT(transaksi.id) as total_transaksi"), DB::raw("SUM(transaksi.total_bayar_akhir) as total_pengeluaran"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"), 'c.id as cabang_id', 'c.nama as nama_cabang')
            ->where(DB::raw("MONTH(transaksi.waktu)"), '>=', Carbon::parse($tanggalAwal)->format('m'))
            ->where(DB::raw("YEAR(transaksi.waktu)"), '>=', Carbon::parse($tanggalAwal)->format('Y'))
            ->where(DB::raw("MONTH(transaksi.waktu)"), '<=', Carbon::parse($tanggalAkhir)->format('m'))
            ->where(DB::raw("YEAR(transaksi.waktu)"), '<=', Carbon::parse($tanggalAkhir)->format('Y'))
            ->where('transaksi.status', 'Selesai')
            ->groupBy('transaksi.gamis_id', 'dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"), 'c.id', 'c.nama')
            ->orderBy('transaksi.waktu', 'asc')
            ->get();

        if ($request->cabang_id) {
            $transaksi = $transaksi->where('cabang_id', $request->cabang_id);
            $nama_cabang = $cabang->where('id', $request->cabang_id)->first();
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry') {
            $cabangId = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = $transaksi->where('cabang_id', $cabangId->id);
            $nama_cabang = $cabangId;
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry' && $request->cabang_id) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        return view('dashboard.laporan.gamis', compact('title', 'transaksi', 'cabang', 'nama_cabang'));
    }

    public function pdfLaporanGamis(Request $request)
    {
        $title = "Laporan Gamis";
        $cabang = Cabang::withTrashed()->get();
        $nama_cabang = null;

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m');
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m');

        $transaksi = Transaksi::query()
            ->with(['cabang' => function($query) {
                $query->withTrashed();
            }])
            ->join('cabang as c', 'c.id', '=', 'transaksi.cabang_id')
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->select('transaksi.gamis_id', 'dg.nama as nama_gamis', DB::raw("COUNT(transaksi.id) as total_transaksi"), DB::raw("SUM(transaksi.total_bayar_akhir) as total_pengeluaran"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"), 'c.id as cabang_id', 'c.nama as nama_cabang')
            ->where(DB::raw("MONTH(transaksi.waktu)"), '>=', Carbon::parse($tanggalAwal)->format('m'))
            ->where(DB::raw("YEAR(transaksi.waktu)"), '>=', Carbon::parse($tanggalAwal)->format('Y'))
            ->where(DB::raw("MONTH(transaksi.waktu)"), '<=', Carbon::parse($tanggalAkhir)->format('m'))
            ->where(DB::raw("YEAR(transaksi.waktu)"), '<=', Carbon::parse($tanggalAkhir)->format('Y'))
            ->where('transaksi.status', 'Selesai')
            ->groupBy('transaksi.gamis_id', 'dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"), 'c.id', 'c.nama')
            ->orderBy('transaksi.waktu', 'asc')
            ->get();

        if ($request->cabang_id) {
            $transaksi = $transaksi->where('cabang_id', $request->cabang_id);
            $nama_cabang = $cabang->where('id', $request->cabang_id)->first();
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry') {
            $cabangId = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = $transaksi->where('cabang_id', $cabangId->id);
            $nama_cabang = $cabangId;
        }

        if (auth()->user()->roles[0]->name == 'manajer_laundry' && $request->cabang_id) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $view = view()->share($title, $transaksi);
        $pdf = Pdf::loadView('dashboard.laporan.pdf.gamis', [
            'judul' => $title,
            'judulTabel' => $title,
            'transaksi' => $transaksi,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'nama_cabang' => $nama_cabang,
            'footer' => $title
        ])
        ->setPaper('a4', 'landscape');
        // return $pdf->download();
        return $pdf->stream();
    }
}
