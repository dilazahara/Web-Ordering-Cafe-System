<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonGroup extends Model
{
    protected $table = 'addon_groups';

    protected $fillable = [
        'name',
        'required',
        'max'
    ];

    public function addons()
    {
        return $this->hasMany(Addon::class, 'addon_group_id');
    }

    // ✅ TAMBAHAN — relasi balik ke Menu
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_addon_group');
    }
}