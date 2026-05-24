<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class LaporanController extends Controller
{
    private function buildQuery(Request $request)
    {
        $query = Order::with(['items.menu'])
            ->whereIn('status', [
                'pending',
                'paid',
                'process',
                'done',
                'delivered'
            ]);

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);

        } elseif ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);

        } elseif ($request->filled('filter')) {
            match ($request->filter) {
                'hari'  => $query->whereDate('created_at', today()),
                'bulan' => $query->whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year),
                'tahun' => $query->whereYear('created_at', now()->year),
                default => null,
            };

        } else {
            $query->whereDate('created_at', today());
        }

        return $query->latest();
    }

    public function index(Request $request)
    {
        $orders = $this->buildQuery($request)->get();

        return view('admin.laporan', compact('orders'));
    }

    public function exportPdf(Request $request)
    {
        $orders = $this->buildQuery($request)->get();

        $pdf = Pdf::loadView('admin.laporan_pdf', compact('orders'));

        return $pdf->download(
            'laporan-admin-' . now()->format('Y-m-d') . '.pdf'
        );
    }
}