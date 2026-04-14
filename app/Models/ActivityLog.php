<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $guarded = [];
    protected $table = 'activity_logs';
    public function user(){
        return $this->BelongsTo(User::class);
    }

    public static function record($action, $desc = null){
        self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'deskripsi' => $desc,
        ]);
    }
}
