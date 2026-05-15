<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Meja;
use App\Models\Notification;
use Illuminate\Http\Request;

class PelayanController extends Controller
{
    // ═══════════════════════════════
    // ANTAR MAKANAN
    // ═══════════════════════════════

    public function antar()
    {
        $orders = Order::with([
                'items.menu'
            ])

            ->where(
                'status',
                'done'
            )

            ->whereDate(
                'created_at',
                today()
            )

            ->latest()

            ->get();

        return view(
            'pelayan.antar',
            compact('orders')
        );
    }


    // ═══════════════════════════════
    // REALTIME POLL
    // ═══════════════════════════════

    public function poll()
    {
        $orders = Order::with([
                'items.menu'
            ])

            ->where(
                'status',
                'done'
            )

            ->whereDate(
                'created_at',
                today()
            )

            ->latest()

            ->get();

        return response()->json(
            $orders
        );
    }


    // ═══════════════════════════════
    // STATUS MEJA
    // ═══════════════════════════════

    public function meja()
    {
        // ORDER AKTIF

        $ordersAktif = Order::whereIn(

                'status',

                [
                    'pending',
                    'process',
                    'done'
                ]

            )

            ->whereDate(
                'created_at',
                today()
            )

            ->orderBy(
                'table_number'
            )

            ->get()

            ->groupBy(
                'table_number'
            );

        // TOTAL MEJA

        $totalMeja = 20;

        return view(

            'pelayan.meja',

            compact(
                'ordersAktif',
                'totalMeja'
            )

        );
    }


    // ═══════════════════════════════
    // TANDAI SUDAH DIANTAR
    // ═══════════════════════════════

    public function tandaiDiantar(int $id)
    {
        $order = Order::findOrFail($id);

        // VALIDASI STATUS

        abort_if(

            $order->status !== 'done',

            422,

            'Pesanan belum siap diantar.'

        );


        // UPDATE STATUS ORDER

        $order->update([

            'status' => 'delivered'

        ]);


        // =====================================
        // AUTO STATUS MEJA = KOSONG
        // =====================================

        if ($order->table_number) {

            Meja::where(
                'nomor_meja',
                $order->table_number
            )->update([
                'status' => 'kosong'
            ]);

        }


        // NOTIF KE KASIR

        Notification::kirim(

            'kasir',

            'order_delivered',

            '✅ Pesanan Berhasil Diantar',

            "Pesanan {$order->queue_number}" .

                ($order->table_number
                    ? " Meja {$order->table_number}"
                    : '') .

                " sudah diantar ke pelanggan.",

            $order

        );


        return back()->with(

            'success',

            "Pesanan meja {$order->table_number} berhasil diantar ✅"

        );
    }
}