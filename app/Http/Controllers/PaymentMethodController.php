<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    // ── INDEX ──────────────────────────────────────────
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('created_at', 'asc')->get();
        return view('admin.pembayaran', compact('paymentMethods'));
    }

    // ── STORE (Tambah) ─────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'kode'        => 'required|string',
            'kode_custom' => 'nullable|string|max:50|regex:/^[a-z0-9_]+$/',
            'aktif'       => 'required|in:0,1',
        ], [
            'nama.required'          => 'Nama metode wajib diisi.',
            'kode.required'          => 'Tipe pembayaran wajib dipilih.',
            'kode_custom.regex'      => 'Kode hanya boleh huruf kecil, angka, dan underscore.',
        ]);

        // Tentukan kode akhir
        $kode = $request->kode === 'lain'
            ? strtolower(trim($request->kode_custom))
            : $request->kode;

        if (empty($kode)) {
            return back()->withErrors(['kode_custom' => 'Kode wajib diisi untuk tipe Lainnya.'])->withInput();
        }

        // Cek duplikat kode
        if (PaymentMethod::where('kode', $kode)->exists()) {
            return back()
                ->withErrors(['kode' => "Kode '{$kode}' sudah digunakan."])
                ->withInput();
        }

        PaymentMethod::create([
            'nama'  => $request->nama,
            'kode'  => $kode,
            'aktif' => (bool) $request->aktif,
        ]);

        return back()->with('success', "Metode pembayaran \"{$request->nama}\" berhasil ditambahkan.");
    }

    // ── UPDATE (Edit Nama & Kode) ──────────────────────
    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'nullable|string|max:50|regex:/^[a-z0-9_]+$/',
        ], [
            'nama.required' => 'Nama metode wajib diisi.',
            'kode.regex'    => 'Kode hanya boleh huruf kecil, angka, dan underscore.',
        ]);

        // Cek duplikat kode (kecuali diri sendiri)
        $kode = $request->kode ? strtolower(trim($request->kode)) : $method->kode;
        if ($kode !== $method->kode && PaymentMethod::where('kode', $kode)->exists()) {
            return back()->withErrors(['kode' => "Kode '{$kode}' sudah digunakan."])->withInput();
        }

        $method->nama = $request->nama;
        $method->kode = $kode;
        $method->save();

        return back()->with('success', "Metode \"{$method->nama}\" berhasil diperbarui.");
    }

    // ── TOGGLE (Aktif/Nonaktif) ────────────────────────
    public function toggle($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->aktif = !$method->aktif;
        $method->save();

        $status = $method->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "{$method->nama} berhasil {$status}.");
    }

    // ── UPDATE QRIS ────────────────────────────────────
    public function updateQris(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'nama_merchant'  => 'nullable|string|max:100',
            'nomor_merchant' => 'nullable|string|max:50',
            'image'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($method->qris_image) {
                Storage::disk('public')->delete($method->qris_image);
            }
            $method->qris_image = $request->file('image')->store('qris', 'public');
        }

        $method->nama_rekening = $request->nama_merchant;
        $method->no_rekening   = $request->nomor_merchant;
        $method->save();

        return back()->with('success', 'Konfigurasi QRIS berhasil disimpan.');
    }

    // ── DESTROY (Hapus) ────────────────────────────────
    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        // Hapus gambar QRIS jika ada
        if ($method->qris_image) {
            Storage::disk('public')->delete($method->qris_image);
        }

        $nama = $method->nama;
        $method->delete();

        return back()->with('success', "Metode pembayaran \"{$nama}\" berhasil dihapus.");
    }
}