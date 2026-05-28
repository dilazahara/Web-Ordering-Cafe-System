<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // =========================================
    // HALAMAN LOGIN
    // =========================================
    public function login()
    {
        return view('auth.login');
    }

    // =========================================
    // PROSES LOGIN
    // =========================================
    public function loginProses(Request $request)
    {
        // ✅ FIX: Tambah validasi — sebelumnya tidak ada validasi sama sekali
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            // ✅ FIX: Gunakan map agar lebih bersih & mudah dikembangkan
            $redirectMap = [
                'admin'  => '/admin/dashboard',
                'kasir'  => '/kasir/dashboard',
                'pelayan'=> '/pelayan/dashboard',
                'dapur'  => '/dapur/proses',
            ];

            if (array_key_exists($role, $redirectMap)) {
                return redirect($redirectMap[$role]);
            }

            // ✅ FIX: Jika role tidak dikenali, logout paksa daripada diam
            Auth::logout();
            return back()->with('error', 'Akun Anda tidak memiliki hak akses yang valid. Hubungi administrator.');
        }

        // ✅ FIX: withInput() agar email tidak hilang dari form saat gagal login
        return back()
            ->with('error', 'Email atau Password salah.')
            ->withInput($request->only('email'));
    }

    // =========================================
    // LOGOUT
    // =========================================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}