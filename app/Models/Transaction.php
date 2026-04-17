<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'loan_id',
        'amount',
        'status',
        'snap_token',
        'payment_method'
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    // Relasi ke Loan
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // Scope untuk transaksi pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope untuk transaksi sukses
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    // Cek apakah transaksi sukses
    public function isSuccess()
    {
        return $this->status === 'success';
    }

    // Cek apakah transaksi pending
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
