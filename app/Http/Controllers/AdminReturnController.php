<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Loan;
use App\Models\Tools;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        ->latest()
        ->get();
        return view('admin.returns.create',compact('activeLoans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'denda' => 'nullable|integer'
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        if($loan->status != 'disetujui'){
            return back()->with('error','Data tidak valid atau sudah dikembalikan.');
        }
        $denda = $request->denda ?? 0;

        $loan->update([
            'status' => 'kembali',
            'tanggal_kembali_aktual' =>now(),
            'denda' => $denda ,
            'status_denda' => $denda > 0 ? 'belum_bayar' : 'tidak_ada',
        ]);

        $tool = Tools::findOrFail($loan->tool_id);
        $tool->increment('stok');

        ActivityLog::record('Pengembalian Alat', 'Memproses Pengembalian alat: ' .$tool->nama_alat);

        DB::commit();
        return redirect()->route('admin.returns.index')->with('success' ,'Alat berhasil dikembalikan.');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal proses pengembalian:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
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
        $loan = Loan::findOrFail($id);

        if($loan->status != 'kembali'){
            return redirect()->route('admin.returns.index');
        }
        return view('admin.returns.edit',compact('loan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try{
        $loan = Loan::findOrFail($id);

        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'denda' => 'required|integer|min:0',
            'status_denda' => 'required|in:tidak_ada,belum_bayar,lunas'
        ]);

        $loan->update([
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
            'denda' => $request->denda,
            'status_denda' => $request->status_denda,
        ]);

        DB::commit();
        return redirect()->route('admin.returns.index')->with('success','Data Pengembalian diperbarui');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal update pengembalian:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
        $loan = Loan::findOrFail($id);

        $loan->delete();

        DB::commit();
        return redirect()->route('admin.returns.index')->with('success','Data Berhasil dihapus');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal destroy pengembalian:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }
    }
    public function konfirmasiBayar($id){
        DB::beginTransaction();
        try{
        $loan = Loan::findOrFail($id);

        if($loan->denda == 0 || $loan->denda == 'tidak_ada'){
            return back()->with('error','tidak ada denda pada peminjamanini');
        }

        $loan->update([
            'status_denda' => 'lunas'
        ]);
        ActivityLog::record('Pembayaran Denda','Konfirmasi pembayaran denda:' . $loan->tool->nama_alat);

        // return redirect()->route('admin.loans.index')->with('success','denda lunas');
        DB::commit();
        return back()->with('success','denda lunas');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal konfirmasi bayar:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }
    }
}
