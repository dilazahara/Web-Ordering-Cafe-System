<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\User;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ─── UPDATE: Menambahkan parameter Request $request ───
    public function index(Request $request)
    {
        // ─────────────────────────────────────
        // CARDS DASHBOARD
        // ─────────────────────────────────────

        // SINKRONISASI: Menambahkan status 'paid' agar sesuai dengan logika laporan keuangan
        $totalPenjualan = Order::whereDate(
                'created_at',
                today()
            )
            ->whereIn('status', [
                'paid',
                'process',
                'done',
                'delivered',
                'completed'
            ])
            ->sum('total');

        $totalOrder = Order::whereDate(
                'created_at',
                today()
            )
            ->count();

        $totalDiproses = Order::whereDate(
                'created_at',
                today()
            )
            ->where('status', 'process')
            ->count();

        $totalSelesai = Order::whereDate(
                'created_at',
                today()
            )
            ->whereIn('status', [
                'done',
                'delivered',
                'completed'
            ])
            ->count();


        // ─────────────────────────────────────
        // TAMBAHAN DASHBOARD
        // ─────────────────────────────────────

        $totalMeja      = Meja::count();
        $totalTransaksi = Order::count();
        $totalUser      = User::count();
        $totalMenu      = Menu::count();


        // ─────────────────────────────────────
        // MENU TERLARIS (MINGGU INI)
        // ─────────────────────────────────────

        $menuTerlaris = OrderItem::select(
                'menu_id',
                DB::raw('SUM(qty) as total_qty')
            )
            ->whereHas('order', function ($q) {
                $q->whereBetween('created_at', [
                    now()->startOfWeek(),   // Senin 00:00:00
                    now()->endOfWeek(),     // Minggu 23:59:59
                ]);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();


        // ─────────────────────────────────────
        // PESANAN TERBARU
        // ─────────────────────────────────────

        $pesananTerbaru = Order::with('items.menu')
            ->whereDate('created_at', today())
            ->latest()
            ->limit(10)
            ->get();


        // ─────────────────────────────────────
        // ─── UPDATE: GRAFIK PENJUALAN DENGAN FILTER PERIODE ───
        // ─────────────────────────────────────

        $periode = $request->query('periode', '7_days');
        
        $queryGrafik = Order::whereIn('status', [
            'paid',
            'process',
            'done',
            'delivered',
            'completed'
        ]);

        switch ($periode) {
            case '30_days':
                $queryGrafik->where('created_at', '>=', now()->subDays(29)->startOfDay());
                $dateFormat = "%d %b";
                break;
            case 'this_month':
                $queryGrafik->where('created_at', '>=', now()->startOfMonth());
                $dateFormat = "%d %b";
                break;
            case 'this_year':
                $queryGrafik->where('created_at', '>=', now()->startOfYear());
                $dateFormat = "%b %Y";
                break;
            case 'all':
                // Tidak ada batas waktu (Semua Data)
                $dateFormat = "%b %Y";
                break;
            case '7_days':
            default:
                $queryGrafik->where('created_at', '>=', now()->subDays(6)->startOfDay());
                $dateFormat = "%d %b";
                break;
        }

        $grafik = $queryGrafik->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as hari"),
                DB::raw('SUM(total) as total')
            )
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '{$dateFormat}')"))
            ->orderBy(DB::raw('MIN(created_at)'))
            ->get();

        $chartLabels = $grafik->pluck('hari');
        $chartData   = $grafik->pluck('total');


        // ─────────────────────────────────────
        // RETURN VIEW
        // ─────────────────────────────────────

        return view('admin.dashboard', compact(
            'totalPenjualan',
            'totalOrder',
            'totalDiproses',
            'totalSelesai',

            'totalMeja',
            'totalTransaksi',
            'totalUser',
            'totalMenu',

            'menuTerlaris',

            'pesananTerbaru',

            'chartLabels',
            'chartData'
        ));
    }
}