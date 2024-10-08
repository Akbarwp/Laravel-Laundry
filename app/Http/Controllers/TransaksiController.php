<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use Illuminate\Http\Request;
use App\Enums\JenisPembayaran;
use App\Enums\StatusTransaksi;
use App\Models\DetailTransaksi;
use App\Models\LayananTambahan;
use Illuminate\Validation\Rule;
use App\Models\LayananPrioritas;
use App\Models\HargaJenisLayanan;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLayananTransaksi;
use App\Models\LayananTambahanTransaksi;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah' || $userRole == 'pic') {
            $cabang = Cabang::withTrashed()->orderBy('created_at', 'asc')->get();
            return view('dashboard.transaksi.lurah.index', compact('title', 'cabang'));

        } else {
            $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $isJadwal = false;
            $status = StatusTransaksi::cases();

            if ($userRole == "manajer_laundry") {
                $transaksi = Transaksi::query()
                    ->with(['pegawai' => function($query) {
                        $query->withTrashed();
                    }])
                    ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
                    ->where('cabang_id', $cabang->id)
                    ->orderBy('waktu', 'desc')->get();

                $monitoring = Transaksi::query()
                    ->with('pelanggan')
                    ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                    ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                    ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                    ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                    ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                    ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                    ->select(
                        'transaksi.id as transaksi_id',
                        'transaksi.pelanggan_id',
                        'transaksi.total_bayar_akhir',
                        'dg.nama as nama_gamis',
                        DB::raw("DATE(transaksi.waktu) as tanggal"),
                        DB::raw("SUM(dt.total_pakaian * hjl.harga) as upah_gamis"),
                        'transaksi.total_biaya_layanan_tambahan',
                        'transaksi.konfirmasi_upah_gamis'
                    )
                    ->where('transaksi.cabang_id', $cabang->id)
                    ->where('jl.for_gamis', true)
                    ->where('transaksi.status', 'Selesai')
                    ->groupBy(
                        'transaksi.id',
                        'transaksi.pelanggan_id',
                        'transaksi.total_bayar_akhir',
                        'dg.nama',
                        DB::raw("DATE(transaksi.waktu)"),
                        'transaksi.total_biaya_layanan_tambahan',
                        'transaksi.konfirmasi_upah_gamis'
                    )
                    ->orderBy('transaksi.waktu', 'asc')
                    ->orderBy('transaksi.gamis_id', 'asc')
                    ->get();

            } elseif ($userRole == "pegawai_laundry") {
                $transaksi = Transaksi::query()
                    ->with(['pegawai' => function($query) {
                        $query->withTrashed();
                    }])
                    ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
                    ->where('cabang_id', $cabang->id)
                    ->where('pegawai_id', auth()->user()->id)
                    ->orderBy('waktu', 'desc')->get();

                $monitoring = [];
            }

            return view('dashboard.transaksi.index', compact('title', 'cabang', 'transaksi', 'monitoring', 'isJadwal', 'status'));
        }
    }

    public function indexJadwal()
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = true;
        $status = StatusTransaksi::cases();

        if ($userRole == 'lurah' || $userRole == 'pic' || $userRole == 'rw' || $userRole == 'gamis') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $userCabang = auth()->user()->cabang_id;
        $cabang = Cabang::withTrashed()->where('id', $userCabang)->first();
        if ($cabang->deleted_at) {
            return to_route('transaksi');
        }
        $isJadwal = false;
        $status = StatusTransaksi::cases();

        if ($userRole == "manajer_laundry") {
            $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
            ->where('transaksi.cabang_id', $cabang->id)
            ->where('transaksi.status', '!=', 'Selesai')
            ->where('transaksi.status', '!=', 'Batal')
            ->orderBy('lp.prioritas', 'desc')
            ->orderBy('transaksi.waktu', 'asc')
            ->select('transaksi.*')
            ->get();

        } elseif ($userRole == "pegawai_laundry") {
            $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
            ->where('transaksi.cabang_id', $cabang->id)
            ->where('transaksi.status', '!=', 'Selesai')
            ->where('transaksi.status', '!=', 'Batal')
            ->where('pegawai_id', auth()->user()->id)
            ->orderBy('lp.prioritas', 'desc')
            ->orderBy('transaksi.waktu', 'asc')
            ->select('transaksi.*')
            ->get();
        }

        return view('dashboard.transaksi.jadwal', compact('title', 'cabang', 'transaksi', 'isJadwal', 'status'));
    }

    public function indexCabang(Request $request)
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = false;
        $status = StatusTransaksi::cases();

        if ($userRole != 'lurah' && $userRole != 'pic') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::withTrashed()->where('slug', $request->cabang)->first();
        if ($cabang == null) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
            ->where('cabang_id', $cabang->id)
            ->orderBy('waktu', 'desc')->get();

        $monitoring = Transaksi::query()
                ->with('pelanggan')
                ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
                ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
                ->select(
                    'transaksi.id as transaksi_id',
                    'transaksi.pelanggan_id',
                    'transaksi.total_bayar_akhir',
                    'dg.nama as nama_gamis',
                    DB::raw("DATE(transaksi.waktu) as tanggal"),
                    DB::raw("SUM(dt.total_pakaian * hjl.harga) as upah_gamis"),
                    'transaksi.total_biaya_layanan_tambahan',
                    'transaksi.konfirmasi_upah_gamis'
                )
                ->where('transaksi.cabang_id', $cabang->id)
                ->where('jl.for_gamis', true)
                ->where('transaksi.status', 'Selesai')
                ->groupBy(
                    'transaksi.id',
                    'transaksi.pelanggan_id',
                    'transaksi.total_bayar_akhir',
                    'dg.nama',
                    DB::raw("DATE(transaksi.waktu)"),
                    'transaksi.total_biaya_layanan_tambahan',
                    'transaksi.konfirmasi_upah_gamis'
                )
                ->orderBy('transaksi.waktu', 'asc')
                ->orderBy('transaksi.gamis_id', 'asc')
                ->get();

        return view('dashboard.transaksi.lurah.cabang', compact('title', 'cabang', 'transaksi', 'monitoring', 'isJadwal', 'status'));
    }

    public function indexCabangJadwal(Request $request)
    {
        $title = "Jadwal Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = true;
        $status = StatusTransaksi::cases();

        if ($userRole != 'lurah' && $userRole != 'pic') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::where('slug', $request->cabang)->first();
        if ($cabang == null || $cabang->deleted_at) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
            ->where('transaksi.cabang_id', $cabang->id)
            ->where('transaksi.status', '!=', 'Selesai')
            ->where('transaksi.status', '!=', 'Batal')
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

        if ($userRole == 'lurah' || $userRole == 'pic') {
            $cabang = Cabang::withTrashed()->where('slug', $request->cabang)->first();
            $transaksi = Transaksi::query()
                ->with(['pegawai' => function($query) {
                    $query->withTrashed();
                }])
                ->where('id', $request->transaksi)->where('cabang_id', $cabang->id)->orderBy('waktu', 'asc')->first();

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
            $layananTambahanTransaksi = LayananTambahanTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();

            $userRole = [User::withTrashed()->where('id', $transaksi->pegawai_id)->first()];

            return view('dashboard.transaksi.lurah.lihat', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'isJadwal', 'layananTambahanTransaksi'));

        } else {
            $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = Transaksi::query()
                ->with(['pegawai' => function($query) {
                    $query->withTrashed();
                }])
                ->where('id', $request->transaksi)->first();

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
            $layananTambahanTransaksi = LayananTambahanTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
            return view('dashboard.transaksi.lihat', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'isJadwal', 'layananTambahanTransaksi'));
        }
    }

    public function createTransaksiCabang(Request $request)
    {
        $title = "Tambah Transaksi";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = $request->isJadwal;
        $jenisPembayaran = JenisPembayaran::cases();

        if ($userRole == 'lurah' || $userRole == 'pic') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        if ($userRole == 'lurah' || $userRole == 'pic') {
            $cabang = Cabang::withTrashed()->where('slug', $request->cabang)->first();
            if ($cabang->deleted_at) {
                abort(404, 'FITUR TIDAK DAPAT DIGUNAKAN.');
            }
            $pelanggan = Pelanggan::get();
            $gamis = User::query()
                ->join('detail_gamis as dg', 'users.id', '=', 'dg.user_id')
                ->where('users.cabang_id', $cabang->id)
                ->get();
            $pakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
            $layananTambahan = LayananTambahan::where('cabang_id', $cabang->id)->get();
            return view('dashboard.transaksi.lurah.tambah', compact('title', 'cabang', 'pelanggan', 'gamis', 'pakaian', 'layananPrioritas', 'isJadwal', 'jenisPembayaran', 'layananTambahan'));

        } else {
            $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            if ($cabang->deleted_at) {
                abort(404, 'FITUR TIDAK DAPAT DIGUNAKAN.');
            }
            $pelanggan = Pelanggan::get();
            $gamis = User::query()
                ->join('detail_gamis as dg', 'users.id', '=', 'dg.user_id')
                ->where('users.cabang_id', $cabang->id)
                ->get();
            $pakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
            $layananTambahan = LayananTambahan::where('cabang_id', $cabang->id)->get();
            return view('dashboard.transaksi.tambah', compact('title', 'cabang', 'pelanggan', 'gamis', 'pakaian', 'layananPrioritas', 'isJadwal', 'jenisPembayaran', 'layananTambahan'));
        }
    }

    public function storeTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        //? Lurah
        if ($userRole == 'lurah' || $userRole == 'pic') {
            $cabang = Cabang::where('slug', $request->cabang)->first();

            $validatorTransaksi = Validator::make($request->all(), [
                'total_biaya_layanan' => 'required|decimal:0,2',
                'total_biaya_prioritas' => 'required|decimal:0,2',
                'total_biaya_layanan_tambahan' => 'required|decimal:0,2',
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
            $nota = Carbon::now()->format('His') . "-" . Carbon::now()->format('dmY') . "-" . $cabang->id . $request->pelanggan_id;
            $validatedTransaksi['nota_layanan'] = "layanan-" . $nota;
            $validatedTransaksi['nota_pelanggan'] = "pelanggan-" . $nota;
            $validatedTransaksi['status'] = StatusTransaksi::BARU->value;

            $transaksi = Transaksi::create($validatedTransaksi);

            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->where('id', $request->layanan_prioritas_id)->first();

            foreach ($request->jenis_pakaian_id as $item => $value) {
                $detailTransaksi = DetailTransaksi::create([
                    'total_pakaian' => $request->total_pakaian[$item],
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

            if ($request->layanan_tambahan_id) {
                foreach ($request->layanan_tambahan_id as $item) {
                    LayananTambahanTransaksi::create([
                        'layanan_tambahan_id' => $item,
                        'transaksi_id' => $transaksi->id,
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
                'total_biaya_layanan_tambahan' => 'required|decimal:0,2',
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
            $nota = Carbon::now()->format('His') . "-" . Carbon::now()->format('dmY') . "-" . $cabang->id . $request->pelanggan_id;
            $validatedTransaksi['nota_layanan'] = "layanan-" . $nota;
            $validatedTransaksi['nota_pelanggan'] = "pelanggan-" . $nota;
            $validatedTransaksi['status'] = StatusTransaksi::BARU->value;

            $transaksi = Transaksi::create($validatedTransaksi);

            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->where('id', $request->layanan_prioritas_id)->first();

            foreach ($request->jenis_pakaian_id as $item => $value) {
                $detailTransaksi = DetailTransaksi::create([
                    'total_pakaian' => $request->total_pakaian[$item],
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

            if ($request->layanan_tambahan_id) {
                foreach ($request->layanan_tambahan_id as $item) {
                    LayananTambahanTransaksi::create([
                        'layanan_tambahan_id' => $item,
                        'transaksi_id' => $transaksi->id,
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
        $jenisPembayaran = JenisPembayaran::cases();

        if ($userRole == 'lurah' || $userRole == 'pic') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        if ($userRole == 'lurah' || $userRole == 'pic') {
            $cabang = Cabang::withTrashed()->where('slug', $request->cabang)->first();
            if ($cabang->deleted_at) {
                abort(404, 'FITUR TIDAK DAPAT DIGUNAKAN.');
            }
            $pelanggan = Pelanggan::get();
            $gamis = User::query()
                ->join('detail_gamis as dg', 'users.id', '=', 'dg.user_id')
                ->where('users.cabang_id', $cabang->id)
                ->get();
            $pakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
            $layananTambahan = LayananTambahan::where('cabang_id', $cabang->id)->get();
            $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

            $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->get();
            $layanan = JenisLayanan::where('cabang_id', $cabang->id)->get();

            return view('dashboard.transaksi.lurah.ubah', compact('title', 'cabang', 'status', 'pelanggan', 'gamis', 'pakaian', 'layananPrioritas', 'transaksi', 'layanan', 'hargaLayanan', 'isJadwal', 'jenisPembayaran', 'layananTambahan'));

        } else {
            $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            if ($cabang->deleted_at) {
                abort(404, 'FITUR TIDAK DAPAT DIGUNAKAN.');
            }
            $pelanggan = Pelanggan::get();
            $gamis = User::query()
                ->join('detail_gamis as dg', 'users.id', '=', 'dg.user_id')
                ->where('users.cabang_id', $cabang->id)
                ->get();
            $pakaian = JenisPakaian::where('cabang_id', $cabang->id)->get();
            $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
            $layananTambahan = LayananTambahan::where('cabang_id', $cabang->id)->get();
            $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

            if ($transaksi->status == 'Selesai' && $userRole == 'pegawai_laundry') {
                abort(403, 'Transaksi Ini Tidak Dapat Diubah');
            }

            $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->get();
            $layanan = JenisLayanan::where('cabang_id', $cabang->id)->get();

            return view('dashboard.transaksi.ubah', compact('title', 'cabang', 'status', 'pelanggan', 'gamis', 'pakaian', 'layananPrioritas', 'transaksi', 'layanan', 'hargaLayanan', 'isJadwal', 'jenisPembayaran', 'layananTambahan'));
        }
    }

    public function updateTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        //? Lurah
        if ($userRole == 'lurah' || $userRole == 'pic') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $getTransaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

            $validatorTransaksi = Validator::make($request->all(), [
                'total_biaya_layanan' => 'required|decimal:0,2',
                'total_biaya_prioritas' => 'required|decimal:0,2',
                'total_biaya_layanan_tambahan' => 'required|decimal:0,2',
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
            // $validatedTransaksi['pegawai_id'] = auth()->user()->id;

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

            LayananTambahanTransaksi::where('transaksi_id', $getTransaksi->id)->delete();
            if ($request->layanan_tambahan_id) {
                foreach ($request->layanan_tambahan_id as $item) {
                    LayananTambahanTransaksi::create([
                        'layanan_tambahan_id' => $item,
                        'transaksi_id' => $getTransaksi->id,
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
                'total_biaya_layanan_tambahan' => 'required|decimal:0,2',
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
            // $validatedTransaksi['pegawai_id'] = auth()->user()->id;

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

            LayananTambahanTransaksi::where('transaksi_id', $getTransaksi->id)->delete();
            if ($request->layanan_tambahan_id) {
                foreach ($request->layanan_tambahan_id as $item) {
                    LayananTambahanTransaksi::create([
                        'layanan_tambahan_id' => $item,
                        'transaksi_id' => $getTransaksi->id,
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

        if ($userRole == 'lurah' || $userRole == 'pic') {
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

        if ($userRole == 'lurah' || $userRole == 'pic') {
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

        if ($userRole == 'lurah' || $userRole == 'pic') {
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

        if ($userRole == 'lurah' || $userRole == 'pic') {
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

        if ($userRole == 'lurah' || $userRole == 'pic') {
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

    public function ubahLayananTambahan(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah' || $userRole == 'pic') {
            $cabang = Cabang::where('slug', $request->cabang)->first();
            $hargaLayananAkhir = 0;
            foreach ($request->layananTambahanId as $item) {
                $hargaLayanan = LayananTambahan::query()
                    ->where('cabang_id', $cabang->id)
                    ->where('id', $item)
                    ->first();
                $hargaLayananAkhir += $hargaLayanan->harga;
            }
            return $hargaLayananAkhir;

        } else {
            $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
            $hargaLayananAkhir = 0;
            foreach ($request->layananTambahanId as $item) {
                $hargaLayanan = LayananTambahan::query()
                    ->where('cabang_id', $cabang->id)
                    ->where('id', $item)
                    ->first();
                $hargaLayananAkhir += $hargaLayanan->harga;
            }
            return $hargaLayananAkhir;
        }
    }

    public function hitungTotalBayar(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah' || $userRole == 'pic') {
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
            $totalBayar = $biayaLayanan + $biayaPrioritas + $request->layananTambahan;
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
            $totalBayar = $biayaLayanan + $biayaPrioritas + $request->layananTambahan;
            return [$biayaLayanan, $biayaPrioritas, $totalBayar];
        }
    }

    public function cetakStrukTransaksi(Request $request)
    {
        $title = "Cetak Struk";
        $transaksi = Transaksi::query()
                ->with(['pegawai' => function($query) {
                    $query->withTrashed();
                }])
                ->where('id', $request->transaksi)->first();
        $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
        $layananTambahanTransaksi = LayananTambahanTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
        $cabang = Cabang::where('id', $transaksi->cabang_id)->first();

        // $pdf = Pdf::loadView('dashboard.transaksi.struk.index', [
        //     'judul' => $title,
        //     'transaksi' => $transaksi,
        //     'detailTransaksi' => $detailTransaksi,
        //     'footer' => $title
        // ])
        // ->setPaper('a4', 'potrait');
        // return $pdf->stream();

        return view('dashboard.transaksi.struk.index', compact('title', 'transaksi', 'detailTransaksi', 'cabang', 'layananTambahanTransaksi'));
    }

    public function konfirmasiUpah(Request $request)
    {
        if (!$request->konfirmasi) {
            Transaksi::where('id', $request->transaksi_id)->update([
                'konfirmasi_upah_gamis' => true
            ]);
        } else {
            Transaksi::where('id', $request->transaksi_id)->update([
                'konfirmasi_upah_gamis' => false
            ]);
        }
    }

    public function transaksiGamisHarian()
    {
        $title = "Transaksi Gamis Harian";
        $userRole = auth()->user()->roles[0]->name;
        $isHarian = true;

        if ($userRole != 'gamis') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
        if ($cabang == null) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->where('dg.user_id', auth()->user()->id)
            ->where('transaksi.cabang_id', $cabang->id)
            ->where(DB::raw('DATE(transaksi.waktu)'), Carbon::now()->format('Y-m-d'))
            ->select('transaksi.*')
            ->orderBy('waktu', 'asc')->get();

        $monitoring = Transaksi::query()
            ->with('pelanggan')
            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->select(
                'transaksi.id as transaksi_id',
                'transaksi.pelanggan_id',
                'transaksi.total_bayar_akhir',
                'dg.nama as nama_gamis',
                DB::raw("DATE(transaksi.waktu) as tanggal"),
                DB::raw("SUM(dt.total_pakaian * hjl.harga) as upah_gamis"),
                'transaksi.total_biaya_layanan_tambahan',
                'transaksi.konfirmasi_upah_gamis'
            )
            ->where('transaksi.cabang_id', $cabang->id)
            ->where('jl.for_gamis', true)
            ->where('transaksi.status', 'Selesai')
            ->where('dg.user_id', auth()->user()->id)
            ->where(DB::raw('DATE(transaksi.waktu)'), Carbon::now()->format('Y-m-d'))
            ->groupBy(
                'transaksi.id',
                'transaksi.pelanggan_id',
                'transaksi.total_bayar_akhir',
                'dg.nama',
                DB::raw("DATE(transaksi.waktu)"),
                'transaksi.total_biaya_layanan_tambahan',
                'transaksi.konfirmasi_upah_gamis'
            )
            ->orderBy('transaksi.waktu', 'asc')
            ->orderBy('transaksi.gamis_id', 'asc')
            ->get();

        return view('dashboard.transaksi.gamis.index', compact('title', 'cabang', 'transaksi', 'monitoring', 'isHarian'));
    }

    public function transaksiGamisSemua()
    {
        $title = "Transaksi Gamis";
        $userRole = auth()->user()->roles[0]->name;
        $isHarian = false;

        if ($userRole != 'gamis') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
        if ($cabang == null) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->where('dg.user_id', auth()->user()->id)
            ->where('transaksi.cabang_id', $cabang->id)
            ->select('transaksi.*')
            ->orderBy('waktu', 'asc')->get();

        $monitoring = Transaksi::query()
            ->with('pelanggan')
            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
            ->join('jenis_pakaian as jp', 'jp.id', '=', 'hjl.jenis_pakaian_id')
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->select(
                'transaksi.id as transaksi_id',
                'transaksi.pelanggan_id',
                'transaksi.total_bayar_akhir',
                'dg.nama as nama_gamis',
                DB::raw("DATE(transaksi.waktu) as tanggal"),
                DB::raw("SUM(dt.total_pakaian * hjl.harga) as upah_gamis"),
                'transaksi.total_biaya_layanan_tambahan',
                'transaksi.konfirmasi_upah_gamis'
            )
            ->where('transaksi.cabang_id', $cabang->id)
            ->where('jl.for_gamis', true)
            ->where('transaksi.status', 'Selesai')
            ->where('dg.user_id', auth()->user()->id)
            ->groupBy(
                'transaksi.id',
                'transaksi.pelanggan_id',
                'transaksi.total_bayar_akhir',
                'dg.nama',
                DB::raw("DATE(transaksi.waktu)"),
                'transaksi.total_biaya_layanan_tambahan',
                'transaksi.konfirmasi_upah_gamis'
            )
            ->orderBy('transaksi.waktu', 'asc')
            ->orderBy('transaksi.gamis_id', 'asc')
            ->get();

        return view('dashboard.transaksi.gamis.index', compact('title', 'cabang', 'transaksi', 'monitoring', 'isHarian'));
    }

    public function viewDetailTransaksiGamis(Request $request)
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isHarian = $request->isHarian;

        $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
        $transaksi = Transaksi::query()
            ->with(['pegawai' => function($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama', 'gamis:id,nama'])
            ->join('detail_gamis as dg', 'dg.id', '=', 'transaksi.gamis_id')
            ->where('dg.user_id', auth()->user()->id)
            ->where('transaksi.id', $request->transaksi)
            ->select('transaksi.*')
            ->first();

        $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
        $layananTambahanTransaksi = LayananTambahanTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
        return view('dashboard.transaksi.gamis.lihat', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'layananTambahanTransaksi', 'isHarian'));
    }
}
