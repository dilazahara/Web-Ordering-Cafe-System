<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::latest()->get();

        // Pastikan semua meja sudah punya QR token
        foreach ($mejas as $meja) {
            $meja->getOrCreateQrToken();
        }

        return view('admin.meja.index', compact('mejas'));
    }

    public function create()
    {
        return view('admin.meja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:mejas,nomor_meja',
            'status'     => 'nullable|in:kosong,terisi,reserved',
        ], [
            'nomor_meja.required' => 'Nomor meja wajib diisi.',
            'nomor_meja.max'      => 'Nomor meja maksimal 10 karakter.',
            'nomor_meja.unique'   => 'Nomor meja sudah digunakan.',
            'status.in'           => 'Status meja tidak valid.',
        ]);

        // Generate QR token langsung saat meja dibuat
        $meja = Meja::create([
            'nomor_meja' => $request->nomor_meja,
            'status'     => $request->status ?? 'kosong',
        ]);
        $meja->refreshQrToken();

        return redirect('/admin/meja')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $meja = Meja::findOrFail($id);
        $meja->getOrCreateQrToken(); // pastikan token ada
        return view('admin.meja.edit', compact('meja'));
    }

    public function update(Request $request, $id)
    {
        $meja = Meja::findOrFail($id);

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

    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);

        if ($meja->status === 'terisi') {
            return redirect('/admin/meja')
                ->with('error', "Meja {$meja->nomor_meja} tidak dapat dihapus karena sedang dalam kondisi terisi.");
        }

        $meja->delete();

        return redirect('/admin/meja')->with('success', 'Meja berhasil dihapus.');
    }

    /**
     * Refresh QR Token meja — membuat QR lama tidak berlaku.
     * Berguna jika QR fisik hilang/dicuri atau admin ingin invalidate semua sesi aktif meja ini.
     */
    public function refreshQr($id)
    {
        $meja = Meja::findOrFail($id);
        $meja->refreshQrToken();

        return redirect('/admin/meja')
            ->with('success', "QR Code Meja {$meja->nomor_meja} berhasil di-refresh. QR lama sudah tidak berlaku.");
    }
}