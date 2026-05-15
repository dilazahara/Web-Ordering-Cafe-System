<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'aktif',
        'qris_image',
        'nama_rekening',
        'no_rekening',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // ── Accessor: $pm->is_active ──────────────────────
    public function getIsActiveAttribute(): bool
    {
        return (bool) $this->aktif;
    }

    // ── Mutator: $pm->is_active = true ───────────────
    public function setIsActiveAttribute($value): void
    {
        $this->aktif = $value;
    }

    // ── Accessor: $pm->image ─────────────────────────
    public function getImageAttribute(): ?string
    {
        return $this->qris_image;
    }

    // ── Mutator: $pm->image = '...' ──────────────────
    public function setImageAttribute($value): void
    {
        $this->qris_image = $value;
    }

    // ── Accessor: $pm->nama_merchant ─────────────────
    public function getNamaMerchantAttribute(): ?string
    {
        return $this->nama_rekening;
    }

    // ── Mutator: $pm->nama_merchant = '...' ──────────
    public function setNamaMerchantAttribute($value): void
    {
        $this->nama_rekening = $value;
    }

    // ── Accessor: $pm->nomor_merchant ────────────────
    public function getNomorMerchantAttribute(): ?string
    {
        return $this->no_rekening;
    }

    // ── Mutator: $pm->nomor_merchant = '...' ─────────
    public function setNomorMerchantAttribute($value): void
    {
        $this->no_rekening = $value;
    }
}