<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // =========================================
    // TAMPILKAN HALAMAN CART
    // =========================================
    public function index()
    {
        return view('customer.cart');
    }

    // =========================================
    // VALIDASI CART SEBELUM CHECKOUT
    // ✅ FIX: Sebelumnya tidak ada validasi server-side sama sekali.
    // Cart dikelola localStorage, sehingga harga & qty bisa dimanipulasi.
    // Method ini dipanggil via AJAX dari halaman cart/checkout.
    // =========================================
    public function validateCart(Request $request)
    {
        $request->validate([
            'items'           => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.qty'     => 'required|integer|min:1|max:99',
            'items.*.price'   => 'required|numeric|min:0',
        ], [
            'items.required'         => 'Keranjang tidak boleh kosong.',
            'items.min'              => 'Keranjang tidak boleh kosong.',
            'items.*.menu_id.exists' => 'Salah satu menu tidak ditemukan atau sudah tidak tersedia.',
            'items.*.qty.min'        => 'Jumlah item minimal 1.',
            'items.*.qty.max'        => 'Jumlah item maksimal 99.',
            'items.*.price.min'      => 'Harga item tidak valid.',
        ]);

        // ✅ Verifikasi harga & ketersediaan menu dari DB (anti-tamper localStorage)
        $errors = [];
        foreach ($request->items as $item) {
            $menu = Menu::find($item['menu_id']);

            if (!$menu) {
                $errors[] = "Menu tidak ditemukan.";
                continue;
            }

            if ((int) $menu->status === 0) {
                $errors[] = "Menu '{$menu->name}' sudah tidak tersedia.";
                continue;
            }

            // Cek apakah harga dari client cocok dengan harga di DB
            if ((int) $item['price'] !== (int) $menu->price) {
                $errors[] = "Harga menu '{$menu->name}' sudah berubah (Rp " . number_format($menu->price, 0, ',', '.') . "). Silakan refresh halaman.";
            }
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        return response()->json(['valid' => true]);
    }
}