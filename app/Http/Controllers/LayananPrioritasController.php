<?php

namespace App\Http\Controllers;

use App\Models\LayananPrioritas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LayananPrioritasController extends Controller
{
    public function index()
    {
        $title = "Layanan Prioritas";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $layananPrioritas = LayananPrioritas::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $layananPrioritasTrash = LayananPrioritas::where('cabang_id', $userCabang)->onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.layanan-prioritas.index', compact('title', 'layananPrioritas', 'layananPrioritasTrash'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:App\Models\LayananPrioritas,nama',
            'deskripsi' => 'nullable',
            'jenis_satuan' => 'required|string|max:255',
            'harga' => 'required|decimal:0,2',
            'prioritas' => 'required|integer',
        ],
        [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
        ]);

        $validated = $validator->validated();
        $validated['cabang_id'] = auth()->user()->cabang_id;

        $tambah = LayananPrioritas::create($validated);
        if ($tambah) {
            return to_route('layanan-prioritas')->with('success', 'Layanan Prioritas Berhasil Ditambahkan');
        } else {
            return to_route('layanan-prioritas')->with('error', 'Layanan Prioritas Gagal Ditambahkan');
        }
    }

    public function show(Request $request)
    {
        $layananPrioritas = LayananPrioritas::withTrashed()->findOrFail($request->id);
        return $layananPrioritas;
    }

    public function edit(Request $request)
    {
        $layananPrioritas = LayananPrioritas::findOrFail($request->id);
        return $layananPrioritas;
    }

    public function update(Request $request)
    {
        $layananPrioritas = LayananPrioritas::find($request->id);
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255', Rule::unique('layanan_prioritas')->ignore($layananPrioritas)],
            'deskripsi' => 'nullable',
            'jenis_satuan' => 'required|string|max:255',
            'harga' => 'required|decimal:0,2',
            'prioritas' => 'required|integer',
        ],
        [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
        ]);

        $validated = $validator->validated();
        $validated['cabang_id'] = auth()->user()->cabang_id;

        $perbarui = LayananPrioritas::where('id', $request->id)->update($validated);
        if ($perbarui) {
            return to_route('layanan-prioritas')->with('success', 'Layanan Prioritas Berhasil Diperbarui');
        } else {
            return to_route('layanan-prioritas')->with('error', 'Layanan Prioritas Gagal Diperbarui');
        }
    }

    public function delete(Request $request)
    {
        $hapus = LayananPrioritas::where('id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Layanan Prioritas Berhasil Dihapus');
        } else {
            abort(400, 'Layanan Prioritas Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = LayananPrioritas::where('id', $request->id)->restore();
        if ($pulih) {
            abort(200, 'Layanan Prioritas Berhasil Dihapus');
        } else {
            abort(400, 'Layanan Prioritas Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = LayananPrioritas::where('id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Layanan Prioritas Berhasil Dihapus');
        } else {
            abort(400, 'Layanan Prioritas Gagal Dihapus');
        }
    }
}
