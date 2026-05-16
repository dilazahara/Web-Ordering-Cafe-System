<?php

namespace App\Http\Controllers;

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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Menggunakan avatar
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Update data user dasar
        $user->name  = $request->name;
        $user->email = $request->email;

        // Username
        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        // Phone
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        // Logika Upload Foto Profil (Menggunakan kolom avatar)
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada di dalam folder public
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru ke folder 'public/avatars'
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Simpan ke database (Aman dari error SQLSTATE dan garis merah)
        $user->save();

        // Redirect ke dashboard
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

        // Cek password lama
        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {
            return back()->withErrors([
                'current_password' => 'Password lama yang kamu masukkan salah!'
            ]);
        }

        // Update password baru
        $user->update([
            'password' => Hash::make(
                $request->new_password
            )
        ]);

        // Redirect ke dashboard
        return redirect('/kasir/dashboard')
            ->with(
                'success',
                'Password berhasil diubah!'
            );
    }
}