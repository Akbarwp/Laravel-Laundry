<?php

namespace App\Http\Controllers;

use App\Models\DetailGamis;
use App\Models\Lurah;
use App\Models\ManajerLaundry;
use App\Models\PegawaiLaundry;
use App\Models\PIC;
use App\Models\RW;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $title = "Profile User";
        $userRole = auth()->user()->roles[0]->name;
        $user = User::where('slug', $request->user)->first();

        if ($user == null || $user->slug != auth()->user()->slug) {
            abort(404, 'USER TIDAK DITEMUKAN.');
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

        return view('dashboard.profile.index', compact('title', 'user', 'profile'));
    }

    public function edit(Request $request)
    {
        $title = "Ubah Profile User";
        $user = User::where('slug', $request->user)->first();
        if ($user == null || $user->slug != auth()->user()->slug) {
            abort(404, 'USER TIDAK DITEMUKAN.');
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

        return view('dashboard.profile.ubah', compact('title', 'user', 'profile'));
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

        $validatorProfile = Validator::make($request->all(), [
            'foto' => 'nullable|image|file',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:1|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
        ],
        [
            'required' => ':attribute harus diisi.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'date' => ':attribute harus berupa tanggal.',
            'uploaded' => 'Silakan pilih foto lain.',
        ]);
        $validatedProfile = $validatorProfile->validated();

        $userUpdate = User::where('id', $user->id)->update($validatedUser);
        $validatedProfile['user_id'] = $user->id;

        if ($request->file('foto')) {
            $validatedProfile['foto'] = $request->file('foto')->store('photo-profile');
        }

        switch (auth()->user()->roles[0]->name) {
            case 'lurah':
                $lurah = Lurah::where('user_id', $user->id)->first();
                if ($lurah->foto) {
                    Storage::delete($lurah->foto);
                }
                $profileUpdate = $lurah->update($validatedProfile);
                break;
            case 'pic':
                $pic = PIC::where('user_id', $user->id)->first();
                if ($pic->foto) {
                    Storage::delete($pic->foto);
                }
                $profileUpdate = $pic->update($validatedProfile);
                break;
            case 'rw':
                $rw = RW::where('user_id', $user->id)->first();
                if ($rw->foto) {
                    Storage::delete($rw->foto);
                }
                $profileUpdate = $rw->update($validatedProfile);
                break;
            case 'manajer_laundry':
                $manajer = ManajerLaundry::where('user_id', $user->id)->first();
                if ($manajer->foto) {
                    Storage::delete($manajer->foto);
                }
                $profileUpdate = ManajerLaundry::where('user_id', $user->id)->update($validatedProfile);
                break;
            case 'pegawai_laundry':
                $pegawai = PegawaiLaundry::where('user_id', $user->id)->first();
                if ($pegawai->foto) {
                    Storage::delete($pegawai->foto);
                }
                $profileUpdate = PegawaiLaundry::where('user_id', $user->id)->update($validatedProfile);
                break;
            case 'gamis':
                $gamis = DetailGamis::where('user_id', $user->id)->first();
                if ($gamis->foto) {
                    Storage::delete($gamis->foto);
                }
                $profileUpdate = DetailGamis::where('user_id', $user->id)->update($validatedProfile);
                break;
        }

        if ($userUpdate && $profileUpdate) {
            return to_route('profile', $request->user)->with('success', 'User Berhasil Diperbarui');
        } else {
            return to_route('profile', $request->user)->with('error', 'User Gagal Diperbarui');
        }
    }

    public function editPassword(Request $request)
    {
        $title = "Ubah Password User";
        $user = User::where('slug', $request->user)->first();
        if ($user == null || $user->slug != auth()->user()->slug) {
            abort(404, 'USER TIDAK DITEMUKAN.');
        }
        return view('dashboard.profile.ubahPassword', compact('title', 'user'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ],
        [
            'required' => ':attribute harus diisi.',
            'current_password' => 'Password lama salah.',
            'confirmed' => 'Konfirmasi :attribute tidak sama.',
            'min' => 'minimal :min karakter.',
        ]);

        $updatePassword = User::where('slug', $request->slug)->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($updatePassword) {
            return to_route('profile', $request->slug)->with('success', 'Password User Berhasil Diganti');
        } else {
            return to_route('profile', $request->slug)->with('error', 'Password User Gagal Diganti');
        }
    }
}
