<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // =========================
    // FORM LOGIN
    // =========================
    public function index()
    {
        return view('login');
    }

    // =========================
    // PROSES LOGIN
    // =========================
    public function login(Request $request)
    {
        // VALIDASI
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // COBA LOGIN
        if (Auth::attempt($request->only('email', 'password')))
        {
            // regenerate session
            $request->session()->regenerate();

            // ambil user login
            $user = Auth::user();

            // =========================
            // REDIRECT SESUAI ROLE
            // =========================

            // ADMIN
            if ($user->role == 'admin')
            {
                return redirect('/admin/dashboard');
            }

            // KASIR
            if ($user->role == 'kasir')
            {
                return redirect('/kasir/dashboard');
            }

            // PELAYAN
            if ($user->role == 'pelayan')
            {
                return redirect('/pelayan/antar');
            }

            // DAPUR
            if ($user->role == 'dapur')
            {
                return redirect('/dapur/pesanan');
            }

            // DEFAULT
            return redirect('/customer/home');
        }

        // LOGIN GAGAL
        return back()->with('error', 'Email atau password salah');
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
