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
        // ✅ FIX: Tambah max length & unique agar tidak ada duplikat kategori
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

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil ditambah.');
    }

    // =========================================
    // EDIT
    // =========================================
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    // =========================================
    // UPDATE
    // =========================================
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        // ✅ FIX KRITIS #1: Tambah validasi — sebelumnya tidak ada validasi di update()
        // ✅ FIX KRITIS #2: unique rule dikecualikan untuk ID saat ini agar tidak
        //                   conflict dengan dirinya sendiri
        $request->validate([
            'name' => 'required|string|max:100|unique:kategoris,name,' . $id,
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
            'name.unique'   => 'Nama kategori sudah digunakan.',
        ]);

        $kategori->update([
            // ✅ FIX KRITIS #3: Bug typo! Sebelumnya $request->nama (selalu null)
            //                   sehingga update tidak pernah menyimpan perubahan.
            'name' => $request->name,
        ]);

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil diupdate.');
    }

    // =========================================
    // DESTROY
    // =========================================
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // ✅ FIX: Cegah hapus kategori yang masih dipakai oleh menu
        // Tanpa ini, menu yang kategorinya dihapus akan kehilangan relasi
        $menuCount = $kategori->menus()->count();

        if ($menuCount > 0) {
            return redirect('/admin/kategori')
                ->with('error', "Kategori '{$kategori->name}' tidak dapat dihapus karena masih digunakan oleh {$menuCount} menu. Pindahkan atau hapus menu tersebut terlebih dahulu.");
        }

        $kategori->delete();

        return redirect('/admin/kategori')->with('success', 'Kategori berhasil dihapus.');
    }
}