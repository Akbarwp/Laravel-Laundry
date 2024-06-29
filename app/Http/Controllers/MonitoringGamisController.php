<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\DetailGamis;
use App\Models\MonitoringGamis;
use App\Models\Transaksi;
use App\Models\UMR;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MonitoringGamisController extends Controller
{
    public function index(Request $request)
    {
        $title = "Monitoring Gamis";
        $userRole = auth()->user()->roles[0]->name;
        $umr = UMR::where('is_used', true)->first();
        $tahun = $request->tahun ? $request->tahun : null;
        $cabangId = $request->cabang_id ? $request->cabang_id : null;
        $cabang = Cabang::withTrashed()->get();

        if ($userRole == 'lurah') {
            $gamis = DetailGamis::query()
                ->join('users as u', 'u.id', '=', 'detail_gamis.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('detail_gamis.*', 'c.nama as nama_cabang', 'c.deleted_at as cabang_deleted_at')
                ->get();

            $monitoring = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select(
                    'monitoring_gamis.*',
                    'dg.nama as  nama_gamis',
                    'c.nama as nama_cabang',
                    'c.deleted_at as cabang_deleted_at'
                )
                ->when($cabangId, function ($query, $cabangId) {
                    return $query->where('c.id', $cabangId);
                })
                ->when($tahun, function ($query, $tahun) {
                    return $query->where('monitoring_gamis.tahun', $tahun);
                })
                ->orderBy('c.id', 'asc')
                ->orderBy('monitoring_gamis.tahun', 'asc')
                ->orderBy('monitoring_gamis.bulan', 'asc')
                ->orderBy('monitoring_gamis.detail_gamis_id', 'asc')
                ->get();

            $pendapatanGamis = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select(
                    'monitoring_gamis.bulan',
                    DB::raw("SUM(monitoring_gamis.upah) as pendapatan_gamis")
                )
                ->when($cabangId, function ($query, $cabangId) {
                    return $query->where('c.id', $cabangId);
                })
                ->when($tahun, function ($query, $tahun) {
                    return $query->where('monitoring_gamis.tahun', $tahun);
                })
                ->groupBy(
                    'monitoring_gamis.bulan'
                )
                ->orderBy('c.id', 'asc')
                ->orderBy('monitoring_gamis.bulan', 'asc')
                ->get()
                ->keyBy('bulan');

            $hasilPendapatanGamis = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $hasilPendapatanGamis[$bulan] = [
                    'bulan' => $bulan,
                    'hasil' => isset($pendapatanGamis[$bulan]) ? $pendapatanGamis[$bulan]->pendapatan_gamis : 0,
                ];
            }

            $jumlahGamis = DetailGamis::query()
                ->join('users as u', 'u.id', '=', 'detail_gamis.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select(
                    DB::raw('MONTH(detail_gamis.created_at) as bulan'),
                    DB::raw("COUNT(detail_gamis.id) as jumlah_gamis")
                )
                ->when($cabangId, function ($query, $cabangId) {
                    return $query->where('c.id', $cabangId);
                })
                ->when($tahun, function ($query, $tahun) {
                    return $query->where(DB::raw('YEAR(detail_gamis.created_at)'), $tahun);
                })
                ->groupBy(
                    DB::raw('MONTH(detail_gamis.created_at)')
                )
                ->get()
                ->keyBy('bulan');

            $hasilJumlahGamis = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $hasilJumlahGamis[$bulan] = [
                    'bulan' => $bulan,
                    'hasil' => isset($jumlahGamis[$bulan]) ? $jumlahGamis[$bulan]->jumlah_gamis : 0,
                ];
            }

            $statusGamis = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select(
                    'monitoring_gamis.status',
                    'monitoring_gamis.bulan',
                    DB::raw("COUNT(monitoring_gamis.detail_gamis_id) as jumlah_gamis")
                )
                ->when($cabangId, function ($query, $cabangId) {
                    return $query->where('c.id', $cabangId);
                })
                ->when($tahun, function ($query, $tahun) {
                    return $query->where('monitoring_gamis.tahun', $tahun);
                })
                ->groupBy(
                    'monitoring_gamis.bulan',
                    'monitoring_gamis.status'
                )
                ->orderBy('c.id', 'asc')
                ->orderBy('monitoring_gamis.bulan', 'asc')
                ->get()
                ->keyBy('bulan');

                $hasilStatusGamis = [];
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    $hasilStatusGamis['Lulus'][$bulan] = [
                        'bulan' => $bulan,
                        'hasil' => isset($statusGamis[$bulan]) ? ($statusGamis[$bulan]->status == 'Lulus' ? $statusGamis[$bulan]->jumlah_gamis : 0) : 0,
                    ];
                    $hasilStatusGamis['Gamis'][$bulan] = [
                        'bulan' => $bulan,
                        'hasil' => isset($statusGamis[$bulan]) ? ($statusGamis[$bulan]->status == 'Gamis' ? $statusGamis[$bulan]->jumlah_gamis : 0) : 0,
                    ];
                }

        } else {
            $gamis = DetailGamis::query()
                ->join('users as u', 'u.id', '=', 'detail_gamis.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('detail_gamis.*', 'c.nama as nama_cabang', 'c.deleted_at as cabang_deleted_at')
                ->where('u.cabang_id', auth()->user()->cabang_id)
                ->get();

            $monitoring = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select(
                    'monitoring_gamis.*',
                    'dg.nama as  nama_gamis',
                    'c.nama as nama_cabang',
                    'c.deleted_at as cabang_deleted_at'
                )
                ->where('u.cabang_id', auth()->user()->cabang_id)
                ->when($cabangId, function ($query, $cabangId) {
                    return $query->where('c.id', $cabangId);
                })
                ->when($tahun, function ($query, $tahun) {
                    return $query->where('monitoring_gamis.tahun', $tahun);
                })
                ->orderBy('c.id', 'asc')
                ->orderBy('monitoring_gamis.tahun', 'asc')
                ->orderBy('monitoring_gamis.bulan', 'asc')
                ->orderBy('monitoring_gamis.detail_gamis_id', 'asc')
                ->get();

            $pendapatanGamis = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select(
                    'monitoring_gamis.bulan',
                    DB::raw("SUM(monitoring_gamis.upah) as pendapatan_gamis")
                )
                ->where('u.cabang_id', auth()->user()->cabang_id)
                ->when($cabangId, function ($query, $cabangId) {
                    return $query->where('c.id', $cabangId);
                })
                ->when($tahun, function ($query, $tahun) {
                    return $query->where('monitoring_gamis.tahun', $tahun);
                })
                ->groupBy(
                    'monitoring_gamis.bulan'
                )
                ->orderBy('c.id', 'asc')
                ->orderBy('monitoring_gamis.bulan', 'asc')
                ->get()
                ->keyBy('bulan');

            $hasilPendapatanGamis = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $hasilPendapatanGamis[$bulan] = [
                    'bulan' => $bulan,
                    'hasil' => isset($pendapatanGamis[$bulan]) ? $pendapatanGamis[$bulan]->pendapatan_gamis : 0,
                ];
            }

            $jumlahGamis = DetailGamis::query()
                ->join('users as u', 'u.id', '=', 'detail_gamis.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select(
                    DB::raw('MONTH(detail_gamis.created_at) as bulan'),
                    DB::raw("COUNT(detail_gamis.id) as jumlah_gamis")
                )
                ->where('u.cabang_id', auth()->user()->cabang_id)
                ->when($cabangId, function ($query, $cabangId) {
                    return $query->where('c.id', $cabangId);
                })
                ->when($tahun, function ($query, $tahun) {
                    return $query->where(DB::raw('YEAR(detail_gamis.created_at)'), $tahun);
                })
                ->groupBy(
                    DB::raw('MONTH(detail_gamis.created_at)')
                )
                ->get()
                ->keyBy('bulan');

            $hasilJumlahGamis = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $hasilJumlahGamis[$bulan] = [
                    'bulan' => $bulan,
                    'hasil' => isset($jumlahGamis[$bulan]) ? $jumlahGamis[$bulan]->jumlah_gamis : 0,
                ];
            }

            $statusGamis = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select(
                    'monitoring_gamis.status',
                    'monitoring_gamis.bulan',
                    DB::raw("COUNT(monitoring_gamis.detail_gamis_id) as jumlah_gamis")
                )
                ->where('u.cabang_id', auth()->user()->cabang_id)
                ->when($cabangId, function ($query, $cabangId) {
                    return $query->where('c.id', $cabangId);
                })
                ->when($tahun, function ($query, $tahun) {
                    return $query->where('monitoring_gamis.tahun', $tahun);
                })
                ->groupBy(
                    'monitoring_gamis.bulan',
                    'monitoring_gamis.status'
                )
                ->orderBy('c.id', 'asc')
                ->orderBy('monitoring_gamis.bulan', 'asc')
                ->get()
                ->keyBy('bulan');

                $hasilStatusGamis = [];
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    $hasilStatusGamis['Lulus'][$bulan] = [
                        'bulan' => $bulan,
                        'hasil' => isset($statusGamis[$bulan]) ? ($statusGamis[$bulan]->status == 'Lulus' ? $statusGamis[$bulan]->jumlah_gamis : 0) : 0,
                    ];
                    $hasilStatusGamis['Gamis'][$bulan] = [
                        'bulan' => $bulan,
                        'hasil' => isset($statusGamis[$bulan]) ? ($statusGamis[$bulan]->status == 'Gamis' ? $statusGamis[$bulan]->jumlah_gamis : 0) : 0,
                    ];
                }
        }

        return view('dashboard.monitoring.index', compact('title', 'monitoring', 'umr', 'gamis', 'cabang', 'tahun', 'hasilPendapatanGamis', 'hasilJumlahGamis', 'hasilStatusGamis'));
    }

    public function perbaruiDataMonitoring()
    {
        $userRole = auth()->user()->roles[0]->name;
        $umr = UMR::where('is_used', true)->first();

        if ($userRole == 'lurah') {
            $gamis = User::query()
                ->withTrashed()
                ->join('detail_gamis as dg', 'dg.user_id', '=', 'users.id')
                ->select('dg.*')
                ->get();

            MonitoringGamis::query()
                ->where('bulan', Carbon::now()->format('m'))
                ->where('tahun', Carbon::now()->format('Y'))
                ->delete();

            foreach ($gamis as $item) {
                $upahGamis = 0;
                $upahTambahan = Transaksi::query()
                    ->select('total_biaya_layanan_tambahan')
                    ->where('gamis_id', $item->id)
                    ->where(DB::raw("MONTH(waktu)"), Carbon::now()->format('m'))
                    ->where(DB::raw("YEAR(waktu)"), Carbon::now()->format('Y'))
                    ->where('status', 'Selesai')
                    ->orderBy('waktu', 'asc')
                    ->sum('total_biaya_layanan_tambahan');

                $monitoring = Transaksi::query()
                    ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                    ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                    ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                    ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                    ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                    ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                    ->select(
                        'dg.nama as nama_gamis',
                        DB::raw("SUM(dt.total_pakaian * hjl.harga) as upah_gamis"),
                        DB::raw("MONTH(transaksi.waktu) as bulan"),
                        DB::raw("YEAR(transaksi.waktu) as tahun")
                    )
                    ->where('transaksi.gamis_id', $item->id)
                    ->where('jl.for_gamis', true)
                    ->where(DB::raw("MONTH(transaksi.waktu)"), Carbon::now()->format('m'))
                    ->where(DB::raw("YEAR(transaksi.waktu)"), Carbon::now()->format('Y'))
                    ->where('transaksi.status', 'Selesai')
                    ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                    ->orderBy('transaksi.waktu', 'asc')
                    ->first();
                if ($monitoring) {
                    $upahGamis += $monitoring->upah_gamis;
                }
                $upahAkhir = $upahGamis + $item->pemasukkan + $upahTambahan;

                if ($monitoring) {
                    if ($upahAkhir >= $umr->upah) {
                        MonitoringGamis::create([
                            'upah' => $upahAkhir,
                            'status' => "Lulus",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    } else {
                        MonitoringGamis::create([
                            'upah' => $upahAkhir,
                            'status' => "Gamis",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    }
                } else {
                    MonitoringGamis::create([
                        'upah' => 0 + $item->pemasukkan,
                        'status' => "Gamis",
                        'bulan' => Carbon::now()->format('m'),
                        'tahun' => Carbon::now()->format('Y'),
                        'detail_gamis_id' => $item->id,
                    ]);
                }
            }
        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $gamis = User::query()
                ->withTrashed()
                ->join('detail_gamis as dg', 'dg.user_id', '=', 'users.id')
                ->where('users.cabang_id', $cabang->id)
                ->select('dg.*')
                ->get();

            $cabangGamis = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->where('u.cabang_id', $cabang->id)
                ->where('bulan', Carbon::now()->format('m'))
                ->where('tahun', Carbon::now()->format('Y'))
                ->select('dg.nama as nama_gamis', 'monitoring_gamis.detail_gamis_id as gamis_id')
                ->get();

            foreach ($cabangGamis as $item) {
                MonitoringGamis::query()
                    ->where('detail_gamis_id', $item->gamis_id)
                    ->where('bulan', Carbon::now()->format('m'))
                    ->where('tahun', Carbon::now()->format('Y'))
                    ->delete();
            }

            foreach ($gamis as $item) {
                $upahGamis = 0;
                $upahTambahan = Transaksi::query()
                    ->select('total_biaya_layanan_tambahan')
                    ->where('gamis_id', $item->id)
                    ->where(DB::raw("MONTH(waktu)"), Carbon::now()->format('m'))
                    ->where(DB::raw("YEAR(waktu)"), Carbon::now()->format('Y'))
                    ->where('status', 'Selesai')
                    ->orderBy('waktu', 'asc')
                    ->sum('total_biaya_layanan_tambahan');

                $monitoring = Transaksi::query()
                    ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                    ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                    ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                    ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                    ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                    ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                    ->select('dg.nama as  nama_gamis', DB::raw("SUM(dt.total_pakaian * hjl.harga) as upah_gamis"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"))
                    ->where('transaksi.gamis_id', $item->id)
                    ->where('jl.for_gamis', true)
                    ->where(DB::raw("MONTH(transaksi.waktu)"), Carbon::now()->format('m'))
                    ->where(DB::raw("YEAR(transaksi.waktu)"), Carbon::now()->format('Y'))
                    ->where('transaksi.status', 'Selesai')
                    ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                    ->orderBy('transaksi.waktu', 'asc')
                    ->first();
                if ($monitoring) {
                    $upahGamis += $monitoring->upah_gamis;
                }
                $upahAkhir = $upahGamis + $item->pemasukkan + $upahTambahan;

                if ($monitoring) {
                    if ($upahAkhir >= $umr->upah) {
                        MonitoringGamis::create([
                            'upah' => $upahAkhir,
                            'status' => "Lulus",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    } else {
                        MonitoringGamis::create([
                            'upah' => $upahAkhir,
                            'status' => "Gamis",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    }
                } else {
                    MonitoringGamis::create([
                        'upah' => 0 + $item->pemasukkan,
                        'status' => "Gamis",
                        'bulan' => Carbon::now()->format('m'),
                        'tahun' => Carbon::now()->format('Y'),
                        'detail_gamis_id' => $item->id,
                    ]);
                }
            }

            return to_route('monitoring')->with('success', 'Perbarui Data Berhasil Dilakukan');
        }
    }

    public function resetDataMonitoring()
    {
        $userRole = auth()->user()->roles[0]->name;
        $umr = UMR::where('is_used', true)->first();

        if ($userRole == 'lurah') {
            $gamis = User::query()
                ->withTrashed()
                ->join('detail_gamis as dg', 'dg.user_id', '=', 'users.id')
                ->select('dg.*')
                ->get();

            MonitoringGamis::truncate();

            foreach ($gamis as $itemGamis) {
                $upahGamis = [];

                $upahTambahan = Transaksi::query()
                    ->select(
                        DB::raw("SUM(total_biaya_layanan_tambahan) as total_biaya_layanan_tambahan"),
                        DB::raw("MONTH(waktu) as bulan"),
                        DB::raw("YEAR(waktu) as tahun")
                    )
                    ->where('gamis_id', $itemGamis->id)
                    ->where('status', 'Selesai')
                    ->groupBy(DB::raw("MONTH(waktu)"), DB::raw("YEAR(waktu)"))
                    ->orderBy('waktu', 'asc')
                    ->get();
                foreach ($upahTambahan as $data) {
                    $key = $data->tahun . '-' . $data->bulan;
                    if (!isset($upahGamis[$key])) {
                        $upahGamis[$key] = 0;
                    }
                    $upahGamis[$key] += $data->total_biaya_layanan_tambahan;
                }

                $monitoring = Transaksi::query()
                    ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                    ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                    ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                    ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                    ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                    ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                    ->select('dg.nama as  nama_gamis', DB::raw("SUM(dt.total_pakaian * hjl.harga) as upah_gamis"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"))
                    ->where('transaksi.gamis_id', $itemGamis->id)
                    ->where('jl.for_gamis', true)
                    ->where('transaksi.status', 'Selesai')
                    ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                    ->orderBy('transaksi.waktu', 'asc')
                    ->get();

                foreach ($monitoring as $data) {
                    $key = $data->tahun . '-' . $data->bulan;
                    if (!isset($upahGamis[$key])) {
                        $upahGamis[$key] = 0;
                    }
                    $upahGamis[$key] += $data->upah_gamis;
                }

                if ($monitoring->first()) {
                    foreach ($monitoring as $itemMonitoring) {
                        $key = $itemMonitoring->tahun . '-' . $itemMonitoring->bulan;
                        $totalUpah = $upahGamis[$key] + $itemGamis->pemasukkan;

                        if ($totalUpah >= $umr->upah) {
                            MonitoringGamis::create([
                                'upah' => $totalUpah,
                                'status' => "Lulus",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        } else {
                            MonitoringGamis::create([
                                'upah' => $totalUpah,
                                'status' => "Gamis",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        }
                    }
                } else {
                    MonitoringGamis::create([
                        'upah' => 0 + $itemGamis->pemasukkan,
                        'status' => "Gamis",
                        'bulan' => Carbon::now()->format('m'),
                        'tahun' => Carbon::now()->format('Y'),
                        'detail_gamis_id' => $itemGamis->id,
                    ]);
                }
            }
        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $gamis = User::query()
                ->withTrashed()
                ->join('detail_gamis as dg', 'dg.user_id', '=', 'users.id')
                ->where('users.cabang_id', $cabang->id)
                ->select('dg.*')
                ->get();

            $cabangGamis = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->where('u.cabang_id', $cabang->id)
                ->select('dg.nama as nama_gamis', 'monitoring_gamis.detail_gamis_id as gamis_id')
                ->get();

            foreach ($cabangGamis as $item) {
                MonitoringGamis::query()
                    ->where('detail_gamis_id', $item->gamis_id)
                    ->delete();
            }

            foreach ($gamis as $itemGamis) {
                $upahGamis = [];

                $upahTambahan = Transaksi::query()
                    ->select(
                        DB::raw("SUM(total_biaya_layanan_tambahan) as total_biaya_layanan_tambahan"),
                        DB::raw("MONTH(waktu) as bulan"),
                        DB::raw("YEAR(waktu) as tahun")
                    )
                    ->where('gamis_id', $itemGamis->id)
                    ->where('status', 'Selesai')
                    ->groupBy(DB::raw("MONTH(waktu)"), DB::raw("YEAR(waktu)"))
                    ->orderBy('waktu', 'asc')
                    ->get();
                foreach ($upahTambahan as $data) {
                    $key = $data->tahun . '-' . $data->bulan;
                    if (!isset($upahGamis[$key])) {
                        $upahGamis[$key] = 0;
                    }
                    $upahGamis[$key] += $data->total_biaya_layanan_tambahan;
                }

                $monitoring = Transaksi::query()
                    ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                    ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                    ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                    ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                    ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                    ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                    ->select('dg.nama as  nama_gamis', DB::raw("SUM(dt.total_pakaian * hjl.harga) as upah_gamis"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"))
                    ->where('transaksi.gamis_id', $itemGamis->id)
                    ->where('jl.for_gamis', true)
                    ->where('transaksi.status', 'Selesai')
                    ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                    ->orderBy('transaksi.waktu', 'asc')
                    ->get();
                foreach ($monitoring as $data) {
                    $key = $data->tahun . '-' . $data->bulan;
                    if (!isset($upahGamis[$key])) {
                        $upahGamis[$key] = 0;
                    }
                    $upahGamis[$key] += $data->upah_gamis;
                }

                if ($monitoring->first()) {
                    foreach ($monitoring as $itemMonitoring) {
                        $key = $itemMonitoring->tahun . '-' . $itemMonitoring->bulan;
                        $totalUpah = $upahGamis[$key] + $itemGamis->pemasukkan;

                        if ($totalUpah >= $umr->upah) {
                            MonitoringGamis::create([
                                'upah' => $totalUpah,
                                'status' => "Lulus",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        } else {
                            MonitoringGamis::create([
                                'upah' => $totalUpah,
                                'status' => "Gamis",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        }
                    }
                } else {
                    MonitoringGamis::create([
                        'upah' => 0 + $itemGamis->pemasukkan,
                        'status' => "Gamis",
                        'bulan' => Carbon::now()->format('m'),
                        'tahun' => Carbon::now()->format('Y'),
                        'detail_gamis_id' => $itemGamis->id,
                    ]);
                }
            }

            return to_route('monitoring')->with('success', 'Perbarui Data Berhasil Dilakukan');
        }
    }

    public function editPemasukkan(Request $request)
    {
        $gamis = DetailGamis::find($request->id, ['id', 'nama_pemasukkan', 'pemasukkan']);
        return $gamis;
    }

    public function updatePemasukkan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemasukkan' => 'required|string|max:255',
            'pemasukkan' => 'required|decimal:0,2',
        ],
        [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
        ]);
        $validated = $validator->validated();

        $perbarui = DetailGamis::where('id', $request->id)->update([
            'nama_pemasukkan' => $validated['nama_pemasukkan'],
            'pemasukkan' => $validated['pemasukkan'],
        ]);

        if ($perbarui) {
            return to_route('monitoring')->with('success', 'Gamis Berhasil Diperbarui');
        } else {
            return to_route('monitoring')->with('error', 'Gamis Gagal Diperbarui');
        }
    }

    public function indexRw(Request $request)
    {
        $title = "Monitoring Gamis";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole != 'rw') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $rw = User::query()
            ->join('rw', 'rw.user_id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->select('rw.nomor_rw as nomor_rw')
            ->first();

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m');
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m');

        $monitoring = MonitoringGamis::query()
            ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
            ->join('gamis as g', 'g.id', '=', 'dg.gamis_id')
            ->join('users as u', 'u.id', '=', 'dg.user_id')
            ->where('g.rw', $rw->nomor_rw)
            ->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->where(function ($subQuery) use ($tanggalAwal) {
                    $subQuery->where('tahun', '>', Carbon::parse($tanggalAwal)->format('Y'))
                        ->orWhere(function ($nestedQuery) use ($tanggalAwal) {
                            $nestedQuery->where('tahun', '=', Carbon::parse($tanggalAwal)->format('Y'))
                                ->where('bulan', '>=', Carbon::parse($tanggalAwal)->format('m'));
                        });
                })->where(function ($subQuery) use ($tanggalAkhir) {
                    $subQuery->where('tahun', '<', Carbon::parse($tanggalAkhir)->format('Y'))
                        ->orWhere(function ($nestedQuery) use ($tanggalAkhir) {
                            $nestedQuery->where('tahun', '=', Carbon::parse($tanggalAkhir)->format('Y'))
                                ->where('bulan', '<=', Carbon::parse($tanggalAkhir)->format('m'));
                        });
                });
            })
            ->select('monitoring_gamis.*', 'dg.nama as nama_gamis', 'dg.pemasukkan as pemasukkan_gamis', 'g.rw as nomor_rw')
            ->orderBy('monitoring_gamis.tahun', 'asc')
            ->orderBy('monitoring_gamis.bulan', 'asc')
            ->orderBy('monitoring_gamis.detail_gamis_id', 'asc')
            ->get();

        return view('dashboard.monitoring.rw', compact('title', 'monitoring', 'rw'));
    }

    public function pdfMonitoringGamisRw(Request $request)
    {
        $title = "Monitoring Gamis";

        $rw = User::query()
            ->join('rw', 'rw.user_id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->select('rw.nomor_rw as nomor_rw')
            ->first();

        $tanggalAwal = $request->tanggalAwal ? $request->tanggalAwal : Carbon::now()->format('Y-') . Carbon::now()->format('m');
        $tanggalAkhir = $request->tanggalAkhir ? $request->tanggalAkhir : Carbon::now()->format('Y-m');

        $monitoring = MonitoringGamis::query()
            ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
            ->join('gamis as g', 'g.id', '=', 'dg.gamis_id')
            ->join('users as u', 'u.id', '=', 'dg.user_id')
            ->where('g.rw', $rw->nomor_rw)
            ->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->where(function ($subQuery) use ($tanggalAwal) {
                    $subQuery->where('tahun', '>', Carbon::parse($tanggalAwal)->format('Y'))
                        ->orWhere(function ($nestedQuery) use ($tanggalAwal) {
                            $nestedQuery->where('tahun', '=', Carbon::parse($tanggalAwal)->format('Y'))
                                ->where('bulan', '>=', Carbon::parse($tanggalAwal)->format('m'));
                        });
                })->where(function ($subQuery) use ($tanggalAkhir) {
                    $subQuery->where('tahun', '<', Carbon::parse($tanggalAkhir)->format('Y'))
                        ->orWhere(function ($nestedQuery) use ($tanggalAkhir) {
                            $nestedQuery->where('tahun', '=', Carbon::parse($tanggalAkhir)->format('Y'))
                                ->where('bulan', '<=', Carbon::parse($tanggalAkhir)->format('m'));
                        });
                });
            })
            ->select('monitoring_gamis.*', 'dg.nama as  nama_gamis', 'g.rw as nomor_rw')
            ->orderBy('monitoring_gamis.tahun', 'asc')
            ->orderBy('monitoring_gamis.bulan', 'asc')
            ->orderBy('monitoring_gamis.detail_gamis_id', 'asc')
            ->get();

        $view = view()->share($title, $monitoring);
        $pdf = Pdf::loadView('dashboard.laporan.pdf.monitoring-gamis-rw', [
            'judul' => $title,
            'judulTabel' => $title,
            'monitoring' => $monitoring,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'rw' => $rw,
            'footer' => $title
        ])
            ->setPaper('a4', 'landscape');
        // return $pdf->download();
        return $pdf->stream();
    }

    public function riwayatPendapatan(Request $request)
    {
        $title = "Riwayat Pendapatan Gamis";
        $umr = UMR::where('is_used', true)->first();
        $gamis = $request->gamis;
        $tahun = $request->tahun ? $request->tahun : null;

        $monitoring = MonitoringGamis::query()
            ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
            ->join('users as u', 'u.id', '=', 'dg.user_id')
            ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
            ->where('monitoring_gamis.detail_gamis_id', $gamis)
            ->when($tahun, function ($query, $tahun) {
                return $query->where('monitoring_gamis.tahun', $tahun);
            })
            ->select('monitoring_gamis.*', 'dg.nama as  nama_gamis', 'c.nama as nama_cabang', 'c.deleted_at as cabang_deleted_at', 'c.id as cabang_id')
            ->orderBy('monitoring_gamis.tahun', 'asc')
            ->orderBy('monitoring_gamis.bulan', 'asc')
            ->get();

        $pendapatanGamis = MonitoringGamis::query()
            ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
            ->join('users as u', 'u.id', '=', 'dg.user_id')
            ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
            ->select(
                'monitoring_gamis.bulan',
                DB::raw("SUM(monitoring_gamis.upah) as pendapatan_gamis")
            )
            ->when($tahun, function ($query, $tahun) {
                return $query->where('monitoring_gamis.tahun', $tahun);
            })
            ->where('monitoring_gamis.detail_gamis_id', $gamis)
            ->groupBy(
                'monitoring_gamis.bulan'
            )
            ->orderBy('c.id', 'asc')
            ->orderBy('monitoring_gamis.bulan', 'asc')
            ->get()
            ->keyBy('bulan');

        $riwayatMonitoringGamis = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $riwayatMonitoringGamis[$bulan] = [
                'bulan' => $bulan,
                'hasil' => isset($pendapatanGamis[$bulan]) ? $pendapatanGamis[$bulan]->pendapatan_gamis : 0,
            ];
        }

        return view('dashboard.monitoring.riwayat', compact('title', 'umr', 'monitoring', 'riwayatMonitoringGamis', 'gamis'));
    }
}
