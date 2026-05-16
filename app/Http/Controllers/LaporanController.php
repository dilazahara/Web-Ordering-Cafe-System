<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class LaporanController extends Controller
{
    // =====================================
    // HALAMAN LAPORAN
    // =====================================
    public function index(Request $request)
    {
        $query = Order::with(['items.menu'])
            ->whereIn('status', [
                'pending',
                'paid',
                'process',
                'done',
                'delivered'
            ]);

        // FILTER TANGGAL
        if ($request->filled('tanggal')) {

            $query->whereDate(
                'created_at',
                $request->tanggal
            );

        } elseif (
            $request->filled('dari') &&
            $request->filled('sampai')
        ) {

            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);

        } else {

            $query->whereDate(
                'created_at',
                today()
            );

        }

        $orders = $query
            ->latest()
            ->get();

        return view(
            'admin.laporan',
            compact('orders')
        );
    }

    // =====================================
    // EXPORT PDF
    // =====================================
    public function exportPdf(Request $request)
    {
        $query = Order::with(['items.menu'])
            ->whereIn('status', [
                'pending',
                'paid',
                'process',
                'done',
                'delivered'
            ]);

        // FILTER TANGGAL
        if ($request->filled('tanggal')) {

            $query->whereDate(
                'created_at',
                $request->tanggal
            );

        } elseif (
            $request->filled('dari') &&
            $request->filled('sampai')
        ) {

            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);

        } else {

            $query->whereDate(
                'created_at',
                today()
            );

        }

        $orders = $query
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'admin.laporan_pdf',
            compact('orders')
        );

        return $pdf->download(
            'laporan-admin-' .
            now()->format('Y-m-d') .
            '.pdf'
        );
    }
}

