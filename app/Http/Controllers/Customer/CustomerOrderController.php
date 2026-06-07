<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerOrderController extends Controller
{
    // =========================================================================
    // METODE PEMBAYARAN YANG DITANGANI MIDTRANS SNAP
    // Semua selain 'cash' dan 'qris' (manual) diarahkan ke Midtrans
    // =========================================================================
    private const MIDTRANS_METHODS = [
        'gopay', 'ovo', 'dana', 'shopeepay',
        'bca', 'bni', 'bri', 'mandiri', 'permata',
        'credit_card', 'midtrans',
    ];

    // =========================================================================
    // STORE — Buat pesanan baru
    // =========================================================================
    public function store(Request $request)
    {
        $activeKodes = PaymentMethod::where('aktif', true)->pluck('kode')->toArray();

        $request->validate([
            'cart'           => 'required|string',
            'payment_method' => ['required', 'string', 'in:' . implode(',', $activeKodes)],
            'table_number'   => 'nullable|string|max:10',
            'note'           => 'nullable|string|max:500',
            'customer_name'  => 'required|string|max:100',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart) || !is_array($cart)) {
            return back()->withErrors(['cart' => 'Keranjang kosong atau tidak valid.']);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $menu = Menu::find($item['id']);
            if (!$menu) {
                return back()->withErrors(['cart' => 'Menu tidak ditemukan.']);
            }
            $subtotal += $menu->price * (int) $item['quantity'];
        }

        $total       = $subtotal + 2000; // biaya layanan Rp 2.000
        $tableNumber = $request->table_number;

        $order = DB::transaction(function () use ($request, $cart, $total, $tableNumber) {
            // Generate nomor antrian
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
                'customer_name'  => $request->customer_name,
                'note'           => $request->note,
                'payment_method' => $request->payment_method,
                'status'         => 'pending',
                'total'          => $total,
            ]);

            if ($tableNumber) {
                Meja::where('nomor_meja', $tableNumber)->update(['status' => 'terisi']);
            }

            foreach ($cart as $item) {
                $menu = Menu::find($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $menu->id,
                    'name'     => $menu->name,
                    'qty'      => (int) $item['quantity'],
                    'price'    => $menu->price,
                    'subtotal' => $menu->price * (int) $item['quantity'],
                    'notes'    => $item['notes'] ?? '',
                ]);
            }

            return $order;
        });

        // ─── Routing berdasarkan metode pembayaran ─────────────────────────

        // 1. MIDTRANS SNAP (GoPay, OVO, DANA, VA Bank, dll.)
        if (in_array($request->payment_method, self::MIDTRANS_METHODS)) {
            return $this->createMidtransTransaction($order);
        }

        // 2. QRIS manual (gambar QR dari admin)
        if ($request->payment_method === 'qris') {
            return redirect()->route('customer.order.qris', $order->id);
        }

        // 3. Cash — tunjukkan bill ke kasir
        if ($request->payment_method === 'cash') {
            return redirect()->route('customer.order.bill', $order->id);
        }

        return redirect()->route('customer.order.success', $order->id);
    }

    // =========================================================================
    // CREATE MIDTRANS TRANSACTION — Generate Snap Token
    // =========================================================================
    private function createMidtransTransaction(Order $order)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        // ID unik untuk transaksi ini
        $midtransOrderId = 'ORDER-' . $order->id . '-' . time();

        // Siapkan item detail
        $itemDetails = $order->items->map(fn($item) => [
            'id'       => 'MENU-' . $item->menu_id,
            'price'    => (int) $item->price,
            'quantity' => $item->qty,
            'name'     => substr($item->name, 0, 50), // max 50 karakter
        ])->toArray();

        // Biaya layanan
        $itemDetails[] = [
            'id'       => 'SERVICE-FEE',
            'price'    => 2000,
            'quantity' => 1,
            'name'     => 'Biaya Layanan',
        ];

        // Parameter transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => (int) $order->total,
            ],
            'item_details'    => $itemDetails,
            'customer_details' => [
                'first_name' => $order->customer_name ?? 'Customer',
                'email'      => 'customer@example.com',
            ],
            // Callbacks: kembalikan user ke halaman pembayaran kita setelah selesai
            // onClose / "Return to merchant's page" akan redirect ke sini
            'callbacks' => [
                'finish' => route('customer.order.midtrans.payment', $order->id),
            ],
            // Batasi metode pembayaran jika perlu (hapus untuk tampilkan semua)
            // 'enabled_payments' => ['gopay', 'bank_transfer', 'credit_card'],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan midtrans_order_id dan snap_token ke database
            $order->update([
    'midtrans_order_id' => $midtransOrderId,
    'snap_token' => $snapToken,
    'status' => 'waiting_payment',
]);

            return redirect()->route('customer.order.midtrans.payment', $order->id);

        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'trace'    => $e->getTraceAsString(),
            ]);

            // Fallback: redirect ke halaman sukses dengan pesan error
            return redirect()->route('customer.order.bill', $order->id)
                ->with('error', 'Gagal terhubung ke Midtrans. Silakan bayar ke kasir. (' . $e->getMessage() . ')');
        }
    }

    // =========================================================================
    // MIDTRANS PAYMENT PAGE — Halaman pembayaran Midtrans Snap
    // =========================================================================
    public function midtransPayment(int $id)
    {
        $order = Order::with('items')->findOrFail($id);

        // Pastikan order ini memang untuk Midtrans
        if (!in_array($order->payment_method, self::MIDTRANS_METHODS)) {
            return redirect()->route('customer.order.success', $order->id);
        }

        // Jika belum punya snap token, generate ulang
        if (!$order->snap_token) {
            return $this->createMidtransTransaction($order);
        }

        return view('customer.midtrans-payment', [
            'order'     => $order,
            'snapToken' => $order->snap_token,
            'clientKey' => config('midtrans.client_key'),
            'snapUrl'   => config('midtrans.snap_url', 'https://app.sandbox.midtrans.com/snap/snap.js'),
        ]);
    }

    // =========================================================================
    // MIDTRANS CONFIRM — Dipanggil frontend setelah popup Snap sukses
    // Verifikasi status ke Midtrans sebelum update status order
    // =========================================================================
    public function midtransConfirm(int $id, Request $request)
    {
        $order = Order::findOrFail($id);

        // Hanya proses jika order masih dalam status menunggu pembayaran
        if (!in_array($order->status, ['pending', 'waiting_payment'])) {
            return response()->json([
                'status'   => 'ok',
                'redirect' => route('customer.order.midtrans.receipt', $order->id),
            ]);
        }

        // Verifikasi ke Midtrans menggunakan midtrans_order_id
        if (!$order->midtrans_order_id) {
            return response()->json(['status' => 'error', 'message' => 'ID transaksi tidak ditemukan.'], 422);
        }

        try {
            \Midtrans\Config::$serverKey    = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

            $transactionStatus = \Midtrans\Transaction::status($order->midtrans_order_id);

            $txStatus    = $transactionStatus->transaction_status ?? null;
            $fraudStatus = $transactionStatus->fraud_status ?? 'accept';

            // Hanya set process jika benar-benar sukses di Midtrans
            $isPaid = ($txStatus === 'settlement') ||
                      ($txStatus === 'capture' && $fraudStatus === 'accept');

            if ($isPaid) {
                // Resolve metode bayar spesifik dari response Midtrans
                $resolvedMethod = $this->resolvePaymentMethodFromStatus($transactionStatus);

                $updateData = [
                    'status'         => 'process',
                    'payment_method' => $resolvedMethod,
                ];

                if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'confirmed_at')) {
                    $updateData['confirmed_at'] = now();
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'process_at')) {
                    $updateData['process_at'] = now();
                }

                $order->update($updateData);

                return response()->json([
                    'status'   => 'ok',
                    'redirect' => route('customer.order.midtrans.receipt', $order->id),
                ]);
            }

            // Pembayaran masih pending (VA belum dibayar, dll.)
            if ($txStatus === 'pending') {
                // Simpan metode bayar dari pending juga (misal: BCA VA sudah diketahui)
                $resolvedMethod = $this->resolvePaymentMethodFromStatus($transactionStatus);
                if ($resolvedMethod !== 'midtrans') {
                    $order->update(['payment_method' => $resolvedMethod]);
                }

                return response()->json([
                    'status'  => 'pending',
                    'message' => 'Pembayaran belum dikonfirmasi. Silakan selesaikan pembayaran.',
                ]);
            }

            // Pembayaran gagal / expire / cancel
            $order->update(['status' => 'cancelled']);
            return response()->json([
                'status'  => 'failed',
                'message' => 'Pembayaran gagal atau dibatalkan.',
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans confirm error: ' . $e->getMessage(), ['order_id' => $order->id]);
            return response()->json([
                'status'   => 'ok',
                'redirect' => route('customer.order.midtrans.receipt', $order->id),
            ]);
        }
    }

    // =========================================================================
    // MIDTRANS RECEIPT — Struk setelah pembayaran Midtrans
    // =========================================================================
    public function midtransReceipt(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        if (!in_array($order->payment_method, self::MIDTRANS_METHODS)) {
            return redirect()->route('customer.order.success', $order->id);
        }

        return view('customer.midtrans-receipt', compact('order'));
    }

    // =========================================================================
    // MIDTRANS WEBHOOK — Notifikasi otomatis dari server Midtrans
    // =========================================================================
    public function webhook(Request $request)
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

        try {
            $notification = new \Midtrans\Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;
            $midtransOrderId   = $notification->order_id;
            $paymentType       = $notification->payment_type ?? null; // gopay, bank_transfer, credit_card, dll.

            // Ambil ID order dari midtrans_order_id
            $order = Order::where('midtrans_order_id', $midtransOrderId)->first();

            if (!$order) {
                Log::warning('Webhook: Order tidak ditemukan', ['midtrans_order_id' => $midtransOrderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            Log::info('Midtrans webhook', [
                'order_id'           => $order->id,
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
                'payment_type'       => $paymentType,
            ]);

            // Normalkan payment_type ke kode yang lebih ramah
            $resolvedMethod = $this->resolvePaymentMethod($notification);

            // Update status order berdasarkan notifikasi Midtrans
            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'challenge') {
                    $order->update(['status' => 'waiting_payment']);
                } elseif ($fraudStatus === 'accept') {
                    $updateData = ['status' => 'process', 'payment_method' => $resolvedMethod];
                    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'process_at')) {
                        $updateData['process_at'] = now();
                    }
                    $order->update($updateData);
                }
            } elseif ($transactionStatus === 'settlement') {
                $updateData = ['status' => 'process', 'payment_method' => $resolvedMethod];
                if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'process_at')) {
                    $updateData['process_at'] = now();
                }
                $order->update($updateData);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update(['status' => 'cancelled']);
            } elseif ($transactionStatus === 'pending') {
                $order->update(['status' => 'waiting_payment']);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'OK']);
    }

    // =========================================================================
    // RESOLVE PAYMENT METHOD dari Notification (webhook)
    // =========================================================================
    private function resolvePaymentMethod($notification): string
    {
        return $this->resolveFromFields(
            $notification->payment_type ?? null,
            $notification->va_numbers[0]->bank ?? null,
            $notification->issuer ?? null,
            $notification->acquirer ?? null,
        );
    }

    // =========================================================================
    // RESOLVE PAYMENT METHOD dari Transaction::status (confirm)
    // =========================================================================
    private function resolvePaymentMethodFromStatus($txStatus): string
    {
        $type     = $txStatus->payment_type ?? null;
        $bank     = $txStatus->va_numbers[0]->bank ?? null;
        $issuer   = $txStatus->issuer ?? null;
        $acquirer = $txStatus->acquirer ?? null;

        return $this->resolveFromFields($type, $bank, $issuer, $acquirer);
    }

    // =========================================================================
    // RESOLVE — Core logic (dipakai oleh keduanya)
    // =========================================================================
    private function resolveFromFields(?string $type, ?string $bank, ?string $issuer, ?string $acquirer): string
    {
        $type = $type ?? 'midtrans';

        return match(true) {
            // E-wallet dengan issuer/acquirer spesifik
            in_array($type, ['gopay','wallet']) && in_array($issuer ?? $acquirer, ['ovo'])    => 'ovo',
            in_array($type, ['gopay','wallet']) && in_array($issuer ?? $acquirer, ['dana'])   => 'dana',
            $type === 'gopay'                                                                  => 'gopay',
            $type === 'shopeepay'                                                              => 'shopeepay',
            $type === 'ovo'                                                                    => 'ovo',
            $type === 'dana'                                                                   => 'dana',
            $type === 'qris'                                                                   => 'qris',
            $type === 'credit_card'                                                            => 'credit_card',
            $type === 'bank_transfer' && $bank === 'bca'                                       => 'bca',
            $type === 'bank_transfer' && $bank === 'bni'                                       => 'bni',
            $type === 'bank_transfer' && $bank === 'bri'                                       => 'bri',
            $type === 'bank_transfer' && $bank === 'mandiri'                                   => 'mandiri',
            $type === 'bank_transfer' && $bank === 'permata'                                   => 'permata',
            $type === 'echannel'                                                                => 'mandiri',
            default                                                                             => 'midtrans',
        };
    }

    // =========================================================================
    // QRIS MANUAL (existing)
    // =========================================================================
    public function qrisPayment(int $id)
    {
        $order = Order::with('items')->findOrFail($id);

        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.order.success', $order->id);
        }

        $amount  = (int) $order->total;
        $orderId = 'ORDER-' . $order->id . '-' . time();

        $qrisString =
            '00020101021226670016ID.CO.TELKOM.WWW011893600898' .
            '93600009150' . str_pad($order->id, 4, '0', STR_PAD_LEFT) .
            '0215ID' . strtoupper(substr(md5($orderId), 0, 10)) .
            '03130' . str_pad($order->queue_number, 5, '0', STR_PAD_LEFT) .
            '520458145303360' .
            '5405' . $amount .
            '5802ID' .
            '5915Cafe Tugas Akhir' .
            '6007Batam' .
            '6105291466' .
            '6304CAFE';

        return view('customer.qris-payment', compact('order', 'qrisString'));
    }

    public function qrisConfirm(int $id)
    {
        $order = Order::findOrFail($id);

        if (in_array($order->status, ['pending', 'waiting_payment'])) {
            $updateData = ['status' => 'process'];

            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'confirmed_at')) {
                $updateData['confirmed_at'] = now();
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'process_at')) {
                $updateData['process_at'] = now();
            }

            $order->update($updateData);
        }

        return response()->json([
            'status'   => 'ok',
            'redirect' => route('customer.order.qris.receipt', $order->id),
        ]);
    }

    public function qrisReceipt(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.order.success', $order->id);
        }

        return view('customer.qris-receipt', compact('order'));
    }

    // =========================================================================
    // CASH BILL (existing)
    // =========================================================================
    public function cashBill(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        if ($order->payment_method !== 'cash') {
            return redirect()->route('customer.order.success', $order->id);
        }

        return view('customer.cash-bill', compact('order'));
    }

    // =========================================================================
    // SUCCESS — hanya untuk cash (fallback)
    // Midtrans dan QRIS punya halaman receipt sendiri
    // =========================================================================
    public function success(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        // Guard: Midtrans punya halaman receipt sendiri
        if (in_array($order->payment_method, self::MIDTRANS_METHODS)) {
            return redirect()->route('customer.order.midtrans.receipt', $order->id);
        }

        // Guard: QRIS punya halaman receipt sendiri
        if ($order->payment_method === 'qris') {
            return redirect()->route('customer.order.qris.receipt', $order->id);
        }

        return view('customer.order-success', compact('order'));
    }
}