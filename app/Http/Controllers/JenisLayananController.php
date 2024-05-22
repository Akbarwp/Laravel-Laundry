<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\HargaJenisLayanan;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JenisLayananController extends Controller
{
    public function index()
    {
        $title = "Jenis Layanan";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $jenisLayanan = JenisLayanan::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $jenisLayananTrash = JenisLayanan::where('cabang_id', $userCabang)->onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.jenis-layanan.index', compact('title', 'jenisLayanan', 'jenisLayananTrash'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable',
            'for_gamis' => 'required|boolean',
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

        $tambah = JenisLayanan::create($validated);

        if ($userRole == 'manajer_laundry') {
            if ($tambah) {
                return to_route('jenis-layanan')->with('success', 'Jenis Layanan Berhasil Ditambahkan');
            } else {
                return to_route('jenis-layanan')->with('error', 'Jenis Layanan Gagal Ditambahkan');
            }
        } else if ($userRole == 'lurah') {
            if ($tambah) {
                return back()->with('success', 'Jenis Layanan Berhasil Ditambahkan');
            } else {
                return back()->with('error', 'Jenis Layanan Gagal Ditambahkan');
            }
        }
    }

    public function show(Request $request)
    {
        $jenisLayanan = JenisLayanan::withTrashed()->findOrFail($request->id);
        return $jenisLayanan;
    }

    public function edit(Request $request)
    {
        $jenisLayanan = JenisLayanan::findOrFail($request->id);
        return $jenisLayanan;
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable',
            'for_gamis' => 'required|boolean',
        ],
        [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
        ]);

        $validated = $validator->validated();
        $userRole = auth()->user()->roles[0]->name;
        $perbarui = JenisLayanan::where('id', $request->id)->update($validated);

        if ($userRole == 'manajer_laundry') {
            if ($perbarui) {
                return to_route('jenis-layanan')->with('success', 'Jenis Layanan Berhasil Diperbarui');
            } else {
                return to_route('jenis-layanan')->with('error', 'Jenis Layanan Gagal Diperbarui');
            }
        } else if ($userRole == 'lurah') {
            if ($perbarui) {
                return back()->with('success', 'Jenis Layanan Berhasil Diperbarui');
            } else {
                return back()->with('error', 'Jenis Layanan Gagal Diperbarui');
            }
        }
    }

    public function delete(Request $request)
    {
        $hapus = JenisLayanan::where('id', $request->id)->delete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Layanan Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = JenisLayanan::where('id', $request->id)->restore();
        $cekJenisPakaian = HargaJenisLayanan::query()
            ->join('jenis_pakaian as jp', 'harga_jenis_layanan.jenis_pakaian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $request->cabang_id)
            ->where('harga_jenis_layanan.jenis_layanan_id', $request->id)
            ->where('jp.deleted_at', null)
            ->select('harga_jenis_layanan.*', 'jp.id as id_pakaian')
            ->onlyTrashed()->get();

        foreach ($cekJenisPakaian as $item) {
            HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $request->id)->where('jenis_pakaian_id', $item->id_pakaian)->restore();
        }

        if ($pulih) {
            abort(200, 'Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Layanan Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = JenisLayanan::where('id', $request->id)->forceDelete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Layanan Gagal Dihapus');
        }
    }
}
