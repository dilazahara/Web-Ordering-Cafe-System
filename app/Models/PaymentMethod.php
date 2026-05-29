<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = [
        'nama',
        'kode',
        'aktif',
        'qris_image',
        'nama_rekening',
        'no_rekening',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getIsQrisAttribute(): bool
    {
        return $this->kode === 'qris';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->aktif ? 'Aktif' : 'Nonaktif';
    }

    public function getQrisUrlAttribute(): ?string
    {
        return $this->qris_image
            ? asset('storage/' . $this->qris_image)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATOR
    |--------------------------------------------------------------------------
    */

    public function setNamaAttribute(mixed $value): void
    {
        $this->attributes['nama'] = trim((string) $value);
    }

    public function setKodeAttribute(mixed $value): void
    {
        $this->attributes['kode'] = strtolower(
            trim((string) $value)
        );
    }

    public function setNamaRekeningAttribute(mixed $value): void
    {
        $this->attributes['nama_rekening'] = filled($value)
            ? trim((string) $value)
            : null;
    }

    public function setNoRekeningAttribute(mixed $value): void
    {
        $this->attributes['no_rekening'] = filled($value)
            ? trim((string) $value)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeNonaktif(Builder $query): Builder
    {
        return $query->where('aktif', false);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    public function isQris(): bool
    {
        return $this->kode === 'qris';
    }

    public function isCash(): bool
    {
        return $this->kode === 'cash';
    }
}