<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\AdminReturnController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\UserController;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// no role
Route::get('/', function () {
    if(Auth::check()){
        $role = Auth::user()->role;
        if ($role =='admin') return redirect('/admin/dashboard');
        if ($role =='petugas') return redirect('/petugas/dashboard');
        return redirect('/peminjam/dashboard');
    }
    return view('welcome');
})->name('home');
route::get('/login',[AuthController::class,'showLoginForm'])->name('login');
route::get('/register',[AuthController::class,'showRegisterForm'])->name('register');
route::post('/login',[AuthController::class,'login']);
route::post('/logout',[AuthController::class,'logout'])->name('logout');
route::post('/register',[AuthController::class,'register'])->name('register');

route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/admin/dashboard',[AdminController::class,'index']);
    Route::resource('users',UserController::class);
    route::resource('tools',ToolsController::class);
    route::resource('categories',CategoryController::class);
    Route::resource('admin/loans',AdminLoanController::class)->names('admin.loans');
    route::resource('admin/returns',AdminReturnController::class)->names('admin.returns');
    Route::get('/admin/logs',function(){
        $recentLog = ActivityLog::with('user')->latest()->paginate(20);
        return view('admin.logs',compact('recentLog'));
        });
        });

        Route::middleware(['auth','role:petugas'])->group(function(){
            Route::get('/petugas/dashboard',[PetugasController::class,'index']);
            Route::post('/petugas/approve/{id}',[PetugasController::class,'approve']);
            Route::post('/petugas/reject/{id}',[PetugasController::class,'reject']);
            Route::post('/petugas/return/{id}',[PetugasController::class,'processReturn']);
            Route::get('/petugas/laporan',[PetugasController::class,'report']);
            Route::patch('/petugas/{id}/bayar',[PetugasController::class,'konfirmasibayar'])->name('petugas.bayar');
});
Route::middleware(['auth','role:peminjam'])->group(function(){
    Route::get('/peminjam/dashboard',[PeminjamController::class,'index'])->name('peminjam.dashboard');
    Route::post('/peminjam/ajukan',[PeminjamController::class,'store']);
    Route::get('/peminjam/riwayat',[PeminjamController::class,'history']);
    route::post('/peminjam/return/{id}',[PeminjamController::class,'return'])->name('peminjam.return');
});
