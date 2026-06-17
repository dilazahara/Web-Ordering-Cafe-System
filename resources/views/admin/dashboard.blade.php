@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
        /* ── ROOT & BASE VARIABLES ── */
        :root {
            --pos-primary: #4f46e5;
            --pos-primary-dark: #3730a3;
            --pos-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --shadow-premium: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
        }

        /* ── HEADER/BANNER PREMIUM MODERN ── */
        .pos-header {
            background: var(--pos-gradient);
            border-radius: var(--radius-2xl, 20px);
            padding: 32px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px -10px rgba(79, 70, 229, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .header-left .greeting { font-size: 26px; font-weight: 800; margin-bottom: 6px; letter-spacing: -0.5px; }
        .header-left .date { font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.85); display: flex; align-items: center; gap: 8px; }

        /* ── LAYOUT GRID SYSTEM ── */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }
        .full-width-box {
            width: 100%;
            margin-bottom: 24px;
        }

        /* ── BASIC CARD BOX ── */
        .box {
            background: #ffffff; 
            border-radius: var(--radius-2xl, 20px);
            border: 1px solid var(--border-light, #f1f5f9); 
            box-shadow: var(--shadow-premium);
            display: flex; 
            flex-direction: column;
            overflow: hidden;
        }
        .box-header {
            padding: 24px;
            border-bottom: 1px solid var(--border-light, #f1f5f9);
            display: flex; 
            align-items: flex-start; 
            justify-content: space-between; 
            flex-wrap: wrap; 
            gap: 14px;
        }
        .box-header h3 { font-size: 16px; font-weight: 800; color: var(--text-dark, #0f172a); display: flex; align-items: center; gap: 8px; margin: 0; }
        .box-body { padding: 24px; flex: 1; }
        
        /* ── RINGKASAN AKTIVITAS LIST ── */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 8px;
            border-bottom: 1px solid #f8fafc;
            transition: all 0.2s ease;
            border-radius: 12px;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-item:hover {
            background: #f8fafc;
            transform: translateX(4px);
        }
        .activity-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .activity-icon i {
            width: 18px;
            height: 18px;
            stroke-width: 2.2;
        }
        
        /* Tema Warna Icon Ringkasan */
        .activity-icon.orange { background: #fff7ed; color: #f97316; }
        .activity-icon.blue   { background: #eff6ff; color: #3b82f6; }
        .activity-icon.cyan   { background: #ecfeff; color: #06b6d4; }
        .activity-icon.green  { background: #f0fdf4; color: #22c55e; }
        .activity-icon.purple { background: #f5f3ff; color: #7c3aed; }
        .activity-icon.red    { background: #fef2f2; color: #ef4444; }
        .activity-icon.dark   { background: #f1f5f9; color: #334155; }
        .activity-icon.pink   { background: #fdf2f8; color: #ec4899; }

        .activity-label {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }
        .activity-badge {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            background: #f8fafc;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }

        /* ── CHART STYLING ── */
        .chart-stats-wrap { display: flex; gap: 16px; }
        .c-stat-badge { background: #f8fafc; padding: 8px 14px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .c-stat-badge .l { font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; }
        .c-stat-badge .v { font-size: 14px; color: #0f172a; font-weight: 800; margin-top: 2px; }
        .chart-wrap { position: relative; height: 280px; width: 100%; }

        /* ── MENU TERLARIS STYLING ── */
        .menu-header-stats {
            background: #f5f3ff; border: 1px dashed #c4b5fd; padding: 12px 16px; 
            border-radius: 14px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;
        }
        .menu-header-stats .txt { font-size: 13px; font-weight: 600; color: #5b21b6; }
        .menu-header-stats .qty { font-size: 16px; font-weight: 800; color: #4c1d95; background: white; padding: 2px 10px; border-radius: 8px; }

        .menu-rank { display: flex; flex-direction: column; gap: 12px; }
        .menu-rank-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px; border-radius: 16px;
            background: #ffffff; transition: all .2s; border: 1px solid #e2e8f0;
        }
        .menu-rank-item:hover { transform: scale(1.02); box-shadow: var(--shadow-card); border-color: #cbd5e1; }
        .rank-num {
            width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 800; color: white;
        }
        .rank-num.r1 { background: linear-gradient(135deg, #f59e0b, #ea580c); }
        .rank-num.r2 { background: linear-gradient(135deg, #94a3b8, #475569); }
        .rank-num.r3 { background: linear-gradient(135deg, #d97706, #92400e); }
        .rank-num.rn { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
        
        .rank-img { width: 52px; height: 52px; object-fit: cover; border-radius: 12px; flex-shrink: 0; }
        .rank-info { flex: 1; min-width: 0; }
        .rank-name { font-size: 14px; font-weight: 800; color: var(--text-dark, #0f172a); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .rank-sub  { font-size: 12px; font-weight: 600; color: var(--text-muted, #64748b); margin-top: 4px; display: flex; align-items: center; gap: 4px; }
        .rank-qty  { font-size: 15px; font-weight: 800; color: #7c3aed; flex-shrink: 0; background: #f5f3ff; padding: 6px 12px; border-radius: 10px; }

        /* ── RESPONSIVE RESPONSES ── */
        @media (max-width: 1100px) { 
            .dashboard-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 780px) {
            .pos-header { padding: 24px; }
            .chart-stats-wrap { flex-direction: column; gap: 8px; width: 100%; }
        }
</style>
@endpush

@section('content')

@php
    /* --- KALKULASI DATA PENDUKUNG DASHBOARD POS --- */
    $chartDataArr = is_string($chartData) ? json_decode($chartData, true) : json_decode(json_encode($chartData), true);
    $totalMingguIni = is_array($chartDataArr) ? array_sum($chartDataArr) : 0;
    $rataRataPerHari = is_array($chartDataArr) && count($chartDataArr) > 0 ? $totalMingguIni / count($chartDataArr) : 0;

    $totalItemTerjual = $menuTerlaris->sum('total_qty');

    $menungguBayar = max(0, $totalOrder - ($totalDiproses + $totalSelesai));
    $persenTunggu  = $totalOrder > 0 ? ($menungguBayar / $totalOrder) * 100 : 0;
    $persenProses  = $totalOrder > 0 ? ($totalDiproses / $totalOrder) * 100 : 0;
    $persenSelesai = $totalOrder > 0 ? ($totalSelesai / $totalOrder) * 100 : 0;

    $targetBase = 5000000; 
    $targetPenjualan = $totalPenjualan > $targetBase ? (ceil($totalPenjualan / 5000000) * 5000000) : $targetBase;
    $progressTarget = $targetPenjualan > 0 ? min(100, ($totalPenjualan / $targetPenjualan) * 100) : 0;

    // ─── UPDATE: KALKULASI LABEL DINAMIS BERDASARKAN FILTER PERIODE ───
    $periodeLabel = match(request('periode')) {
        '30_days' => '30 Hari Terakhir',
        'this_month' => 'Bulan Ini',
        'this_year' => 'Tahun Ini',
        'all' => 'Semua Data',
        default => '7 Hari Terakhir'
    };

    $totalBadgeLabel = match(request('periode')) {
        '30_days' => 'Total 30 Hari',
        'this_month' => 'Total Bulan Ini',
        'this_year' => 'Total Tahun Ini',
        'all' => 'Total Semua',
        default => 'Total Minggu Ini'
    };

    $avgBadgeLabel = match(request('periode')) {
        'this_year', 'all' => 'Rata-rata / Bulan',
        default => 'Rata-rata / Hari'
    };
@endphp

{{-- 1. BANNER SELAMAT DATANG (MINIMALIS & ELEGAN) --}}
<div class="pos-header">
    <div class="header-left">
        <div class="greeting">Halo, {{ auth()->user()->name }}! 👋</div>
        <div class="date">
            <i data-lucide="calendar" style="width:16px;height:16px;"></i> 
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>
    </div>
</div>

<div class="dashboard-grid">
    {{-- WRAPPER SISI KIRI UNTUK OPERASIONAL & INFORMASI SISTEM --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">
        
        {{-- 2. RINGKASAN OPERASIONAL HARI INI --}}
        <div class="box">
            <div class="box-header">
                <div>
                    <h3><i data-lucide="activity" style="color:#4f46e5;"></i> Ringkasan Operasional Hari Ini</h3>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b; font-weight: 500;">Pantau performa dan aktivitas cafe pada hari ini.</p>
                </div>
            </div>
            <div class="box-body">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-icon orange"><i data-lucide="banknote"></i></div>
                            <span class="activity-label">Total Pendapatan</span>
                        </div>
                        <div class="activity-badge" style="color: #ea580c; background: #fff7ed;">Rp {{ number_format($totalPenjualan,0,',','.') }}</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-icon blue"><i data-lucide="shopping-bag"></i></div>
                            <span class="activity-label">Total Order Masuk</span>
                        </div>
                        <div class="activity-badge">{{ $totalOrder }} Order</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-icon cyan"><i data-lucide="chef-hat"></i></div>
                            <span class="activity-label">Sedang Diproses</span>
                        </div>
                        <div class="activity-badge" style="color: #0891b2; background: #ecfeff;">{{ $totalDiproses }} Order</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-icon green"><i data-lucide="check-circle-2"></i></div>
                            <span class="activity-label">Order Selesai</span>
                        </div>
                        <div class="activity-badge" style="color: #16a34a; background: #f0fdf4;">{{ $totalSelesai }} Order</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. INFORMASI SISTEM (DATA MASTER) --}}
        <div class="box">
            <div class="box-header">
                <div>
                    <h3><i data-lucide="database" style="color:#4f46e5;"></i> Informasi Sistem</h3>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b; font-weight: 500;">Data master yang digunakan dalam operasional aplikasi.</p>
                </div>
            </div>
            <div class="box-body">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-icon purple"><i data-lucide="armchair"></i></div>
                            <span class="activity-label">Total Meja</span>
                        </div>
                        <div class="activity-badge">{{ $totalMeja }} Meja</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-icon dark"><i data-lucide="users"></i></div>
                            <span class="activity-label">Total Pengguna</span>
                        </div>
                        <div class="activity-badge">{{ $totalUser }} User</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-left">
                            <div class="activity-icon pink"><i data-lucide="utensils"></i></div>
                            <span class="activity-label">Total Menu</span>
                        </div>
                        <div class="activity-badge">{{ $totalMenu }} Menu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. MENU TERLARIS --}}
    <div class="box">
        <div class="box-header">
            <h3><i data-lucide="award" style="color:#f59e0b;"></i> Menu Terlaris</h3>
        </div>
        <div class="box-body">
            @if($menuTerlaris->isEmpty())
                <div style="text-align:center; padding:40px 0;">
                    <div style="background:#f1f5f9; width:64px; height:64px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <i data-lucide="inbox" style="width:32px; height:32px; color:#94a3b8;"></i>
                    </div>
                    <p style="color:#64748b; font-weight:600; margin:0;">Belum ada data penjualan</p>
                </div>
            @else
                <div class="menu-header-stats">
                    <span class="txt">Total Item Terjual:</span>
                    <span class="qty">{{ $totalItemTerjual }} Porsi</span>
                </div>
                
                <div class="menu-rank">
                    @foreach($menuTerlaris as $i => $item)
                    <div class="menu-rank-item">
                        <div class="rank-num {{ $i===0?'r1':($i===1?'r2':($i===2?'r3':'rn')) }}">{{ $i+1 }}</div>
                        <img src="{{ !empty($item->menu?->image) ? asset('storage/'.$item->menu->image) : 'https://via.placeholder.com/60' }}" alt="{{ $item->menu->name ?? 'Menu' }}" class="rank-img">
                        <div class="rank-info">
                            <div class="rank-name">{{ $item->menu->name ?? 'Menu Tidak Ditemukan' }}</div>
                            <div class="rank-sub">
                                <i data-lucide="tag" style="width:12px; height:12px;"></i> Produk Aktif
                            </div>
                        </div>
                        <div class="rank-qty">{{ $item->total_qty }}x</div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- 5. GRAFIK PENJUALAN DENGAN FILTER PERIODE --}}
<div class="box full-width-box">
    <div class="box-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div>
            <h3><i data-lucide="bar-chart-3" style="color:#4f46e5;"></i> Analisis Pendapatan ({{ $periodeLabel }})</h3>
        </div>
        
        <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
            <form method="GET" action="{{ url()->current() }}" id="filterForm">
                <select name="periode" onchange="this.form.submit()" style="padding: 8px 14px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 700; color: #475569; background: #ffffff; cursor: pointer; outline: none; box-shadow: var(--shadow-card);">
                    <option value="7_days" {{ request('periode') == '7_days' || !request('periode') ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30_days" {{ request('periode') == '30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="this_month" {{ request('periode') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="this_year" {{ request('periode') == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="all" {{ request('periode') == 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
            </form>

            <div class="chart-stats-wrap">
                <div class="c-stat-badge">
                    <div class="l">{{ $totalBadgeLabel }}</div>
                    <div class="v" style="color:#10b981;">Rp {{ number_format($totalMingguIni,0,',','.') }}</div>
                </div>
                <div class="c-stat-badge">
                    <div class="l">{{ $avgBadgeLabel }}</div>
                    <div class="v">Rp {{ number_format($rataRataPerHari,0,',','.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-body">
        <div class="chart-wrap">
            <canvas id="chartPenjualan"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Script Chart Existing --}}
<script>
const ctx = document.getElementById('chartPenjualan').getContext('2d');

let gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(79, 70, 229, 0.15)');   
gradient.addColorStop(1, 'rgba(79, 70, 229, 0.01)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Penjualan Kotor',
            data: {!! json_encode($chartData) !!},
            borderColor: '#4f46e5',
            backgroundColor: gradient,
            borderWidth: 3,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#4f46e5',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1e293b',
                padding: 12,
                titleFont: { family: 'Inter', size: 13 },
                bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                callbacks: {
                    label: function(context) {
                        let value = context.raw || 0;
                        return ' Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f8fafc', drawBorder: false },
                ticks: {
                    color: '#94a3b8', font: { size: 11, family: 'Inter', weight: 600 },
                    callback: v => 'Rp ' + (v/1000).toFixed(0) + 'k'
                }
            },
            x: {
                grid: { display: false, drawBorder: false },
                ticks: { color: '#64748b', font: { size: 12, family: 'Inter', weight: 600 } }
            }
        }
    }
});
</script>

{{-- Datatables Databinding Existing --}}
<script>
$(document).ready(function () {
    if ($('#recentOrdersTable').length > 0) {
        $('#recentOrdersTable').DataTable({
            pageLength: 10,
            lengthChange: false,
            searching: false,
            ordering: false,
            language: {
                info:     "Menampilkan _START_ sampai _END_ dari _TOTAL_ pesanan",
                paginate: { previous: "Sebelumnya", next: "Selanjutnya" },
                emptyTable: "Belum ada pesanan hari ini"
            }
        });
    }
});
</script>
@endpush