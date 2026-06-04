<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Meja;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Schema;

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
    // POLLING NOTIF & LIVE UPDATE
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
                $q->where('payment_method', 'cash')
                    ->orWhere(function ($q2) {
                        $q2->where('payment_method', 'qris')
                            ->whereIn('status', [
                                'paid',
                                'process',
                                'done',
                                'delivered',
                            ]);
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

        $updateData = ['status' => 'process'];

        if (Schema::hasColumn('orders', 'uang_diterima')) {
            $updateData['uang_diterima'] = $request->input('uang_diterima', 0);
        }
        if (Schema::hasColumn('orders', 'confirmed_at')) {
            $updateData['confirmed_at'] = now();
        }
        if (Schema::hasColumn('orders', 'process_at')) {
            $updateData['process_at'] = now();
        }

        $order->update($updateData);

        if (class_exists(Notification::class)) {
            try {
                Notification::kirim(
                    'dapur',
                    'order_confirmed',
                    '🔥 Pesanan Dikonfirmasi',
                    "Pesanan {$order->queue_number} sudah dikonfirmasi kasir. Segera dimasak!",
                    $order
                );
            } catch (\Throwable $e) {}
        }

        return back()->with('success', 'Pesanan berhasil dikonfirmasi & diteruskan ke dapur 🔥');
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

        if ($order->table_number) {
            Meja::where('nomor_meja', $order->table_number)
                ->update(['status' => 'kosong']);
        }

        if (class_exists(Notification::class)) {
            try {
                Notification::kirim(
                    'admin',
                    'order_delivered',
                    '✅ Transaksi Selesai',
                    "Pesanan {$order->queue_number} sudah diantar & selesai.",
                    $order
                );
            } catch (\Throwable $e) {}
        }

        return back()->with('success', "{$order->queue_number} selesai diantar 🍽️");
    }


    // ═══════════════════════════════
    // TRANSAKSI
    // ═══════════════════════════════

    public function transaksi(Request $request)
    {
        $query = Order::with('items.menu')
            ->whereIn('status', ['process', 'done', 'delivered']);

        if ($request->filled('tanggal')) {
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

        if ($request->filled('tanggal')) {
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
    // ✅ PERBAIKAN: nama file & judul PDF kini menyertakan tanggal laporan
    // ═══════════════════════════════

    public function laporanPdf(Request $request)
    {
        $query = Order::with('items.menu')
            ->whereIn('status', ['process', 'done', 'delivered']);

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
            $label = $request->tanggal;
        } else {
            $query->whereDate('created_at', today());
            $label = today()->format('Y-m-d');
        }

        $orders       = $query->latest()->get();
        $totalOmset   = $orders->sum('total');

        // Format tanggal untuk ditampilkan di dalam PDF, misal: "04 Juni 2026"
        $tanggalLabel = \Carbon\Carbon::parse($label)->translatedFormat('d F Y');

        // Nama file download, misal: "laporan-penjualan-2026-06-04.pdf"
        $namaFile = 'laporan-penjualan-' . $label . '.pdf';

        $pdf = Pdf::loadView('kasir.laporan_pdf', compact('orders', 'totalOmset', 'tanggalLabel'));

        return $pdf->download($namaFile);
    }
}