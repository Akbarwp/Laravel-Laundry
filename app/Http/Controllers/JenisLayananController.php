<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use App\Models\HargaJenisLayanan;
use App\Exports\JenisLayananExport;
use App\Imports\JenisLayananImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Layanan\JenisLayananRequest;

class JenisLayananController extends Controller
{
    public function index()
    {
        $title = "Jenis Layanan";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;
        $cabang = Cabang::where('id', $userCabang)->withTrashed()->first();

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $jenisLayanan = JenisLayanan::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $jenisLayananTrash = JenisLayanan::where('cabang_id', $userCabang)->onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.jenis-layanan.index', compact('title', 'jenisLayanan', 'jenisLayananTrash', 'cabang'));
    }

    public function store(JenisLayananRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'manajer_laundry') {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        } else if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang_slug)->first();
            $validated['cabang_id'] = $cabang->id;
        }

        $tambah = JenisLayanan::create($validated);

        if ($userRole == 'manajer_laundry') {
            if ($tambah) {
                return to_route('jenis-layanan')->with('success', 'Jenis Layanan Berhasil Ditambahkan');
            } else {
                return to_route('jenis-layanan')->with('error', 'Jenis Layanan Gagal Ditambahkan');
            }
        } else if ($userRole == 'lurah') {
            if ($tambah) {
                return back()->with('success', 'Jenis Layanan Berhasil Ditambahkan');
            } else {
                return back()->with('error', 'Jenis Layanan Gagal Ditambahkan');
            }
        }
    }

    public function show(Request $request)
    {
        $jenisLayanan = JenisLayanan::withTrashed()->findOrFail($request->id);
        return $jenisLayanan;
    }

    public function edit(Request $request)
    {
        $jenisLayanan = JenisLayanan::findOrFail($request->id);
        return $jenisLayanan;
    }

    public function update(JenisLayananRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;
        $perbarui = JenisLayanan::where('id', $request->id)->update($validated);

        if ($userRole == 'manajer_laundry') {
            if ($perbarui) {
                return to_route('jenis-layanan')->with('success', 'Jenis Layanan Berhasil Diperbarui');
            } else {
                return to_route('jenis-layanan')->with('error', 'Jenis Layanan Gagal Diperbarui');
            }
        } else if ($userRole == 'lurah') {
            if ($perbarui) {
                return back()->with('success', 'Jenis Layanan Berhasil Diperbarui');
            } else {
                return back()->with('error', 'Jenis Layanan Gagal Diperbarui');
            }
        }
    }

    public function delete(Request $request)
    {
        $hapus = JenisLayanan::where('id', $request->id)->delete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Layanan Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = JenisLayanan::where('id', $request->id)->restore();
        $cekJenisPakaian = HargaJenisLayanan::query()
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $request->cabang_id)
            ->where('harga_jenis_layanan.jenis_layanan_id', $request->id)
            ->where('jp.deleted_at', null)
            ->select('harga_jenis_layanan.*', 'jp.id as id_pakaian')
            ->onlyTrashed()->get();

        foreach ($cekJenisPakaian as $item) {
            HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $request->id)->where('jenis_pakaian_id', $item->id_pakaian)->restore();
        }

        if ($pulih) {
            abort(200, 'Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Layanan Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = JenisLayanan::where('id', $request->id)->forceDelete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Layanan Gagal Dihapus');
        }
    }

    public function import(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;
        try {
            Excel::import(new JenisLayananImport, $request->file('impor'));
            if ($userRole == 'lurah') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('success', 'Jenis Layanan Berhasil Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('jenis-layanan')->with('success', 'Jenis Layanan Berhasil Ditambahkan');
            }
        } catch(\Exception $ex) {
            Log::info($ex);
            if ($userRole == 'lurah') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('error', 'Jenis Layanan Gagal Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('jenis-layanan')->with('error', 'Jenis Layanan Gagal Ditambahkan');
            }
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new JenisLayananExport($request->cabang), 'Data Jenis Layanan '.Carbon::now()->format('d-m-Y').'.xlsx');
    }
}
