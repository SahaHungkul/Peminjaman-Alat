<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];
    protected $table = 'loans';

    protected $casts = [
        'tanggal_kembali_aktual' => 'datetime',
        'tanggal_kembali_rencana' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function tool(){
        return $this->belongsTo(Tools::class);
    }
    public function petugas(){
        return $this->belongsTo(User::class,'petugas_id');
    }
}
