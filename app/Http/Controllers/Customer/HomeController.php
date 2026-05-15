<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil nomor meja dari query param (?meja=X) atau session
        if (request()->has('meja')) {
            session(['table_number' => request('meja')]);
        }

        $tableNumber = session('table_number');
        $menus       = Menu::with('kategori')->get();
        $kategoris   = Kategori::all();

        return view('customer.home', compact('menus', 'kategoris', 'tableNumber'));
    }

    /**
     * Dipanggil saat customer scan QR Code meja.
     * Simpan nomor meja ke session dan arahkan ke halaman menu.
     */
    public function scanMeja($nomor_meja)
    {
        session(['table_number' => $nomor_meja]);

        return redirect()->route('customer.home')
            ->with('qr_success', 'Meja ' . $nomor_meja . ' berhasil dipilih!');
    }
}
