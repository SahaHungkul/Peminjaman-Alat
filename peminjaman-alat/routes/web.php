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
    Route::resource('admin/loans',AdminLoanController::class);
    route::resource('admin/returns',AdminReturnController::class);
    Route::get('/admin/logs',function(){
        $logs = ActivityLog::with('user')->latest()->get();
        return view('admin.logs',compact('logs'));
    });
});

Route::get('/petugas/dashboard',[PetugasController::class,'index']);
Route::middleware(['auth','role:petugas'])->group(function(){
    Route::post('/petugas/approve/{id}',[PetugasController::class,'approve']);
    Route::post('/petugas/return/{id}',[PetugasController::class,'processReturn']);
    Route::get('/petugas/laporan',[PetugasController::class,'report']);
});
Route::middleware(['auth','role:peminjam'])->group(function(){
    Route::get('/peminjam/dashboard',[PeminjamController::class,'index']);
    Route::post('/peminjam/ajukan',[PeminjamController::class,'store']);
    Route::get('/peminjam/riwayat',[PeminjamController::class,'history']);
});
