<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class LaporanController extends Controller
{
    // ══════════════════════════════════════════
    // Helper: format order untuk view
    // ══════════════════════════════════════════
  private function formatOrders(Collection $orders): array
    {
        return $orders->map(function ($order) {
            return [
                'tanggal' => $order->created_at->format('d/m/Y H:i'),
                'kode' => $order->queue_number,
                'meja' => 'Meja ' . $order->table_number,
                'total'   => $order->total,
                'metode'  => strtoupper($order->payment_method ?? 'cash'),
            ];
        })->toArray();
    }

    // ══════════════════════════════════════════
    // INDEX
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $query = Order::whereIn('status', ['process', 'done', 'delivered']);

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        } elseif ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);
        } else {
            $query->whereDate('created_at', today());
        }

        $orders = $query->latest()->get();
        $data   = $this->formatOrders($orders);

        return view('admin.laporan', compact('data'));
    }

    // ══════════════════════════════════════════
    // EXPORT PDF
    // ══════════════════════════════════════════
    public function exportPdf(Request $request)
    {
        $query = Order::whereIn('status', ['process', 'done', 'delivered']);

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        } elseif ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);
        } else {
            $query->whereDate('created_at', today());
        }

        $orders = $query->latest()->get();
        $data   = $this->formatOrders($orders);

        $pdf = Pdf::loadView('admin.laporan_pdf', compact('data'));

        return $pdf->download('laporan-admin-' . now()->format('Y-m-d') . '.pdf');
    }
}