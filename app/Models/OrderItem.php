<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'name',
        'qty',
        'price',         // harga final per item (base_price + total addon price)
        'base_price',    // ✅ harga dasar menu sebelum add-on
        'addon_details', // ✅ JSON array add-on yang dipilih
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'addon_details' => 'array', // ✅ otomatis cast ke/dari JSON
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // ✅ Helper: total harga semua add-on untuk item ini
    public function getAddonPriceAttribute(): int
    {
        if (empty($this->addon_details)) return 0;
        return (int) array_sum(array_column($this->addon_details, 'price'));
    }
}