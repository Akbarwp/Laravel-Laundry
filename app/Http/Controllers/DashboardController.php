<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\UMR;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";
        $userRole = auth()->user()->roles[0]->name;
        $umr = UMR::where('is_used', 1)->first();

        if ($userRole != 'lurah') {
            $jmlUser = User::where('cabang_id', auth()->user()->cabang_id)->count();
            $jmlCabang = '';

        } else {
            $jmlCabang = Cabang::count();
            $jmlUser = User::count();
        }

        return view('dashboard.index', compact('title', 'userRole', 'jmlCabang', 'jmlUser', 'umr'));
    }
}
