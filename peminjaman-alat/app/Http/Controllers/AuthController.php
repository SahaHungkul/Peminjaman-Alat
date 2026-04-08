<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function ShowLoginForm(){
        return view('auth.login');
    }

    // validasi untuk login
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)){
            $request->session()->regenerate();

            // redirect berdasarkan role
            if(Auth::check() && Auth::user()->role == "admin"){
                return redirect('/admin/dashboard');
            }
            if(Auth::check() && Auth::user()->role == "petugas"){
                return redirect('petugas/dashboard');
            }
            if(Auth::attempt($credentials)){
                ActivityLog::record('Login','Pengguna Melakukan Login');
                $request->session()->regenerate();
            }
            return redirect('/peminjam/dashboard');
        }
        return back()->withErrors(['email' => 'Login Gagal']);
    }

    public function showRegisterForm(){
        return view('auth.register');
    }

    public function register(Request $request){
    $request->validate([
        'name'=> 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8|confirmed'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    Auth::login($user);

    return redirect('/peminjam/dashboard')->with('success','Akun berhasil dibuat');

    }

    // function logout
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
