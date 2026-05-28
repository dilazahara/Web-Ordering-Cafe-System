<?php

// ✅ FIX KRITIS: Namespace sebelumnya "App\Http\Controllers\" (ada backslash di akhir)
// yang menyebabkan PHP fatal parse error pada semua route yang mengarah ke controller ini.
namespace App\Http\Controllers;

use Illuminate\Http\Request;

// ✅ FIX: Tambah "extends Controller" yang sebelumnya tidak ada
class PembayaranController extends Controller
{
    public function index()
    {
        return view('admin.pembayaran');
    }
}