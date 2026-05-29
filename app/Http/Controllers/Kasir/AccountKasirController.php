<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountKasirController extends Controller
{
    public function profil()
    {
        return view('kasir.account.profil');
    }

    public function updateProfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'nullable|string|max:100',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->username = $request->username;

        // Ganti password jika diisi
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Password saat ini yang kamu masukkan salah!'])
                    ->withInput();
            }

            $request->validate([
                'new_password' => 'required|min:8|confirmed',
            ]);

            $user->password = Hash::make($request->new_password);
        }

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->route('kasir.account.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

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

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama yang kamu masukkan salah!'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('kasir.account.profil')
            ->with('success', 'Password berhasil diubah!');
    }
}