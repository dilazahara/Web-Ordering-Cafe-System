<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Meja;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.menu')
            ->latest()
            ->get();

        return view(
            'admin.order.index',
            compact('orders')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart'           => 'required|string',
            'payment_method' => 'required|in:cash,qris',
            'table_number'   => 'nullable|string|max:10',
            'note'           => 'nullable|string|max:500',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart)) {
            return back()->withErrors([
                'cart' => 'Keranjang kosong.'
            ]);
        }

        $status = $request->payment_method === 'qris'
            ? 'lunas'
            : 'pending';

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $total = $subtotal + 2000;

        $lastOrder = Order::latest()->first();

        $nextNumber = 1;

        if ($lastOrder && $lastOrder->queue_number) {

            $lastNumber = (int) substr(
                $lastOrder->queue_number,
                2
            );

            $nextNumber = $lastNumber + 1;
        }

        $queueNumber = 'A-' . str_pad(
            $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );

        $order = Order::create([

            'queue_number' => $queueNumber,

            'table_number' => $request->table_number,

            'note' => $request->note,

            'payment_method' => $request->payment_method,

            'status' => $status,

            'total' => $total,

        ]);

        if ($request->table_number) {

            Meja::where(
                'nomor_meja',
                $request->table_number
            )->update([
                'status' => 'terisi'
            ]);

        }

        foreach ($cart as $item) {

            OrderItem::create([

                'order_id' => $order->id,

                'menu_id' => $item['id'],

                'name' => $item['name'],

                'qty' => $item['quantity'],

                'price' => $item['price'],

                'subtotal' =>
                    $item['price'] * $item['quantity'],

                'notes' => $item['notes'] ?? null,

            ]);
        }

        return redirect()->route(
            'customer.order.success',
            $order->id
        );
    }

    public function process(int $id)
    {
        $order = Order::findOrFail($id);

        $order->status = 'process';

        $order->save();

        return back()->with(
            'success',
            'Pesanan diproses'
        );
    }

    public function done(int $id)
    {
        $order = Order::findOrFail($id);

        $order->status = 'done';

        $order->save();

        return back()->with(
            'success',
            'Pesanan selesai'
        );
    }

    public function konfirmasi(
        Request $request,
        Order $order
    ) {

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

        $order->status = 'paid';

        $order->uang_diterima = $request->input(
            'uang_diterima',
            0
        );

        $order->confirmed_at = now();

        $order->save();

        return back()->with(
            'success',
            "Pesanan Meja {$order->table_number} dikonfirmasi."
        );
    }

    public function selesai(Order $order)
    {
        abort_if(
            !in_array(
                $order->status,
                ['process', 'done']
            ),
            422,
            'Status tidak valid.'
        );

        $order->status = 'delivered';

        $order->save();

        if ($order->table_number) {

            Meja::where(
                'nomor_meja',
                $order->table_number
            )->update([
                'status' => 'kosong'
            ]);

        }

        return back()->with(
            'success',
            "Pesanan {$order->queue_number} selesai."
        );
    }
}