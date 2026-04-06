<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Loan;
use App\Models\Tools;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with('user','tool')->latest()->paginate();
        // dd($loans->first()->tool());
        return view('admin.loans.index',compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil user yang rolenya peminjam saja
        $users = User::where('role', 'peminjam')->get();
        // Ambil semua alat
        $tools = Tools::all();
        return view('admin.loans.create',compact('users','tools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'tool_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required'
        ]);

        $tool = Tools::findOrFail($request->tool_id);
        if($request->status == 'disetujui' && $tool->stok <1){
            return back()->withErrors('error', 'Stok alat kosong, tidak bisa set status Disetujui.');
        }

        Loan::create([
            'user_id' => $request->user_id,
            'tool_id' => $request->tool_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status' => $request->status,
            'petugas_id' => Auth::id()
        ]);

        if($request->status == 'disetujui'){
            $tool->decrement('stok');
        }

        ActivityLog::record('Create Loan','Admin membuat data pinjaman baru.');
        return redirect()->route('loans.index')->with('success','Data pinjaman berhasil dibuat.');
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
        $loan = Loan::findOrFail($id);
        $users = User::where('role','peminjam')->get();
        $tools = Tools::all();

        return view('admin.loans.edit', compact('loan','users','tools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loan = Loan::findOrFail($id);
        $tool = Tools::findOrFail($request->tool_id);

        // logika perubahan stok berdasarkan satatus
        // 1. pending ke setuju (stok berkuran)
        if($loan->status == 'pending' && $request->status =='disteujui'){
            $tool->decrement('stok');
        }
        // 2. disetujui ke kembali (stok bertambah)
        elseif($loan->status == 'disetujui' && $request->status == 'kembali'){
            $tool->increment('stok');
            $request->merge(['tanggal_kembali_aktual' => now()]);
        }
        // 3.disetujui ke pending
        elseif($loan->status == 'disetujui' && $request->status == 'pending'){
            $tool->increment('stol');
        }

        $loan::update([
            'user_id' => $request->user_id,
            'tool_id' => $request->tool_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status' => $request->status,
            'petugas_id' => Auth::id()
        ]);

        return redirect()->route('admin.loans.index')->with('success','Data peminjaman berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = Loan::findOrFail($id);

        if($loan->status =='disetujui'){
            $loan->tools->increment('stok');
        }

        $loan->delete();
        return redirect()->route('admin.loans.index')->with('success','Data peminjaman dihapus');

    }
}
