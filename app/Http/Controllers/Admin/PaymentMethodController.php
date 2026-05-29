<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('created_at', 'asc')->get();

        return view('admin.pembayaran', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'kode'        => 'required|string',
            'kode_custom' => 'nullable|string|max:50|regex:/^[a-z0-9_]+$/',
            'aktif'       => 'required|in:0,1',
        ], [
            'nama.required'     => 'Nama metode wajib diisi.',
            'kode.required'     => 'Tipe pembayaran wajib dipilih.',
            'kode_custom.regex' => 'Kode hanya boleh huruf kecil, angka, dan underscore.',
            'aktif.required'    => 'Status aktif wajib dipilih.',
            'aktif.in'          => 'Nilai status tidak valid.',
        ]);

        $kode = $request->kode === 'lain'
            ? strtolower(trim($request->kode_custom))
            : $request->kode;

        if (empty($kode)) {
            return back()
                ->withErrors([
                    'kode_custom' => 'Kode wajib diisi untuk tipe Lainnya.'
                ])
                ->withInput();
        }

        if (PaymentMethod::where('kode', $kode)->exists()) {
            return back()
                ->withErrors([
                    'kode' => "Kode '{$kode}' sudah digunakan."
                ])
                ->withInput();
        }

        PaymentMethod::create([
            'nama'  => $request->nama,
            'kode'  => $kode,
            'aktif' => (bool) $request->aktif,
        ]);

        return back()->with(
            'success',
            "Metode pembayaran \"{$request->nama}\" berhasil ditambahkan."
        );
    }

    public function update(Request $request, int $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'nama'  => 'required|string|max:100',
            'kode'  => 'nullable|string|max:50|regex:/^[a-z0-9_]+$/',
            'aktif' => 'nullable|in:0,1',
        ], [
            'nama.required' => 'Nama metode wajib diisi.',
            'kode.regex'    => 'Kode hanya boleh huruf kecil, angka, dan underscore.',
            'aktif.in'      => 'Nilai status tidak valid.',
        ]);

        $kode = $request->kode
            ? strtolower(trim($request->kode))
            : $method->kode;

        if (
            $kode !== $method->kode &&
            PaymentMethod::where('kode', $kode)->exists()
        ) {
            return back()
                ->withErrors([
                    'kode' => "Kode '{$kode}' sudah digunakan."
                ])
                ->withInput();
        }

        $method->nama = $request->nama;
        $method->kode = $kode;
        $method->aktif = $request->has('aktif')
            ? (bool) $request->aktif
            : $method->aktif;

        $method->save();

        return back()->with(
            'success',
            "Metode \"{$method->nama}\" berhasil diperbarui."
        );
    }

    public function toggle(int $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $method->aktif = !$method->aktif;
        $method->save();

        $status = $method->aktif
            ? 'diaktifkan'
            : 'dinonaktifkan';

        return back()->with(
            'success',
            "{$method->nama} berhasil {$status}."
        );
    }

    public function updateQris(Request $request, int $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'nama_merchant'  => 'nullable|string|max:100',
            'nomor_merchant' => 'nullable|string|max:50',
            'image'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {

            if ($method->qris_image) {
                Storage::disk('public')->delete($method->qris_image);
            }

            $method->qris_image = $request
                ->file('image')
                ->store('qris', 'public');
        }

        $method->nama_rekening = $request->nama_merchant;
        $method->no_rekening = $request->nomor_merchant;

        $method->save();

        return back()->with(
            'success',
            'Konfigurasi QRIS berhasil disimpan.'
        );
    }

    public function destroy(int $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $orderCount = Order::where(
            'payment_method',
            $method->kode
        )
            ->whereIn('status', [
                'pending',
                'process',
                'done',
                'paid',
                'lunas'
            ])
            ->count();

        if ($orderCount > 0) {
            return back()->with(
                'error',
                "Metode '{$method->nama}' tidak dapat dihapus karena masih ada {$orderCount} order aktif yang menggunakannya."
            );
        }

        if ($method->qris_image) {
            Storage::disk('public')->delete($method->qris_image);
        }

        $nama = $method->nama;
        $method->delete();

        return back()->with(
            'success',
            "Metode pembayaran \"{$nama}\" berhasil dihapus."
        );
    }
}