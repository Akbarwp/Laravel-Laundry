<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\HargaJenisLayanan;
use App\Models\JenisPakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JenisPakaianController extends Controller
{
    public function index()
    {
        $title = "Jenis Pakaian";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $jenisPakaian = JenisPakaian::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $jenisPakaianTrash = JenisPakaian::where('cabang_id', $userCabang)->onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.jenis-pakaian.index', compact('title', 'jenisPakaian', 'jenisPakaianTrash'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable',
        ],
        [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
        ]);

        $validated = $validator->validated();
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'manajer_laundry') {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        } else if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang_slug)->first();
            $validated['cabang_id'] = $cabang->id;
        }

        $tambah = JenisPakaian::create($validated);

        if ($userRole == 'manajer_laundry') {
            if ($tambah) {
                return to_route('jenis-pakaian')->with('success', 'Jenis Pakaian Berhasil Ditambahkan');
            } else {
                return to_route('jenis-pakaian')->with('error', 'Jenis Pakaian Gagal Ditambahkan');
            }
        } else if ($userRole == 'lurah') {
            if ($tambah) {
                return back()->with('success', 'Jenis Pakaian Berhasil Ditambahkan');
            } else {
                return back()->with('error', 'Jenis Pakaian Gagal Ditambahkan');
            }
        }
    }

    public function show(Request $request)
    {
        $jenisPakaian = JenisPakaian::withTrashed()->findOrFail($request->id);
        return $jenisPakaian;
    }

    public function edit(Request $request)
    {
        $jenisPakaian = JenisPakaian::findOrFail($request->id);
        return $jenisPakaian;
    }

    public function update(Request $request)
    {
        $jenisPakaian = JenisPakaian::find($request->id);
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable',
        ],
        [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
        ]);

        $validated = $validator->validated();
        $userRole = auth()->user()->roles[0]->name;
        $perbarui = JenisPakaian::where('id', $request->id)->update($validated);

        if ($userRole == 'manajer_laundry') {
            if ($perbarui) {
                return to_route('jenis-pakaian')->with('success', 'Jenis Pakaian Berhasil Diperbarui');
            } else {
                return to_route('jenis-pakaian')->with('error', 'Jenis Pakaian Gagal Diperbarui');
            }
        } else if ($userRole == 'lurah') {
            if ($perbarui) {
                return back()->with('success', 'Jenis Pakaian Berhasil Diperbarui');
            } else {
                return back()->with('error', 'Jenis Pakaian Gagal Diperbarui');
            }
        }
    }

    public function delete(Request $request)
    {
        $hapus = JenisPakaian::where('id', $request->id)->delete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_pakaian_id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Jenis Pakaian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Pakaian Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = JenisPakaian::where('id', $request->id)->restore();
        $cekJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->where('harga_jenis_layanan.cabang_id', $request->cabang_id)
            ->where('harga_jenis_layanan.jenis_pakaian_id', $request->id)
            ->where('jl.deleted_at', null)
            ->select('harga_jenis_layanan.*', 'jl.id as id_layanan')
            ->onlyTrashed()->get();

        foreach ($cekJenisLayanan as $item) {
            HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $item->id_layanan)->where('jenis_pakaian_id', $request->id)->restore();
        }

        if ($pulih) {
            abort(200, 'Jenis Pakaian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Pakaian Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = JenisPakaian::where('id', $request->id)->forceDelete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_pakaian_id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Jenis Pakaian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Pakaian Gagal Dihapus');
        }
    }
}
