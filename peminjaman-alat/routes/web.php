<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin/dashboard',[AdminController::class,'index']);
route::get('/login',[AuthController::class,'showLoginForm'])->name('login');
