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

    /**
     * Membangun query laporan berdasarkan filter yang dipilih secara konsisten.
     */
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
                'completed',
            ]);

        // 1. Filter Berdasarkan Tanggal Tertentu (Spesifik)
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);

        // 2. Filter Berdasarkan Rentang Tanggal (Jika Digunakan)
        } elseif ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ]);

        // 3. Filter Berdasarkan Opsi Periode Cepat
        } elseif ($request->filled('filter')) {
            match ($request->filter) {
                'hari'         => $query->whereDate('created_at', today()),
                'last_7_days'  => $query->where('created_at', '>=', now()->subDays(7)),  // PERBAIKAN: Membatasi transaksi 7 hari terakhir
                'last_30_days' => $query->where('created_at', '>=', now()->subDays(30)), // PERBAIKAN: Membatasi transaksi 30 hari terakhir
                'bulan'        => $query->whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year),
                'tahun'        => $query->whereYear('created_at', now()->year),
                'all'          => null, // PERBAIKAN: "Semua Data" dibiarkan lolos tanpa batasan clause tanggal
                default        => null,
            };

        // 4. Default: Jika halaman baru dibuka tanpa request filter, tampilkan data "Hari Ini"
        } else {
            $query->whereDate('created_at', today());
        }

        // Seluruh filter menggunakan field 'created_at' yang sama secara konsisten
        return $query->latest();
    }

    /**
     * Menampilkan halaman utama laporan admin.
     */
    public function index(Request $request)
    {
        $orders = $this->buildQuery($request)->get();

        return view('admin.laporan', compact('orders'));
    }

    /**
     * Mengekspor data laporan ke PDF dengan sinkronisasi filter yang dipilih.
     */
    public function exportPdf(Request $request)
    {
        $orders = $this->buildQuery($request)->get();

        // Label tanggal untuk nama file & judul internal dokumen PDF
        if ($request->filled('tanggal')) {
            $label = $request->tanggal;
            $tanggalLabel = \Carbon\Carbon::parse($label)->translatedFormat('d F Y');
            
        } elseif ($request->filled('filter')) {
            $label = $request->filter;
            
            // PERBAIKAN: Mencegah error Carbon::parse pada string filter non-tanggal
            $tanggalLabel = match($request->filter) {
                'hari'         => 'Hari Ini (' . today()->translatedFormat('d F Y') . ')',
                'last_7_days'  => '7 Hari Terakhir',
                'last_30_days' => '30 Hari Terakhir',
                'bulan'        => 'Bulan Ini (' . now()->translatedFormat('F Y') . ')',
                'tahun'        => 'Tahun Ini (' . now()->year . ')',
                'all'          => 'Semua Data',
                default        => today()->translatedFormat('d F Y'),
            };
            
        } else {
            $label = today()->format('Y-m-d');
            $tanggalLabel = today()->translatedFormat('d F Y');
        }

        $pdf = Pdf::loadView('admin.laporan_pdf', compact('orders', 'tanggalLabel'));

        // Menghasilkan nama file yang aman (contoh: laporan-admin-last_7_days.pdf)
        return $pdf->download('laporan-admin-' . $label . '.pdf');
    }
}