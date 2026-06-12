<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meja extends Model
{
    protected $fillable = [
        'nomor_meja',
        'kapasitas',
        'status',
        'qr_token',
    ];

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    /**
     * Generate atau refresh token QR untuk meja ini.
     * Dipanggil saat admin membuat/mengedit meja atau mencetak QR baru.
     * Token lama langsung tidak berlaku setelah ini.
     */
    public function refreshQrToken(): string
    {
        $token = Str::random(40);
        $this->update(['qr_token' => $token]);
        return $token;
    }

    /**
     * Pastikan meja selalu punya token QR.
     * Dipanggil dari controller sebelum menampilkan QR.
     */
    public function getOrCreateQrToken(): string
    {
        if (empty($this->qr_token)) {
            return $this->refreshQrToken();
        }
        return $this->qr_token;
    }
}