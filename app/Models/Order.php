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
        'status',
        'total',
        'note',
        'uang_diterima',
        'confirmed_at',
        'process_at',
        'done_at',
    ];

    // ✅ HAPUS $attributes — ini yang bikin status selalu pending
    // protected $attributes = [
    //     'status' => 'pending',
    //     'payment_method' => 'cash',
    // ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}