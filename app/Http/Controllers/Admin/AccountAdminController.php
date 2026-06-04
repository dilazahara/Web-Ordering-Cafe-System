<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountAdminController extends Controller
{
    // =====================================
    // PROFIL
    // =====================================

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

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        // Ganti password hanya jika current_password dan new_password terisi
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

        $user->save();

        return redirect()->route('admin.account.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    // =====================================
    // VERIFY PASSWORD (AJAX)
    // Dipanggil sebelum user bisa ganti password.
    // Hanya cek kecocokan, tidak menyimpan apapun.
    // =====================================

    public function verifyPassword(Request $request)
    {
        $request->validate(['password' => 'required']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Password salah, coba lagi.',
        ], 422);
    }

    // =====================================
    // UPDATE AVATAR (AJAX — otomatis)
    // =====================================

    public function updateAvatar(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ── HAPUS AVATAR ──
        if ($request->input('delete_avatar') == '1') {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
            $user->save();

            return response()->json(['success' => true, 'message' => 'Foto profil berhasil dihapus!']);
        }

        // ── SIMPAN AVATAR CROP (base64) ──
        if ($request->filled('avatar_cropped')) {
            $base64 = $request->input('avatar_cropped');

            if (str_contains($base64, ',')) {
                $base64 = explode(',', $base64)[1];
            }

            $imageData = base64_decode($base64);

            if ($imageData === false || strlen($imageData) === 0) {
                return response()->json(['success' => false, 'message' => 'Data foto tidak valid.'], 422);
            }

            $filename = 'avatars/' . $user->id . '_' . time() . '.jpg';

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            Storage::disk('public')->put($filename, $imageData);
            $user->avatar = $filename;
            $user->save();

            return response()->json([
                'success'    => true,
                'message'    => 'Foto profil berhasil diperbarui!',
                'avatar_url' => asset('storage/' . $filename),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada data foto.'], 422);
    }

    // =====================================
    // GANTI PASSWORD (halaman terpisah)
    // =====================================

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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama yang kamu masukkan salah!'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.account.profil')
            ->with('success', 'Password berhasil diubah!');
    }
}