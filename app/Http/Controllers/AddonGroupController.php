<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddonGroup;

class AddonGroupController extends Controller
{
    // =========================
    // STORE GROUP
    // =========================
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