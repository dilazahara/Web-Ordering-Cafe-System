<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'qty',
        'price',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ✅ Tambah ini
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}