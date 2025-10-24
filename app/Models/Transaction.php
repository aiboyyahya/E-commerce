<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_id',
        'address',
        'status',
        'total',
        'notes',
        'payment_method',
        'snap_token',
        'payment_status',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
