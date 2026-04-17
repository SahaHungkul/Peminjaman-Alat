<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;

class Loan extends Model
{
    protected $guarded = [];
    protected $table = 'loans';

    protected $casts = [
        'tanggal_kembali_aktual' => 'datetime',
        'tanggal_kembali_rencana' => 'datetime',
        'tanggal_pinjam' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tool(){
        return $this->belongsTo(Tools::class,'tool_id');
    }

    public function petugas(){
        return $this->belongsTo(User::class,'petugas_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latest();
}
    public function getDendaSaatIniAttribute(){
        if($this->status == 'kembali'){
            return (float) $this->denda;
        }

        $rencana = $this->tanggal_kembali_rencana->startOfDay();
        $sekarang = now()->startOfDay();

        if($sekarang->gt($rencana)){
            $hariTerlambat = $sekarang->diffInDays($rencana);

            $tarifDenda = $this->tool->denda_per_hari ?? 5000;
            return ($tarifDenda * $this->qty) * abs($hariTerlambat);
        }
        return 0;
    }
}
