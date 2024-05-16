<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $title = "Users Management";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'lurah') {
            $users = User::get();
        } elseif ($userRole == 'manajer_laundry') {
            $users = User::where('cabang_id', auth()->user()->cabang_id)->get();
        }

        $cabang = Cabang::get();

        return view('dashboard.user.index', compact('title', 'users', 'cabang'));
    }

    public function indexCabang(Request $request)
    {
        $title = "Users Management";

        $userRole = auth()->user()->roles[0]->name;
        if (!$userRole == 'lurah') {
            abort(403);
        }

        $cabang = Cabang::where('slug', $request->cabang)->first();
        $users = User::where('cabang_id', $cabang->id)->get();

        $titleCabang = $cabang->nama;

        return view('dashboard.user.index-cabang', compact('title', 'titleCabang', 'users'));
    }
}
