<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Meja;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class KasirController extends Controller
{
    // ═══════════════════════════════
    // DASHBOARD
    // ═══════════════════════════════

    public function dashboard()
    {
        $orders = Order::with('items.menu')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('kasir.dashboard', compact('orders'));
    }


    // ═══════════════════════════════
    // POLLING NOTIF
    // ═══════════════════════════════

    public function poll()
    {
        $orders = Order::with('items.menu')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return response()->json($orders);
    }


    // ═══════════════════════════════
    // PESANAN
    // ═══════════════════════════════

    public function pesanan()
    {
        $orders = Order::with('items.menu')
            ->whereDate('created_at', today())
            ->where(function ($q) {

                // CASH — semua status
                $q->where('payment_method', 'cash')

                // QRIS — sudah bukan pending
                ->orWhere(function ($q2) {
                    $q2->where('payment_method', 'qris')
                        ->where('status', '!=', 'pending');
                });

            })
            ->latest()
            ->get();

        $mejas = Meja::all();

        return view('kasir.pesanan', compact('orders', 'mejas'));
    }


    // ═══════════════════════════════
    // KONFIRMASI CASH
    // ═══════════════════════════════

    public function konfirmasi(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        abort_if(
            $order->payment_method !== 'cash',
            403,
            'Hanya untuk pembayaran cash.'
        );

        abort_if(
            $order->status !== 'pending',
            422,
            'Status pesanan tidak valid.'
        );

        $order->update([
            'status'        => 'process',
            'uang_diterima' => $request->input('uang_diterima', 0),
            'confirmed_at'  => now(),
            'process_at'    => now(),
        ]);

        // Notifikasi ke dapur: pesanan dikonfirmasi, segera masak
        Notification::kirim(
            'dapur',
            'order_confirmed',
            '🔥 Pesanan Dikonfirmasi',
            "Pesanan {$order->queue_number} sudah dikonfirmasi kasir. Segera dimasak!",
            $order
        );

        return back()->with('success', 'Pesanan langsung diproses dapur 🔥');
    }


    // ═══════════════════════════════
    // SELESAI DIANTAR
    // ═══════════════════════════════

    public function selesai(int $id)
    {
        $order = Order::findOrFail($id);

        abort_if(
            !in_array($order->status, ['process', 'done']),
            422,
            'Status tidak valid.'
        );

        $order->update(['status' => 'delivered']);

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

        // Notifikasi ke admin: transaksi selesai
        Notification::kirim(
            'admin',
            'order_delivered',
            '✅ Transaksi Selesai',
            "Pesanan {$order->queue_number} sudah diantar & selesai.",
            $order
        );

        return back()->with('success', "{$order->queue_number} selesai diantar 🍽️");
    }


    // ═══════════════════════════════
    // TRANSAKSI
    // ═══════════════════════════════

    public function transaksi(Request $request)
    {
        $query = Order::with('items.menu')
            ->whereIn('status', ['process', 'done', 'delivered']);

        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        } else {
            $query->whereDate('created_at', today());
        }

        $orders = $query->latest()->get();

        return view('kasir.transaksi', compact('orders'));
    }


    // ═══════════════════════════════
    // DETAIL PESANAN
    // ═══════════════════════════════

    public function detail(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        return view('kasir.detail', compact('order'));
    }


    // ═══════════════════════════════
    // LAPORAN
    // ═══════════════════════════════

    public function laporan(Request $request)
    {
        $query = Order::with('items.menu')
            ->whereIn('status', ['process', 'done', 'delivered']);

        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        } else {
            $query->whereDate('created_at', today());
        }

        $orders     = $query->latest()->get();
        $totalOmset = $orders->sum('total');

        return view('kasir.laporan', compact('orders', 'totalOmset'));
    }


    // ═══════════════════════════════
    // EXPORT PDF
    // ═══════════════════════════════

    public function laporanPdf(Request $request)
    {
        $query = Order::with('items.menu')
            ->whereIn('status', ['process', 'done', 'delivered']);

        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        } else {
            $query->whereDate('created_at', today());
        }

        $orders     = $query->latest()->get();
        $totalOmset = $orders->sum('total');

        $pdf = Pdf::loadView(
            'kasir.laporan_pdf',
            compact('orders', 'totalOmset')
        );

        return $pdf->download(
            'laporan-kasir-' . now()->format('Y-m-d') . '.pdf'
        );
    }
}