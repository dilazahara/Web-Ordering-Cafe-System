<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\AddonGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuController extends Controller
{
    // ─────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = Menu::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $menus = $query->latest()->paginate(10)->withQueryString();

        return view('admin.menu.index', compact('menus'));
    }

    // ─────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────
    public function create(): View
    {
        $kategoris = Kategori::orderBy('name')->get();
        $groups    = AddonGroup::with('addons')->orderBy('name')->get();
        return view('admin.menu.create', compact('kategoris', 'groups'));
    }

    // ─────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:menus,name',
            'kategori_id' => 'required|exists:kategoris,id',
            'price'       => 'required|numeric|min:0|max:99999999',
            'description' => 'required|string|max:500',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:0,1',
        ], [
            'name.required'        => 'Nama menu wajib diisi.',
            'name.max'             => 'Nama menu maksimal 255 karakter.',
            'name.unique'          => 'Nama menu sudah terdaftar, gunakan nama lain.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid.',
            'price.required'       => 'Harga wajib diisi dengan angka yang valid.',
            'price.numeric'        => 'Harga wajib diisi dengan angka yang valid.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'price.max'            => 'Harga terlalu besar.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.max'      => 'Deskripsi maksimal 500 karakter.',
            'image.required'       => 'Gambar wajib diisi.',
            'image.image'          => 'File harus berupa gambar.',
            'image.mimes'          => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
            'status.required'      => 'Status wajib dipilih.',
            'status.in'            => 'Status tidak valid.',
        ]);

        $data = $request->only(['name', 'kategori_id', 'price', 'description', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu = Menu::create($data);

        // Simpan relasi addon groups
        if ($request->has('addon_groups')) {
            $menu->addonGroups()->sync($request->addon_groups);
        } else {
            $menu->addonGroups()->detach();
        }

        return redirect('/admin/menu')
                         ->with('success', 'Menu "' . $data['name'] . '" berhasil ditambahkan!');
    }

    // ─────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────
    public function edit(int $id): View
    {
        $menu      = Menu::with('addonGroups')->findOrFail($id);
        $kategoris = Kategori::orderBy('name')->get();
        $groups    = AddonGroup::with('addons')->orderBy('name')->get();
        return view('admin.menu.edit', compact('menu', 'kategoris', 'groups'));
    }

    // ─────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────
    public function update(Request $request, int $id): RedirectResponse
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:menus,name,' . $id,
            'kategori_id' => 'required|exists:kategoris,id',
            'price'       => 'required|numeric|min:0|max:99999999',
            'description' => 'required|string|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:0,1',
        ], [
            'name.required'        => 'Nama menu wajib diisi.',
            'name.max'             => 'Nama menu maksimal 255 karakter.',
            'name.unique'          => 'Nama menu sudah digunakan menu lain.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak valid.',
            'price.required'       => 'Harga wajib diisi dengan angka yang valid.',
            'price.numeric'        => 'Harga wajib diisi dengan angka yang valid.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'price.max'            => 'Harga terlalu besar.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.max'      => 'Deskripsi maksimal 500 karakter.',
            'image.image'          => 'File harus berupa gambar.',
            'image.mimes'          => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
            'status.required'      => 'Status wajib dipilih.',
            'status.in'            => 'Status tidak valid.',
        ]);

        $data = $request->only(['name', 'kategori_id', 'price', 'description', 'status']);

        if ($request->hasFile('image')) {
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);

        // Simpan relasi addon groups
        if ($request->has('addon_groups')) {
            $menu->addonGroups()->sync($request->addon_groups);
        } else {
            $menu->addonGroups()->detach();
        }

        return redirect('/admin/menu')
                         ->with('success', 'Menu "' . $data['name'] . '" berhasil diperbarui!');
    }

    // ─────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────
    public function destroy(int $id): RedirectResponse
    {
        $menu = Menu::findOrFail($id);

        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->addonGroups()->detach();
        $menu->delete();

        return redirect('/admin/menu')
                         ->with('success', 'Menu berhasil dihapus.');
    }
}