<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\MonitoringGamis;
use App\Models\Transaksi;
use App\Models\UMR;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        } else {
            $monitoring = MonitoringGamis::query()
                ->join('detail_gamis as dg', 'dg.id', '=', 'monitoring_gamis.detail_gamis_id')
                ->join('users as u', 'u.id', '=', 'dg.user_id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->where('u.cabang_id', auth()->user()->cabang_id)
                ->select('monitoring_gamis.*', 'dg.nama as  nama_gamis', 'c.nama as nama_cabang')
                ->orderBy('c.id', 'asc')->orderBy('monitoring_gamis.detail_gamis_id', 'asc')->orderBy('monitoring_gamis.bulan', 'asc')->orderBy('monitoring_gamis.tahun', 'asc')->get();
        }

        return view('dashboard.monitoring.index', compact('title', 'monitoring', 'umr'));
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
                    if ($monitoring->upah_gamis >= $umr->upah) {
                        MonitoringGamis::create([
                            'upah' => $monitoring->upah_gamis,
                            'status' => "Lulus",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    } else {
                        MonitoringGamis::create([
                            'upah' => $monitoring->upah_gamis,
                            'status' => "Gamis",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    }
                } else {
                    MonitoringGamis::create([
                        'upah' => 0,
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
                    if ($monitoring->upah_gamis >= $umr->upah) {
                        MonitoringGamis::create([
                            'upah' => $monitoring->upah_gamis,
                            'status' => "Lulus",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    } else {
                        MonitoringGamis::create([
                            'upah' => $monitoring->upah_gamis,
                            'status' => "Gamis",
                            'bulan' => $monitoring->bulan,
                            'tahun' => $monitoring->tahun,
                            'detail_gamis_id' => $item->id,
                        ]);
                    }
                } else {
                    MonitoringGamis::create([
                        'upah' => 0,
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

                if ($monitoring->first()) {
                    foreach ($monitoring as $itemMonitoring) {
                        if ($itemMonitoring->upah_gamis >= $umr->upah) {
                            MonitoringGamis::create([
                                'upah' => $itemMonitoring->upah_gamis,
                                'status' => "Lulus",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        } else {
                            MonitoringGamis::create([
                                'upah' => $itemMonitoring->upah_gamis,
                                'status' => "Gamis",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        }
                    }
                } else {
                    MonitoringGamis::create([
                        'upah' => 0,
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

            foreach ($gamis as $itemGamis) {
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

                if ($monitoring->first()) {
                    foreach ($monitoring as $itemMonitoring) {
                        if ($itemMonitoring->upah_gamis >= $umr->upah) {
                            MonitoringGamis::create([
                                'upah' => $itemMonitoring->upah_gamis,
                                'status' => "Lulus",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        } else {
                            MonitoringGamis::create([
                                'upah' => $itemMonitoring->upah_gamis,
                                'status' => "Gamis",
                                'bulan' => $itemMonitoring->bulan,
                                'tahun' => $itemMonitoring->tahun,
                                'detail_gamis_id' => $itemGamis->id,
                            ]);
                        }
                    }
                } else {
                    MonitoringGamis::create([
                        'upah' => 0,
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

    public function editStatus(Request $request)
    {
        $monitoring = MonitoringGamis::where('id', $request->monitoring_id)->first(['id', 'status']);
        return $monitoring;
    }

    public function updateStatus(Request $request)
    {
        $monitoring = MonitoringGamis::where('id', $request->id)->update(['status' => $request->status]);
        if ($monitoring) {
            return to_route('monitoring')->with('success', 'Status Berhasil Diperbarui');
        } else {
            return to_route('monitoring')->with('error', 'Status Gagal Diperbarui');
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
            ->select('monitoring_gamis.*', 'dg.nama as nama_gamis', 'g.rw as nomor_rw')
            ->orderBy('monitoring_gamis.tahun', 'asc')
            ->orderBy('monitoring_gamis.bulan', 'asc')
            ->orderBy('monitoring_gamis.detail_gamis_id', 'asc')
            ->get();

        // dd($tes);

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
}
