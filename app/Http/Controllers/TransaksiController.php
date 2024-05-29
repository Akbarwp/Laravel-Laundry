<?php

namespace App\Http\Controllers;

use App\Enums\StatusTransaksi;
use App\Models\Cabang;
use App\Models\DetailLayananTransaksi;
use App\Models\DetailTransaksi;
use App\Models\HargaJenisLayanan;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use App\Models\LayananPrioritas;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::orderBy('created_at', 'asc')->get();
            return view('dashboard.transaksi.lurah.index', compact('title', 'cabang'));

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $isJadwal = false;
            $status = StatusTransaksi::cases();

            $transaksi = Transaksi::where('cabang_id', $cabang->id)->orderBy('waktu', 'asc')->get();
            return view('dashboard.transaksi.index', compact('title', 'cabang', 'transaksi', 'isJadwal', 'status'));
        }
    }

    public function indexJadwal()
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = true;
        $status = StatusTransaksi::cases();

        if ($userRole == 'lurah' || $userRole == 'rw' || $userRole == 'gamis') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $userCabang = auth()->user()->cabang_id;
        $cabang = Cabang::where('id', $userCabang)->first();
        $isJadwal = false;
        $status = StatusTransaksi::cases();

        $transaksi = Transaksi::query()
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
            ->where('transaksi.cabang_id', $cabang->id)
            ->orderBy('lp.prioritas', 'desc')
            ->orderBy('transaksi.waktu', 'asc')
            ->select('transaksi.*')
            ->get();

        return view('dashboard.transaksi.jadwal', compact('title', 'cabang', 'transaksi', 'isJadwal', 'status'));
    }

    public function indexCabang(Request $request)
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = false;
        $status = StatusTransaksi::cases();

        if ($userRole != 'lurah') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::where('slug', $request->cabang)->first();
        if ($cabang == null || $cabang->deleted_at) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $transaksi = Transaksi::where('cabang_id', $cabang->id)->orderBy('waktu', 'asc')->get();
        return view('dashboard.transaksi.lurah.cabang', compact('title', 'cabang', 'transaksi', 'isJadwal', 'status'));
    }

    public function indexCabangJadwal(Request $request)
    {
        $title = "Jadwal Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = true;
        $status = StatusTransaksi::cases();

        if ($userRole != 'lurah') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::where('slug', $request->cabang)->first();
        if ($cabang == null || $cabang->deleted_at) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $transaksi = Transaksi::query()
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
            ->where('transaksi.cabang_id', $cabang->id)
            ->orderBy('lp.prioritas', 'desc')
            ->orderBy('transaksi.waktu', 'asc')
            ->select('transaksi.*')
            ->get();

        return view('dashboard.transaksi.lurah.jadwal', compact('title', 'cabang', 'transaksi', 'isJadwal', 'status'));
    }

    public function viewDetailTransaksi(Request $request)
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = $request->isJadwal;

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $transaksi = Transaksi::where('id', $request->transaksi)->first();
            $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
            return view('dashboard.transaksi.lurah.lihat', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'isJadwal'));

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $transaksi = Transaksi::where('id', $request->transaksi)->first();
            $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
            return view('dashboard.transaksi.lihat', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'isJadwal'));
        }
    }

    public function createTransaksiCabang(Request $request)
    {
        $title = "Tambah Transaksi";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = $request->isJadwal;

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $pelanggan = Pelanggan::where('cabang_id', $cabang->id)->get();
            $gamis = User::query()
                ->join('detail_gamis as dg', 'users.id', '=', 'dg.user_id')
                ->where('users.cabang_id', $cabang->id)
                ->get();
            $pakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
            return view('dashboard.transaksi.lurah.tambah', compact('title', 'cabang', 'pelanggan', 'gamis', 'pakaian', 'layananPrioritas', 'isJadwal'));

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $pelanggan = Pelanggan::where('cabang_id', $cabang->id)->get();
            $gamis = User::query()
                ->join('detail_gamis as dg', 'users.id', '=', 'dg.user_id')
                ->where('users.cabang_id', $cabang->id)
                ->get();
            $pakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
            return view('dashboard.transaksi.tambah', compact('title', 'cabang', 'pelanggan', 'gamis', 'pakaian', 'layananPrioritas', 'isJadwal'));
        }
    }

    public function storeTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        //? Lurah
        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();

            $validatorTransaksi = Validator::make($request->all(), [
                'total_biaya_layanan' => 'required|decimal:0,2',
                'total_biaya_prioritas' => 'required|decimal:0,2',
                'total_bayar_akhir' => 'required|decimal:0,2',
                'jenis_pembayaran' => 'required|string|max:255',
                'bayar' => 'required|decimal:0,2',
                'kembalian' => 'required|decimal:0,2',
                'layanan_prioritas_id' => 'required|integer',
                'pelanggan_id' => 'required|integer',
                'gamis_id' => 'nullable|integer',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'integer' => ':attribute harus berupa angka.',
                'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
            ]);

            $validatedTransaksi = $validatorTransaksi->validated();
            $validatedTransaksi['cabang_id'] = $cabang->id;
            $validatedTransaksi['pegawai_id'] = auth()->user()->id;
            $validatedTransaksi['waktu'] = Carbon::now();
            $nota = Carbon::now()->format('His') . "-" . Carbon::now()->format('dmY') . "-" . str()->uuid();
            $validatedTransaksi['nota_layanan'] = "layanan-" . $nota;
            $validatedTransaksi['nota_pelanggan'] = "pelanggan-" . $nota;
            $validatedTransaksi['status'] = StatusTransaksi::BARU->value;

            $transaksi = Transaksi::create($validatedTransaksi);

            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->where('id', $request->layanan_prioritas_id)->first();

            foreach ($request->jenis_pakaian_id as $item => $value) {
                $detailTransaksi = DetailTransaksi::create([
                    'total_pakaian' => $request->total_pakaian[$item],
                    'jenis_satuan' => 'kg',
                    'harga_layanan_akhir' => $request->harga_jenis_layanan_id[$item],
                    'total_biaya_layanan' => $request->total_pakaian[$item] * $request->harga_jenis_layanan_id[$item],
                    'total_biaya_prioritas' => $request->total_pakaian[$item] * $layananPrioritas->harga,
                    'transaksi_id' => $transaksi->id,
                ]);

                foreach ($request->jenis_layanan_id[$item] as $layanan) {
                    $jenisPakaian = JenisPakaian::where('cabang_id', $cabang->id)->where('id', $value)->first();
                    $jenisLayanan = JenisLayanan::where('cabang_id', $cabang->id)->where('id', $layanan)->first();
                    $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->where('jenis_pakaian_id', $jenisPakaian->id)->where('jenis_layanan_id', $jenisLayanan->id)->first();
                    DetailLayananTransaksi::create([
                        'harga_jenis_layanan_id' => $hargaLayanan->id,
                        'detail_transaksi_id' => $detailTransaksi->id,
                    ]);
                }
            }

            if ($transaksi) {
                return $transaksi;
            } else {
                return abort(400, 'Transaksi Gagal Dibuat');
            }

        //? Pegawai
        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();

            $validatorTransaksi = Validator::make($request->all(), [
                'total_biaya_layanan' => 'required|decimal:0,2',
                'total_biaya_prioritas' => 'required|decimal:0,2',
                'total_bayar_akhir' => 'required|decimal:0,2',
                'jenis_pembayaran' => 'required|string|max:255',
                'bayar' => 'required|decimal:0,2',
                'kembalian' => 'required|decimal:0,2',
                'layanan_prioritas_id' => 'required|integer',
                'pelanggan_id' => 'required|integer',
                'gamis_id' => 'nullable|integer',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'integer' => ':attribute harus berupa angka.',
                'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
            ]);

            $validatedTransaksi = $validatorTransaksi->validated();
            $validatedTransaksi['cabang_id'] = $cabang->id;
            $validatedTransaksi['pegawai_id'] = auth()->user()->id;
            $validatedTransaksi['waktu'] = Carbon::now();
            $nota = Carbon::now()->format('His') . "-" . Carbon::now()->format('dmY') . "-" . str()->uuid();
            $validatedTransaksi['nota_layanan'] = "layanan-" . $nota;
            $validatedTransaksi['nota_pelanggan'] = "pelanggan-" . $nota;
            $validatedTransaksi['status'] = StatusTransaksi::BARU->value;

            $transaksi = Transaksi::create($validatedTransaksi);

            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->where('id', $request->layanan_prioritas_id)->first();

            foreach ($request->jenis_pakaian_id as $item => $value) {
                $detailTransaksi = DetailTransaksi::create([
                    'total_pakaian' => $request->total_pakaian[$item],
                    'jenis_satuan' => 'kg',
                    'harga_layanan_akhir' => $request->harga_jenis_layanan_id[$item],
                    'total_biaya_layanan' => $request->total_pakaian[$item] * $request->harga_jenis_layanan_id[$item],
                    'total_biaya_prioritas' => $request->total_pakaian[$item] * $layananPrioritas->harga,
                    'transaksi_id' => $transaksi->id,
                ]);

                foreach ($request->jenis_layanan_id[$item] as $layanan) {
                    $jenisPakaian = JenisPakaian::where('cabang_id', $cabang->id)->where('id', $value)->first();
                    $jenisLayanan = JenisLayanan::where('cabang_id', $cabang->id)->where('id', $layanan)->first();
                    $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->where('jenis_pakaian_id', $jenisPakaian->id)->where('jenis_layanan_id', $jenisLayanan->id)->first();
                    DetailLayananTransaksi::create([
                        'harga_jenis_layanan_id' => $hargaLayanan->id,
                        'detail_transaksi_id' => $detailTransaksi->id,
                    ]);
                }
            }

            if ($transaksi) {
                return $transaksi;
            } else {
                return abort(400, 'Transaksi Gagal Dibuat');
            }
        }
    }

    public function editTransaksiCabang(Request $request)
    {
        $title = "Ubah Transaksi";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = $request->isJadwal;
        $status = StatusTransaksi::cases();

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $pelanggan = Pelanggan::where('cabang_id', $cabang->id)->get();
            $gamis = User::query()
                ->join('detail_gamis as dg', 'users.id', '=', 'dg.user_id')
                ->where('users.cabang_id', $cabang->id)
                ->get();
            $pakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
            $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

            $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->get();
            $layanan = JenisLayanan::where('cabang_id', $cabang->id)->get();

            return view('dashboard.transaksi.lurah.ubah', compact('title', 'cabang', 'status', 'pelanggan', 'gamis', 'pakaian', 'layananPrioritas', 'transaksi', 'layanan', 'hargaLayanan', 'isJadwal'));

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $pelanggan = Pelanggan::where('cabang_id', $cabang->id)->get();
            $gamis = User::query()
                ->join('detail_gamis as dg', 'users.id', '=', 'dg.user_id')
                ->where('users.cabang_id', $cabang->id)
                ->get();
            $pakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
            $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

            $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->get();
            $layanan = JenisLayanan::where('cabang_id', $cabang->id)->get();

            return view('dashboard.transaksi.ubah', compact('title', 'cabang', 'status', 'pelanggan', 'gamis', 'pakaian', 'layananPrioritas', 'transaksi', 'layanan', 'hargaLayanan', 'isJadwal'));
        }
    }

    public function updateTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        //? Lurah
        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $getTransaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

            $validatorTransaksi = Validator::make($request->all(), [
                'total_biaya_layanan' => 'required|decimal:0,2',
                'total_biaya_prioritas' => 'required|decimal:0,2',
                'total_bayar_akhir' => 'required|decimal:0,2',
                'jenis_pembayaran' => 'required|string|max:255',
                'bayar' => 'required|decimal:0,2',
                'kembalian' => 'required|decimal:0,2',
                'status' => 'required|string',
                'layanan_prioritas_id' => 'required|integer',
                'pelanggan_id' => 'required|integer',
                'gamis_id' => 'nullable|integer',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'integer' => ':attribute harus berupa angka.',
                'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
            ]);

            $validatedTransaksi = $validatorTransaksi->validated();
            $validatedTransaksi['pegawai_id'] = auth()->user()->id;

            $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $getTransaksi->id)->update($validatedTransaksi);

            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->where('id', $request->layanan_prioritas_id)->first();

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $getTransaksi->id)->get();
            foreach ($detailTransaksi as $item) {
                DetailLayananTransaksi::where('detail_transaksi_id', $item->id)->delete();
            }
            DetailTransaksi::where('transaksi_id', $getTransaksi->id)->delete();

            foreach ($request->jenis_pakaian_id as $item => $value) {
                $detailTransaksi = DetailTransaksi::create([
                    'total_pakaian' => $request->total_pakaian[$item],
                    'jenis_satuan' => 'kg',
                    'harga_layanan_akhir' => $request->harga_jenis_layanan_id[$item],
                    'total_biaya_layanan' => $request->total_pakaian[$item] * $request->harga_jenis_layanan_id[$item],
                    'total_biaya_prioritas' => $request->total_pakaian[$item] * $layananPrioritas->harga,
                    'transaksi_id' => $getTransaksi->id,
                ]);

                foreach ($request->jenis_layanan_id[$item] as $layanan) {
                    $jenisPakaian = JenisPakaian::where('cabang_id', $cabang->id)->where('id', $value)->first();
                    $jenisLayanan = JenisLayanan::where('cabang_id', $cabang->id)->where('id', $layanan)->first();
                    $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->where('jenis_pakaian_id', $jenisPakaian->id)->where('jenis_layanan_id', $jenisLayanan->id)->first();
                    DetailLayananTransaksi::create([
                        'harga_jenis_layanan_id' => $hargaLayanan->id,
                        'detail_transaksi_id' => $detailTransaksi->id,
                    ]);
                }
            }

            if ($transaksi) {
                return $transaksi;
            } else {
                return abort(400, 'Transaksi Gagal Dibuat');
            }

        //? Pegawai
        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $getTransaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

            $validatorTransaksi = Validator::make($request->all(), [
                'total_biaya_layanan' => 'required|decimal:0,2',
                'total_biaya_prioritas' => 'required|decimal:0,2',
                'total_bayar_akhir' => 'required|decimal:0,2',
                'jenis_pembayaran' => 'required|string|max:255',
                'bayar' => 'required|decimal:0,2',
                'kembalian' => 'required|decimal:0,2',
                'status' => ['required', Rule::in(StatusTransaksi::cases())],
                'layanan_prioritas_id' => 'required|integer',
                'pelanggan_id' => 'required|integer',
                'gamis_id' => 'nullable|integer',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'integer' => ':attribute harus berupa angka.',
                'decimal' => ':attribute tidak boleh lebih dari :max nol dibelakang koma.',
            ]);

            $validatedTransaksi = $validatorTransaksi->validated();
            $validatedTransaksi['pegawai_id'] = auth()->user()->id;

            $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $getTransaksi->id)->update($validatedTransaksi);

            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->where('id', $request->layanan_prioritas_id)->first();

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $getTransaksi->id)->get();
            foreach ($detailTransaksi as $item) {
                DetailLayananTransaksi::where('detail_transaksi_id', $item->id)->delete();
            }
            DetailTransaksi::where('transaksi_id', $getTransaksi->id)->delete();

            foreach ($request->jenis_pakaian_id as $item => $value) {
                $detailTransaksi = DetailTransaksi::create([
                    'total_pakaian' => $request->total_pakaian[$item],
                    'jenis_satuan' => 'kg',
                    'harga_layanan_akhir' => $request->harga_jenis_layanan_id[$item],
                    'total_biaya_layanan' => $request->total_pakaian[$item] * $request->harga_jenis_layanan_id[$item],
                    'total_biaya_prioritas' => $request->total_pakaian[$item] * $layananPrioritas->harga,
                    'transaksi_id' => $getTransaksi->id,
                ]);

                foreach ($request->jenis_layanan_id[$item] as $layanan) {
                    $jenisPakaian = JenisPakaian::where('cabang_id', $cabang->id)->where('id', $value)->first();
                    $jenisLayanan = JenisLayanan::where('cabang_id', $cabang->id)->where('id', $layanan)->first();
                    $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->where('jenis_pakaian_id', $jenisPakaian->id)->where('jenis_layanan_id', $jenisLayanan->id)->first();
                    DetailLayananTransaksi::create([
                        'harga_jenis_layanan_id' => $hargaLayanan->id,
                        'detail_transaksi_id' => $detailTransaksi->id,
                    ]);
                }
            }

            if ($transaksi) {
                return $transaksi;
            } else {
                return abort(400, 'Transaksi Gagal Dibuat');
            }
        }
    }

    public function editStatusTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi_id)->first(['id', 'status']);
            return $transaksi;

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi_id)->first(['id', 'status']);
            return $transaksi;
        }
    }

    public function updateStatusTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $perbarui = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->id)->update(['status' => $request->status]);

            if ($request->isJadwal) {
                if ($perbarui) {
                    return to_route('transaksi.lurah.cabang.jadwal', $cabang->slug)->with('success', 'Status Transaksi Berhasil Diperbarui');
                } else {
                    return to_route('transaksi.lurah.cabang.jadwal', $cabang->slug)->with('error', 'Status Transaksi Gagal Diperbarui');
                }
            } else {
                if ($perbarui) {
                    return to_route('transaksi.lurah.cabang', $cabang->slug)->with('success', 'Status Transaksi Berhasil Diperbarui');
                } else {
                    return to_route('transaksi.lurah.cabang', $cabang->slug)->with('error', 'Status Transaksi Gagal Diperbarui');
                }
            }
        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $perbarui = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->id)->update(['status' => $request->status]);

            if ($request->isJadwal) {
                if ($perbarui) {
                    return to_route('transaksi.jadwal')->with('success', 'Status Transaksi Berhasil Diperbarui');
                } else {
                    return to_route('transaksi.jadwal')->with('error', 'Status Transaksi Gagal Diperbarui');
                }
            } else {
                if ($perbarui) {
                    return to_route('transaksi')->with('success', 'Status Transaksi Berhasil Diperbarui');
                } else {
                    return to_route('transaksi')->with('error', 'Status Transaksi Gagal Diperbarui');
                }
            }
        }
    }

    public function deleteTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $getTransaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi_id)->first();

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $getTransaksi->id)->get();
            foreach ($detailTransaksi as $item) {
                DetailLayananTransaksi::where('detail_transaksi_id', $item->id)->delete();
            }
            DetailTransaksi::where('transaksi_id', $getTransaksi->id)->delete();
            $hapus = Transaksi::where('id', $request->transaksi_id)->delete();

            if ($hapus) {
                abort(200, 'Transaksi Berhasil Dihapus');
            } else {
                abort(400, 'Transaksi Gagal Dihapus');
            }

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $getTransaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi_id)->first();

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $getTransaksi->id)->get();
            foreach ($detailTransaksi as $item) {
                DetailLayananTransaksi::where('detail_transaksi_id', $item->id)->delete();
            }
            DetailTransaksi::where('transaksi_id', $getTransaksi->id)->delete();
            $hapus = Transaksi::where('id', $request->transaksi_id)->delete();

            if ($hapus) {
                abort(200, 'Transaksi Berhasil Dihapus');
            } else {
                abort(400, 'Transaksi Gagal Dihapus');
            }
        }
    }

    public function ubahJenisPakaian(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $layanan = HargaJenisLayanan::query()
                ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
                ->where('harga_jenis_layanan.cabang_id', $cabang->id)
                ->where('harga_jenis_layanan.jenis_pakaian_id', $request->jenisPakaianId)
                ->select('jl.id', 'jl.nama')->get();
            return $layanan;

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $layanan = HargaJenisLayanan::query()
                ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
                ->where('harga_jenis_layanan.cabang_id', $cabang->id)
                ->where('harga_jenis_layanan.jenis_pakaian_id', $request->jenisPakaianId)
                ->select('jl.id', 'jl.nama')->get();
            return $layanan;
        }
    }

    public function ubahJenisLayanan(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $hargaLayananAkhir = 0;
            foreach ($request->jenisLayananId as $item) {
                $hargaLayanan = HargaJenisLayanan::query()
                    ->where('cabang_id', $cabang->id)
                    ->where('jenis_pakaian_id', $request->jenisPakaianId)
                    ->where('jenis_layanan_id', $item)
                    ->first();
                $hargaLayananAkhir += $hargaLayanan->harga;
            }
            return $hargaLayananAkhir;

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $hargaLayananAkhir = 0;
            foreach ($request->jenisLayananId as $item) {
                $hargaLayanan = HargaJenisLayanan::query()
                    ->where('cabang_id', $cabang->id)
                    ->where('jenis_pakaian_id', $request->jenisPakaianId)
                    ->where('jenis_layanan_id', $item)
                    ->first();
                $hargaLayananAkhir += $hargaLayanan->harga;
            }
            return $hargaLayananAkhir;
        }
    }

    public function hitungTotalBayar(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $hargaLayananId = $request->hargaLayanan;
            $totalPakaian = $request->totalPakaian;
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->where('id', $request->layananPrioritas)->first();

            $biayaLayanan = 0;
            $biayaPrioritas = 0;
            foreach ($hargaLayananId as $item => $value) {
                $biayaLayanan += $value * $totalPakaian[$item];
                $biayaPrioritas += $layananPrioritas->harga * $totalPakaian[$item];
            }
            $totalBayar = $biayaLayanan + $biayaPrioritas;
            return [$biayaLayanan, $biayaPrioritas, $totalBayar];

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $hargaLayananId = $request->hargaLayanan;
            $totalPakaian = $request->totalPakaian;
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->where('id', $request->layananPrioritas)->first();

            $biayaLayanan = 0;
            $biayaPrioritas = 0;
            foreach ($hargaLayananId as $item => $value) {
                $biayaLayanan += $value * $totalPakaian[$item];
                $biayaPrioritas += $layananPrioritas->harga * $totalPakaian[$item];
            }
            $totalBayar = $biayaLayanan + $biayaPrioritas;
            return [$biayaLayanan, $biayaPrioritas, $totalBayar];
        }
    }
}
