<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UMRController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::group(['middleware' => 'guest'], function() {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAttempt'])->name('login.attempt');
});

Route::group([
    'middleware' => ['auth'],
], function() {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group([
        'prefix' => 'cabang',
        'middleware' => ['role:lurah'],
    ], function() {

        Route::get('/', [CabangController::class, 'index'])->name('cabang');
        Route::post('/tambah', [CabangController::class, 'store'])->name('cabang.store');
        Route::get('/lihat', [CabangController::class, 'show'])->name('cabang.show');
        Route::get('/ubah', [CabangController::class, 'edit'])->name('cabang.edit');
        Route::post('/ubah', [CabangController::class, 'update'])->name('cabang.update');
        Route::post('/hapus', [CabangController::class, 'delete'])->name('cabang.delete');
        Route::get('/trash', [CabangController::class, 'trash'])->name('cabang.trash');
        Route::post('/pulihkan', [CabangController::class, 'restore'])->name('cabang.restore');
        Route::post('/hapus-permanen', [CabangController::class, 'destroy'])->name('cabang.destroy');
    });

    Route::group([
        'prefix' => 'umr',
        'middleware' => ['role:lurah'],
    ], function() {

        Route::get('/', [UMRController::class, 'index'])->name('umr');
        Route::post('/tambah', [UMRController::class, 'store'])->name('umr.store');
        Route::get('/lihat', [UMRController::class, 'show'])->name('umr.show');
        Route::get('/ubah', [UMRController::class, 'edit'])->name('umr.edit');
        Route::post('/ubah', [UMRController::class, 'update'])->name('umr.update');
        Route::post('/hapus', [UMRController::class, 'delete'])->name('umr.delete');
    });

    Route::group([
        'prefix' => 'user',
        'middleware' => ['role:lurah|manajer_laundry'],
    ], function() {

        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::get('/{cabang:slug}', [UserController::class, 'indexCabang'])->name('user.cabang');
    });

    Route::post('/laundry/logout', [AuthController::class, 'logout'])->name('logout');
});

require __DIR__.'/auth.php';
