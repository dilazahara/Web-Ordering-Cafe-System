<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\AddonGroup;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // =========================================
    // INDEX
    // =========================================
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

    // =========================================
    // CREATE
    // =========================================
    public function create()
    {
        $kategoris = Kategori::all();
        $groups    = AddonGroup::with('addons')->get();

        return view('admin.menu.create', compact('kategoris', 'groups'));
    }

    // =========================================
    // STORE
    // =========================================
    public function store(Request $request)
    {
        // ✅ FIX: Tambah min:0 pada price, max length pada name & description,
        //         tambah webp pada mimes, dan pesan error yang jelas
        $request->validate([
            'name'        => 'required|string|max:150',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'nullable|in:0,1',
        ], [
            'name.required'        => 'Nama menu wajib diisi.',
            'name.max'             => 'Nama menu maksimal 150 karakter.',
            'price.required'       => 'Harga wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak ditemukan.',
            'image.image'          => 'File harus berupa gambar.',
            'image.mimes'          => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
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

        if ($request->addon_groups) {
            $menu->addonGroups()->sync($request->addon_groups);
        }

        return redirect('/admin/menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    // =========================================
    // EDIT
    // =========================================
    public function edit($id)
    {
        $menu      = Menu::with('addonGroups')->findOrFail($id);
        $kategoris = Kategori::all();
        $groups    = AddonGroup::with('addons')->get();

        return view('admin.menu.edit', compact('menu', 'kategoris', 'groups'));
    }

    // =========================================
    // UPDATE
    // =========================================
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        // ✅ FIX: Validasi sama seperti store() — konsisten
        $request->validate([
            'name'        => 'required|string|max:150',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'nullable|in:0,1',
        ], [
            'name.required'        => 'Nama menu wajib diisi.',
            'name.max'             => 'Nama menu maksimal 150 karakter.',
            'price.required'       => 'Harga wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak ditemukan.',
            'image.image'          => 'File harus berupa gambar.',
            'image.mimes'          => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imagePath = $menu->image;

        if ($request->hasFile('image')) {
            // Hapus gambar lama sebelum simpan yang baru
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

        $menu->addonGroups()->sync($request->addon_groups ?? []);

        return redirect('/admin/menu')->with('success', 'Menu berhasil diupdate.');
    }

    // =========================================
    // DESTROY
    // =========================================
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // ✅ FIX: Cegah hapus menu yang masih ada di order aktif
        // Tanpa ini, OrderItem akan jadi orphan (referensi ke menu yang sudah tidak ada)
        $activeOrderCount = OrderItem::where('menu_id', $id)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['pending', 'process', 'done', 'paid', 'lunas']);
            })
            ->count();

        if ($activeOrderCount > 0) {
            return redirect('/admin/menu')
                ->with('error', "Menu '{$menu->name}' tidak dapat dihapus karena masih ada di {$activeOrderCount} pesanan aktif.");
        }

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect('/admin/menu')->with('success', 'Menu berhasil dihapus.');
    }
}