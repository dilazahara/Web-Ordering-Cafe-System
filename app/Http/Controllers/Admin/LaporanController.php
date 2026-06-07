<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class LaporanController extends Controller
{
    // Midtrans methods — sama dengan di CustomerOrderController
    private const MIDTRANS_METHODS = [
        'gopay','ovo','dana','shopeepay',
        'bca','bni','bri','mandiri','permata',
        'credit_card','midtrans',
    ];

    private function buildQuery(Request $request)
    {
        // Hanya tampilkan order yang sudah benar-benar dibayar / sedang diproses / selesai
        // Exclude: pending (belum bayar cash), waiting_payment (belum bayar Midtrans), cancelled
        $query = Order::with(['items.menu'])
            ->whereIn('status', [
                'paid',
                'process',
                'done',
                'delivered',
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

        // Label tanggal untuk nama file & judul PDF
        if ($request->filled('tanggal')) {
            $label = $request->tanggal;
        } elseif ($request->filled('filter')) {
            $label = match($request->filter) {
                'hari'  => today()->format('Y-m-d'),
                'bulan' => now()->format('Y-m'),
                'tahun' => now()->format('Y'),
                default => today()->format('Y-m-d'),
            };
        } else {
            $label = today()->format('Y-m-d');
        }

        $tanggalLabel = \Carbon\Carbon::parse($label)->translatedFormat('d F Y');

        $pdf = Pdf::loadView('admin.laporan_pdf', compact('orders', 'tanggalLabel'));

        return $pdf->download('laporan-admin-' . $label . '.pdf');
    }
}