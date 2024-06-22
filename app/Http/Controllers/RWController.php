<?php

namespace App\Http\Controllers;

use App\Models\RW;
use App\Models\User;
use App\Models\Lurah;
use App\Models\Cabang;
use App\Exports\User2Export;
use App\Imports\User2Import;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RWController extends Controller
{
    public function __construct()
    {
        if (!auth()->user()->roles[0]->name == 'lurah') {
            abort(403);
        }
    }

    public function index()
    {
        $title = "Lurah & RW Management";
        $userRole = auth()->user()->roles[0]->name;

        $lurah = Lurah::join('users as u', 'lurah.user_id', '=', 'u.id')->where('u.deleted_at', null)->orderBy('lurah.created_at', 'asc')->get()->except(auth()->id());
        $rw = RW::join('users as u', 'rw.user_id', '=', 'u.id')->where('u.deleted_at', null)->orderBy('rw.created_at', 'asc')->get();

        $lurahTrash = User::join('lurah as p', 'p.user_id', '=', 'users.id')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();
        $rwTrash = User::join('rw as p', 'p.user_id', '=', 'users.id')->onlyTrashed()->orderBy('p.created_at', 'asc')->get();

        return view('dashboard.user.lurah-rw.index', compact('title', 'lurah', 'rw', 'lurahTrash', 'rwTrash'));
    }

    public function view(Request $request)
    {
        $title = "Detail User";
        $trash = false;
        $userRole = auth()->user()->roles[0]->name;
        $user = User::where('slug', $request->user)->first();

        if ($user == null && $userRole != 'lurah') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug ) {
            return to_route('profile', $user->slug);
        }

        if ($user->getRoleNames()[0] == 'lurah') {
            $profile = Lurah::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'rw') {
            $profile = RW::where('user_id', $user->id)->first();
        }

        return view('dashboard.user.lurah-rw.lihat', compact('title', 'user', 'profile', 'trash'));
    }

    public function create()
    {
        $title = "Tambah User";
        $userRole = auth()->user()->roles[0]->name;
        $role = Role::where('name', 'lurah')->orWhere('name', 'rw')->get();
        return view('dashboard.user.lurah-rw.tambah', compact('title', 'role'));
    }

    public function store(Request $request)
    {
        $validatorUser = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|confirmed',
        ],
        [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'confirmed' => 'Konfirmasi :attribute tidak sama.',
        ]);
        $validatedUser = $validatorUser->validated();

        if ($request->role == 'rw') {
            $validatorProfile = Validator::make($request->all(), [
                'nomor_rw' => 'required|integer',
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
        }
        $validatedProfile = $validatorProfile->validated();

        $user = User::create($validatedUser);
        $user->assignRole($request->role);
        $validatedProfile['user_id'] = $user->id;

        switch ($request->role) {
            case 'lurah':
                $profile = Lurah::create($validatedProfile);
                break;
            case 'rw':
                $profile = RW::create($validatedProfile);
                break;
        }

        if ($user && $profile) {
            return to_route('rw')->with('success', 'User Berhasil Ditambahkan');
        } else {
            return to_route('rw')->with('error', 'User Gagal Ditambahkan');
        }
    }

    public function edit(Request $request)
    {
        $title = "Ubah User";
        $userRole = auth()->user()->roles[0]->name;

        $role = Role::where('name', 'lurah')->orWhere('name', 'rw')->get();
        $cabang = Cabang::where('deleted_at', null)->get();

        $user = User::where('slug', $request->user)->first();
        if ($user == null && $userRole != 'lurah') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug ) {
            return to_route('profile', $user->slug);
        }

        if ($user->getRoleNames()[0] == 'lurah') {
            $profile = Lurah::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'rw') {
            $profile = RW::where('user_id', $user->id)->first();
        }

        return view('dashboard.user.lurah-rw.ubah', compact('title', 'cabang', 'role', 'user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = User::where('slug', $request->user)->first();
        $validatorUser = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
        ],
        [
            'required' => ':attribute harus diisi.',
            'unique' => ':attribute sudah ada, silakan isi yang lain.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
        ]);
        $validatedUser = $validatorUser->validated();
        $validatedUser['slug'] = str()->slug($validatedUser['username']);

        if ($request->role == 'rw') {
            $validatorProfile = Validator::make($request->all(), [
                'nomor_rw' => 'required|integer',
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
        }
        $validatedProfile = $validatorProfile->validated();

        $userUpdate = User::where('id', $user->id)->update($validatedUser);
        $user->removeRole($user->getRoleNames()[0]);
        $user->assignRole($request->role);
        $validatedProfile['user_id'] = $user->id;

        switch ($request->role) {
            case 'lurah':
                $profileUpdate = Lurah::where('user_id', $user->id)->update($validatedProfile);
                break;
            case 'rw':
                $profileUpdate = RW::where('user_id', $user->id)->update($validatedProfile);
                break;
        }

        if ($userUpdate && $profileUpdate) {
            return to_route('rw')->with('success', 'User Berhasil Diperbarui');
        } else {
            return to_route('rw')->with('error', 'User Gagal Diperbarui');
        }
    }

    public function editPassword(Request $request)
    {
        $title = "Ubah Password User";

        $user = User::where('slug', $request->user)->first();
        $userRole = auth()->user()->roles[0]->name;
        if ($user == null && $userRole != 'lurah') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug ) {
            return to_route('profile', $user->slug);
        }
        return view('dashboard.user.lurah-rw.ubahPassword', compact('title', 'user'));
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
            return to_route('rw')->with('success', 'Password User Berhasil Diganti');
        } else {
            return to_route('rw')->with('error', 'Password User Gagal Diganti');
        }
    }

    public function delete(Request $request)
    {
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

        if ($user == null && $userRole != 'lurah') {
            abort(404, 'USER TIDAK DITEMUKAN.');
        } else if ($user->slug == auth()->user()->slug ) {
            return to_route('profile', $user->slug);
        }

        if ($user->getRoleNames()[0] == 'lurah') {
            $profile = Lurah::where('user_id', $user->id)->first();
        } else if ($user->getRoleNames()[0] == 'rw') {
            $profile = RW::where('user_id', $user->id)->first();
        }

        return view('dashboard.user.lurah-rw.lihat', compact('title', 'user', 'profile', 'trash'));
    }

    public function restore(Request $request)
    {
        $pulih = User::where('slug', $request->slug)->restore();
        if ($pulih) {
            abort(200, 'User Berhasil Dihapus');
        } else {
            abort(400, 'User Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $user = User::where('slug', $request->slug)->onlyTrashed()->first();
        $userRole = $user->roles[0]->name;

        if ($userRole == 'lurah') {
            $profile = Lurah::where('user_id', $user->id)->delete();
        } else if ($userRole == 'rw') {
            $profile = RW::where('user_id', $user->id)->delete();
        }

        $user->removeRole($userRole);
        $hapusPermanen = $user->forceDelete();

        if ($hapusPermanen && $profile) {
            abort(200, 'User Berhasil Dihapus');
        } else {
            abort(400, 'User Gagal Dihapus');
        }
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new User2Import, $request->file('impor'));
            return to_route('rw')->with('success', 'User Berhasil Ditambahkan');
        } catch(\Exception $ex) {
            Log::info($ex);
            return to_route('rw')->with('error', 'User Gagal Ditambahkan');
        }
    }

    public function export()
    {
        return Excel::download(new User2Export, 'Data Pegawai Inti '.Carbon::now()->format('d-m-Y').'.xlsx');
    }
}
