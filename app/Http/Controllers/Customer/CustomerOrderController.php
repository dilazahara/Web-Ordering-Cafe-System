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
        'credit_card', 'midtrans', 'qris',
    ];

    // =========================================================================
    // STORE — Buat pesanan baru
    // =========================================================================
    public function store(Request $request)
    {
        $activeKodes = PaymentMethod::where('aktif', true)->pluck('kode')->toArray();
        $validKodes = array_merge($activeKodes, ['midtrans_snap']);

        $request->validate([
            'cart'           => 'required|string',
            'payment_method' => ['required', 'string', 'in:' . implode(',', $validKodes)],
            'table_number'   => 'nullable|string|max:10',
            'note'           => 'nullable|string|max:500',
            'customer_name'  => 'required|string|max:100',
        ]);

        $paymentMethod = $request->payment_method;
        if ($paymentMethod === 'midtrans_snap') {
            $midtransMethod = PaymentMethod::where('aktif', true)
                ->whereIn('kode', self::MIDTRANS_METHODS)
                ->first();
            $paymentMethod = $midtransMethod ? $midtransMethod->kode : 'midtrans';
        }

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

        $order = DB::transaction(function () use ($request, $cart, $total, $tableNumber, $paymentMethod) {
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
                'payment_method' => $paymentMethod,
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

        // Routing Berdasarkan Metode Pembayaran
        if (in_array($paymentMethod, self::MIDTRANS_METHODS)) {
            return $this->createMidtransTransaction($order);
        }

        if ($paymentMethod === 'qris') {
            return redirect()->route('customer.order.qris', $order->id);
        }

        if ($paymentMethod === 'cash') {
            return redirect()->route('customer.order.bill', $order->id);
        }

        return redirect()->route('customer.order.success', $order->id);
    }

    // =========================================================================
    // CREATE MIDTRANS TRANSACTION — Generate Snap Token
    // =========================================================================
    private function createMidtransTransaction(Order $order)
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $midtransOrderId = 'ORDER-' . $order->id . '-' . time();

        $itemDetails = $order->items->map(fn($item) => [
            'id'       => 'MENU-' . $item->menu_id,
            'price'    => (int) $item->price,
            'quantity' => $item->qty,
            'name'     => substr($item->name, 0, 50),
        ])->toArray();

        $itemDetails[] = [
            'id'       => 'SERVICE-FEE',
            'price'    => 2000,
            'quantity' => 1,
            'name'     => 'Biaya Layanan',
        ];

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
            // Callback bawaan Midtrans (fallback jika popup tidak sengaja tertutup di mobile browser)
            'callbacks' => [
                'finish'   => route('customer.order.midtrans.receipt', $order->id),
                'unfinish' => route('customer.order.midtrans.receipt', $order->id),
                'error'    => route('customer.order.midtrans.payment', $order->id),
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $order->update([
                'midtrans_order_id' => $midtransOrderId,
                'snap_token'        => $snapToken,
                'status'            => 'waiting_payment',
            ]);

            return redirect()->route('customer.order.midtrans.payment', $order->id);

        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);

            return redirect()->route('customer.order.bill', $order->id)
                ->with('error', 'Gagal terhubung ke Midtrans. Silakan bayar ke kasir. (' . $e->getMessage() . ')');
        }
    }

    // =========================================================================
    // MIDTRANS PAYMENT PAGE — Halaman pemicu Snap Popup resmi
    // =========================================================================
    public function midtransPayment(int $id)
    {
        $order = Order::with('items')->findOrFail($id);

        if (!in_array($order->payment_method, self::MIDTRANS_METHODS)) {
            return redirect()->route('customer.order.success', $order->id);
        }

        if (in_array($order->status, ['process', 'done', 'completed', 'delivered'])) {
            return redirect()->route('customer.order.midtrans.receipt', $order->id);
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('customer.home')
                ->with('error', 'Pesanan dibatalkan karena pembayaran gagal/kadaluarsa.');
        }

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
    // MIDTRANS RECEIPT — Tampilan Struk Akhir Pelanggan
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
    // MIDTRANS CHECK STATUS — Dipanggil oleh polling JS di halaman pembayaran
    // Route: POST /customer/order/midtrans/{id}/confirm
    // Name:  customer.order.midtrans.confirm
    // =========================================================================
    public function midtransCheckStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        // ── 1. Sudah selesai dari sisi DB (webhook sudah tiba lebih dulu) ──
        if (in_array($order->status, ['process', 'done', 'completed', 'delivered'])) {
            return response()->json([
                'status'   => 'ok',
                'redirect' => route('customer.order.midtrans.receipt', $order->id),
            ]);
        }

        // ── 2. Dibatalkan / expired ──
        if ($order->status === 'cancelled') {
            return response()->json(['status' => 'cancelled']);
        }

        // ── 3. Belum ada midtrans_order_id — tidak bisa cek ──
        if (!$order->midtrans_order_id) {
            return response()->json(['status' => 'pending']);
        }

        // ── 4. Tanya langsung ke Midtrans API ──
        try {
            \Midtrans\Config::$serverKey    = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

            $status = \Midtrans\Transaction::status($order->midtrans_order_id);

            $transactionStatus = $status->transaction_status ?? 'pending';
            $fraudStatus       = $status->fraud_status       ?? 'accept';

            Log::info('Midtrans CheckStatus', [
                'order_id'           => $order->id,
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
            ]);

            if ($transactionStatus === 'settlement'
                || ($transactionStatus === 'capture' && $fraudStatus === 'accept')
            ) {
                $resolvedMethod = $this->resolveFromFields(
                    $status->payment_type  ?? null,
                    $status->va_numbers[0]->bank ?? null,
                    $status->issuer  ?? null,
                    $status->acquirer ?? null,
                );

                $this->markAsProcessed($order, $resolvedMethod);

                return response()->json([
                    'status'   => 'ok',
                    'redirect' => route('customer.order.midtrans.receipt', $order->id),
                ]);
            }

            if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update(['status' => 'cancelled']);
                if ($order->table_number) {
                    Meja::where('nomor_meja', $order->table_number)
                        ->update(['status' => 'kosong']);
                }
                return response()->json(['status' => 'cancelled']);
            }

            return response()->json(['status' => 'pending']);

        } catch (\Exception $e) {
            Log::warning('Midtrans CheckStatus error: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);
            return response()->json(['status' => 'pending']);
        }
    }

    // =========================================================================
    // MIDTRANS WEBHOOK — Single Source of Truth status Pembayaran
    // =========================================================================
    public function webhook(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        \Midtrans\Config::$serverKey    = $serverKey;
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

        try {
            $notification = new \Midtrans\Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;
            $midtransOrderId   = $notification->order_id;
            $statusCode        = $notification->status_code;
            $grossAmount       = $notification->gross_amount;
            $signatureKey      = $notification->signature_key;

            // 1. Keamanan: Validasi Signature Key dari Server Midtrans
            $localSignature = hash("sha512", $midtransOrderId . $statusCode . $grossAmount . $serverKey);
            if ($localSignature !== $signatureKey) {
                Log::error('Webhook Security Alert: Invalid Signature Key.', ['midtrans_order_id' => $midtransOrderId]);
                return response()->json(['message' => 'Invalid Signature Key'], 403);
            }

            $order = Order::where('midtrans_order_id', $midtransOrderId)->first();
            if (!$order) {
                Log::warning('Webhook: Order tidak ditemukan', ['midtrans_order_id' => $midtransOrderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            Log::info('Midtrans Webhook Received', [
                'order_id'           => $order->id,
                'transaction_status' => $transactionStatus,
                'payment_type'       => $notification->payment_type ?? null,
            ]);

            // 2. Resolve metode pembayaran spesifik (VA Bank, GoPay, OVO, dll.)
            $resolvedMethod = $this->resolvePaymentMethod($notification);

            // 3. Mutasi status database berdasarkan response resmi Midtrans
            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'challenge') {
                    $order->update(['status' => 'waiting_payment']);
                } elseif ($fraudStatus === 'accept') {
                    $this->markAsProcessed($order, $resolvedMethod);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->markAsProcessed($order, $resolvedMethod);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update(['status' => 'cancelled']);
                
                // Lepas status meja kembali kosong jika transaksi batal/hangus
                if ($order->table_number) {
                    Meja::where('nomor_meja', $order->table_number)->update(['status' => 'kosong']);
                }
            } elseif ($transactionStatus === 'pending') {
                $order->update([
                    'status'         => 'waiting_payment', 
                    'payment_method' => $resolvedMethod
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'OK']);
    }

    // Helper update status lunas
    private function markAsProcessed(Order $order, string $paymentMethod)
    {
        $updateData = [
            'status'         => 'process',
            'payment_method' => $paymentMethod,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'confirmed_at')) {
            $updateData['confirmed_at'] = now();
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'process_at')) {
            $updateData['process_at'] = now();
        }

        $order->update($updateData);
    }

    // =========================================================================
    // RESOLVE PAYMENT METHOD secara aman dari properti Notification Webhook
    // =========================================================================
    private function resolvePaymentMethod($notification): string
    {
        $bank = null;
        if (isset($notification->va_numbers) && is_array($notification->va_numbers) && count($notification->va_numbers) > 0) {
            $bank = $notification->va_numbers[0]->bank ?? null;
        } elseif (isset($notification->bca_va_number)) {
            $bank = 'bca';
        } elseif (isset($notification->bni_va_number)) {
            $bank = 'bni';
        } elseif (isset($notification->bri_va_number)) {
            $bank = 'bri';
        } elseif (isset($notification->permata_va_number)) {
            $bank = 'permata';
        }

        return $this->resolveFromFields(
            $notification->payment_type ?? null,
            $bank,
            $notification->issuer ?? null,
            $notification->acquirer ?? null,
        );
    }

    private function resolveFromFields(?string $type, ?string $bank, ?string $issuer, ?string $acquirer): string
    {
        $type = $type ?? 'midtrans';

        return match(true) {
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
    // ALUR LAIN (MANUAL QRIS / CASH) — Tetap Dipertahankan
    // =========================================================================
    public function qrisPayment(int $id)
    {
        $order = Order::with('items')->findOrFail($id);
        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.order.success', $order->id);
        }
        $amount  = (int) $order->total;
        $orderId = 'ORDER-' . $order->id . '-' . time();
        $qrisString = '00020101021226670016ID.CO.TELKOM.WWW01189360089893600009150' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '0215ID' . strtoupper(substr(md5($orderId), 0, 10)) . '03130' . str_pad($order->queue_number, 5, '0', STR_PAD_LEFT) . '520458145303360' . '5405' . $amount . '5802ID' . '5915Cafe Tugas Akhir' . '6007Batam' . '6105291466' . '6304CAFE';
        return view('customer.qris-payment', compact('order', 'qrisString'));
    }

    public function qrisConfirm(int $id)
    {
        $order = Order::findOrFail($id);
        if (in_array($order->status, ['pending', 'waiting_payment'])) {
            $updateData = ['status' => 'process'];
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'confirmed_at')) { $updateData['confirmed_at'] = now(); }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'process_at')) { $updateData['process_at'] = now(); }
            $order->update($updateData);
        }
        return response()->json(['status' => 'ok', 'redirect' => route('customer.order.qris.receipt', $order->id)]);
    }

    public function qrisReceipt(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);
        if ($order->payment_method !== 'qris') { return redirect()->route('customer.order.success', $order->id); }
        return view('customer.qris-receipt', compact('order'));
    }

    public function cashBill(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);
        if ($order->payment_method !== 'cash') { return redirect()->route('customer.order.success', $order->id); }
        return view('customer.cash-bill', compact('order'));
    }

    public function success(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);
        if (in_array($order->payment_method, self::MIDTRANS_METHODS)) {
            return redirect()->route('customer.order.midtrans.receipt', $order->id);
        }
        if ($order->payment_method === 'qris') {
            return redirect()->route('customer.order.qris.receipt', $order->id);
        }
        return view('customer.order-success', compact('order'));
    }
}