<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Kategori::create([
            'name' => $request->name
        ]);

        return redirect('/admin/kategori')->with('success', 'Berhasil ditambah');
    }

    public function edit($id)
{
    $kategori = Kategori::findOrFail($id);

    return view('admin.kategori.edit', compact('kategori'));
}

    public function update(Request $request, $id)
{
    $kategori = Kategori::findOrFail($id);

    $kategori->update([
        'name' => $request->nama
    ]);

    return redirect('/admin/kategori')->with('success', 'Berhasil update');
}

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();

        return redirect('/admin/kategori')->with('success', 'Berhasil dihapus');
    }
}