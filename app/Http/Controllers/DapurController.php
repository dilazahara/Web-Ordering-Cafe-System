<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Meja;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DapurController extends Controller
{
    // ═══════════════════════════════
    // PESANAN MASUK
    // ═══════════════════════════════

    public function index()
    {
        $orders = Order::with(['items.menu'])
            ->where('status', 'process')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view(
            'dapur.pesanan',
            compact('orders')
        );
    }


    // ═══════════════════════════════
    // SEDANG DIPROSES
    // ═══════════════════════════════

    public function proses()
    {
        $orders = Order::with(['items.menu'])
            ->where('status', 'process')
            ->whereDate('created_at', today())
            ->orderBy('process_at', 'asc')
            ->get();

        return view(
            'dapur.proses',
            compact('orders')
        );
    }


    // ═══════════════════════════════
    // PESANAN SELESAI
    // ═══════════════════════════════

    public function selesaiView()
    {
        $orders = Order::with(['items.menu'])
            ->where('status', 'done')
            ->whereDate('created_at', today())
            ->orderBy('done_at', 'desc')
            ->get();

        return view(
            'dapur.selesai',
            compact('orders')
        );
    }


    // ═══════════════════════════════
    // TANDAI SELESAI
    // ═══════════════════════════════

    public function selesai(int $id)
    {
        $order = Order::findOrFail($id);

        // VALIDASI

        abort_if(
            $order->status !== 'process',
            422,
            'Pesanan belum diproses.'
        );

        // UPDATE STATUS

        $order->update([

            'status'  => 'done',

            'done_at' => now(),

        ]);


        // NOTIF KE PELAYAN

        Notification::kirim(

            'pelayan',

            'order_done',

            '🍽️ Makanan Siap Diantar',

            "Pesanan {$order->queue_number}" .
            ($order->table_number
                ? " meja {$order->table_number}"
                : '') .
            " sudah selesai dimasak.",

            $order

        );


        return back()->with(

            'success',

            "Pesanan {$order->queue_number} selesai dimasak!"

        );
    }


    // ═══════════════════════════════
    // PROFIL
    // ═══════════════════════════════

    public function profil()
    {
        return view('dapur.account.profil');
    }


    public function updateProfil(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|unique:users,email,' . Auth::id(),
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();

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

        // UPLOAD AVATAR
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Simpan foto baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        // ✅ Redirect ke /dapur/proses agar flash success muncul di proses.blade.php
        return redirect('/dapur/proses')
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
        return view('dapur.account.ganti-sandi');
    }


    public function updatePassword(Request $request)
    {
        $request->validate([

            'current_password' => 'required',

            'new_password' =>
                'required|min:8|confirmed',

        ]);

        /** @var User $user */
        $user = Auth::user();


        // CEK PASSWORD LAMA

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


        // UPDATE PASSWORD BARU

        $user->update([

            'password' => Hash::make(
                $request->new_password
            )

        ]);


        return redirect('/dapur/proses')
            ->with(
                'success',
                'Password berhasil diubah!'
            );
    }
}