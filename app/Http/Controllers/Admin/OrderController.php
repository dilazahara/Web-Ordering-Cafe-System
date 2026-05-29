<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // =========================================
    // INDEX
    // =========================================
    public function index()
    {
        $orders = Order::with('items.menu')
            ->latest()
            ->get();

        return view('admin.order.index', compact('orders'));
    }

    // =========================================
    // STORE
    // =========================================
    public function store(Request $request)
    {
        // ✅ FIX: Gunakan data dinamis dari DB — sebelumnya hardcoded 'cash,qris'
        // Jika admin menambah metode baru, validasi ini otomatis mengikuti
        $activeKodes = PaymentMethod::where('aktif', true)->pluck('kode')->toArray();

        $request->validate([
            'cart'           => 'required|string',
            'payment_method' => ['required', 'string', 'in:' . implode(',', $activeKodes)],
            'table_number'   => 'nullable|string|max:10',
            'note'           => 'nullable|string|max:500',
        ], [
            'cart.required'           => 'Keranjang tidak boleh kosong.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in'       => 'Metode pembayaran tidak valid atau tidak aktif.',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart) || !is_array($cart)) {
            return back()->withErrors(['cart' => 'Keranjang kosong atau data tidak valid.']);
        }

        // ✅ FIX: Validasi setiap item — cek menu ada & qty valid
        foreach ($cart as $item) {
            if (empty($item['id']) || empty($item['quantity']) || (int) $item['quantity'] < 1) {
                return back()->withErrors(['cart' => 'Data item dalam keranjang tidak valid.']);
            }
            $menu = Menu::find($item['id']);
            if (!$menu || (int) $menu->status === 0) {
                return back()->withErrors(['cart' => 'Salah satu menu tidak ditemukan atau sudah tidak tersedia.']);
            }
        }

        // ✅ FIX: Hitung total dari DB, bukan dari data client
        $subtotal = 0;
        foreach ($cart as $item) {
            $menu      = Menu::find($item['id']);
            $subtotal += $menu->price * (int) $item['quantity'];
        }
        $total = $subtotal + (int) config('app.biaya_layanan', 2000);

        $status = $request->payment_method === 'qris' ? 'lunas' : 'pending';

        // ✅ FIX: Gunakan DB transaction + lockForUpdate() untuk cegah race condition
        $order = DB::transaction(function () use ($request, $cart, $total, $status) {

            $lastOrder  = Order::lockForUpdate()->latest()->first();
            $nextNumber = 1;

            if ($lastOrder && $lastOrder->queue_number) {
                $lastNumber = (int) substr($lastOrder->queue_number, 2);
                $nextNumber = $lastNumber + 1;
            }

            $queueNumber = 'A-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'queue_number'   => $queueNumber,
                'table_number'   => $request->table_number,
                'note'           => $request->note,
                'payment_method' => $request->payment_method,
                'status'         => $status,
                'total'          => $total,
            ]);

            if ($request->table_number) {
                Meja::where('nomor_meja', $request->table_number)
                    ->update(['status' => 'terisi']);
            }

            // ✅ FIX: name & price dari DB, bukan dari client
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

        return redirect()->route('customer.order.success', $order->id);
    }

    // =========================================
    // PROCESS — Ubah status ke 'process'
    // =========================================
    public function process(int $id)
    {
        $order = Order::findOrFail($id);

        // ✅ FIX: Validasi status sebelum diubah — cegah status mundur
        // (misal dari 'delivered' balik ke 'process')
        abort_if(
            !in_array($order->status, ['pending', 'paid', 'lunas']),
            422,
            'Pesanan tidak dapat diproses dari status saat ini.'
        );

        $order->status     = 'process';
        $order->process_at = now();
        $order->save();

        return back()->with('success', 'Pesanan berhasil diproses.');
    }

    // =========================================
    // DONE — Ubah status ke 'done'
    // =========================================
    public function done(int $id)
    {
        $order = Order::findOrFail($id);

        // ✅ FIX: Hanya bisa dari status 'process'
        abort_if(
            $order->status !== 'process',
            422,
            'Pesanan belum dalam status diproses.'
        );

        $order->status  = 'done';
        $order->done_at = now();
        $order->save();

        return back()->with('success', 'Pesanan selesai dibuat.');
    }

    // =========================================
    // KONFIRMASI CASH
    // =========================================
    public function konfirmasi(Request $request, Order $order)
    {
        abort_if($order->payment_method !== 'cash', 403, 'Hanya untuk pembayaran cash.');
        abort_if($order->status !== 'pending', 422, 'Status pesanan tidak valid untuk dikonfirmasi.');

        // ✅ FIX: Validasi uang_diterima — sebelumnya tidak divalidasi sama sekali
        $request->validate([
            'uang_diterima' => 'nullable|numeric|min:0',
        ], [
            'uang_diterima.numeric' => 'Jumlah uang harus berupa angka.',
            'uang_diterima.min'     => 'Jumlah uang tidak boleh negatif.',
        ]);

        $uangDiterima = (float) $request->input('uang_diterima', 0);

        // ✅ FIX: Pastikan uang diterima tidak kurang dari total
        if ($uangDiterima > 0 && $uangDiterima < $order->total) {
            return back()->with('error', 'Uang yang diterima (Rp ' . number_format($uangDiterima, 0, ',', '.') . ') kurang dari total pesanan (Rp ' . number_format($order->total, 0, ',', '.') . ').');
        }

        $order->status        = 'paid';
        $order->uang_diterima = $uangDiterima;
        $order->confirmed_at  = now();
        $order->save();

        return back()->with('success', "Pesanan {$order->queue_number} berhasil dikonfirmasi.");
    }

    // =========================================
    // SELESAI — Ubah status ke 'delivered'
    // =========================================
    public function selesai(Order $order)
    {
        abort_if(
            !in_array($order->status, ['process', 'done']),
            422,
            'Status tidak valid untuk diselesaikan.'
        );

        $order->status = 'delivered';
        $order->save();

        if ($order->table_number) {
            Meja::where('nomor_meja', $order->table_number)
                ->update(['status' => 'kosong']);
        }

        return back()->with('success', "Pesanan {$order->queue_number} selesai.");
    }
}