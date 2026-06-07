<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'queue_number',
        'table_number',
        'customer_name',
        'order_type',
        'payment_method',
        'midtrans_order_id', // ← tambah ini
        'snap_token',        // ← tambah ini
        'status',
        'total',
        'note',
        'uang_diterima',
        'confirmed_at',
        'process_at',
        'done_at',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}