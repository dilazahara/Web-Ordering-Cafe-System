<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'kategori_id',
        'price',
        'status',
        'image'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'addon_menu');
    }

    // ✅ TAMBAHKAN INI
    public function addonGroups()
    {
        return $this->belongsToMany(AddonGroup::class, 'menu_addon_group');
    }
}