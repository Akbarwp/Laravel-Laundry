<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cabang\StoreCabangRequest;
use App\Http\Requests\Cabang\UpdateCabangRequest;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function __construct()
    {
        if (!auth()->user()->roles[0]->name == 'lurah' || !auth()->user()->roles[0]->name == 'pic') {
            abort(403);
        }
    }

    public function index()
    {
        $title = "Cabang";

        $cabang = Cabang::orderBy('created_at', 'asc')->get();
        $cabangTrash = Cabang::onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.cabang.index', compact('title', 'cabang', 'cabangTrash'));
    }

    public function store(StoreCabangRequest $request)
    {
        $validated = $request->validated();

        $tambah = Cabang::create($validated);
        if ($tambah) {
            return to_route('cabang')->with('success', 'Cabang Berhasil Ditambahkan');
        } else {
            return to_route('cabang')->with('error', 'Cabang Gagal Ditambahkan');
        }
    }

    public function show(Request $request)
    {
        $cabang = Cabang::withTrashed()->findOrFail($request->id);
        return $cabang;
    }

    public function edit(Request $request)
    {
        $cabang = Cabang::find($request->id);
        return $cabang;
    }

    public function update(UpdateCabangRequest $request)
    {
        $validated = $request->validated();

        $perbarui = Cabang::where('id', $request->id)->update($validated);
        if ($perbarui) {
            return to_route('cabang')->with('success', 'Cabang Berhasil Diperbarui');
        } else {
            return to_route('cabang')->with('error', 'Cabang Gagal Diperbarui');
        }
    }

    public function delete(Request $request)
    {
        $hapus = Cabang::where('id', $request->id)->delete();
        User::where('cabang_id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Cabang Berhasil Dihapus');
        } else {
            abort(400, 'Cabang Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = Cabang::where('id', $request->id)->restore();
        User::where('cabang_id', $request->id)->restore();
        if ($pulih) {
            abort(200, 'Cabang Berhasil Dihapus');
        } else {
            abort(400, 'Cabang Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = Cabang::where('id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Cabang Berhasil Dihapus');
        } else {
            abort(400, 'Cabang Gagal Dihapus');
        }
    }
}
