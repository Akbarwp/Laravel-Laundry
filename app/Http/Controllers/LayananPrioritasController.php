<?php

namespace App\Http\Controllers;

use App\Http\Requests\Layanan\LayananPrioritasRequest;
use App\Models\Cabang;
use App\Models\LayananPrioritas;
use Illuminate\Http\Request;

class LayananPrioritasController extends Controller
{
    public function index()
    {
        $title = "Layanan Prioritas";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;
        $cabang = Cabang::where('id', $userCabang)->withTrashed()->first();

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $layananPrioritas = LayananPrioritas::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $layananPrioritasTrash = LayananPrioritas::where('cabang_id', $userCabang)->onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.layanan-prioritas.index', compact('title', 'layananPrioritas', 'layananPrioritasTrash', 'cabang'));
    }

    public function store(LayananPrioritasRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'manajer_laundry') {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        } else if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang_slug)->first();
            $validated['cabang_id'] = $cabang->id;
        }

        $tambah = LayananPrioritas::create($validated);

        if ($userRole == 'manajer_laundry') {
            if ($tambah) {
                return to_route('layanan-prioritas')->with('success', 'Layanan Prioritas Berhasil Ditambahkan');
            } else {
                return to_route('layanan-prioritas')->with('error', 'Layanan Prioritas Gagal Ditambahkan');
            }
        } else if ($userRole == 'lurah') {
            if ($tambah) {
                return back()->with('success', 'Layanan Prioritas Berhasil Ditambahkan');
            } else {
                return back()->with('error', 'Layanan Prioritas Gagal Ditambahkan');
            }
        }
    }

    public function show(Request $request)
    {
        $layananPrioritas = LayananPrioritas::withTrashed()->findOrFail($request->id);
        return $layananPrioritas;
    }

    public function edit(Request $request)
    {
        $layananPrioritas = LayananPrioritas::findOrFail($request->id);
        return $layananPrioritas;
    }

    public function update(LayananPrioritasRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;
        $perbarui = LayananPrioritas::where('id', $request->id)->update($validated);

        if ($userRole == 'manajer_laundry') {
            if ($perbarui) {
                return to_route('layanan-prioritas')->with('success', 'Layanan Prioritas Berhasil Diperbarui');
            } else {
                return to_route('layanan-prioritas')->with('error', 'Layanan Prioritas Gagal Diperbarui');
            }
        } else if ($userRole == 'lurah') {
            if ($perbarui) {
                return back()->with('success', 'Layanan Prioritas Berhasil Diperbarui');
            } else {
                return back()->with('error', 'Layanan Prioritas Gagal Diperbarui');
            }
        }
    }

    public function delete(Request $request)
    {
        $hapus = LayananPrioritas::where('id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Layanan Prioritas Berhasil Dihapus');
        } else {
            abort(400, 'Layanan Prioritas Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = LayananPrioritas::where('id', $request->id)->restore();
        if ($pulih) {
            abort(200, 'Layanan Prioritas Berhasil Dihapus');
        } else {
            abort(400, 'Layanan Prioritas Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = LayananPrioritas::where('id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Layanan Prioritas Berhasil Dihapus');
        } else {
            abort(400, 'Layanan Prioritas Gagal Dihapus');
        }
    }
}
