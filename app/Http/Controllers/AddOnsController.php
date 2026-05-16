<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Addon;
use App\Models\AddonGroup;

class AddOnsController extends Controller
{
    // ======================
    // INDEX
    // ======================
    public function index()
    {
        $addons = Addon::with('group')->latest()->get();

        return view('admin.addons.index', compact('addons'));
    }

    // ======================
    // CREATE
    // ======================
    public function create()
    {
        $groups = AddonGroup::all();

        return view('admin.addons.create', compact('groups'));
    }

    // ======================
    // STORE
    // ======================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'addon_group_id' => 'required'
        ]);

        Addon::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'addon_group_id' => $request->addon_group_id,
            'status' => $request->status ?? 1
        ]);

        return redirect('/admin/addons')
            ->with('success', 'Add-on berhasil ditambahkan');
    }

    // ======================
    // EDIT
    // ======================
    public function edit(int $id)
    {
        $addon = Addon::findOrFail($id);
        $groups = AddonGroup::all();

        return view('admin.addons.edit', compact('addon', 'groups'));
    }

    // ======================
    // UPDATE
    // ======================
    public function update(Request $request, int $id)
    {
        $addon = Addon::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'addon_group_id' => 'required'
        ]);

        $addon->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'addon_group_id' => $request->addon_group_id,
            'status' => $request->status ?? 1
        ]);

        return redirect('/admin/addons')
            ->with('success', 'Add-on berhasil diupdate');
    }

    // ======================
    // DELETE
    // ======================
    public function destroy(int $id)
    {
        Addon::findOrFail($id)->delete();

        return redirect('/admin/addons')
            ->with('success', 'Add-on berhasil dihapus');
    }
}