<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];
    protected $table = 'loans';

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
