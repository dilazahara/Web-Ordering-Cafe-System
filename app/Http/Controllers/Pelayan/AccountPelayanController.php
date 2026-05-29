<?php

namespace App\Http\Controllers\Pelayan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountPelayanController extends Controller
{
    // =====================================
    // PROFIL
    // =====================================

    public function profil()
    {
        return view('pelayan.account.profil');
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
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

        // Ganti password jika diisi
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini yang kamu masukkan salah!'
                ])->withInput();
            }

            $request->validate([
                'new_password' => 'required|min:8|confirmed',
            ]);

            $user->password = Hash::make($request->new_password);
        }

        // Upload avatar
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('pelayan.account.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    // =====================================
    // GANTI PASSWORD (halaman terpisah)
    // =====================================

    public function gantiSandi()
    {
        return view('pelayan.account.ganti-sandi');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama yang kamu masukkan salah!'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('pelayan.account.profil')
            ->with('success', 'Password berhasil diubah!');
    }
}