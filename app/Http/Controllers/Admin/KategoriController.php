<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // =========================================
    // INDEX
    // =========================================
    public function index()
    {
        $kategori = Kategori::latest()->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    // =========================================
    // CREATE
    // =========================================
    public function create()
    {
        return view('admin.kategori.create');
    }

    // =========================================
    // STORE
    // =========================================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:kategoris,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
            'name.unique'   => 'Nama kategori sudah ada.',
        ]);

        Kategori::create([
            'name' => $request->name,
        ]);

        return redirect('/admin/kategori')
            ->with('success', 'Kategori berhasil ditambah.');
    }

    // =========================================
    // EDIT
    // =========================================
    public function edit(int $id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    // =========================================
    // UPDATE
    // =========================================
    public function update(Request $request, int $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100|unique:kategoris,name,' . $id,
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max'      => 'Nama kategori maksimal 100 karakter.',
            'nama.unique'   => 'Nama kategori sudah digunakan.',
        ]);

        $kategori->update([
            'name' => $request->nama,
        ]);

        return redirect('/admin/kategori')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    // =========================================
    // DESTROY
    // =========================================
    public function destroy(int $id)
    {
        $kategori = Kategori::findOrFail($id);

        $menuCount = $kategori->menus()->count();

        // Nullify kategori_id pada semua menu yang menggunakan kategori ini
        // (aman karena kolom kategori_id sudah nullable di migration)
        if ($menuCount > 0) {
            $kategori->menus()->update(['kategori_id' => null]);
        }

        $kategori->delete();

        return redirect('/admin/kategori')
            ->with('success', "Kategori '{$kategori->name}' berhasil dihapus." .
                ($menuCount > 0 ? " {$menuCount} menu terkait kini tanpa kategori." : ''));
    }
}