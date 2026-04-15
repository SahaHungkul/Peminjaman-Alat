<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Tools;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        // mengambil data statistik untuk kartu dashboard
        $totalUser = User::count();
        $totalAlat = Tools::count();
        $totalStok = Tools::sum('stok');
        $totalKategori = Category::count();

        // menghitungn pinjaman yang sedang berlangsung (status disetujui)
        $sedangDipinjam = Loan::where('status','disetujui')->count();
        // jumlah dikembalikan
        $sudahDikembalikan = Loan::where('status','kembali')->count();
        // data 5 log aktivitas terbaru
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();
        $peminjamTeratas = Loan::select('user_id', DB::raw('count(*) as total_loans'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('total_loans', 'desc')
            ->take(5)
            ->get();
            // Di Controller
        $alatTerpopuler = Loan::select('tool_id', DB::raw('count(*) as total_borrowed'))
            ->with('tool')
            ->groupBy('tool_id')
            ->orderBy('total_borrowed', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard',compact(
            'totalUser',
            'totalAlat',
            'totalStok',
            'totalKategori',
            'sedangDipinjam',
            'sudahDikembalikan',
            'recentLogs',
            'peminjamTeratas',
            'alatTerpopuler'
        ));
    }
    public function log(){
        $recentLog = ActivityLog::with('user')->latest()->paginate(20);
        // dd($recentLog);
        return view('admin.logs' , compact('recentLog'));
    }
}
