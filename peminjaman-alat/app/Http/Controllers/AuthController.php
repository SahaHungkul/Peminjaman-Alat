<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function ShowLoginForm(){
        return view('auth.login');
    }

    // validasi untuk login
    public function login(Request $request)
    {
        DB::beginTransaction();

        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required']
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                ActivityLog::record('Login', 'Pengguna melakukan login');

                DB::commit();

                // Redirect berdasarkan role
                if (Auth::user()->role == 'admin') {
                    return redirect('/admin/dashboard');
                } elseif (Auth::user()->role == 'petugas') {
                    return redirect('/petugas/dashboard');
                }

                return redirect('/peminjam/dashboard');
            }

            DB::commit();

            return back()->withErrors(['email' => 'Login gagal']);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal login: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    public function showRegisterForm(){
        return view('auth.register');
    }

    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);

            ActivityLog::record('Register', 'Pengguna membuat akun baru');

            DB::commit();

            return redirect('/peminjam/dashboard')->with('success', 'Akun berhasil dibuat');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal register: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    // function logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
