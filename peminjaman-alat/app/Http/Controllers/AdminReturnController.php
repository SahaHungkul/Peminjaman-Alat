<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Loan;
use App\Models\Tools;
use Illuminate\Http\Request;

class AdminReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = Loan::with(['user','tool'])
        ->where('status','kembali')
        ->latest('tanggal_kembali_aktual')
        ->paginate(10);
        return view('admin.returns.index',compact('returns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeLoans= Loan::with(['user','tool'])
        ->where('status','disetujui')
        ->latest('')
        ->get();
        return view('admin.returns.create',compact('activeLoans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'denda' => 'nullable|integer'
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        if($loan->status != 'disetujui'){
            return back()->with('error','Data tidak valid atau sudah dikembalikan.');
        }
        $loan->update([
            'status' => 'kembali',
            'tanggal_kembali_aktual' =>now(),
            // 'denda' => $request->denda,
        ]);

        $tool = Tools::findOrFail($loan->tool_id);
        $tool->increment('stok');

        ActivityLog::record('Pengembalian Alat', 'Memproses Pengembalian alat: ' .$tool->nama_alat);
        return redirect()->route('admin.returns.index')->with('success' ,'Alat berhasil dikembalikan.');
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

        if($loan->status != 'kembali'){
            return redirect()->route('admin.loans.index');
        }
        return view('admin.loans.edit',compact('loan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'tanggal_kembali_aktual' => 'required|date'
        ]);

        $loan->update([
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual
        ]);

        return redirect()->route('admin.loans.edit')->with('success','Data Pengembalian diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = Loan::findOrFail($id);

        $loan->delete();

        return redirect()->route('admin.loans.index')->with('success','Data Berhasil dihapus');
    }
}
