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
        $midtransMethods = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans','qris'];

        $orders = Order::with('items.menu')
            ->whereDate('created_at', today())
            ->where(function ($q) use ($midtransMethods) {
                $q->where('payment_method', 'cash')
                  ->orWhere('payment_method', 'qris')
                  ->orWhere(function ($q3) use ($midtransMethods) {
                      $q3->whereIn('payment_method', $midtransMethods);
                  });
            })
            ->whereIn('status', [
                'pending',
                'waiting_payment',
                'paid',
                'process',
                'done',
                'ready_delivery',
                'ready_pickup',
                'delivered',
                'completed',
            ])
            ->latest()
            ->get();

        $mejas = Meja::all();

        $paymentMethodMap = \App\Models\PaymentMethod::all()->keyBy('kode');

        return view('kasir.pesanan', compact('orders', 'mejas', 'paymentMethodMap'));
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
            !in_array($order->status, ['pending', 'waiting_payment']),
            422,
            'Status pesanan tidak valid untuk dikonfirmasi.'
        );

        $uangDiterima = (float) $request->input('uang_diterima', 0);

        if ($uangDiterima > 0 && $uangDiterima < $order->total) {
            return back()->with(
                'error',
                'Uang yang diterima (Rp ' . number_format($uangDiterima, 0, ',', '.') .
                ') kurang dari total pesanan (Rp ' . number_format($order->total, 0, ',', '.') . ').'
            );
        }

        $updateData = ['status' => 'process'];

        if (Schema::hasColumn('orders', 'uang_diterima')) {
            $updateData['uang_diterima'] = $uangDiterima;
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
                    '🔥 Pesanan Cash Dikonfirmasi',
                    "Pesanan {$order->queue_number} sudah dibayar cash & dikonfirmasi kasir. Segera dimasak!",
                    $order
                );
            } catch (\Throwable $e) {}
        }

        return back()->with('success', "Pesanan {$order->queue_number} berhasil dikonfirmasi & diteruskan ke dapur 🔥");
    }


    // ═══════════════════════════════
    // SELESAI DIANTAR  (Dine In)
    // ═══════════════════════════════

    public function selesai(int $id)
    {
        $order = Order::findOrFail($id);

        // ✅ FIX: terima 'done' (legacy) ATAU 'ready_delivery' (status flow
        // final saat ini untuk Dine In) ATAU 'delivered' (sudah selesai
        // sebelumnya, idempotent).
        abort_if(
            !in_array($order->status, ['done', 'ready_delivery', 'delivered']),
            422,
            'Pesanan belum selesai dimasak atau diantar.'
        );

        $order->update(['status' => 'delivered']);

        // Hanya kosongkan meja untuk Dine In
        if (($order->order_type ?? 'dine_in') === 'dine_in' && $order->table_number) {
            $meja = Meja::where('nomor_meja', $order->table_number)->first();

            if ($meja) {
                $meja->update(['status' => 'kosong']);
                $meja->refreshQrToken();
            }
        }

        if (class_exists(Notification::class)) {
            try {
                Notification::kirim(
                    'admin',
                    'order_delivered',
                    '✅ Transaksi Selesai',
                    "Pesanan {$order->queue_number} sudah selesai & meja dikosongkan.",
                    $order
                );
            } catch (\Throwable $e) {}
        }

        return back()->with('success', "{$order->queue_number} selesai diantar 🍽️");
    }


    // ═══════════════════════════════════════════════════
    // SUDAH DIAMBIL  (Take Away only)
    // Kasir menekan tombol ini saat customer mengambil
    // pesanan Take Away di kasir → status: completed
    // ═══════════════════════════════════════════════════

    public function tandaiDiambil(int $id)
    {
        $order = Order::findOrFail($id);

        abort_if(
            ($order->order_type ?? 'dine_in') !== 'take_away',
            403,
            'Aksi ini hanya untuk pesanan Take Away.'
        );

        // ✅ FIX: terima 'done' ATAU 'ready_pickup' karena keduanya adalah
        // status valid "siap diambil" sesuai flow nyata di database.
        abort_if(
            !in_array($order->status, ['done', 'ready_pickup']),
            422,
            'Pesanan belum siap untuk diambil.'
        );

        $updateData = ['status' => 'completed'];

        if (Schema::hasColumn('orders', 'completed_at')) {
            $updateData['completed_at'] = now();
        }

        $order->update($updateData);

        if (class_exists(Notification::class)) {
            try {
                Notification::kirim(
                    'admin',
                    'order_completed',
                    '✅ Pesanan Take Away Diambil',
                    "Pesanan {$order->queue_number} sudah diambil customer.",
                    $order
                );
            } catch (\Throwable $e) {}
        }

        return back()->with('success', "Pesanan {$order->queue_number} sudah diambil customer ✅");
    }


    // ═══════════════════════════════
    // TRANSAKSI
    // ═══════════════════════════════

    public function transaksi(Request $request)
    {
        $query = Order::with('items.menu')
            ->whereIn('status', [
                'paid',
                'process',
                'done',
                'ready_delivery',
                'ready_pickup',
                'delivered',
                'completed',
            ]);

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
            ->whereIn('status', [
                'paid',
                'process',
                'done',
                'ready_delivery',
                'ready_pickup',
                'delivered',
                'completed',
            ]);

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
    // ═══════════════════════════════

    public function laporanPdf(Request $request)
    {
        $query = Order::with('items.menu')
            ->whereIn('status', [
                'paid',
                'process',
                'done',
                'ready_delivery',
                'ready_pickup',
                'delivered',
                'completed',
            ]);

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
            $label = $request->tanggal;
        } else {
            $query->whereDate('created_at', today());
            $label = today()->format('Y-m-d');
        }

        $orders       = $query->latest()->get();
        $totalOmset   = $orders->sum('total');
        $tanggalLabel = \Carbon\Carbon::parse($label)->translatedFormat('d F Y');
        $namaFile     = 'laporan-kasir-' . $label . '.pdf';

        $pdf = Pdf::loadView('kasir.laporan_pdf', compact('orders', 'totalOmset', 'tanggalLabel'));

        return $pdf->download($namaFile);
    }
}