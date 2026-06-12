<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\Meja;

class HomeController extends Controller
{
    public function index()
    {
        $tableNumber = session('table_number');
        $scannedAt   = session('table_scanned_at');

        $menus     = Menu::with('kategori')->get();
        $kategoris = Kategori::all();

        $sisaMenit = null;
        if ($scannedAt) {
            try {
                $sisaMenit = max(0, 180 - (int) \Carbon\Carbon::parse($scannedAt)->diffInMinutes(now()));
            } catch (\Exception $e) {
                $sisaMenit = 0;
            }
        }

        return view('customer.home', compact('menus', 'kategoris', 'tableNumber', 'sisaMenit'));
    }

    public function scanMeja(string $nomor_meja, string $token)
    {
        $meja = Meja::where('nomor_meja', $nomor_meja)->first();

        if (! $meja) {
            return redirect()->route('customer.scan.required')
                ->with('error', 'QR Code tidak valid. Meja tidak ditemukan.');
        }

        $validToken = $meja->getOrCreateQrToken();

        if (! hash_equals($validToken, $token)) {
            return redirect()->route('customer.scan.required')
                ->with('error', 'QR Code tidak valid atau sudah kedaluwarsa.');
        }

        session()->invalidate();
        session()->regenerateToken();

        session([
            'table_number'     => $nomor_meja,
            'table_scanned_at' => now()->toDateTimeString(),
            'table_scan_token' => $token,
        ]);

        $meja->update(['status' => 'terisi']);

        return redirect()->route('customer.home')
            ->with('qr_success', 'Selamat datang! Anda berada di Meja ' . $nomor_meja . '.');
    }
}