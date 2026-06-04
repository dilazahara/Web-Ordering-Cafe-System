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

class CustomerOrderController extends Controller
{
    public function store(Request $request)
    {
        $activeKodes = PaymentMethod::where('aktif', true)->pluck('kode')->toArray();

        $request->validate([
            'cart' => 'required|string',
            'payment_method' => ['required', 'string', 'in:' . implode(',', $activeKodes)],
            'table_number' => 'nullable|string|max:10',
            'note' => 'nullable|string|max:500',
            'customer_name' => 'required|string|max:100',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart) || !is_array($cart)) {
            return back()->withErrors([
                'cart' => 'Keranjang kosong atau tidak valid.'
            ]);
        }

        $subtotal = 0;

        foreach ($cart as $item) {
            $menu = Menu::find($item['id']);

            if (!$menu) {
                return back()->withErrors([
                    'cart' => 'Menu tidak ditemukan.'
                ]);
            }

            $subtotal += $menu->price * (int) $item['quantity'];
        }

        $total = $subtotal + 2000;
        $tableNumber = $request->table_number;

        $order = DB::transaction(function () use ($request, $cart, $total, $tableNumber) {

            $lastOrder = Order::lockForUpdate()->latest()->first();
            $nextNumber = 1;

            if ($lastOrder && $lastOrder->queue_number) {
                $lastNumber = (int) substr($lastOrder->queue_number, 2);
                $nextNumber = $lastNumber + 1;
            }

            $queueNumber = 'A-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'queue_number' => $queueNumber,
                'table_number' => $tableNumber,
                'customer_name' => $request->customer_name,
                'note' => $request->note,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total' => $total,
            ]);

            if ($tableNumber) {
                Meja::where('nomor_meja', $tableNumber)
                    ->update([
                        'status' => 'terisi'
                    ]);
            }

            foreach ($cart as $item) {
                $menu = Menu::find($item['id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'name' => $menu->name,
                    'qty' => (int) $item['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $menu->price * (int) $item['quantity'],
                    'notes' => $item['notes'] ?? '',
                ]);
            }

            return $order;
        });

        if ($request->payment_method === 'qris') {
            return redirect()->route('customer.order.qris', $order->id);
        }

        if ($request->payment_method === 'cash') {
            return redirect()->route('customer.order.bill', $order->id);
        }

        return redirect()->route('customer.order.success', $order->id);
    }

    public function qrisPayment(int $id)
    {
        $order = Order::with('items')->findOrFail($id);

        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.order.success', $order->id);
        }

        $amount = (int) $order->total;
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

        $updateData = [
            'status' => 'process',
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'confirmed_at')) {
            $updateData['confirmed_at'] = now();
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'process_at')) {
            $updateData['process_at'] = now();
        }

        $order->update($updateData);
    }

    return response()->json([
        'status' => 'ok',
        'redirect' => route('customer.order.qris.receipt', $order->id),
    ]);
}
    public function webhook(Request $request)
    {
        return response()->json([
            'message' => 'OK'
        ]);
    }

    public function cashBill(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        if ($order->payment_method !== 'cash') {
            return redirect()->route('customer.order.success', $order->id);
        }

        return view('customer.cash-bill', compact('order'));
    }

    public function qrisReceipt(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.order.success', $order->id);
        }

        return view('customer.qris-receipt', compact('order'));
    }

    public function success(int $id)
    {
        $order = Order::with('items.menu')->findOrFail($id);
        return view('customer.order-success', compact('order'));
    }
}