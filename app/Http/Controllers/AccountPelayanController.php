<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountPelayanController extends Controller
{
    // ═══════════════════════════════
    // PROFIL
    // ═══════════════════════════════

    public function profil()
    {
        return view('pelayan.account.profil');
    }

    public function updateProfil(Request $request)
    {
        $request->validate([

            'name' => 'required|string|max:100',

            'email' =>
                'required|email|unique:users,email,' .
                Auth::id(),

        ]);

        $user = Auth::user();

        // UPDATE DATA

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

        // SAVE

        $user->save();

        return redirect('/pelayan/antar')

            ->with(
                'success',
                'Profil berhasil diperbarui!'
            );
    }


    // ═══════════════════════════════
    // GANTI PASSWORD
    // ═══════════════════════════════

    public function gantiSandi()
    {
        return view(
            'pelayan.account.ganti-sandi'
        );
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

        // UPDATE PASSWORD

        Auth::user()->update([

            'password' => Hash::make(
                $request->new_password
            )

        ]);

        return redirect('/pelayan/antar')

            ->with(
                'success',
                'Password berhasil diubah!'
            );
    }
}