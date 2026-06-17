<?php

namespace App\Http\Controllers\Pelayan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Meja;
use App\Models\Notification;
use Illuminate\Http\Request;

class PelayanController extends Controller
{
    // ═══════════════════════════════
    // ANTAR MAKANAN
    // ✅ PATCH: hanya tampilkan Dine In (order_type = 'dine_in')
    // Take Away tidak perlu diantar — customer ambil sendiri di kasir
    // ═══════════════════════════════

    public function antar()
    {
        $orders = Order::with(['items.menu'])
            ->where('status', 'ready_delivery')
            // ✅ PATCH: filter hanya Dine In
            ->where('order_type', 'dine_in')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('pelayan.antar', compact('orders'));
    }


    // ═══════════════════════════════
    // REALTIME POLL
    // ✅ PATCH: hanya poll Dine In agar counter badge pelayan tidak
    // menghitung pesanan Take Away yang tidak perlu diantar
    // ═══════════════════════════════

    public function poll()
    {
        $orders = Order::with(['items.menu'])
            ->where('status', 'ready_delivery')
            // ✅ PATCH: filter hanya Dine In
            ->where('order_type', 'dine_in')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return response()->json($orders);
    }


    // ═══════════════════════════════
    // STATUS MEJA
    // (tidak ada perubahan — meja hanya relevan untuk Dine In)
    // ═══════════════════════════════

    public function meja()
    {
        $ordersAktif = Order::whereIn('status', ['pending', 'process', 'done'])
            ->whereDate('created_at', today())
            ->orderBy('table_number')
            ->get()
            ->groupBy('table_number');

        $totalMeja = 20;

        return view('pelayan.meja', compact('ordersAktif', 'totalMeja'));
    }


    // ═══════════════════════════════
    // TANDAI SUDAH DIANTAR  (Dine In only)
    // ✅ PATCH: tambahkan guard order_type agar Take Away tidak bisa
    // masuk endpoint ini secara tidak sengaja
    // ═══════════════════════════════

    public function tandaiDiantar(int $id)
    {
        $order = Order::findOrFail($id);

        // Guard: hanya Dine In yang bisa ditandai diantar oleh pelayan
        abort_if(
            ($order->order_type ?? 'dine_in') !== 'dine_in',
            403,
            'Pesanan Take Away tidak perlu diantar oleh pelayan.'
        );

        // Validasi status
       abort_if(
    $order->status !== 'ready_delivery',
    422,
    'Pesanan belum siap diantar.'
);

        $order->update(['status' => 'delivered']);

        // Auto status meja → kosong
        if ($order->table_number) {
            Meja::where('nomor_meja', $order->table_number)
                ->update(['status' => 'kosong']);
        }

        // Notif ke kasir & admin
        Notification::kirim(
            'kasir',
            'order_delivered',
            '✅ Pesanan Berhasil Diantar',
            "Pesanan {$order->queue_number}" .
                ($order->table_number ? " Meja {$order->table_number}" : '') .
                " sudah diantar ke pelanggan.",
            $order
        );

        return back()->with(
            'success',
            "Pesanan meja {$order->table_number} berhasil diantar ✅"
        );
    }
}