<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AddonGroup;

class AddonGroupController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = AddonGroup::create([
            'name' => $request->name,
            'required' => $request->required ?? 0,
            'max' => $request->max ?? null,
        ]);

        return response()->json([
            'success' => true,
            'group' => $group
        ]);
    }
}