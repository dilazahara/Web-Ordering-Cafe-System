<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Ambil settings dari file config atau bisa simpan ke .env / tabel DB
        // Untuk tugas akhir: cukup simpan ke session agar simple
        $settings = session('app_settings', [
            'nama_cafe'    => 'Tjap Nyonya',
            'alamat'       => '',
            'no_telp'      => '',
            'jam_buka'     => '08:00',
            'jam_tutup'    => '22:00',
            'biaya_layanan'=> 2000,
        ]);

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_cafe'     => 'required|string|max:100',
            'biaya_layanan' => 'required|integer|min:0',
        ]);

        // Simpan ke session (sederhana, cukup untuk demo sidang)
        session(['app_settings' => [
            'nama_cafe'     => $request->nama_cafe,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'jam_buka'      => $request->jam_buka,
            'jam_tutup'     => $request->jam_tutup,
            'biaya_layanan' => $request->biaya_layanan,
        ]]);

        return back()->with('success', 'Settings berhasil disimpan!');
    }
}