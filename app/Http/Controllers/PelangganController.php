<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Exports\PelangganExport;
use App\Imports\PelangganImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Pelanggan\PelangganRequest;

class PelangganController extends Controller
{
    public function index()
    {
        $title = "Pelanggan";
        $pelanggan = Pelanggan::orderBy('created_at', 'asc')->get();
        return view('dashboard.pelanggan.index', compact('title', 'pelanggan'));
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
        $pelanggan = Pelanggan::where('pelanggan.id', $request->id)->first();
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

    public function import(Request $request)
    {
        try {
            Excel::import(new PelangganImport, $request->file('impor'));
            return to_route('pelanggan')->with('success', 'Pelanggan Berhasil Ditambahkan');
        } catch(\Exception $ex) {
            Log::info($ex);
            return to_route('pelanggan')->with('error', 'Pelanggan Gagal Ditambahkan');
        }
    }

    public function export()
    {
        return Excel::download(new PelangganExport, 'Data Pelanggan '.Carbon::now()->format('d-m-Y').'.xlsx');
    }
}
