<?php

namespace App\Http\Controllers\Pelayan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|unique:users,email,' . Auth::id(),
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ── UPDATE DATA ───────────────────────────
        $user->name  = $request->name;
        $user->email = $request->email;

        // USERNAME
        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        // PHONE
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        // ── UPLOAD AVATAR ─────────────────────────
        if ($request->hasFile('avatar')) {

            // Hapus avatar lama jika ada
            if (
                $user->avatar &&
                Storage::disk('public')->exists($user->avatar)
            ) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan avatar baru
            $path = $request
                ->file('avatar')
                ->store('avatars', 'public');

            $user->avatar = $path;
        }

        // ── SAVE ──────────────────────────────────
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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ── CEK PASSWORD LAMA ─────────────────────
        if (
            !Hash::check(
                $request->current_password,
                $user->password
            )
        ) {

            return back()->withErrors([

                'current_password' =>
                    'Password lama yang kamu masukkan salah!'

            ]);
        }

        // ── UPDATE PASSWORD ───────────────────────
        $user->password = Hash::make(
            $request->new_password
        );

        $user->save();

        return redirect('/pelayan/antar')
            ->with(
                'success',
                'Password berhasil diubah!'
            );
    }
}