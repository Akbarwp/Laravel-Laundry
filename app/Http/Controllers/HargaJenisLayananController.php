<?php

namespace App\Http\Controllers;

use App\Models\HargaJenisLayanan;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HargaJenisLayananController extends Controller
{
    public function index()
    {
        $title = "Harga Jenis Layanan";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $jenis_layanan = JenisLayanan::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $jenis_pakaian = JenisPakaian::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();

        $hargaJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $userCabang)
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_pakaian')
            ->orderBy('jenis_pakaian_id', 'asc')->orderBy('jenis_layanan_id', 'asc')->get();
        $hargaJenisLayananTrash = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $userCabang)
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_pakaian')
            ->onlyTrashed()->orderBy('harga_jenis_layanan.jenis_pakaian_id', 'asc')->orderBy('harga_jenis_layanan.jenis_layanan_id', 'asc')->get();

        // dd($hargaJenisLayanan);

        return view('dashboard.harga-jenis-layanan.index', compact('title', 'hargaJenisLayanan', 'hargaJenisLayananTrash', 'jenis_layanan', 'jenis_pakaian'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'harga' => 'required|decimal:0,2',
            'jenis_satuan' => 'required|string|max:255',
            'jenis_layanan_id' => 'required|integer',
            'jenis_pakaian_id' => 'required|integer',
        ],
        [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
        ]);

        $validated = $validator->validated();
        $validated['cabang_id'] = auth()->user()->cabang_id;

        if (HargaJenisLayanan::where('cabang_id', $validated['cabang_id'])->where('jenis_layanan_id', $validated['jenis_layanan_id'])->where('jenis_pakaian_id', $validated['jenis_pakaian_id'])->first()) {
            return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Sudah Ada');
        }

        $tambah = HargaJenisLayanan::create($validated);
        if ($tambah) {
            return to_route('harga-jenis-layanan')->with('success', 'Harga Jenis Layanan Berhasil Ditambahkan');
        } else {
            return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Gagal Ditambahkan');
        }
    }

    public function show(Request $request)
    {
        $hargaJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_pakaian')
            ->withTrashed()->where('harga_jenis_layanan.id', $request->id)->first();

        return $hargaJenisLayanan;
    }

    public function edit(Request $request)
    {
        $hargaJenisLayanan = HargaJenisLayanan::findOrFail($request->id);
        return $hargaJenisLayanan;
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'harga' => 'required|decimal:0,2',
            'jenis_satuan' => 'required|string|max:255',
            'jenis_layanan_id' => 'required|integer',
            'jenis_pakaian_id' => 'required|integer',
        ],
        [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
        ]);

        $validated = $validator->validated();
        $validated['cabang_id'] = auth()->user()->cabang_id;

        $hargaJenisLayanan = HargaJenisLayanan::where('id', $request->id)->first();
        $cekHargaJenisLayanan = HargaJenisLayanan::where('cabang_id', $validated['cabang_id'])->where('jenis_layanan_id', $validated['jenis_layanan_id'])->where('jenis_pakaian_id', $validated['jenis_pakaian_id'])->first();

        if ($cekHargaJenisLayanan) {
            if ($hargaJenisLayanan->id == $cekHargaJenisLayanan->id) {
                $perbarui = $hargaJenisLayanan->update($validated);
                if ($perbarui) {
                    return to_route('harga-jenis-layanan')->with('success', 'Harga Jenis Layanan Berhasil Diperbarui');
                } else {
                    return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Gagal Diperbarui');
                }
            } else {
                return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Sudah Ada');
            }
        }

        $perbarui = $hargaJenisLayanan->update($validated);
        if ($perbarui) {
            return to_route('harga-jenis-layanan')->with('success', 'Harga Jenis Layanan Berhasil Diperbarui');
        } else {
            return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Gagal Diperbarui');
        }
    }

    public function delete(Request $request)
    {
        $hapus = HargaJenisLayanan::where('id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = HargaJenisLayanan::where('id', $request->id)->restore();
        if ($pulih) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = HargaJenisLayanan::where('id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }
}
