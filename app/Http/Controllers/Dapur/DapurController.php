<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DapurController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.menu'])
            ->where('status', 'process')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('dapur.pesanan', compact('orders'));
    }

    public function proses()
    {
        $query = Order::with(['items.menu'])
            ->where('status', 'process')
            ->whereDate('created_at', today());

        if (Schema::hasColumn('orders', 'process_at')) {
            $query->orderBy('process_at', 'asc');
        } else {
            $query->orderBy('created_at', 'asc');
        }

        $orders = $query->get();

        $totalSelesaiHariIni = Order::where('status', 'done')
            ->whereDate('created_at', today())
            ->count();

        $rataRataWaktu = 0;

        if (
            Schema::hasColumn('orders', 'process_at') &&
            Schema::hasColumn('orders', 'done_at')
        ) {
            $selesaiHariIni = Order::where('status', 'done')
                ->whereDate('created_at', today())
                ->whereNotNull('process_at')
                ->whereNotNull('done_at')
                ->get();

            if ($selesaiHariIni->isNotEmpty()) {
                $totalMenit = $selesaiHariIni->sum(function ($o) {
                    return Carbon::parse($o->process_at)
                        ->diffInMinutes(Carbon::parse($o->done_at));
                });

                $rataRataWaktu = round(
                    $totalMenit / $selesaiHariIni->count()
                );
            }
        }

        return view('dapur.proses', compact(
            'orders',
            'totalSelesaiHariIni',
            'rataRataWaktu'
        ));
    }

    public function selesaiView()
    {
        $query = Order::with(['items.menu'])
            ->where('status', 'done')
            ->whereDate('created_at', today());

        if (Schema::hasColumn('orders', 'done_at')) {
            $query->orderBy('done_at', 'desc');
        } else {
            $query->latest();
        }

        $orders = $query->get();

        return view('dapur.selesai', compact('orders'));
    }

    public function selesai(int $id)
    {
        $order = Order::findOrFail($id);

        abort_if(
            $order->status !== 'process',
            422,
            'Pesanan belum diproses.'
        );

        $updateData = [
            'status' => 'done',
        ];

        if (Schema::hasColumn('orders', 'done_at')) {
            $updateData['done_at'] = now();
        }

        $order->update($updateData);

        if (class_exists(Notification::class)) {
            try {
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
            } catch (\Throwable $e) {
                // skip
            }
        }

        return back()->with(
            'success',
            "Pesanan {$order->queue_number} selesai dimasak!"
        );
    }

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

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->hasFile('avatar')) {
            if (
                $user->avatar &&
                Storage::disk('public')->exists($user->avatar)
            ) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request
                ->file('avatar')
                ->store('avatars', 'public');
        }

        $user->save();

        return redirect('/dapur/proses')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function gantiSandi()
    {
        return view('dapur.account.ganti-sandi');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {
            return back()->withErrors([
                'current_password' =>
                    'Password lama yang kamu masukkan salah!'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect('/dapur/proses')
            ->with('success', 'Password berhasil diubah!');
    }

    public function pollOrders()
    {
        $orders = Order::where('status', 'process')
            ->select('id')
            ->get();

        $totalSelesai = Order::where('status', 'done')
            ->whereDate('created_at', today())
            ->count();

        $rataRataWaktu = 0;

        if (
            Schema::hasColumn('orders', 'process_at') &&
            Schema::hasColumn('orders', 'done_at')
        ) {
            $selesaiHariIni = Order::where('status', 'done')
                ->whereDate('created_at', today())
                ->whereNotNull('process_at')
                ->whereNotNull('done_at')
                ->get();

            if ($selesaiHariIni->isNotEmpty()) {
                $totalMenit = $selesaiHariIni->sum(function ($o) {
                    return Carbon::parse($o->process_at)
                        ->diffInMinutes(Carbon::parse($o->done_at));
                });

                $rataRataWaktu = round(
                    $totalMenit / $selesaiHariIni->count()
                );
            }
        }

        return response()->json([
            'orders' => $orders,
            'totalSelesai' => $totalSelesai,
            'rataRataWaktu' => $rataRataWaktu,
        ]);
    }
}