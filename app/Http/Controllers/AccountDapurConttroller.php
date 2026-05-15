<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountDapurController extends Controller
{
    // ════════════════════════════════════════
    // PROFIL
    // ════════════════════════════════════════

    public function profil()
    {
        return view('dapur.account.profil');
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();

        // UPDATE DATA USER

        $user->name = $request->name;
        $user->email = $request->email;

        // USERNAME
        if ($request->filled('username')) {

            $user->username = $request->username;

        }

        // PHONE
        if ($request->filled('phone')) {

            $user->phone = $request->phone;

        }

        // SIMPAN
        $user->save();

        // REDIRECT
        return redirect('/dapur/proses')
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
        return view('dapur.account.ganti-sandi');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([

            'current_password' => 'required',

            'new_password' =>
                'required|min:8|confirmed',

        ]);

        // CEK PASSWORD LAMA

        if (
            !Hash::check(
                $request->current_password,
                Auth::user()->password
            )
        ) {

            return back()->withErrors([

                'current_password' =>
                    'Password lama yang kamu masukkan salah!'

            ]);

        }

        // UPDATE PASSWORD BARU

        Auth::user()->update([

            'password' => Hash::make(
                $request->new_password
            )

        ]);

        // REDIRECT

        return redirect('/dapur/proses')
            ->with(
                'success',
                'Password berhasil diubah!'
            );
    }
}