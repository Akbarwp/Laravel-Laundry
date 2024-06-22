<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gamis\GamisRequest;
use App\Imports\GamisImport;
use App\Models\DetailGamis;
use App\Models\Gamis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class GamisController extends Controller
{
    public function index()
    {
        $title = "Gamis";
        $gamis = Gamis::orderBy('created_at', 'asc')->get();
        return view('dashboard.gamis.index', compact('title', 'gamis'));
    }

    public function store(GamisRequest $request)
    {
        $validated = $request->validated();
        $tambah = Gamis::create($validated);
        if ($tambah) {
            return to_route('gamis')->with('success', 'Gamis Berhasil Ditambahkan');
        } else {
            return to_route('gamis')->with('error', 'Gamis Gagal Ditambahkan');
        }
    }

    public function show(Request $request)
    {
        $gamis = Gamis::findOrFail($request->id);
        return $gamis;
    }

    public function edit(Request $request)
    {
        $gamis = Gamis::find($request->id);
        return $gamis;
    }

    public function update(GamisRequest $request)
    {
        $validated = $request->validated();
        $perbarui = Gamis::where('id', $request->id)->update($validated);
        if ($perbarui) {
            return to_route('gamis')->with('success', 'Gamis Berhasil Diperbarui');
        } else {
            return to_route('gamis')->with('error', 'Gamis Gagal Diperbarui');
        }
    }

    public function delete(Request $request)
    {
        $hapus = Gamis::where('id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Gamis Berhasil Dihapus');
        } else {
            abort(400, 'Gamis Gagal Dihapus');
        }
    }

    public function anggota(Request $request)
    {
        $title = "Anggota Keluarga";
        $gamis = Gamis::where('kartu_keluarga', $request->detail_gamis)->orderBy('created_at', 'asc')->first();
        $detailGamis = DetailGamis::where('gamis_id', $gamis->id)->orderBy('created_at', 'asc')->get();
        return view('dashboard.gamis.anggota', compact('title', 'gamis', 'detailGamis'));
    }

    public function detailAnggota(Request $request)
    {
        $detailGamis = DetailGamis::where('id', $request->id)->orderBy('created_at', 'asc')->first();
        $detailGamis['tanggal_lahir'] = Carbon::parse($detailGamis['tanggal_lahir'])->format('d F Y');
        return $detailGamis;
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new GamisImport, $request->file('impor'));
            return to_route('gamis')->with('success', 'Gamis Berhasil Ditambahkan');
        } catch(\Exception $ex) {
            Log::info($ex);
            return to_route('gamis')->with('error', 'Gamis Gagal Ditambahkan');
        }
    }
}
