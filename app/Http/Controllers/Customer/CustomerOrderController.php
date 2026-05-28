<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    // =========================================
    // STORE — Simpan pesanan & arahkan sesuai metode
    // =========================================
    public function store(Request $request)
    {
        $activeKodes = PaymentMethod::where('aktif', true)->pluck('kode')->toArray();

        $request->validate([
            'cart'           => 'required|string',
            'payment_method' => ['required', 'string', 'in:' . implode(',', $activeKodes)],
            'table_number'   => 'nullable|string|max:10',
            'note'           => 'nullable|string|max:500',
        ], [
            'cart.required'           => 'Keranjang pesanan tidak boleh kosong.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in'       => 'Metode pembayaran tidak valid atau sedang tidak aktif.',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart) || !is_array($cart)) {
            return back()->withErrors(['cart' => 'Keranjang kosong atau data tidak valid.']);
        }

        foreach ($cart as $item) {
            if (empty($item['id']) || empty($item['quantity']) || (int) $item['quantity'] < 1) {
                return back()->withErrors(['cart' => 'Data item dalam keranjang tidak valid.']);
            }
            $menu = Menu::find($item['id']);
            if (!$menu) {
                return back()->withErrors(['cart' => 'Salah satu menu tidak ditemukan di sistem.']);
            }
            if ((int) $menu->status === 0) {
                return back()->withErrors(['cart' => "Menu '{$menu->name}' sudah tidak tersedia."]);
            }
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $menu      = Menu::find($item['id']);
            $subtotal += $menu->price * (int) $item['quantity'];
        }
        $total       = $subtotal + 2000;
        $tableNumber = $request->table_number;

        $order = DB::transaction(function () use ($request, $cart, $total, $tableNumber) {
            $lastOrder  = Order::lockForUpdate()->latest()->first();
            $nextNumber = 1;

            if ($lastOrder && $lastOrder->queue_number) {
                $lastNumber = (int) substr($lastOrder->queue_number, 2);
                $nextNumber = $lastNumber + 1;
            }

            $queueNumber = 'A-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'queue_number'   => $queueNumber,
                'table_number'   => $tableNumber,
                'note'           => $request->note,
                'payment_method' => $request->payment_method,
                'status'         => 'pending',
                'total'          => $total,
            ]);

            if ($tableNumber) {
                Meja::where('nomor_meja', $tableNumber)
                    ->update(['status' => 'terisi']);
            }

            foreach ($cart as $item) {
                $menu = Menu::find($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $item['id'],
                    'name'     => $menu->name,
                    'qty'      => (int) $item['quantity'],
                    'price'    => $menu->price,
                    'subtotal' => $menu->price * (int) $item['quantity'],
                    'notes'    => $item['notes'] ?? null,
                ]);
            }

            return $order;
        });

        Notification::kirim(
            ['kasir', 'dapur', 'admin'],
            'order_new',
            '🛎️ Pesanan Baru Masuk',
            "Pesanan {$order->queue_number} " .
                ($tableNumber ? "Meja {$tableNumber}" : "Take Away") .
                " — " . ucfirst($order->payment_method),
            $order
        );

        // Arahkan ke halaman QRIS
        if ($request->payment_method === 'qris') {
            return redirect()->route('customer.order.qris', $order->id);
        }

        return redirect()->route('customer.order.success', $order->id);
    }

    // =========================================
    // QRIS PAYMENT — Simulasi QRIS realistis (tanpa Midtrans)
    // =========================================
    public function qrisPayment(int $id)
    {
        $order = Order::with('items')->findOrFail($id);

        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.order.success', $order->id);
        }

        // Generate QRIS string format EMVCo yang realistis
        $amount   = (int) $order->total;
        $orderId  = 'ORDER-' . $order->id . '-' . time();

        $qrisString =
            '00020101021226670016ID.CO.TELKOM.WWW011893600898' .
            '93600009150' . str_pad($order->id, 4, '0', STR_PAD_LEFT) .
            '0215ID' . strtoupper(substr(md5($orderId), 0, 10)) .
            '03130' . str_pad($order->queue_number, 5, '0', STR_PAD_LEFT) .
            '520458145303360' .
            '5405' . $amount .
            '5802ID' .
            '5915Cafe Tugas Akhir' .
            '6007Batam  ' .
            '6105291466' .
            '6233' .
            '0510' . str_pad($order->id, 10, '0', STR_PAD_LEFT) .
            '6304CAFE';

        return view('customer.qris-payment', compact('order', 'qrisString'));
    }

    // =========================================
    // QRIS CONFIRM — Simulasi konfirmasi pembayaran berhasil
    // =========================================
    public function qrisConfirm(int $id)
    {
        $order = Order::findOrFail($id);

        if (in_array($order->status, ['pending', 'waiting_payment'])) {
            $order->update([
                'status'       => 'process',
                'confirmed_at' => now(),
                'process_at'   => now(),
            ]);

            Notification::kirim(
                ['kasir', 'dapur', 'admin'],
                'order_confirmed',
                '✅ Pembayaran QRIS Berhasil',
                "Pesanan {$order->queue_number} sudah dibayar via QRIS. Segera diproses!",
                $order
            );
        }

        return response()->json([
            'status'   => 'ok',
            'redirect' => route('customer.order.success', $order->id),
        ]);
    }

    // =========================================
    // MIDTRANS WEBHOOK — Auto update status order (tetap ada untuk produksi)
    // =========================================
    public function webhook(Request $request)
    {
        // Import Midtrans hanya jika kelas tersedia
        if (!class_exists(\Midtrans\Config::class)) {
            return response()->json(['message' => 'Midtrans not configured'], 200);
        }

        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        $notif             = new \Midtrans\Notification();
        $transactionStatus = $notif->transaction_status;
        $fraudStatus       = $notif->fraud_status;

        $orderId = explode('-', $notif->order_id)[1] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid order id'], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {

            $order->update([
                'status'       => 'process',
                'confirmed_at' => now(),
                'process_at'   => now(),
            ]);

            Notification::kirim(
                ['kasir', 'dapur', 'admin'],
                'order_confirmed',
                '✅ Pembayaran QRIS Berhasil',
                "Pesanan {$order->queue_number} sudah dibayar via QRIS. Segera diproses!",
                $order
            );

        } elseif ($transactionStatus === 'pending') {
            $order->update(['status' => 'pending']);

        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'OK']);
    }

    // =========================================
    // SUCCESS PAGE
    // =========================================
    public function success(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);
        return view('customer.order-success', compact('order'));
    }
}