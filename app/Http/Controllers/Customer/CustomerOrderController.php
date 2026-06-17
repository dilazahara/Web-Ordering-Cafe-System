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
    // =========================================================================
    private const MIDTRANS_METHODS = [
        'gopay', 'ovo', 'dana', 'shopeepay',
        'bca', 'bni', 'bri', 'mandiri', 'permata',
        'credit_card', 'midtrans', 'qris',
    ];

    private const PAYMENT_METHOD_LABELS = [
        'gopay'       => 'GoPay',
        'ovo'         => 'OVO',
        'dana'        => 'DANA',
        'shopeepay'   => 'ShopeePay',
        'bca'         => 'BCA Virtual Account',
        'bni'         => 'BNI Virtual Account',
        'bri'         => 'BRI Virtual Account',
        'mandiri'     => 'Mandiri Virtual Account',
        'permata'     => 'Permata Virtual Account',
        'credit_card' => 'Kartu Kredit',
        'qris'        => 'QRIS',
        'midtrans'    => 'Online (Midtrans)',
    ];

    // =========================================================================
    // STORE — Buat pesanan baru
    // =========================================================================
    public function store(Request $request)
    {
        $orderType = $request->input('order_type', 'dine_in');
        if (! in_array($orderType, ['dine_in', 'take_away'])) {
            $orderType = 'dine_in';
        }

        $tableNumber = null;
        $meja        = null;

        if ($orderType === 'dine_in') {
            $tableNumber = session('table_number');

            if (empty($tableNumber)) {
                return redirect()->route('customer.scan.required')
                    ->with('error', 'Silakan scan QR meja terlebih dahulu.');
            }

            $meja = Meja::where('nomor_meja', $tableNumber)->first();
            if (! $meja) {
                session()->forget('table_number');
                return redirect()->route('customer.scan.required')
                    ->with('error', 'Session meja tidak valid. Silakan scan QR meja lagi.');
            }
        }

        $activeKodes = PaymentMethod::where('aktif', true)->pluck('kode')->toArray();
        $validKodes  = array_merge($activeKodes, ['midtrans_snap']);

        $request->validate([
            'cart'           => 'required|string',
            'payment_method' => ['required', 'string', 'in:' . implode(',', $validKodes)],
            'note'           => 'nullable|string|max:500',
            'customer_name'  => 'required|string|max:100',
            'order_type'     => 'nullable|string|in:dine_in,take_away',
        ]);

        $paymentMethod = $request->payment_method;
        if ($paymentMethod === 'midtrans_snap') {
            $midtransMethod = PaymentMethod::where('aktif', true)
                ->whereIn('kode', self::MIDTRANS_METHODS)
                ->first();
            $paymentMethod = $midtransMethod ? $midtransMethod->kode : 'midtrans';
        }

        $cart = json_decode($request->cart, true);

        if (empty($cart) || ! is_array($cart)) {
            return back()->withErrors(['cart' => 'Keranjang kosong atau tidak valid.']);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $menu = Menu::find($item['id']);
            if (! $menu) {
                return back()->withErrors(['cart' => 'Menu tidak ditemukan.']);
            }
            $itemPrice = isset($item['price']) && (int)$item['price'] >= (int)$menu->price
                ? (int) $item['price']
                : (int) $menu->price;
            $subtotal += $itemPrice * (int) $item['quantity'];
        }

        $total = $subtotal + 2000;

        $order = DB::transaction(function () use ($request, $cart, $total, $tableNumber, $paymentMethod, $orderType, $meja) {
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
                'order_type'     => $orderType,
                'note'           => $request->note,
                'payment_method' => $paymentMethod,
                'status'         => 'pending',
                'total'          => $total,
            ]);

            if ($meja) {
                $meja->update(['status' => 'terisi']);
            }

            foreach ($cart as $item) {
                $menu = Menu::find($item['id']);
                $itemPrice = isset($item['price']) && (int)$item['price'] >= (int)$menu->price
                    ? (int) $item['price']
                    : (int) $menu->price;

                $basePrice    = (int) $menu->price;
                $addonDetails = (isset($item['addons']) && is_array($item['addons']))
                    ? array_map(fn($a) => [
                        'id'    => (int)   ($a['id']    ?? 0),
                        'name'  => (string)($a['name']  ?? ''),
                        'price' => (int)   ($a['price'] ?? 0),
                      ], $item['addons'])
                    : [];

                OrderItem::create([
                    'order_id'      => $order->id,
                    'menu_id'       => $menu->id,
                    'name'          => $menu->name,
                    'qty'           => (int) $item['quantity'],
                    'price'         => $itemPrice,
                    'base_price'    => $basePrice,
                    'addon_details' => $addonDetails ?: null,
                    'subtotal'      => $itemPrice * (int) $item['quantity'],
                    'notes'         => $item['notes'] ?? '',
                ]);
            }

            return $order;
        });

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
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $order->customer_name ?? 'Customer',
                'email'      => 'customer@example.com',
            ],
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
            Log::error('Midtrans createTransaction error: ' . $e->getMessage(), ['order_id' => $order->id]);

            return redirect()->route('customer.order.bill', $order->id)
                ->with('error', 'Gagal terhubung ke Midtrans. Silakan bayar ke kasir. (' . $e->getMessage() . ')');
        }
    }

    // =========================================================================
    // HELPER: Validasi kepemilikan order oleh session meja saat ini
    // =========================================================================
    private function authorizeOrderBySession(Order $order): bool
    {
        if (($order->order_type ?? 'dine_in') === 'take_away') {
            return true;
        }

        $sessionTable = session('table_number');

        if (empty($sessionTable)) {
            return false;
        }

        return (string) $order->table_number === (string) $sessionTable;
    }

    private function denyOrderAccess(Request $request, string $pesan = 'Anda tidak memiliki akses ke pesanan ini.')
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['error' => 'forbidden', 'message' => $pesan], 403);
        }

        return redirect()->route('customer.home')->with('error', $pesan);
    }

    // =========================================================================
    // CASH BILL
    // =========================================================================
    public function cashBill(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        if (! $this->authorizeOrderBySession($order)) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        if ($order->payment_method !== 'cash') {
            return redirect()->route('customer.order.success', $order->id);
        }

        return view('customer.cash-bill', compact('order'));
    }

    // =========================================================================
    // MIDTRANS PAYMENT PAGE
    // =========================================================================
    public function midtransPayment(int $id)
    {
        $order = Order::with('items')->findOrFail($id);

        if (! $this->authorizeOrderBySession($order)) {
            abort(403, 'Anda tidak memiliki akses ke halaman pembayaran ini.');
        }

        if (! in_array($order->payment_method, self::MIDTRANS_METHODS)) {
            return redirect()->route('customer.order.success', $order->id);
        }

        if (in_array($order->status, ['process', 'done', 'completed', 'delivered'])) {
            return redirect()->route('customer.order.midtrans.receipt', $order->id);
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('customer.home')
                ->with('error', 'Pesanan dibatalkan karena pembayaran gagal/kadaluarsa.');
        }

        if (! $order->snap_token) {
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
    // MIDTRANS RECEIPT
    // =========================================================================
    public function midtransReceipt(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        if (! $this->authorizeOrderBySession($order)) {
            abort(403, 'Anda tidak memiliki akses ke struk ini.');
        }

        if (! in_array($order->payment_method, self::MIDTRANS_METHODS)) {
            return redirect()->route('customer.order.success', $order->id);
        }

        return view('customer.midtrans-receipt', compact('order'));
    }

    // =========================================================================
// MIDTRANS CONFIRM ALIAS
// =========================================================================
public function midtransConfirm(Request $request, int $id)
{
    return $this->midtransCheckStatus($request, $id);
}

    // =========================================================================
    // MIDTRANS CHECK STATUS (dipanggil dari onSuccess blade via AJAX)
    // ✅ FIX: Pisahkan catch DB error vs API error supaya tidak silent fail
    // =========================================================================
    public function midtransCheckStatus(Request $request, int $id)
    {
        Log::info('[CheckStatus] START', [
            'order_id'      => $id,
            'ip'            => $request->ip(),
            'session_table' => session('table_number'),
        ]);

        $order = Order::findOrFail($id);

        if (! $this->authorizeOrderBySession($order)) {
            Log::warning('[CheckStatus] FORBIDDEN', ['order_id' => $id]);
            return response()->json(['error' => 'forbidden'], 403);
        }

        // Sudah diproses sebelumnya — langsung kembalikan redirect
        if (in_array($order->status, ['process', 'done', 'completed', 'delivered'])) {
            Log::info('[CheckStatus] Already processed', ['order_id' => $id, 'status' => $order->status]);
            return response()->json([
                'status'   => 'ok',
                'redirect' => route('customer.order.midtrans.receipt', $order->id),
            ]);
        }

        if ($order->status === 'cancelled') {
            return response()->json(['status' => 'cancelled']);
        }

        if (! $order->midtrans_order_id) {
            Log::warning('[CheckStatus] No midtrans_order_id', ['order_id' => $id]);
            return response()->json(['status' => 'pending']);
        }

        try {
            \Midtrans\Config::$serverKey    = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

            Log::info('[CheckStatus] Calling Midtrans API', [
                'order_id'           => $order->id,
                'midtrans_order_id'  => $order->midtrans_order_id,
            ]);

            $status = \Midtrans\Transaction::status($order->midtrans_order_id);

            $transactionStatus = $status->transaction_status ?? 'pending';
            $fraudStatus       = $status->fraud_status       ?? 'accept';

            Log::info('[CheckStatus] API Response', [
                'order_id'           => $order->id,
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
                'payment_type'       => $status->payment_type ?? null,
            ]);

            if ($transactionStatus === 'settlement'
                || ($transactionStatus === 'capture' && $fraudStatus === 'accept')
            ) {
                Log::info('[CheckStatus] SETTLEMENT → markAsProcessed', ['order_id' => $order->id]);

                $paymentType    = $status->payment_type ?? null;
                $resolvedMethod = $this->resolveFromFields(
                    $paymentType,
                    $status->va_numbers[0]->bank ?? null,
                    $status->issuer   ?? null,
                    $status->acquirer ?? null,
                );

                // ✅ FIX: Tangkap DB error secara terpisah — jangan biarkan
                // exception dari markAsProcessed() tertelan oleh catch luar
                // yang hanya return 'pending', menyebabkan silent fail.
                try {
                    $this->markAsProcessed($order, $resolvedMethod, $paymentType, $resolvedMethod);
                    Log::info('[CheckStatus] markAsProcessed OK', ['order_id' => $order->id]);
                } catch (\Exception $dbErr) {
                    Log::error('[CheckStatus] markAsProcessed FAILED', [
                        'order_id' => $order->id,
                        'error'    => $dbErr->getMessage(),
                        'sql'      => method_exists($dbErr, 'getSql') ? $dbErr->getSql() : null,
                    ]);
                    return response()->json([
                        'status'  => 'db_error',
                        'message' => $dbErr->getMessage(),
                    ], 500);
                }

                return response()->json([
                    'status'   => 'ok',
                    'redirect' => route('customer.order.midtrans.receipt', $order->id),
                ]);
            }

            if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update(['status' => 'cancelled', 'payment_status' => 'failed']);
                if ($order->table_number) {
                    Meja::where('nomor_meja', $order->table_number)->update(['status' => 'kosong']);
                }
                Log::info('[CheckStatus] CANCELLED', ['order_id' => $order->id, 'reason' => $transactionStatus]);
                return response()->json(['status' => 'cancelled']);
            }

            Log::info('[CheckStatus] Still pending', [
                'order_id'           => $order->id,
                'transaction_status' => $transactionStatus,
            ]);
            return response()->json(['status' => 'pending']);

        } catch (\Exception $e) {
            // Hanya untuk Midtrans API error (404, timeout, network) — bukan DB error
            Log::warning('[CheckStatus] Midtrans API error: ' . $e->getMessage(), ['order_id' => $order->id]);
            return response()->json(['status' => 'pending']);
        }
    }

    // =========================================================================
    // MIDTRANS WEBHOOK — Single Source of Truth status Pembayaran
    // =========================================================================
    public function webhook(Request $request)
    {
        Log::info('[Webhook] Request masuk', ['ip' => $request->ip()]);

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

            $localSignature = hash('sha512', $midtransOrderId . $statusCode . $grossAmount . $serverKey);
            if ($localSignature !== $signatureKey) {
                Log::error('[Webhook] Invalid Signature', ['midtrans_order_id' => $midtransOrderId]);
                return response()->json(['message' => 'Invalid Signature Key'], 403);
            }

            $order = Order::where('midtrans_order_id', $midtransOrderId)->first();
            if (! $order) {
                Log::warning('[Webhook] Order tidak ditemukan', ['midtrans_order_id' => $midtransOrderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            Log::info('[Webhook] Midtrans Webhook Received', [
                'order_id'           => $order->id,
                'transaction_status' => $transactionStatus,
                'payment_type'       => $notification->payment_type ?? null,
            ]);

            $resolvedMethod = $this->resolvePaymentMethod($notification);
            $paymentType    = $notification->payment_type ?? null;

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'challenge') {
                    $order->update(['status' => 'waiting_payment', 'payment_status' => 'pending']);
                } elseif ($fraudStatus === 'accept') {
                    $this->markAsProcessed($order, $resolvedMethod, $paymentType, $resolvedMethod);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->markAsProcessed($order, $resolvedMethod, $paymentType, $resolvedMethod);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update(['status' => 'cancelled', 'payment_status' => 'failed']);
                if ($order->table_number) {
                    Meja::where('nomor_meja', $order->table_number)->update(['status' => 'kosong']);
                }
            } elseif ($transactionStatus === 'pending') {
                $order->update([
                    'status'          => 'waiting_payment',
                    'payment_method'  => $resolvedMethod,
                    'payment_status'  => 'pending',
                    'payment_type'    => $paymentType,
                    'payment_channel' => $resolvedMethod,
                ]);
            }

            Log::info('[Webhook] Selesai diproses', ['order_id' => $order->id, 'new_status' => $order->fresh()->status]);

        } catch (\Exception $e) {
            Log::error('[Webhook] Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'OK']);
    }

    // =========================================================================
    // QRIS MANUAL
    // =========================================================================
    public function qrisPayment(int $id)
    {
        $order = Order::with('items')->findOrFail($id);

        if (! $this->authorizeOrderBySession($order)) {
            abort(403, 'Anda tidak memiliki akses ke halaman pembayaran ini.');
        }

        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.order.success', $order->id);
        }

        $amount     = (int) $order->total;
        $orderId    = 'ORDER-' . $order->id . '-' . time();
        $qrisString = '00020101021226670016ID.CO.TELKOM.WWW01189360089893600009150'
            . str_pad($order->id, 4, '0', STR_PAD_LEFT)
            . '0215ID' . strtoupper(substr(md5($orderId), 0, 10))
            . '03130' . str_pad($order->queue_number, 5, '0', STR_PAD_LEFT)
            . '520458145303360' . '5405' . $amount . '5802ID'
            . '5915Cafe Tugas Akhir' . '6007Batam' . '6105291466' . '6304CAFE';

        return view('customer.qris-payment', compact('order', 'qrisString'));
    }

    public function qrisConfirm(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        if (! $this->authorizeOrderBySession($order)) {
            return response()->json(['error' => 'forbidden'], 403);
        }

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

        if (! $this->authorizeOrderBySession($order)) {
            abort(403, 'Anda tidak memiliki akses ke struk ini.');
        }

        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.order.success', $order->id);
        }

        return view('customer.qris-receipt', compact('order'));
    }

    public function success(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        if (! $this->authorizeOrderBySession($order)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (in_array($order->payment_method, self::MIDTRANS_METHODS)) {
            return redirect()->route('customer.order.midtrans.receipt', $order->id);
        }

        if ($order->payment_method === 'qris') {
            return redirect()->route('customer.order.qris.receipt', $order->id);
        }

        return view('customer.order-success', compact('order'));
    }

    // =========================================================================
    // HELPERS PRIVATE
    // =========================================================================

    private function markAsProcessed(Order $order, string $paymentMethod, ?string $paymentType = null, ?string $paymentChannel = null): void
    {
        $updateData = [
            'status'         => 'process',
            'payment_method' => $paymentMethod,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'payment_status')) {
            $updateData['payment_status'] = 'paid';
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'payment_type')) {
            $updateData['payment_type'] = $paymentType;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'payment_channel')) {
            $updateData['payment_channel'] = $paymentChannel ?? $paymentMethod;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'payment_method_label')) {
            $updateData['payment_method_label'] = self::PAYMENT_METHOD_LABELS[$paymentMethod] ?? ucfirst($paymentMethod);
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'confirmed_at')) {
            $updateData['confirmed_at'] = now();
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'process_at')) {
            $updateData['process_at'] = now();
        }

        $order->update($updateData);
    }

    public static function refreshMejaToken(string $tableNumber): void
    {
        if (empty($tableNumber)) return;

        $meja = Meja::where('nomor_meja', $tableNumber)->first();
        if ($meja) {
            $meja->refreshQrToken();
        }
    }

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
            $notification->issuer  ?? null,
            $notification->acquirer ?? null,
        );
    }

    private function resolveFromFields(?string $type, ?string $bank, ?string $issuer, ?string $acquirer): string
    {
        $type = $type ?? 'midtrans';

        return match (true) {
            in_array($type, ['gopay', 'wallet']) && in_array($issuer ?? $acquirer, ['ovo'])  => 'ovo',
            in_array($type, ['gopay', 'wallet']) && in_array($issuer ?? $acquirer, ['dana']) => 'dana',
            $type === 'gopay'                                                                 => 'gopay',
            $type === 'shopeepay'                                                             => 'shopeepay',
            $type === 'ovo'                                                                   => 'ovo',
            $type === 'dana'                                                                  => 'dana',
            $type === 'qris'                                                                  => 'qris',
            $type === 'credit_card'                                                           => 'credit_card',
            $type === 'bank_transfer' && $bank === 'bca'                                      => 'bca',
            $type === 'bank_transfer' && $bank === 'bni'                                      => 'bni',
            $type === 'bank_transfer' && $bank === 'bri'                                      => 'bri',
            $type === 'bank_transfer' && $bank === 'mandiri'                                  => 'mandiri',
            $type === 'bank_transfer' && $bank === 'permata'                                  => 'permata',
            $type === 'echannel'                                                               => 'mandiri',
            default                                                                            => 'midtrans',
        };
    }
}