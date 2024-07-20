<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Imports\UserImport;
use App\Models\Cabang;
use App\Models\DetailGamis;
use App\Models\Gamis;
use App\Models\Lurah;
use App\Models\ManajerLaundry;
use App\Models\PegawaiLaundry;
use App\Models\PIC;
use App\Models\RW;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $title = "Users Management";
        $userRole = auth()->user()->roles[0]->name;
        $cabang = Cabang::get();
        $role = Role::get();

        if ($userRole == 'lurah' || $userRole == 'pic') {
            $manajer = ManajerLaundry::query()
                ->join('users as u', 'manajer_laundry.user_id', '=', 'u.id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('manajer_laundry.*', 'u.*', 'c.nama as nama_cabang')
                ->where('u.deleted_at', null)
                ->orderBy('manajer_laundry.created_at', 'asc')->get();

            $pegawai = PegawaiLaundry::query()
                ->join('users as u', 'pegawai_laundry.user_id', '=', 'u.id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('pegawai_laundry.*', 'u.*', 'c.nama as nama_cabang')
                ->where('u.deleted_at', null)
                ->orderBy('pegawai_laundry.created_at', 'asc')->get();

            $gamis = DetailGamis::query()
                ->join('users as u', 'detail_gamis.user_id', '=', 'u.id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('detail_gamis.*', 'u.*', 'c.nama as nama_cabang')
                ->where('u.deleted_at', null)
                ->orderBy('detail_gamis.created_at', 'asc')->get();

            $manajerTrash = User::join('manajer_laundry as p', 'p.user_id', '=', 'users.id')->join('cabang as c', 'c.id', '=', 'users.cabang_id')->select('users.*', 'p.*', 'c.nama as nama_cabang')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();
            $pegawaiTrash = User::join('pegawai_laundry as p', 'p.user_id', '=', 'users.id')->join('cabang as c', 'c.id', '=', 'users.cabang_id')->select('users.*', 'p.*', 'c.nama as nama_cabang')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();
            $gamisTrash = User::join('detail_gamis as p', 'p.user_id', '=', 'users.id')->join('cabang as c', 'c.id', '=', 'users.cabang_id')->select('users.*', 'p.*', 'c.nama as nama_cabang')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();

            return view('dashboard.user.index', compact('title', 'cabang', 'role', 'manajer', 'pegawai', 'gamis', 'manajerTrash', 'pegawaiTrash', 'gamisTrash'));

        } elseif ($userRole == 'manajer_laundry') {
            $cabangId = auth()->user()->cabang_id;
            $pegawai = PegawaiLaundry::query()
                ->join('users as u', 'pegawai_laundry.user_id', '=', 'u.id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('pegawai_laundry.*', 'u.*', 'c.nama as nama_cabang')
                ->where('u.cabang_id', $cabangId)
                ->where('u.deleted_at', null)
                ->orderBy('pegawai_laundry.created_at', 'asc')->get();

            $gamis = DetailGamis::query()
                ->join('users as u', 'detail_gamis.user_id', '=', 'u.id')
                ->join('cabang as c', 'c.id', '=', 'u.cabang_id')
                ->select('detail_gamis.*', 'u.*', 'c.nama as nama_cabang')
                ->where('u.cabang_id', $cabangId)
                ->where('u.deleted_at', null)
                ->orderBy('detail_gamis.created_at', 'asc')->get();

            $pegawaiTrash = User::join('pegawai_laundry as p', 'p.user_id', '=', 'users.id')->join('cabang as c', 'c.id', '=', 'users.cabang_id')->where('users.cabang_id', $cabangId)->select('users.*', 'p.*', 'c.nama as nama_cabang')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();
            $gamisTrash = User::join('detail_gamis as p', 'p.user_id', '=', 'users.id')->join('cabang as c', 'c.id', '=', 'users.cabang_id')->where('users.cabang_id', $cabangId)->select('users.*', 'p.*', 'c.nama as nama_cabang')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();

            return view('dashboard.user.index', compact('title', 'cabang', 'role', 'pegawai', 'gamis', 'pegawaiTrash', 'gamisTrash'));
        }
    }

    public function view(Request $request)
    {
        $title = "Detail User";
        $trash = false;
        $userRole = auth()->user()->roles[0]->name;
        $user = User::where('slug', $request->user)->first();

        if ($user == null || $user->cabang_id != auth()->user()->cabang_id && $userRole != 'lurah' && $userRole != 'pic') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug ) {
            return to_route('profile', $user->slug);
        }

        if ($user->getRoleNames()[0] == 'lurah') {
            $profile = Lurah::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'rw') {
            $profile = RW::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'manajer_laundry') {
            $profile = ManajerLaundry::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'pegawai_laundry') {
            $profile = PegawaiLaundry::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'gamis') {
            $profile = DetailGamis::where('user_id', $user->id)->first();
        }

        return view('dashboard.user.lihat', compact('title', 'user', 'profile', 'trash'));
    }

    public function create()
    {
        $title = "Tambah User";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        $cabang = Cabang::where('id', auth()->user()->cabang_id)->withTrashed()->first();
        if ($cabang && $cabang->deleted_at) {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        $kkGamis = Gamis::get();
        $isCabang = [false];

        if ($userRole == 'pic') {
            $role = Role::where('name', '!=', 'lurah')->where('name', '!=', 'rw')->where('name', '!=', 'pic')->get();
            $cabang = Cabang::where('deleted_at', null)->get();
        } else if ($userRole == 'manajer_laundry') {
            $role = Role::where('name', '!=', 'lurah')->where('name', '!=', 'manajer_laundry')->where('name', '!=', 'rw')->where('name', '!=', 'pic')->get();
            $cabang = Cabang::where('deleted_at', null)->where('id', auth()->user()->cabang_id)->get();
        }
        return view('dashboard.user.tambah', compact('title', 'cabang', 'role', 'kkGamis', 'isCabang'));
    }

    public function store(Request $request)
    {
        $validatorUser = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|confirmed',
            'cabang_id' => 'nullable|integer',
        ],
        [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'confirmed' => 'Konfirmasi :attribute tidak sama.',
        ]);
        $validatedUser = $validatorUser->validated();

        if ($request->role == 'gamis') {
            $validatorProfile = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|string|max:1|in:L,P',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
                'mulai_kerja' => 'nullable|date',
                'gamis_id' => 'required',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'date' => ':attribute harus berupa tanggal.',
            ]);
            $validatedProfile = $validatorProfile->validated();

        } else {
            $validatorProfile = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|string|max:1|in:L,P',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
                'mulai_kerja' => 'nullable|date',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'date' => ':attribute harus berupa tanggal.',
            ]);
            $validatedProfile = $validatorProfile->validated();
        }

        $user = User::create($validatedUser);
        $user->assignRole($request->role);
        $validatedProfile['user_id'] = $user->id;

        switch ($request->role) {
            case 'manajer_laundry':
                $profile = ManajerLaundry::create($validatedProfile);
                break;
            case 'pegawai_laundry':
                $profile = PegawaiLaundry::create($validatedProfile);
                break;
            case 'gamis':
                $profile = DetailGamis::create($validatedProfile);
                break;
        }

        if ($user && $profile) {
            return to_route('user')->with('success', 'User Berhasil Ditambahkan');
        } else {
            return to_route('user')->with('error', 'User Gagal Ditambahkan');
        }
    }

    public function edit(Request $request)
    {
        $title = "Ubah User";
        $userRole = auth()->user()->roles[0]->name;
        $kkGamis = Gamis::get();

        if ($userRole == 'lurah') {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        $cabang = Cabang::where('id', auth()->user()->cabang_id)->withTrashed()->first();
        if ($cabang && $cabang->deleted_at) {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        if ($userRole == 'pic') {
            $role = Role::where('name', '!=', 'lurah')->where('name', '!=', 'rw')->where('name', '!=', 'pic')->get();
            $cabang = Cabang::where('deleted_at', null)->get();
        } else if ($userRole == 'manajer_laundry') {
            $role = Role::where('name', '!=', 'lurah')->where('name', '!=', 'manajer_laundry')->where('name', '!=', 'rw')->where('name', '!=', 'pic')->get();
            $cabang = Cabang::where('deleted_at', null)->where('id', auth()->user()->cabang_id)->get();
        }

        $user = User::where('slug', $request->user)->first();
        if ($user == null || $user->cabang_id != auth()->user()->cabang_id && $userRole != 'pic') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug ) {
            return to_route('profile', $user->slug);
        }

        if ($user->getRoleNames()[0] == 'lurah') {
            $profile = Lurah::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'pic') {
            $profile = PIC::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'rw') {
            $profile = RW::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'manajer_laundry') {
            $profile = ManajerLaundry::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'pegawai_laundry') {
            $profile = PegawaiLaundry::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'gamis') {
            $profile = DetailGamis::where('user_id', $user->id)->first();
        }

        return view('dashboard.user.ubah', compact('title', 'cabang', 'role', 'kkGamis', 'user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = User::where('slug', $request->user)->first();
        $validatorUser = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
            'cabang_id' => 'nullable|integer',
        ],
        [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
        ]);
        $validatedUser = $validatorUser->validated();
        $validatedUser['slug'] = str()->slug($validatedUser['username']);

        if ($request->role == 'gamis') {
            $validatorProfile = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|string|max:1|in:L,P',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
                'mulai_kerja' => 'nullable|date',
                'selesai_kerja' => 'nullable|date',
                'gamis_id' => 'required',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'date' => ':attribute harus berupa tanggal.',
            ]);
            $validatedProfile = $validatorProfile->validated();

        } else {
            $validatorProfile = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|string|max:1|in:L,P',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
                'mulai_kerja' => 'nullable|date',
                'selesai_kerja' => 'nullable|date',
            ],
            [
                'required' => ':attribute harus diisi.',
                'max' => ':attribute tidak boleh lebih dari :max karakter.',
                'date' => ':attribute harus berupa tanggal.',
            ]);
            $validatedProfile = $validatorProfile->validated();
        }

        $userUpdate = User::where('id', $user->id)->update($validatedUser);
        $user->removeRole($user->getRoleNames()[0]);
        $user->assignRole($request->role);
        $validatedProfile['user_id'] = $user->id;

        switch ($request->role) {
            case 'manajer_laundry':
                if (DetailGamis::where('user_id', $user->id)->first()) {
                    DetailGamis::where('user_id', $user->id)->delete();
                    $profileUpdate = ManajerLaundry::create($validatedProfile);

                } elseif (PegawaiLaundry::where('user_id', $user->id)->first()) {
                    PegawaiLaundry::where('user_id', $user->id)->delete();
                    $profileUpdate = ManajerLaundry::create($validatedProfile);

                } else {
                    $profileUpdate = ManajerLaundry::where('user_id', $user->id)->update($validatedProfile);
                }
                break;
            case 'pegawai_laundry':
                if (DetailGamis::where('user_id', $user->id)->first()) {
                    DetailGamis::where('user_id', $user->id)->delete();
                    $profileUpdate = PegawaiLaundry::create($validatedProfile);

                } elseif (ManajerLaundry::where('user_id', $user->id)->first()) {
                    ManajerLaundry::where('user_id', $user->id)->delete();
                    $profileUpdate = PegawaiLaundry::create($validatedProfile);

                } else {
                    $profileUpdate = PegawaiLaundry::where('user_id', $user->id)->update($validatedProfile);
                }
                break;
            case 'gamis':
                if (PegawaiLaundry::where('user_id', $user->id)->first()) {
                    PegawaiLaundry::where('user_id', $user->id)->delete();
                    $profileUpdate = DetailGamis::create($validatedProfile);

                } elseif (ManajerLaundry::where('user_id', $user->id)->first()) {
                    ManajerLaundry::where('user_id', $user->id)->delete();
                    $profileUpdate = DetailGamis::create($validatedProfile);

                } else {
                    $profileUpdate = DetailGamis::where('user_id', $user->id)->update($validatedProfile);
                }
                break;
        }

        if ($userUpdate && $profileUpdate) {
            return to_route('user')->with('success', 'User Berhasil Diperbarui');
        } else {
            return to_route('user')->with('error', 'User Gagal Diperbarui');
        }
    }

    public function editPassword(Request $request)
    {
        $title = "Ubah Password User";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        $cabang = Cabang::where('id', auth()->user()->cabang_id)->withTrashed()->first();
        if ($cabang && $cabang->deleted_at) {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        $user = User::where('slug', $request->user)->first();
        if ($user == null || $user->cabang_id != auth()->user()->cabang_id && $userRole != 'pic') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug ) {
            return to_route('profile', $user->slug);
        }
        return view('dashboard.user.ubahPassword', compact('title', 'user'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            // 'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ],
        [
            'required' => ':attribute harus diisi.',
            // 'current_password' => 'Password lama salah.',
            'confirmed' => 'Konfirmasi :attribute tidak sama.',
            'min' => 'minimal :min karakter.',
        ]);

        $updatePassword = User::where('slug', $request->slug)->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($updatePassword) {
            return to_route('user')->with('success', 'Password User Berhasil Diganti');
        } else {
            return to_route('user')->with('error', 'Password User Gagal Diganti');
        }
    }

    public function delete(Request $request)
    {
        $cabang = Cabang::where('id', auth()->user()->cabang_id)->withTrashed()->first();
        if ($cabang && $cabang->deleted_at) {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        $hapus = User::where('slug', $request->slug)->delete();
        if ($hapus) {
            abort(200, 'User Berhasil Dihapus');
        } else {
            abort(400, 'User Gagal Dihapus');
        }
    }

    public function trash(Request $request)
    {
        $title = "Detail User Trash";
        $trash = true;
        $userRole = auth()->user()->roles[0]->name;
        $user = User::where('slug', $request->user)->onlyTrashed()->first();

        if ($user == null || $user->cabang_id != auth()->user()->cabang_id && $userRole != 'lurah' && $userRole != 'pic') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug ) {
            return to_route('profile', $user->slug);
        }

        if ($user->getRoleNames()[0] == 'lurah') {
            $profile = Lurah::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'pic') {
            $profile = PIC::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'rw') {
            $profile = RW::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'manajer_laundry') {
            $profile = ManajerLaundry::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'pegawai_laundry') {
            $profile = PegawaiLaundry::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'gamis') {
            $profile = DetailGamis::where('user_id', $user->id)->first();
        }

        return view('dashboard.user.lihat', compact('title', 'user', 'profile', 'trash'));
    }

    public function restore(Request $request)
    {
        $cabang = Cabang::where('id', auth()->user()->cabang_id)->withTrashed()->first();
        if ($cabang && $cabang->deleted_at) {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        $pulih = User::where('slug', $request->slug)->restore();
        if ($pulih) {
            abort(200, 'User Berhasil Dihapus');
        } else {
            abort(400, 'User Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $cabang = Cabang::where('id', auth()->user()->cabang_id)->withTrashed()->first();
        if ($cabang && $cabang->deleted_at) {
            abort(403, 'USER DOES NOT HAVE PERMISSION.');
        }

        $user = User::where('slug', $request->slug)->onlyTrashed()->first();
        $userRole = $user->roles[0]->name;

        if ($userRole == 'lurah') {
            $profile = Lurah::where('user_id', $user->id)->delete();
        } else if ($userRole == 'pic') {
            $profile = PIC::where('user_id', $user->id)->delete();
        } else if ($userRole == 'rw') {
            $profile = RW::where('user_id', $user->id)->delete();
        } else if ($userRole == 'manajer_laundry') {
            $profile = ManajerLaundry::where('user_id', $user->id)->delete();
        } else if ($userRole == 'pegawai_laundry') {
            $profile = PegawaiLaundry::where('user_id', $user->id)->delete();
        } else if ($userRole == 'gamis') {
            $profile = DetailGamis::where('user_id', $user->id)->delete();
        }

        $user->removeRole($userRole);
        $hapusPermanen = $user->forceDelete();

        if ($hapusPermanen && $profile) {
            abort(200, 'User Berhasil Dihapus');
        } else {
            abort(400, 'User Gagal Dihapus');
        }
    }

    public function indexCabang(Request $request)
    {
        $title = "Users Management";

        $userRole = auth()->user()->roles[0]->name;
        if ($userRole != 'lurah' && $userRole != 'pic') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::where('slug', $request->cabang)->withTrashed()->first();
        if ($cabang == null || $cabang->deleted_at) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $role = Role::get();
        $users = User::where('cabang_id', $cabang->id)->get();

        $manajer = ManajerLaundry::join('users as u', 'manajer_laundry.user_id', '=', 'u.id')->where('u.deleted_at', null)->where('u.cabang_id', $cabang->id)->orderBy('manajer_laundry.created_at', 'asc')->get();
        $pegawai = PegawaiLaundry::join('users as u', 'pegawai_laundry.user_id', '=', 'u.id')->where('u.deleted_at', null)->where('u.cabang_id', $cabang->id)->orderBy('pegawai_laundry.created_at', 'asc')->get();
        $gamis = DetailGamis::join('users as u', 'detail_gamis.user_id', '=', 'u.id')->where('u.deleted_at', null)->where('u.cabang_id', $cabang->id)->orderBy('detail_gamis.created_at', 'asc')->get();

        $manajerTrash = User::join('manajer_laundry as p', 'p.user_id', '=', 'users.id')->join('cabang as c', 'c.id', '=', 'users.cabang_id')->where('users.cabang_id', $cabang->id)->select('users.*', 'p.*', 'c.nama as nama_cabang')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();
        $pegawaiTrash = User::join('pegawai_laundry as p', 'p.user_id', '=', 'users.id')->join('cabang as c', 'c.id', '=', 'users.cabang_id')->where('users.cabang_id', $cabang->id)->select('users.*', 'p.*', 'c.nama as nama_cabang')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();
        $gamisTrash = User::join('detail_gamis as p', 'p.user_id', '=', 'users.id')->join('cabang as c', 'c.id', '=', 'users.cabang_id')->where('users.cabang_id', $cabang->id)->select('users.*', 'p.*', 'c.nama as nama_cabang')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();

        return view('dashboard.user.cabang.index-cabang', compact('title', 'cabang', 'role', 'manajer', 'pegawai', 'gamis', 'manajerTrash', 'pegawaiTrash', 'gamisTrash'));
    }

    public function createUserCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;
        if ($userRole != 'pic') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::where('deleted_at', null)->where('slug', $request->cabang)->get();
        if ($cabang->first() == null) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $kkGamis = Gamis::get();
        $role = Role::where('name', '!=', 'lurah')->where('name', '!=', 'rw')->where('name', '!=', 'pic')->get();
        $title = "Tambah User";
        $isCabang = [true, $cabang[0]->nama, $cabang[0]->id];

        return view('dashboard.user.tambah', compact('title', 'cabang', 'role', 'kkGamis', 'isCabang'));
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new UserImport, $request->file('impor'));
            return to_route('user')->with('success', 'User Berhasil Ditambahkan');
        } catch(\Exception $ex) {
            Log::info($ex);
            return to_route('user')->with('error', 'User Gagal Ditambahkan');
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new UserExport($request->cabang), 'Data Pegawai '.Carbon::now()->format('d-m-Y').'.xlsx');
    }
}
