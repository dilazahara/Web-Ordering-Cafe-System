<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'queue_number',
        'table_number',
        'customer_name',
        'order_type',        // ✅ TAKE AWAY: 'dine_in' atau 'take_away'
        'payment_method',
        'payment_status',        // ✅ FIX: status keuangan murni: pending|paid|failed
        'payment_type',          // ✅ FIX: payment_type asli dari Midtrans (bank_transfer, gopay, qris, dll)
        'payment_channel',       // ✅ FIX: channel spesifik (bca, bni, bri, gopay, shopeepay, qris, dst)
        'payment_method_label',  // ✅ FIX: label deskriptif (mis. "BCA Virtual Account")
        'midtrans_order_id',
        'snap_token',
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

    /**
     * Helper: Cek apakah pesanan ini adalah Take Away.
     */
    public function isTakeAway(): bool
    {
        return ($this->order_type ?? 'dine_in') === 'take_away';
    }
}