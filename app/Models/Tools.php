<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    protected $guarded = [];
    protected $table = 'tools';
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function loans(){
        return $this->hasMany(Loan::class);
    }
}
