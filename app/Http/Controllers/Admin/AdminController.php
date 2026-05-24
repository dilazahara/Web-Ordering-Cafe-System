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
    public function index()
    {
        // ─────────────────────────────────────
        // CARDS DASHBOARD
        // ─────────────────────────────────────

        $totalPenjualan = Order::whereDate(
                'created_at',
                today()
            )
            ->whereIn('status', [

                'process',
                'done',
                'delivered'

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
            ->where(
                'status',
                'process'
            )
            ->count();


        $totalSelesai = Order::whereDate(
                'created_at',
                today()
            )
            ->whereIn('status', [

                'done',
                'delivered'

            ])
            ->count();


        // ─────────────────────────────────────
        // TAMBAHAN DASHBOARD
        // ─────────────────────────────────────

        $totalMeja = Meja::count();

        $totalTransaksi = Order::count();

        $totalUser = User::count();

        $totalMenu = Menu::count();


        // ─────────────────────────────────────
        // MENU TERLARIS
        // ─────────────────────────────────────

        $menuTerlaris = OrderItem::select(

                'menu_id',

                DB::raw('SUM(qty) as total_qty')

            )

            ->whereHas('order', function ($q) {

                $q->whereDate(

                    'created_at',

                    today()

                );

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

            ->whereDate(
                'created_at',
                today()
            )

            ->latest()

            ->limit(10)

            ->get();


        // ─────────────────────────────────────
        // GRAFIK 7 HARI TERAKHIR
        // ─────────────────────────────────────

        $grafik = Order::select(

                DB::raw("DATE_FORMAT(created_at, '%d %b') as hari"),

                DB::raw('SUM(total) as total')

            )

            ->whereIn('status', [

                'process',
                'done',
                'delivered'

            ])

            ->where(
                'created_at',
                '>=',
                now()->subDays(6)->startOfDay()
            )

            ->groupBy('hari')

            ->orderBy('hari')

            ->get();


        // LABEL & DATA CHART

        $chartLabels = $grafik->pluck('hari');

        $chartData = $grafik->pluck('total');


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