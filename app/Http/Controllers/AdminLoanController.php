<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Loan;
use App\Models\Tools;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with('user','tool')->latest()->paginate(10);
        return view('admin.loans.index', compact('loans'));
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
            'qty' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date',
            'status' => 'required'
        ]);

        DB::beginTransaction();
        try {

            $tool = Tools::findOrFail($request->tool_id);

            if ($request->status == 'disetujui' && $tool->stok < $request->qty) {
                return back()->with('error', 'Stok alat kosong, tidak bisa set status Disetujui.');
            }

            Loan::create([
                'user_id' => $request->user_id,
                'tool_id' => $request->tool_id,
                'qty' => $request->qty,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'status' => $request->status,
                'petugas_id' => Auth::id()
            ]);

            if ($request->status == 'disetujui') {
                $tool->decrement('stok', $request->qty);
            }

            ActivityLog::record('Create Loan', 'Admin membuat data pinjaman baru.');

            DB::commit();

            return redirect()->route('admin.loans.index')->with('success', 'Data pinjaman berhasil dibuat.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal store peminjaman manual: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
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
        $users = User::where('role','peminjam')->get();
        $tools = Tools::all();

        return view('admin.loans.edit', compact('loan','users','tools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required',
            'tool_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required'
        ]);

        DB::beginTransaction();
        try {
        $loan = Loan::findOrFail($id);
        $tool = Tools::findOrFail($request->tool_id);

            if ($loan->status == 'pending' && $request->status == 'disetujui') {
                if ($tool->stok < 1) {
                    return back()->with('error', 'Stok alat kosong, tidak bisa set status Disetujui.');
                }

                $tool->decrement('stok', $loan->qty);
            } elseif ($loan->status == 'disetujui' && $request->status == 'kembali') {
                $tool->increment('stok', $loan->qty);
                $request->merge(['tanggal_kembali_aktual' => now()]);
            } elseif ($loan->status == 'disetujui' && $request->status == 'pending') {
                $tool->increment('stok', $loan->qty);
            } elseif($loan->status == 'kembali' && $request->status == 'disetujui'){
                $tool->decrement('stok', $loan->qty);
            }

            $loan->update([
                'user_id' => $request->user_id,
                'tool_id' => $request->tool_id,
                'qty' => $request->qty,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'status' => $request->status,
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual ?? $loan->tanggal_kembali_aktual
            ]);

            DB::commit();

            return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil diperbarui');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal update peminjaman manual: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $loan = Loan::findOrFail($id);

            if ($loan->status == 'disetujui') {
                $loan->tool->increment('stok');
            }

            $loan->delete();

            DB::commit();

            return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman dihapus');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal destroy peminjaman manual: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }
}
