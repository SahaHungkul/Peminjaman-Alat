<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
// use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        if($request->has('search')){
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        $users = $query->latest()->paginate(10);
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' =>$request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        ActivityLog::record('Tambah User','Menambahkan user baru'. $user->name. '(' . $user->role . ')' );

        DB::commit();
        return redirect()->route('users.index')->with('success','User Berhasil ditambahkan');
        } catch (\Exception $e){
            DB::rollBack();
            Log::error('gagal store user:' . $e->getMessage());
            return redirect()->back()->with('error','Terjadi Kesalahan sistem. Silahkan Coba lagi.')->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        DB::beginTransaction();
        try{
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);
        $data = [
            'name' => $request->name,
            'email' =>$request->email,
            'role' => $request->role,
          ];
            if($request->filled('password')){
                $data['password'] = Hash::make($request->password());
            }

            $user->update($data);

        ActivityLog::record('Update User','Memperbarui data user '. $user->name);

        DB::commit();
        return redirect()->route('users.index')->with('success','User Berhasil diperbaharui');
        }catch(\Exception $e){
            DB::rollBack();

            Log::error('gagal update user:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id == Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        DB::beginTransaction();
        try {
            $nama = $user->name;
            $user->delete();

            ActivityLog::record('Hapus User', 'Menghapus user: ' . $nama);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal destroy user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}
