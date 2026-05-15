<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\AddonGroup;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('kategori');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orWhereHas('kategori', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        $menus = $query->latest()->paginate(5)->withQueryString();

        return view('admin.menu.index', compact('menus'));
    }

    // ✅ STEP 3 — ganti $addons jadi $groups
    public function create()
    {
        $kategoris = Kategori::all();
        $groups    = AddonGroup::with('addons')->get();

        return view('admin.menu.create', compact('kategoris', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'price'       => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu', 'public');
        }

        $menu = Menu::create([
            'name'        => $request->name,
            'description' => $request->description,
            'kategori_id' => $request->kategori_id,
            'price'       => $request->price,
            'status'      => $request->status ?? 1,
            'image'       => $imagePath,
        ]);

        // ✅ sync addon groups ke pivot
        if ($request->addon_groups) {
            $menu->addonGroups()->sync($request->addon_groups);
        }

        return redirect('/admin/menu')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $menu      = Menu::with('addonGroups')->findOrFail($id);
        $kategoris = Kategori::all();
        $groups    = AddonGroup::with('addons')->get(); // ✅ ganti dari $addons

        return view('admin.menu.edit', compact('menu', 'kategoris', 'groups'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name'        => 'required',
            'price'       => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imagePath = $menu->image;

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $imagePath = $request->file('image')->store('menu', 'public');
        }

        $menu->update([
            'name'        => $request->name,
            'description' => $request->description,
            'kategori_id' => $request->kategori_id,
            'price'       => $request->price,
            'status'      => $request->status ?? 1,
            'image'       => $imagePath,
        ]);

        // ✅ update addon groups
        $menu->addonGroups()->sync($request->addon_groups ?? []);

        return redirect('/admin/menu')->with('success', 'Menu berhasil diupdate');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect('/admin/menu')->with('success', 'Menu berhasil dihapus');
    }
}