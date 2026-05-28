<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\AddonGroup;
use Illuminate\Http\Request;

class AddOnsController extends Controller
{
    // =========================================
    // INDEX
    // =========================================
    public function index()
    {
        $addons = Addon::with('group')->latest()->get();
        return view('admin.addons.index', compact('addons'));
    }

    // =========================================
    // CREATE
    // =========================================
    public function create()
    {
        $groups = AddonGroup::all();
        return view('admin.addons.create', compact('groups'));
    }

    // =========================================
    // STORE
    // =========================================
    public function store(Request $request)
    {
        // ✅ FIX: Tambah min:0 pada price, exists pada addon_group_id,
        //         max length pada name & description
        $request->validate([
            'name'           => 'required|string|max:100',
            'price'          => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:500',
            'addon_group_id' => 'required|exists:addon_groups,id',
            'status'         => 'nullable|in:0,1',
        ], [
            'name.required'           => 'Nama add-on wajib diisi.',
            'name.max'                => 'Nama add-on maksimal 100 karakter.',
            'price.required'          => 'Harga wajib diisi.',
            'price.numeric'           => 'Harga harus berupa angka.',
            'price.min'               => 'Harga tidak boleh negatif.',
            'addon_group_id.required' => 'Grup add-on wajib dipilih.',
            'addon_group_id.exists'   => 'Grup add-on tidak ditemukan.',
        ]);

        Addon::create([
            'name'           => $request->name,
            'description'    => $request->description,
            'price'          => $request->price,
            'addon_group_id' => $request->addon_group_id,
            'status'         => $request->status ?? 1,
        ]);

        return redirect('/admin/addons')->with('success', 'Add-on berhasil ditambahkan.');
    }

    // =========================================
    // EDIT
    // =========================================
    public function edit(int $id)
    {
        $addon  = Addon::findOrFail($id);
        $groups = AddonGroup::all();
        return view('admin.addons.edit', compact('addon', 'groups'));
    }

    // =========================================
    // UPDATE
    // =========================================
    public function update(Request $request, int $id)
    {
        $addon = Addon::findOrFail($id);

        // ✅ FIX: Validasi sama dan konsisten dengan store()
        $request->validate([
            'name'           => 'required|string|max:100',
            'price'          => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:500',
            'addon_group_id' => 'required|exists:addon_groups,id',
            'status'         => 'nullable|in:0,1',
        ], [
            'name.required'           => 'Nama add-on wajib diisi.',
            'name.max'                => 'Nama add-on maksimal 100 karakter.',
            'price.required'          => 'Harga wajib diisi.',
            'price.numeric'           => 'Harga harus berupa angka.',
            'price.min'               => 'Harga tidak boleh negatif.',
            'addon_group_id.required' => 'Grup add-on wajib dipilih.',
            'addon_group_id.exists'   => 'Grup add-on tidak ditemukan.',
        ]);

        $addon->update([
            'name'           => $request->name,
            'description'    => $request->description,
            'price'          => $request->price,
            'addon_group_id' => $request->addon_group_id,
            'status'         => $request->status ?? 1,
        ]);

        return redirect('/admin/addons')->with('success', 'Add-on berhasil diupdate.');
    }

    // =========================================
    // DESTROY
    // =========================================
    public function destroy(int $id)
    {
        Addon::findOrFail($id)->delete();
        return redirect('/admin/addons')->with('success', 'Add-on berhasil dihapus.');
    }
}