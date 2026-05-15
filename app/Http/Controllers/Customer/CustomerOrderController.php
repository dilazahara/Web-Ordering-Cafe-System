<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\Meja;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    // =========================================
    // STORE — Simpan pesanan & arahkan sesuai metode
    // =========================================
    public function store(Request $request)
    {
        // Ambil semua kode metode yang aktif dari DB (dinamis)
        $activeKodes = PaymentMethod::where('aktif', true)->pluck('kode')->toArray();

        $request->validate([
            'cart'           => 'required|string',
            'payment_method' => ['required', 'string', 'in:' . implode(',', $activeKodes)],
            'table_number'   => 'nullable|string|max:10',
            'note'           => 'nullable|string|max:500',
        ], [
            'payment_method.in' => 'Metode pembayaran tidak valid atau sedang tidak aktif.',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Keranjang kosong.']);
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $total    = $subtotal + 2000; // biaya layanan

        // Tentukan table_number dan order_type berdasarkan input
        $tableNumber = $request->table_number;


        $lastOrder = Order::latest()->first();

$nextNumber = 1;

if ($lastOrder && $lastOrder->queue_number) {

    $lastNumber = (int) substr($lastOrder->queue_number, 2);

    $nextNumber = $lastNumber + 1;
}

$queueNumber = 'A-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        // Buat order
        $order = Order::create([
            'queue_number' => $queueNumber,
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

        // Simpan item pesanan
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id'  => $item['id'],
                'name'     => $item['name'],
                'qty'      => $item['quantity'],
                'price'    => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
                'notes'    => $item['notes'] ?? null,
            ]);
        }

        // ─── NOTIFIKASI REALTIME ────────────────────────────────
        Notification::kirim(
            ['kasir', 'dapur', 'admin'],
            'order_new',
            '🛎️ Pesanan Baru Masuk',
            "Pesanan {$order->queue_number} " .
                ($tableNumber ? "Meja {$tableNumber}" : "Take Away") .
                " — " . ucfirst($order->payment_method),
            $order
        );

        // ─── ALUR BERDASARKAN METODE PEMBAYARAN ──────────────────
        if ($request->payment_method === 'qris') {
            return redirect()->route('customer.order.qris', $order->id);
        }

        return redirect()->route('customer.order.success', $order->id);
    }

    // =========================================
    // QRIS PAYMENT PAGE — Tampilkan barcode QRIS
    // =========================================
    public function qrisPayment(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        $qrisMethod   = PaymentMethod::where('kode', 'qris')->first();
        $qrisImageUrl = $qrisMethod?->qris_image
            ? asset('storage/' . $qrisMethod->qris_image)
            : null;

        return view('customer.qris-payment', compact('order', 'qrisImageUrl'));
    }

    // =========================================
    // ORDER SUCCESS — Tampilkan halaman sukses
    // =========================================
    public function success(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        // Jika QRIS dan masih pending (customer konfirmasi dari halaman QRIS)
        if ($order->payment_method === 'qris' && $order->status === 'pending') {
            $order->status = 'lunas';
            $order->save();
        }

        return view('customer.order-success', compact('order'));
    }
}
