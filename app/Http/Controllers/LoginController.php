<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // =========================================
    // FORM LOGIN
    // =========================================
    public function index()
    {
        return view('login');
    }

    // =========================================
    // PROSES LOGIN
    // =========================================
    public function login(Request $request)
    {
        // VALIDASI
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            // REGENERATE SESSION
            $request->session()->regenerate();

            $user = Auth::user();

            // REDIRECT SESUAI ROLE
            $redirectMap = [
                'admin'  => '/admin/dashboard',
                'kasir'  => '/kasir/dashboard',
                'pelayan'=> '/pelayan/antar',
                'dapur'  => '/dapur/proses',
            ];

            if (array_key_exists($user->role, $redirectMap)) {
                return redirect($redirectMap[$user->role]);
            }

            // ✅ FIX: Role tidak dikenali → logout paksa
            Auth::logout();
            return back()->with('error', 'Akun Anda tidak memiliki hak akses yang valid.');
        }

        // ✅ FIX: Kembalikan email ke form agar tidak hilang
        return back()
            ->with('error', 'Email atau password salah.')
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