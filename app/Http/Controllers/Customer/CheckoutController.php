<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Meja;

class CheckoutController extends Controller
{
    // =========================================
    // TAMPILKAN HALAMAN CHECKOUT
    // =========================================
    public function index()
    {
        $paymentMethods = PaymentMethod::where('aktif', true)->get();

        // ✅ FIX: Guard jika tidak ada metode pembayaran aktif
        if ($paymentMethods->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tidak ada metode pembayaran yang tersedia saat ini. Hubungi kasir.');
        }

        $tableNumber = session('table_number');

        // ✅ FIX: Validasi nomor meja dari session — meja bisa saja sudah dihapus admin
        if ($tableNumber) {
            $meja = Meja::where('nomor_meja', $tableNumber)->first();

            if (!$meja) {
                // Hapus session meja yang tidak valid
                session()->forget('table_number');
                $tableNumber = null;
            }
        }

        return view('customer.checkout', compact('paymentMethods', 'tableNumber'));
    }
}