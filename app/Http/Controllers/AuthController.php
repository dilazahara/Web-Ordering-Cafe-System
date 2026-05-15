<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // halaman login
    public function login()
    {
        return view('auth.login');
    }

    // proses login
    public function loginProses(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            // redirect sesuai role

            if(Auth::user()->role == 'admin')
            {
                return redirect('/admin/dashboard');
            }

            if(Auth::user()->role == 'kasir')
            {
                return redirect('/kasir/dashboard');
            }

            if(Auth::user()->role == 'pelayan')
            {
                return redirect('/pelayan/dashboard');
            }

            if(Auth::user()->role == 'dapur')
            {
                return redirect('/dapur/proses');
            }
        }

        return back()->with('error', 'Email atau Password salah');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}