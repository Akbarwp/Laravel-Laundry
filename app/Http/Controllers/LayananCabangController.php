<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use Illuminate\Http\Request;
use App\Models\LayananPrioritas;
use App\Enums\JenisSatuanLayanan;
use App\Models\HargaJenisLayanan;
use App\Models\LayananTambahan;

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
        $title = "Layanan Cabang";
        $cabang = Cabang::where('slug', $request->cabang)->withTrashed()->first();
        if ($cabang == null) {
            abort(404, 'CABANG TIDAK DITEMUKAN.');
        }

        $jenisLayanan = JenisLayanan::where('cabang_id', $cabang->id)->orderBy('created_at', 'asc')->get();
        $layananTambahan = LayananTambahan::where('cabang_id', $cabang->id)->orderBy('created_at', 'asc')->get();
        $jenisPakaian = JenisPakaian::where('cabang_id', $cabang->id)->orderBy('created_at', 'asc')->get();
        $hargaJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $cabang->id)
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_pakaian')
            ->orderBy('jenis_pakaian_id', 'asc')->orderBy('jenis_layanan_id', 'asc')->get();
        $jenisSatuanLayanan = JenisSatuanLayanan::cases();
        $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->orderBy('created_at', 'asc')->get();

        return view('dashboard.layanan-cabang.cabang', compact('title', 'cabang', 'jenisLayanan', 'layananTambahan', 'jenisPakaian', 'hargaJenisLayanan', 'layananPrioritas', 'jenisSatuanLayanan'));
    }

    public function indexCabangTrash(Request $request)
    {
        $title = "Layanan Cabang Trash";
        $cabang = Cabang::where('slug', $request->cabang)->withTrashed()->first();
        if ($cabang == null) {
            abort(404, 'CABANG TIDAK DITEMUKAN.');
        }

        $jenisLayananTrash = JenisLayanan::where('cabang_id', $cabang->id)->onlyTrashed()->orderBy('created_at', 'asc')->get();
        $layananTambahanTrash = LayananTambahan::where('cabang_id', $cabang->id)->onlyTrashed()->orderBy('created_at', 'asc')->get();
        $jenisPakaianTrash = JenisPakaian::where('cabang_id', $cabang->id)->onlyTrashed()->orderBy('created_at', 'asc')->get();
        $hargaJenisLayananTrash = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $cabang->id)
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_pakaian')
            ->onlyTrashed()->orderBy('jenis_pakaian_id', 'asc')->orderBy('jenis_layanan_id', 'asc')->get();
        $layananPrioritasTrash = LayananPrioritas::where('cabang_id', $cabang->id)->onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.layanan-cabang.trash', compact('title', 'cabang', 'jenisLayananTrash', 'layananTambahanTrash', 'jenisPakaianTrash', 'hargaJenisLayananTrash', 'layananPrioritasTrash'));
    }
}
