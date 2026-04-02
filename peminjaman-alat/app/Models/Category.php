<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    protected $table = 'categories';

    public function tools (){
        return $this->hasMany(Tools::class);
    }
}
