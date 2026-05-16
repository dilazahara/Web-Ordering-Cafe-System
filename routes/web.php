<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ── AUTH ──────────────────────────────────────────────
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

// ── ADMIN ─────────────────────────────────────────────
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AddOnsController;
use App\Http\Controllers\AddonGroupController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AccountAdminController;
use App\Http\Controllers\PaymentMethodController;

// ── KASIR ─────────────────────────────────────────────
use App\Http\Controllers\KasirController;
use App\Http\Controllers\AccountKasirController;
use App\Http\Controllers\KonfirmasiPesananController;

// ── DAPUR ─────────────────────────────────────────────
use App\Http\Controllers\DapurController;

// ── PELAYAN ───────────────────────────────────────────
use App\Http\Controllers\PelayanController;
use App\Http\Controllers\AccountPelayanController;

// ── ORDER ─────────────────────────────────────────────
use App\Http\Controllers\OrderController;

// ── NOTIFIKASI ────────────────────────────────────────
use App\Http\Controllers\NotificationController;

// ── CUSTOMER ──────────────────────────────────────────
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\AddonController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerOrderController;

// ── MODELS ────────────────────────────────────────────
use App\Models\Order;

// ══════════════════════════════════════════════════════
// ROOT
// ══════════════════════════════════════════════════════

Route::get('/', function () {
    return redirect('/login');
});

// ══════════════════════════════════════════════════════
// AUTH
// ══════════════════════════════════════════════════════

Route::get(
    '/login',
    [LoginController::class, 'index']
)->name('login');

Route::post(
    '/login',
    [LoginController::class, 'login']
)->name('login.post');

Route::get(
    '/register',
    [RegisterController::class, 'index']
);

Route::middleware('auth')->group(function () {

    Route::get(
        '/logout',
        [LoginController::class, 'logout']
    );

    Route::post('/logout', function () {

        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/login');

    })->name('logout');

});

// ══════════════════════════════════════════════════════
// NOTIFIKASI REALTIME (semua role yang login)
// ══════════════════════════════════════════════════════

Route::middleware('auth')
    ->prefix('notifications')
    ->name('notifications.')
    ->group(function () {

    // SSE stream — client subscribe ke sini
    Route::get('/stream', [NotificationController::class, 'stream'])
        ->name('stream');

    // REST endpoints
    Route::get('/',               [NotificationController::class, 'index'])
        ->name('index');
    Route::get('/unread-count',   [NotificationController::class, 'unreadCount'])
        ->name('unread');
    Route::patch('/{id}/read',    [NotificationController::class, 'markRead'])
        ->name('read');
    Route::post('/read-all',      [NotificationController::class, 'markAllRead'])
        ->name('read-all');

});

// ══════════════════════════════════════════════════════
// CUSTOMER
// ══════════════════════════════════════════════════════

Route::prefix('customer')
    ->name('customer.')
    ->group(function () {
 
    Route::get(
        '/home',
        [HomeController::class, 'index']
    )->name('home');

    // ← QR Scan: simpan nomor meja ke session lalu ke home
    Route::get(
        '/scan/{nomor_meja}',
        [HomeController::class, 'scanMeja']
    )->name('scan');
 
    Route::get(
        '/addons',
        [AddonController::class, 'index']
    )->name('addons');
 
    Route::get(
        '/cart',
        [CartController::class, 'index']
    )->name('cart');
 
    Route::get(
        '/checkout',
        [CheckoutController::class, 'index']
    )->name('checkout');
 
    Route::post(
        '/order',
        [CustomerOrderController::class, 'store']
    )->name('order.store');
 
    // ← BARU: halaman barcode QRIS
    Route::get(
        '/order/qris/{id}',
        [CustomerOrderController::class, 'qrisPayment']
    )->name('order.qris');
 
    Route::get(
        '/order/success/{id}',
        [CustomerOrderController::class, 'success']
    )->name('order.success');
 
});

// ══════════════════════════════════════════════════════
// ADMIN
// ══════════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // =====================================
    // DASHBOARD
    // =====================================
    Route::get(
        '/dashboard',
        [AdminController::class, 'index']
    )->name('dashboard');

    // =====================================
    // ORDER
    // =====================================
    Route::get(
        '/order',
        [OrderController::class, 'index']
    )->name('order.index');

    Route::post(
        '/order/process/{id}',
        [OrderController::class, 'process']
    )->name('order.process');

    Route::post(
        '/order/done/{id}',
        [OrderController::class, 'done']
    )->name('order.done');

    // =====================================
    // MENU
    // =====================================
    Route::get(
        '/menu',
        [MenuController::class, 'index']
    )->name('menu.index');

    Route::get(
        '/menu/create',
        [MenuController::class, 'create']
    )->name('menu.create');

    Route::post(
        '/menu/store',
        [MenuController::class, 'store']
    )->name('menu.store');

    Route::get(
        '/menu/edit/{id}',
        [MenuController::class, 'edit']
    )->name('menu.edit');

    Route::put(
        '/menu/update/{id}',
        [MenuController::class, 'update']
    )->name('menu.update');

    Route::post(
        '/menu/delete/{id}',
        [MenuController::class, 'destroy']
    )->name('menu.delete');

    // =====================================
    // KATEGORI
    // =====================================
    Route::get(
        '/kategori',
        [KategoriController::class, 'index']
    )->name('kategori.index');

    Route::get(
        '/kategori/create',
        [KategoriController::class, 'create']
    )->name('kategori.create');

    Route::post(
        '/kategori/store',
        [KategoriController::class, 'store']
    )->name('kategori.store');

    Route::get(
        '/kategori/edit/{id}',
        [KategoriController::class, 'edit']
    )->name('kategori.edit');

    Route::put(
        '/kategori/update/{id}',
        [KategoriController::class, 'update']
    )->name('kategori.update');

    Route::delete(
        '/kategori/delete/{id}',
        [KategoriController::class, 'destroy']
    )->name('kategori.delete');

    // =====================================
    // ADDONS
    // =====================================
    Route::get(
        '/addons',
        [AddOnsController::class, 'index']
    )->name('addons.index');

    Route::get(
        '/addons/create',
        [AddOnsController::class, 'create']
    )->name('addons.create');

    Route::post(
        '/addons/store',
        [AddOnsController::class, 'store']
    )->name('addons.store');

    Route::post(
        '/addon-groups/store',
        [AddonGroupController::class, 'store']
        )->name('addon-groups.store');


    Route::get(
        '/addons/edit/{id}',
        [AddOnsController::class, 'edit']
    )->name('addons.edit');

    Route::put(
        '/addons/update/{id}',
        [AddOnsController::class, 'update']
    )->name('addons.update');

    Route::post(
        '/addons/delete/{id}',
        [AddOnsController::class, 'destroy']
    )->name('addons.delete');

    // =====================================
    // MEJA
    // =====================================
    Route::get(
        '/meja',
        [MejaController::class, 'index']
    )->name('meja.index');

    Route::get(
        '/meja/create',
        [MejaController::class, 'create']
    )->name('meja.create');

    Route::post(
        '/meja/store',
        [MejaController::class, 'store']
    )->name('meja.store');

    Route::get(
        '/meja/edit/{id}',
        [MejaController::class, 'edit']
    )->name('meja.edit');

    Route::put(
        '/meja/update/{id}',
        [MejaController::class, 'update']
    )->name('meja.update');

    Route::delete(
        '/meja/delete/{id}',
        [MejaController::class, 'destroy']
    )->name('meja.delete');

    Route::get(
        '/meja/monitor',
        [MejaController::class, 'monitor']
    )->name('meja.monitor');

    // =====================================
    // PEMBAYARAN
    // =====================================
    Route::get(
        '/pembayaran',
        [PaymentMethodController::class, 'index']
    )->name('pembayaran.index');

    Route::post(
        '/pembayaran',
        [PaymentMethodController::class, 'store']
    )->name('pembayaran.store');

    Route::put(
        '/pembayaran/{id}',
        [PaymentMethodController::class, 'update']
    )->name('pembayaran.update');

    Route::post(
        '/pembayaran/toggle/{id}',
        [PaymentMethodController::class, 'toggle']
    )->name('pembayaran.toggle');

    Route::post(
        '/pembayaran/qris/{id}',
        [PaymentMethodController::class, 'updateQris']
    )->name('pembayaran.qris');

    Route::delete(
        '/pembayaran/{id}',
        [PaymentMethodController::class, 'destroy']
    )->name('pembayaran.destroy');
    // =====================================
    // USER
    // =====================================
    Route::get(
        '/user',
        [UserController::class, 'index']
    )->name('user.index');

    Route::get(
        '/user/create',
        [UserController::class, 'create']
    )->name('user.create');

    Route::post(
        '/user/store',
        [UserController::class, 'store']
    )->name('user.store');

    Route::get(
        '/user/edit/{id}',
        [UserController::class, 'edit']
    )->name('user.edit');

    Route::post(
        '/user/update/{id}',
        [UserController::class, 'update']
    )->name('user.update');

    Route::get(
        '/user/delete/{id}',
        [UserController::class, 'delete']
    )->name('user.delete');

    // =====================================
    // LAPORAN
    // =====================================
    Route::get(
        '/laporan',
        [LaporanController::class, 'index']
    )->name('laporan.index');

    Route::get(
        '/laporan/pdf',
        [LaporanController::class, 'exportPdf']
    )->name('laporan.pdf');

    // =====================================
    // ACCOUNT ADMIN
    // =====================================
    Route::get(
        '/account/profil',
        [AccountAdminController::class, 'profil']
    )->name('account.profil');

    Route::put(
        '/account/update',
        [AccountAdminController::class, 'updateProfil']
    )->name('account.update');

    Route::get(
        '/account/ganti-sandi',
        [AccountAdminController::class, 'gantiSandi']
    )->name('account.ganti-sandi');

    Route::put(
        '/account/update-password',
        [AccountAdminController::class, 'updatePassword']
    )->name('account.update-password');

});


// ══════════════════════════════════════════════════════
// KASIR
// ══════════════════════════════════════════════════════

Route::middleware(['auth', 'role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

    Route::get(
        '/dashboard',
        [KasirController::class, 'dashboard']
    )->name('dashboard');

    Route::get(
        '/pesanan',
        [KasirController::class, 'pesanan']
    )->name('pesanan');

    Route::get(
        '/transaksi',
        [KasirController::class, 'transaksi']
    )->name('transaksi');

    // =====================================
    // LAPORAN
    // =====================================

    Route::get(
        '/laporan',
        [KasirController::class, 'laporan']
    )->name('laporan');

    // DOWNLOAD PDF
    Route::get(
        '/laporan/pdf',
        [LaporanController::class, 'exportPdf']
    )->name('laporan.pdf');

    // =====================================
    // DETAIL PESANAN
    // =====================================

    Route::get(
        '/detail/{id}',
        [KasirController::class, 'detail']
    )->name('detail');

    // =====================================
    // KONFIRMASI PESANAN
    // =====================================

    Route::patch(
        '/pesanan/{id}/konfirmasi',
        [KasirController::class, 'konfirmasi']
    )->name('konfirmasi');

    Route::patch(
        '/pesanan/{id}/selesai',
        [KasirController::class, 'selesai']
    )->name('selesai');

    // =====================================
    // ACCOUNT
    // =====================================

    Route::get(
        '/account/profil',
        [AccountKasirController::class, 'profil']
    )->name('account.profil');

    Route::put(
        '/account/update',
        [AccountKasirController::class, 'updateProfil']
    )->name('account.update');

    Route::get(
        '/account/ganti-sandi',
        [AccountKasirController::class, 'gantiSandi']
    )->name('account.ganti-sandi');

    Route::put(
        '/account/update-password',
        [AccountKasirController::class, 'updatePassword']
    )->name('account.update-password');

});

// ══════════════════════════════════════════════════════
// DAPUR
// ══════════════════════════════════════════════════════

Route::middleware(['auth', 'role:dapur'])
    ->prefix('dapur')
    ->name('dapur.')
    ->group(function () {

    // HALAMAN UTAMA DAPUR
    Route::get(
        '/proses',
        [DapurController::class, 'proses']
    )->name('proses');

    // RIWAYAT SELESAI
    Route::get(
        '/selesai',
        [DapurController::class, 'selesaiView']
    )->name('selesai');

    // TANDAI SELESAI
    Route::post(
        '/selesai/{id}',
        [DapurController::class, 'selesai']
    )->name('tandaiSelesai');

    // ACCOUNT
    Route::get(
        '/account/profil',
        [DapurController::class, 'profil']
    )->name('account.profil');

    Route::put(
        '/account/update',
        [DapurController::class, 'updateProfil']
    )->name('account.update');

    Route::get(
        '/account/ganti-sandi',
        [DapurController::class, 'gantiSandi']
    )->name('account.ganti-sandi');

    Route::put(
        '/account/update-password',
        [DapurController::class, 'updatePassword']
    )->name('account.update-password');

});

// ══════════════════════════════════════════════════════
// PELAYAN
// ══════════════════════════════════════════════════════

Route::middleware(['auth', 'role:pelayan'])
    ->prefix('pelayan')
    ->name('pelayan.')
    ->group(function () {

    Route::get(
        '/antar',
        [PelayanController::class, 'antar']
    )->name('antar');

    Route::get(
        '/poll',
        [PelayanController::class, 'poll']
    )->name('poll');

    Route::get(
        '/meja',
        [PelayanController::class, 'meja']
    )->name('meja');

    Route::patch(
        '/antar/{id}/diantar',
        [PelayanController::class, 'tandaiDiantar']
    )->name('antar.selesai');

    // ACCOUNT
    Route::get(
        '/account/profil',
        [AccountPelayanController::class, 'profil']
    )->name('account.profil');

    Route::put(
        '/account/update',
        [AccountPelayanController::class, 'updateProfil']
    )->name('account.update');

    Route::get(
        '/account/ganti-sandi',
        [AccountPelayanController::class, 'gantiSandi']
    )->name('account.ganti-sandi');

    Route::put(
        '/account/update-password',
        [AccountPelayanController::class, 'updatePassword']
    )->name('account.update-password');

});

// ══════════════════════════════════════════════════════
// KONFIRMASI PESANAN
// ══════════════════════════════════════════════════════

Route::middleware('auth')
    ->get(
        '/konfirmasi',
        [KonfirmasiPesananController::class, 'index']
    )
    ->name('konfirmasi');