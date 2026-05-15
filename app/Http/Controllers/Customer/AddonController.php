<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class AddonController extends Controller
{
    public function index()
    {
        // Ambil menu yang dipilih dari request
        $menuId = request('menu_id');
        $menu   = Menu::with('addonGroups.addons')->findOrFail($menuId);

        return view('customer.addons', compact('menu'));
    }
}