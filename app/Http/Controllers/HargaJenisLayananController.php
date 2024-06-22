<?php

namespace App\Http\Controllers;

use App\Enums\JenisSatuanLayanan;
use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use Illuminate\Http\Request;
use App\Models\HargaJenisLayanan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HargaJenisLayananExport;
use App\Imports\HargaJenisLayananImport;
use App\Http\Requests\Layanan\HargaJenisLayananRequest;

class HargaJenisLayananController extends Controller
{
    public function index()
    {
        $title = "Harga Jenis Layanan";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;
        $cabang = Cabang::where('id', $userCabang)->withTrashed()->first();
        $jenisSatuanLayanan = JenisSatuanLayanan::cases();

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $jenisLayanan = JenisLayanan::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $jenisPakaian = JenisPakaian::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();

        $hargaJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $userCabang)
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_pakaian')
            ->orderBy('jenis_pakaian_id', 'asc')->orderBy('jenis_layanan_id', 'asc')->get();
        $hargaJenisLayananTrash = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $userCabang)
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_pakaian')
            ->onlyTrashed()->orderBy('harga_jenis_layanan.jenis_pakaian_id', 'asc')->orderBy('harga_jenis_layanan.jenis_layanan_id', 'asc')->get();

        return view('dashboard.harga-jenis-layanan.index', compact('title', 'hargaJenisLayanan', 'hargaJenisLayananTrash', 'jenisLayanan', 'jenisPakaian', 'cabang', 'jenisSatuanLayanan'));
    }

    public function store(HargaJenisLayananRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'manajer_laundry') {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        } else if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang_slug)->first();
            $validated['cabang_id'] = $cabang->id;
        }

        if (HargaJenisLayanan::where('cabang_id', $validated['cabang_id'])->where('jenis_layanan_id', $validated['jenis_layanan_id'])->where('jenis_pakaian_id', $validated['jenis_pakaian_id'])->first()) {
            if ($userRole == 'manajer_laundry') {
                return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Sudah Ada');
            } else if ($userRole == 'lurah') {
                return back()->with('error', 'Harga Jenis Layanan Sudah Ada');
            }
        }

        $tambah = HargaJenisLayanan::create($validated);

        if ($userRole == 'manajer_laundry') {
            if ($tambah) {
                return to_route('harga-jenis-layanan')->with('success', 'Harga Jenis Layanan Berhasil Ditambahkan');
            } else {
                return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Gagal Ditambahkan');
            }
        } else if ($userRole == 'lurah') {
            if ($tambah) {
                return back()->with('success', 'Harga Jenis Layanan Berhasil Ditambahkan');
            } else {
                return back()->with('error', 'Harga Jenis Layanan Gagal Ditambahkan');
            }
        }
    }

    public function show(Request $request)
    {
        $hargaJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_pakaian')
            ->withTrashed()->where('harga_jenis_layanan.id', $request->id)->first();

        return $hargaJenisLayanan;
    }

    public function edit(Request $request)
    {
        $hargaJenisLayanan = HargaJenisLayanan::findOrFail($request->id);
        return $hargaJenisLayanan;
    }

    public function update(HargaJenisLayananRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'manajer_laundry') {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        } else if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang_slug)->first();
            $validated['cabang_id'] = $cabang->id;
        }

        $hargaJenisLayanan = HargaJenisLayanan::where('id', $request->id)->first();
        $cekHargaJenisLayanan = HargaJenisLayanan::where('cabang_id', $validated['cabang_id'])->where('jenis_layanan_id', $validated['jenis_layanan_id'])->where('jenis_pakaian_id', $validated['jenis_pakaian_id'])->first();

        if ($cekHargaJenisLayanan) {
            if ($hargaJenisLayanan->id == $cekHargaJenisLayanan->id) {
                $perbarui = $hargaJenisLayanan->update($validated);
                if ($userRole == 'manajer_laundry') {
                    if ($perbarui) {
                        return to_route('harga-jenis-layanan')->with('success', 'Harga Jenis Layanan Berhasil Diperbarui');
                    } else {
                        return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Gagal Diperbarui');
                    }
                } else if ($userRole == 'lurah') {
                    if ($perbarui) {
                        return back()->with('success', 'Harga Jenis Layanan Berhasil Diperbarui');
                    } else {
                        return back()->with('error', 'Harga Jenis Layanan Gagal Diperbarui');
                    }
                }

            } else {
                if ($userRole == 'manajer_laundry') {
                    return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Sudah Ada');
                } else if ($userRole == 'lurah') {
                    return back()->with('error', 'Harga Jenis Layanan Sudah Ada');
                }
            }
        }

        $perbarui = $hargaJenisLayanan->update($validated);

        if ($userRole == 'manajer_laundry') {
            if ($perbarui) {
                return to_route('harga-jenis-layanan')->with('success', 'Harga Jenis Layanan Berhasil Diperbarui');
            } else {
                return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Gagal Diperbarui');
            }
        } else if ($userRole == 'lurah') {
            if ($perbarui) {
                return back()->with('success', 'Harga Jenis Layanan Berhasil Diperbarui');
            } else {
                return back()->with('error', 'Harga Jenis Layanan Gagal Diperbarui');
            }
        }
    }

    public function delete(Request $request)
    {
        $hapus = HargaJenisLayanan::where('id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = HargaJenisLayanan::where('id', $request->id)->restore();
        if ($pulih) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = HargaJenisLayanan::where('id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }

    public function import(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;
        try {
            Excel::import(new HargaJenisLayananImport, $request->file('impor'));
            if ($userRole == 'lurah') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('success', 'Harga Jenis Layanan Berhasil Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('harga-jenis-layanan')->with('success', 'Harga Jenis Layanan Berhasil Ditambahkan');
            }
        } catch(\Exception $ex) {
            Log::info($ex);
            if ($userRole == 'lurah') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('error', 'Harga Jenis Layanan Gagal Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Gagal Ditambahkan');
            }
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new HargaJenisLayananExport($request->cabang), 'Data Harga Jenis Layanan '.Carbon::now()->format('d-m-Y').'.xlsx');
    }
}
