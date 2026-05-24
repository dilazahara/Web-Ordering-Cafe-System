<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|unique:users,email,' . Auth::id(),
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {
            return back()->withErrors([
                'current_password' => 'Password lama yang kamu masukkan salah!'
            ]);
        }

        $user->update([
            'password' => Hash::make(
                $request->new_password
            )
        ]);

        return redirect('/kasir/dashboard')
            ->with(
                'success',
                'Password berhasil diubah!'
            );
    }
}