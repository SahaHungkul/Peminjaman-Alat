<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Loan;
use App\Models\Tools;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use function Laravel\Prompts\error;

class PetugasController extends Controller
{
    public function index(){
        $loans = Loan::where('status', 'pending')->with(['user', 'tool'])->paginate(5);
        $activeLoans = Loan::whereIn('status', ['disetujui','menunggu_konfirmasi'])->with(['user', 'tool'])->paginate(5);

        $sudahDikembalikan = Loan::where('status', 'kembali')->with(['user', 'tool'])->latest()->paginate(5);

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
        $tool->decrement('stok', $loan->qty);

        DB::commit();
        return back()->with('success','Peminjaman Disetujui');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal approve:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }
    }

    public function reject($id){
        DB::beginTransaction();
        try{
            $reject = Loan::findOrFail($id);

            $reject->update([
                'status' => 'ditolak',
                'petugas_id' =>Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', 'Peminjaman Ditolak');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal tolak: ' . $e->getMessage());
            return redirect()->back()->with('error','terjadi kesalahan Sistem.')->withInput();
        }
    }

    public function processReturn(Request $request, $id){

        DB::beginTransaction();
        try{
            $request->validate([
                // 'denda' => 'nullable|integer|min:0',
                'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $loan = Loan::findOrFail($id);

            $path = null;

            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('gambar', 'public');
            }

            $total = $loan->denda_saat_ini;
            $loan->update([
                'status' => 'kembali',
                'tanggal_kembali_aktual' =>now(),
                'denda' => $total,
                'status_denda' => $total > 0 ? 'belum_bayar' : 'tidak_ada',
                'petugas_id' => Auth::id(),
                'gambar' => $path,
            ]);

            $loan->tool->increment('stok', $loan->qty);

            DB::commit();
            return back()->with('success','Alat telah dikembalikan.');
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal memproses pengembalian:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }
    }

    public function konfirmasiBayar($id){
        DB::beginTransaction();
        try{
        $loan = Loan::findOrFail($id);

        if($loan->denda <= 0 || $loan->status_denda == 'tidak_ada'){
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


    public function report(Request $request){
        $loans = Loan::with(['user','tool'])->get();

        if($request->has('export') && $request->export == 'pdf'){
            $pdf = Pdf::loadView('petugas.laporan',compact('loans'));
            return $pdf->download('laporan.pdf');
        }
        return view('petugas.laporan',compact('loans'));
    }

    public function pdf(){
        $data = Loan::all();
        $pdf = Pdf::loadView('pdf.laporan_template', compact('data'));
    }
}
