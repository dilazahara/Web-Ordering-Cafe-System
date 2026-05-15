<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    // =========================
    // TABLE NAME (optional)
    // =========================
    protected $table = 'kategoris';

    // =========================
    // FIELD YANG BOLEH DIISI
    // =========================
    protected $fillable = [
        'name'
    ];

    // =========================
    // RELASI KE MENU
    // =========================
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}