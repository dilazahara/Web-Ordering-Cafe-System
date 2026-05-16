<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountAdminController extends Controller
{
    // =========================
    // PROFIL
    // =========================
    public function profil()
    {
        return view('admin.account.profil');
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

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        // ─────────────────────────────────────
        // SIMPAN AVATAR (dikirim sebagai base64)
        // ─────────────────────────────────────
        if ($request->filled('avatar_cropped')) {
            $base64 = $request->input('avatar_cropped');

            // Hapus prefix "data:image/jpeg;base64," sebelum decode
            if (str_contains($base64, ',')) {
                $base64 = explode(',', $base64)[1];
            }

            $imageData = base64_decode($base64);

            // Buat nama file unik
            $filename = 'avatars/' . $user->id . '_' . time() . '.jpg';

            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan ke storage/app/public/avatars/
            Storage::disk('public')->put($filename, $imageData);

            $user->avatar = $filename;
        }

        $user->save();

        return redirect('/admin/dashboard')
            ->with('success', 'Profil berhasil diperbarui!');
    }
    
    // =========================
    // GANTI PASSWORD
    // =========================
    public function gantiSandi()
    {
        return view('admin.account.ganti-sandi');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        // cek password lama
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama yang kamu masukkan salah!'
            ]);
        }

        // update password baru
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // redirect dashboard
        return redirect('/admin/dashboard')
            ->with('success', 'Password berhasil diubah!');
    }
}