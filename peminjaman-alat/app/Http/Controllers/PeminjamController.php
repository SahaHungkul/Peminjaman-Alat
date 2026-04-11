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

use function Symfony\Component\Clock\now;

class PeminjamController extends Controller
{
    public function index(Request $request) {

        $query = Tools::with('category');

        if($request->filled('search')){
            $search = $request->search;
            $query->where('nama_alat', 'like', '%' . $search . '%')
                ->orWhereHas('category', function($q) use ($search) {
                    $q->where('nama_kategori', 'like', '%' . $search . '%');
                });
        }

        $tools = $query->latest()->paginate(10)->withQueryString();
        return view('peminjam.dashboard', compact('tools'));
    }

    public function store(Request $request){
        DB::beginTransaction();
        try{
         $tool = Tools::find($request->tool_id);
        if($tool->stok > 0) {
            Loan::create([
                'user_id' => Auth::id(),
                'tool_id' => $request->tool_id,
                'tanggal_pinjam' => now(),
                'tanggal_kembali_rencana' => $request->tanggal_kembali,
                'status' => 'pending'
            ]);
            ActivityLog::record('Tambah Alat', 'Menambahkan alat baru: ' . $request->nama_alat);

            DB::commit();
            // Opsional: Kurangi stok langsung atau saat disetujui (tergantung logika bisnis)
            return back()->with('success', 'Pengajuan berhasil, menunggu persetujuan.');
        }
        }catch(Exception $e){
            DB::rollBack();

            Log::error('gagal Store peminjaman:' . $e->getMessage());
            return redirect()->back()->with('error', 'terjadi kesalahan Sistem.')->withInput();
        }
    }
    public function return($id){
        $loan = Loan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if($loan->status == 'disetujui'){
            $loan->update([
                'status' => 'menunggu_konfirmasi',
                'tanggal_kembali_aktual' => now()
            ]);
            return redirect()->back()->with('success','menunggu pengembalian di konfirmasi');
        }
    }

    public function history() {
        $loans = Loan::where('user_id', Auth::id())
                    ->with('tool')
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('peminjam.riwayat', compact('loans'));
    }
}
