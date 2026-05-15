<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $table = 'addons';

    protected $fillable = [
        'name',
        'description',
        'price',
        'addon_group_id',
        'status'
    ];

    // =========================
    // RELASI KE GROUP
    // =========================
    public function group()
    {
        return $this->belongsTo(AddonGroup::class, 'addon_group_id');
    }
}