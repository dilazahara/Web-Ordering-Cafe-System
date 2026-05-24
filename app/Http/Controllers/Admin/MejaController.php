<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meja;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::latest()->get();

        return view(
            'admin.meja.index',
            compact('mejas')
        );
    }

    public function create()
    {
        return view('admin.meja.create');
    }

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

    public function edit($id)
    {
        $meja = Meja::findOrFail($id);

        return view(
            'admin.meja.edit',
            compact('meja')
        );
    }

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