<?php

namespace App\Http\Controllers;

use App\Http\Requests\UMR\StoreUMRRequest;
use App\Http\Requests\UMR\UpdateUMRRequest;
use App\Models\UMR;
use Illuminate\Http\Request;

class UMRController extends Controller
{
    public function __construct()
    {
        if (!auth()->user()->roles[0]->name == 'lurah') {
            abort(403);
        }
    }

    public function index()
    {
        $title = "UMR";
        $umr = UMR::orderBy('created_at', 'asc')->get();

        return view('dashboard.umr.index', compact('title', 'umr'));
    }

    public function store(StoreUMRRequest $request)
    {
        if ($request->is_used == UMR::where('is_used', true)->first()->is_used) {
            return to_route('umr')->with('error', "UMR yang digunakan sudah ada, silakan pilih is_used dengan tidak");
        }

        $validated = $request->validated();
        $tambah = UMR::create($validated);
        if ($tambah) {
            return to_route('umr')->with('success', 'UMR Berhasil Ditambahkan');
        } else {
            return to_route('umr')->with('error', 'UMR Gagal Ditambahkan');
        }
    }

    public function show(Request $request)
    {
        $umr = UMR::find($request->id);
        return $umr;
    }

    public function edit(Request $request)
    {
        $umr = UMR::find($request->id);
        return $umr;
    }

    public function update(UpdateUMRRequest $request)
    {
        $validated = $request->validated();

        if ($validated['is_used'] == true) {
            UMR::where('is_used', true)->update(['is_used' => false]);
        }

        $perbarui = UMR::where('id', $request->id)->update($validated);
        if ($perbarui) {
            return to_route('umr')->with('success', 'UMR Berhasil Diperbarui');
        } else {
            return to_route('umr')->with('error', 'UMR Gagal Diperbarui');
        }
    }

    public function delete(Request $request)
    {
        if (UMR::count() == 1) {
            abort(400, 'UMR tidak bisa dihapus karena data tersisa satu');
        }
        $umr = UMR::where('id', $request->id);
        if ($umr->first()->is_used == true) {
            UMR::where('is_used', false)->orderBy('created_at', 'asc')->first()->update(['is_used' => true]);
        }
        $hapus = $umr->delete();
        if ($hapus) {
            abort(200, 'UMR Berhasil Dihapus');
        } else {
            abort(400, 'UMR Gagal Dihapus');
        }
    }
}
