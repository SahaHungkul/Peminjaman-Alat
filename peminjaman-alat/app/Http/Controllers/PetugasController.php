<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tools;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetugasController extends Controller
{
    public function index(){
        $loans = Loan::where('status', 'pending')->with(['user', 'tool'])->get();
        $activeLoans = Loan::where('status', 'disetujui')->with(['user', 'tool'])->get();

        $sudahDikembalikan = Loan::where('status', 'kembali')->with(['user', 'tool'])->get();

        return view('petugas.dashboard',compact('loans','activeLoans','sudahDikembalikan'));
    }

    public function approve($id){
        DB::beginTransaction();
        try{
        $loan = Loan::findOrFail($id);

        $loan->update([
            'status' => 'disetujui',
            'petugas_id' => Auth::id(),
            'tanggal_kembali_aktual' =>now()
        ]);

        $tool = Tools::find($loan->tool_id);
        $tool->decrement('stok');

        DB::commit();
        return back()->with('success','Peminjaman Disetujui');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal approve:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }
    }

    public function processReturn(Request $request, $id){

        DB::beginTransaction();
        try{
            $request->validate([
                'denda' => 'nullable|integer|min:0'
            ]);

            $loan = Loan::findOrFail($id);

            $denda = $request->denda ?? 0;
            $loan->update([
                'status' => 'kembali',
                'tanggal_kembali_aktual' =>now(),
                'denda' => $denda,
                'status_denda' => $denda > 0 ? 'belum_bayar' : 'tidak_ada',
            ]);

            $tool = Tools::find($loan->tool_id);
            $tool->increment('stok');

            DB::commit();
            return back()->with('success','Alat telah dikembalikan.');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal memproses pengembalian:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }
    }

    public function report(Request $request){
        $loans = Loan::with(['user','tool'])->get();
        return view('petugas.laporan',compact('loans'));
    }
}
