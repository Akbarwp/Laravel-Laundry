<?php

namespace App\Http\Controllers;

use App\Enums\JenisSatuanLayanan;
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
    public function index()
    {
        $title = "Monitoring Gamis";
        $userRole = auth()->user()->roles[0]->name;
        $umr = UMR::where('is_used', true)->first();

        if ($userRole == 'lurah') {
            $monitoring = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('monitoring_gamis.*', 'dg.nama as  nama_gamis', 'c.nama as nama_cabang', 'c.deleted_at as cabang_deleted_at')
                ->orderBy('c.id', 'asc')->orderBy('monitoring_gamis.detail_gamis_id', 'asc')->orderBy('monitoring_gamis.bulan', 'asc')->orderBy('monitoring_gamis.tahun', 'asc')->get();
            $gamis = DetailGamis::query()
                ->join('users as u', 'u.id', '=', 'detail_gamis.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('detail_gamis.*', 'c.nama as nama_cabang', 'c.deleted_at as cabang_deleted_at')
                ->get();

        } else {
            $monitoring = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->where('u.cabang_id', auth()->user()->cabang_id)
                ->select('monitoring_gamis.*', 'dg.nama as  nama_gamis', 'c.nama as nama_cabang')
                ->orderBy('c.id', 'asc')->orderBy('monitoring_gamis.detail_gamis_id', 'asc')->orderBy('monitoring_gamis.bulan', 'asc')->orderBy('monitoring_gamis.tahun', 'asc')->get();
            $gamis = DetailGamis::query()
                ->join('users as u', 'u.id', '=', 'detail_gamis.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->where('u.cabang_id', auth()->user()->cabang_id)
                ->select('detail_gamis.*', 'c.nama as nama_cabang', 'c.deleted_at as cabang_deleted_at')
                ->get();
        }

        return view('dashboard.monitoring.index', compact('title', 'monitoring', 'umr', 'gamis'));
    }

    public function perbaruiDataMonitoring()
    {
        $userRole = auth()->user()->roles[0]->name;
        $umr = UMR::where('is_used', true)->first();
        $jenisSatuanLayanan = JenisSatuanLayanan::cases();

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
                foreach ($jenisSatuanLayanan as $satuan) {
                    if ($satuan->value == "Kg") {
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
                            ->where('hjl.jenis_satuan', $satuan->value)
                            ->where(DB::raw("MONTH(transaksi.waktu)"), Carbon::now()->format('m'))
                            ->where(DB::raw("YEAR(transaksi.waktu)"), Carbon::now()->format('Y'))
                            ->where('transaksi.status', 'Selesai')
                            ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                            ->orderBy('transaksi.waktu', 'asc')
                            ->first();

                    } elseif ($satuan->value == "Perjalanan") {
                        $monitoring = Transaksi::query()
                            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                            ->select('dg.nama as  nama_gamis', DB::raw("SUM(dt.total_perjalanan * hjl.harga) as upah_gamis"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"))
                            ->where('transaksi.gamis_id', $item->id)
                            ->where('jl.for_gamis', true)
                            ->where('hjl.jenis_satuan', $satuan->value)
                            ->where(DB::raw("MONTH(transaksi.waktu)"), Carbon::now()->format('m'))
                            ->where(DB::raw("YEAR(transaksi.waktu)"), Carbon::now()->format('Y'))
                            ->where('transaksi.status', 'Selesai')
                            ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                            ->orderBy('transaksi.waktu', 'asc')
                            ->first();
                    }
                    if ($monitoring) {
                        $upahGamis += $monitoring->upah_gamis;
                    }
                }

                if ($monitoring) {
                    if ($upahGamis >= $umr->upah) {
                        MonitoringGamis::create([
                            'upah' => $upahGamis + $item->pemasukkan,
                            'status' => "Lulus",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    } else {
                        MonitoringGamis::create([
                            'upah' => $upahGamis + $item->pemasukkan,
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
                foreach ($jenisSatuanLayanan as $satuan) {
                    if ($satuan->value == "Kg") {
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
                            ->where('hjl.jenis_satuan', $satuan->value)
                            ->where(DB::raw("MONTH(transaksi.waktu)"), Carbon::now()->format('m'))
                            ->where(DB::raw("YEAR(transaksi.waktu)"), Carbon::now()->format('Y'))
                            ->where('transaksi.status', 'Selesai')
                            ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                            ->orderBy('transaksi.waktu', 'asc')
                            ->first();

                    } elseif ($satuan->value == "Perjalanan") {
                        $monitoring = Transaksi::query()
                            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                            ->select('dg.nama as  nama_gamis', DB::raw("SUM(dt.total_perjalanan * hjl.harga) as upah_gamis"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"))
                            ->where('transaksi.gamis_id', $item->id)
                            ->where('jl.for_gamis', true)
                            ->where('hjl.jenis_satuan', $satuan->value)
                            ->where(DB::raw("MONTH(transaksi.waktu)"), Carbon::now()->format('m'))
                            ->where(DB::raw("YEAR(transaksi.waktu)"), Carbon::now()->format('Y'))
                            ->where('transaksi.status', 'Selesai')
                            ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                            ->orderBy('transaksi.waktu', 'asc')
                            ->first();
                    }
                    if ($monitoring) {
                        $upahGamis += $monitoring->upah_gamis;
                    }
                }

                if ($monitoring) {
                    if ($upahGamis >= $umr->upah) {
                        MonitoringGamis::create([
                            'upah' => $upahGamis + $item->pemasukkan,
                            'status' => "Lulus",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    } else {
                        MonitoringGamis::create([
                            'upah' => $upahGamis + $item->pemasukkan,
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
        $jenisSatuanLayanan = JenisSatuanLayanan::cases();

        if ($userRole == 'lurah') {
            $gamis = User::query()
                ->withTrashed()
                ->join('detail_gamis as dg', 'dg.user_id', '=', 'users.id')
                ->select('dg.*')
                ->get();

            MonitoringGamis::truncate();

            foreach ($gamis as $itemGamis) {
                $upahGamis = [];
                foreach ($jenisSatuanLayanan as $satuan) {
                    if ($satuan->value == "Kg") {
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
                            ->where('hjl.jenis_satuan', $satuan->value)
                            ->where('transaksi.status', 'Selesai')
                            ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                            ->orderBy('transaksi.waktu', 'asc')
                            ->get();

                    } elseif ($satuan->value == "Perjalanan") {
                        $monitoring = Transaksi::query()
                            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                            ->select('dg.nama as  nama_gamis', DB::raw("SUM(dt.total_perjalanan * hjl.harga) as upah_gamis"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"))
                            ->where('transaksi.gamis_id', $itemGamis->id)
                            ->where('jl.for_gamis', true)
                            ->where('hjl.jenis_satuan', $satuan->value)
                            ->where('transaksi.status', 'Selesai')
                            ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                            ->orderBy('transaksi.waktu', 'asc')
                            ->get();
                    }
                    foreach ($monitoring as $data) {
                        $bulan = $data->bulan;
                        if (!isset($upahGamis[$bulan])) {
                            $upahGamis[$bulan] = 0;
                        }
                        $upahGamis[$data->bulan] += $data->upah_gamis;
                    }
                }

                if ($monitoring->first()) {
                    foreach ($monitoring as $itemMonitoring) {
                        if ($upahGamis[$itemMonitoring->bulan] >= $umr->upah) {
                            MonitoringGamis::create([
                                'upah' => $upahGamis[$itemMonitoring->bulan] + $itemGamis->pemasukkan,
                                'status' => "Lulus",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        } else {
                            MonitoringGamis::create([
                                'upah' => $upahGamis[$itemMonitoring->bulan] + $itemGamis->pemasukkan,
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
                foreach ($jenisSatuanLayanan as $satuan) {
                    if ($satuan->value == "Kg") {
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
                            ->where('hjl.jenis_satuan', $satuan->value)
                            ->where('transaksi.status', 'Selesai')
                            ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                            ->orderBy('transaksi.waktu', 'asc')
                            ->get();

                    } elseif ($satuan->value == "Perjalanan") {
                        $monitoring = Transaksi::query()
                            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                            ->select('dg.nama as  nama_gamis', DB::raw("SUM(dt.total_perjalanan * hjl.harga) as upah_gamis"), DB::raw("MONTH(transaksi.waktu) as bulan"), DB::raw("YEAR(transaksi.waktu) as tahun"))
                            ->where('transaksi.gamis_id', $itemGamis->id)
                            ->where('jl.for_gamis', true)
                            ->where('hjl.jenis_satuan', $satuan->value)
                            ->where('transaksi.status', 'Selesai')
                            ->groupBy('dg.nama', DB::raw("MONTH(transaksi.waktu)"), DB::raw("YEAR(transaksi.waktu)"))
                            ->orderBy('transaksi.waktu', 'asc')
                            ->get();
                    }
                    foreach ($monitoring as $data) {
                        $bulan = $data->bulan;
                        if (!isset($upahGamis[$bulan])) {
                            $upahGamis[$bulan] = 0;
                        }
                        $upahGamis[$data->bulan] += $data->upah_gamis;
                    }
                }

                if ($monitoring->first()) {
                    foreach ($monitoring as $itemMonitoring) {
                        if ($upahGamis[$itemMonitoring->bulan] >= $umr->upah) {
                            MonitoringGamis::create([
                                'upah' => $upahGamis[$itemMonitoring->bulan] + $itemGamis->pemasukkan,
                                'status' => "Lulus",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        } else {
                            MonitoringGamis::create([
                                'upah' => $upahGamis[$itemMonitoring->bulan] + $itemGamis->pemasukkan,
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
        $tahun = $request->tahun ? $request->tahun : Carbon::now()->format('Y');

        $monitoring = MonitoringGamis::query()
            ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
            ->join('users as u', 'u.id', '=', 'dg.user_id')
            ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
            ->where('monitoring_gamis.detail_gamis_id', $gamis)
            ->where('monitoring_gamis.tahun', $tahun)
            ->select('monitoring_gamis.*', 'dg.nama as  nama_gamis', 'c.nama as nama_cabang', 'c.deleted_at as cabang_deleted_at', 'c.id as cabang_id')
            ->orderBy('c.id', 'asc')->orderBy('monitoring_gamis.detail_gamis_id', 'asc')->orderBy('monitoring_gamis.bulan', 'asc')->orderBy('monitoring_gamis.tahun', 'asc')->get()->keyBy('bulan');

        $riwayatMonitoringGamis = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $riwayatMonitoringGamis[$bulan] = [
                'bulan' => $bulan,
                'hasil' => isset($monitoring[$bulan]) ? $monitoring[$bulan]->upah : 0,
            ];
        }

        return view('dashboard.monitoring.riwayat', compact('title', 'umr', 'monitoring', 'riwayatMonitoringGamis', 'gamis'));
    }
}
