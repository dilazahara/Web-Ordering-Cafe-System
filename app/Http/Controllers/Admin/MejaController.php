<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    // =========================================
    // INDEX
    // =========================================
    public function index()
    {
        $mejas = Meja::latest()->get();
        return view('admin.meja.index', compact('mejas'));
    }

    // =========================================
    // CREATE
    // =========================================
    public function create()
    {
        return view('admin.meja.create');
    }

    // =========================================
    // STORE
    // =========================================
    public function store(Request $request)
    {
        // ✅ FIX: Tambah unique, max length, dan validasi status enum
        // Sebelumnya nomor_meja tidak dicek unique — bisa ada nomor meja duplikat
        $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:mejas,nomor_meja',
            'status'     => 'nullable|in:kosong,terisi,reserved',
        ], [
            'nomor_meja.required' => 'Nomor meja wajib diisi.',
            'nomor_meja.max'      => 'Nomor meja maksimal 10 karakter.',
            'nomor_meja.unique'   => 'Nomor meja sudah digunakan.',
            'status.in'           => 'Status meja tidak valid. Pilih: kosong, terisi, atau reserved.',
        ]);

        Meja::create([
            'nomor_meja' => $request->nomor_meja,
            'status'     => $request->status ?? 'kosong',
        ]);

        return redirect('/admin/meja')->with('success', 'Meja berhasil ditambahkan.');
    }

    // =========================================
    // EDIT
    // =========================================
    public function edit($id)
    {
        $meja = Meja::findOrFail($id);
        return view('admin.meja.edit', compact('meja'));
    }

    // =========================================
    // UPDATE
    // =========================================
    public function update(Request $request, $id)
    {
        $meja = Meja::findOrFail($id);

        // ✅ FIX: unique dikecualikan untuk ID saat ini agar tidak conflict
        //         dengan dirinya sendiri saat edit
        $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:mejas,nomor_meja,' . $id,
            'status'     => 'nullable|in:kosong,terisi,reserved',
        ], [
            'nomor_meja.required' => 'Nomor meja wajib diisi.',
            'nomor_meja.max'      => 'Nomor meja maksimal 10 karakter.',
            'nomor_meja.unique'   => 'Nomor meja sudah digunakan oleh meja lain.',
            'status.in'           => 'Status meja tidak valid.',
        ]);

        $meja->update([
            'nomor_meja' => $request->nomor_meja,
            'status'     => $request->status ?? 'kosong',
        ]);

        return redirect('/admin/meja')->with('success', 'Meja berhasil diupdate.');
    }

    // =========================================
    // DESTROY
    // =========================================
    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);

        // ✅ FIX: Jangan izinkan hapus meja yang sedang terisi (ada tamu)
        if ($meja->status === 'terisi') {
            return redirect('/admin/meja')
                ->with('error', "Meja {$meja->nomor_meja} tidak dapat dihapus karena sedang dalam kondisi terisi.");
        }

        $meja->delete();

        return redirect('/admin/meja')->with('success', 'Meja berhasil dihapus.');
    }

    // =========================================
    // MONITOR
    // =========================================
    public function monitor()
    {
        $mejas = Meja::with([
            'orders' => function ($query) {
                $query->latest();
            }
        ])->latest()->get();

        return view('admin.meja.monitor', compact('mejas'));
    }
}