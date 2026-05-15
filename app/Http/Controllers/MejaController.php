<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;

class MejaController extends Controller
{
    // =====================================
    // LIST DATA MEJA
    // =====================================
    public function index()
    {
        $mejas = Meja::latest()->get();

        return view(
            'admin.meja.index',
            compact('mejas')
        );
    }

    // =====================================
    // HALAMAN TAMBAH MEJA
    // =====================================
    public function create()
    {
        return view('admin.meja.create');
    }

    // =====================================
    // SIMPAN MEJA
    // =====================================
    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required',
        ]);

        Meja::create([
            'nomor_meja' => $request->nomor_meja,
            'status'     => $request->status ?? 'kosong',
        ]);

        return redirect('/admin/meja')
            ->with(
                'success',
                'Meja berhasil ditambahkan'
            );
    }

    // =====================================
    // HALAMAN EDIT MEJA
    // =====================================
    public function edit($id)
    {
        $meja = Meja::findOrFail($id);

        return view(
            'admin.meja.edit',
            compact('meja')
        );
    }

    // =====================================
    // UPDATE MEJA
    // =====================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_meja' => 'required',
        ]);

        $meja = Meja::findOrFail($id);

        $meja->update([
            'nomor_meja' => $request->nomor_meja,
            'status'     => $request->status ?? 'kosong',
        ]);

        return redirect('/admin/meja')
            ->with(
                'success',
                'Meja berhasil diupdate'
            );
    }

    // =====================================
    // HAPUS MEJA
    // =====================================
    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);

        $meja->delete();

        return redirect('/admin/meja')
            ->with(
                'success',
                'Meja berhasil dihapus'
            );
    }

    // =====================================
    // MONITOR MEJA
    // =====================================
    public function monitor()
    {
        $mejas = Meja::with([
            'orders' => function ($query) {
                $query->latest();
            }
        ])->latest()->get();

        return view(
            'admin.meja.monitor',
            compact('mejas')
        );
    }
}