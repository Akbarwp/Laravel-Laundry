<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use App\Models\LayananPrioritas;
use Illuminate\Http\Request;

class LayananCabangController extends Controller
{
    public function __construct()
    {
        if (!auth()->user()->roles[0]->name == 'lurah') {
            abort(403);
        }
    }

    public function index()
    {
        $title = "Daftar Layanan Cabang";
        $cabang = Cabang::withTrashed()->get();
        return view('dashboard.layanan-cabang.index', compact('title', 'cabang'));
    }

    public function indexCabang(Request $request)
    {
        $cabang = Cabang::where('slug', $request->cabang)->withTrashed()->first();
        $title = "Layanan Cabang";

        $jenisLayanan = JenisLayanan::where('cabang_id', $cabang->id)->get();
        $jenisPakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
        $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();

        return view('dashboard.layanan-cabang.cabang', compact('title', 'cabang', 'jenisLayanan', 'jenisPakaian', 'layananPrioritas'));
    }
}
