<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountKasirController extends Controller
{
    // ════════════════════════════════════════
    // PROFIL
    // ════════════════════════════════════════

    public function profil()
    {
        return view('kasir.account.profil');
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();

        // update data user
        $user->name  = $request->name;
        $user->email = $request->email;

        // username
        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        // phone
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        // simpan
        $user->save();

        // redirect dashboard
        return redirect('/kasir/dashboard')
            ->with(
                'success',
                'Profil berhasil diperbarui!'
            );
    }

    // ════════════════════════════════════════
    // GANTI PASSWORD
    // ════════════════════════════════════════

    public function gantiSandi()
    {
        return view('kasir.account.ganti-sandi');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        // cek password lama
        if (!Hash::check(
            $request->current_password,
            Auth::user()->password
        )) {

            return back()->withErrors([
                'current_password' =>
                    'Password lama yang kamu masukkan salah!'
            ]);
        }

        // update password baru
        Auth::user()->update([
            'password' => Hash::make(
                $request->new_password
            )
        ]);

        // redirect dashboard
        return redirect('/kasir/dashboard')
            ->with(
                'success',
                'Password berhasil diubah!'
            );
    }
}