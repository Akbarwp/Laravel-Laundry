<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pelanggan\PelangganRequest;
use App\Models\Cabang;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $title = "Pelanggan";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::orderBy('created_at', 'asc')->get();
            $pelanggan = Pelanggan::query()
                ->join('cabang as c', 'pelanggan.cabang_id', '=', 'c.id')
                ->select('pelanggan.*', 'c.nama as nama_cabang', 'c.deleted_at as cabang_deleted_at')
                ->orderBy('pelanggan.cabang_id', 'asc')
                ->orderBy('pelanggan.created_at', 'asc')
                ->get();
        } else {
            $userCabang = auth()->user()->cabang_id;
            $cabang = Cabang::where('id', $userCabang)->orderBy('created_at', 'asc')->get();
            $pelanggan = Pelanggan::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        }

        return view('dashboard.pelanggan.index', compact('title', 'pelanggan', 'cabang'));
    }

    public function indexCabang(Request $request)
    {
        $title = "Pelanggan";
        $cabang = Cabang::where('slug', $request->cabang)->orderBy('created_at', 'asc')->first();
        if ($cabang == null || $cabang->deleted_at) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $pelanggan = Pelanggan::query()
            ->join('cabang as c', 'pelanggan.cabang_id', '=', 'c.id')
            ->where('pelanggan.cabang_id', $cabang->id)
            ->select('pelanggan.*', 'c.nama as nama_cabang')
            ->orderBy('pelanggan.cabang_id', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
        return view('dashboard.pelanggan.cabang', compact('title', 'pelanggan', 'cabang'));
    }

    public function store(PelangganRequest $request)
    {
        $validated = $request->validated();
        $tambah = Pelanggan::create($validated);
        if ($tambah) {
            return to_route('pelanggan')->with('success', 'Pelanggan Berhasil Ditambahkan');
        } else {
            return to_route('pelanggan')->with('error', 'Pelanggan Gagal Ditambahkan');
        }
    }

    public function show(Request $request)
    {
        $pelanggan = Pelanggan::query()
            ->join('cabang as c', 'pelanggan.cabang_id', '=', 'c.id')
            ->where('pelanggan.id', $request->id)
            ->select('pelanggan.*', 'c.nama as nama_cabang')
            ->first();
        return $pelanggan;
    }

    public function edit(Request $request)
    {
        $pelanggan = Pelanggan::find($request->id);
        return $pelanggan;
    }

    public function update(PelangganRequest $request)
    {
        $validated = $request->validated();
        $perbarui = Pelanggan::where('id', $request->id)->update($validated);
        if ($perbarui) {
            return to_route('pelanggan')->with('success', 'Pelanggan Berhasil Diperbarui');
        } else {
            return to_route('pelanggan')->with('error', 'Pelanggan Gagal Diperbarui');
        }
    }

    public function delete(Request $request)
    {
        $hapus = Pelanggan::where('id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Pelanggan Berhasil Dihapus');
        } else {
            abort(400, 'Pelanggan Gagal Dihapus');
        }
    }
}
