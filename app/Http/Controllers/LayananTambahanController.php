<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Models\LayananTambahan;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LayananTambahanExport;
use App\Imports\LayananTambahanImport;
use App\Http\Requests\Layanan\LayananTambahanRequest;

class LayananTambahanController extends Controller
{
    public function index()
    {
        $title = "Layanan Tambahan";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;
        $cabang = Cabang::where('id', $userCabang)->withTrashed()->first();

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $layananTambahan = LayananTambahan::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $layananTambahanTrash = LayananTambahan::where('cabang_id', $userCabang)->onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.layanan-tambahan.index', compact('title', 'layananTambahan', 'layananTambahanTrash', 'cabang'));
    }

    public function store(LayananTambahanRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'manajer_laundry') {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        } else if ($userRole == 'pic') {
            $cabang = Cabang::where('slug', $request->cabang_slug)->first();
            $validated['cabang_id'] = $cabang->id;
        }

        $tambah = LayananTambahan::create($validated);

        if ($userRole == 'manajer_laundry') {
            if ($tambah) {
                return to_route('layanan-tambahan')->with('success', 'Layanan Tambahan Berhasil Ditambahkan');
            } else {
                return to_route('layanan-tambahan')->with('error', 'Layanan Tambahan Gagal Ditambahkan');
            }
        } else if ($userRole == 'pic') {
            if ($tambah) {
                return back()->with('success', 'Layanan Tambahan Berhasil Ditambahkan');
            } else {
                return back()->with('error', 'Layanan Tambahan Gagal Ditambahkan');
            }
        }
    }

    public function show(Request $request)
    {
        $layananTambahan = LayananTambahan::withTrashed()->findOrFail($request->id);
        return $layananTambahan;
    }

    public function edit(Request $request)
    {
        $layananTambahan = LayananTambahan::findOrFail($request->id);
        return $layananTambahan;
    }

    public function update(layananTambahanRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;
        $perbarui = LayananTambahan::where('id', $request->id)->update($validated);

        if ($userRole == 'manajer_laundry') {
            if ($perbarui) {
                return to_route('layanan-tambahan')->with('success', 'Layanan Tambahan Berhasil Diperbarui');
            } else {
                return to_route('layanan-tambahan')->with('error', 'Layanan Tambahan Gagal Diperbarui');
            }
        } else if ($userRole == 'pic') {
            if ($perbarui) {
                return back()->with('success', 'Layanan Tambahan Berhasil Diperbarui');
            } else {
                return back()->with('error', 'Layanan Tambahan Gagal Diperbarui');
            }
        }
    }

    public function delete(Request $request)
    {
        $hapus = LayananTambahan::where('id', $request->id)->delete();
        // HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Layanan Tambahan Berhasil Dihapus');
        } else {
            abort(400, 'Layanan Tambahan Gagal Dihapus');
        }
    }

    public function import(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;
        try {
            Excel::import(new LayananTambahanImport, $request->file('impor'));
            if ($userRole == 'pic') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('success', 'Layanan Tambahan Berhasil Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('layanan-tambahan')->with('success', 'Layanan Tambahan Berhasil Ditambahkan');
            }
        } catch(\Exception $ex) {
            Log::info($ex);
            if ($userRole == 'pic') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('error', 'Layanan Tambahan Gagal Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('layanan-tambahan')->with('error', 'Layanan Tambahan Gagal Ditambahkan');
            }
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new LayananTambahanExport($request->cabang), 'Data Layanan Tambahan '.Carbon::now()->format('d-m-Y').'.xlsx');
    }
}
