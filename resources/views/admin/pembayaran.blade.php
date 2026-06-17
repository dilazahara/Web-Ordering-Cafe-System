@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')

@push('styles')
<style>
/* ════ CSS KHUSUS HALAMAN PEMBAYARAN ════ */

/* ── ALERTS ── */
.alert-success {
    background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0;
    border-radius: var(--radius-lg); padding: 12px 16px; margin-bottom: var(--space-lg);
    font-size: var(--text-md); font-weight: 500;
    display: flex; align-items: center; gap: 8px; transition: opacity 0.4s;
}
.alert-error {
    background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
    border-radius: var(--radius-lg); padding: 12px 16px; margin-bottom: var(--space-lg);
    font-size: var(--text-md); font-weight: 600;
    display: flex; align-items: flex-start; gap: 8px; line-height: 1.5; transition: opacity 0.4s;
}

/* ── PAYMENT TOGGLE CARDS ── */
.payment-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;
}

.payment-card {
    background: var(--bg-white); border-radius: var(--radius-2xl);
    border: 2px solid var(--border-light);
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    padding: 32px 28px; display: flex; flex-direction: column; gap: 20px;
    transition: all 0.3s ease;
}
.payment-card.active-kasir    { border-color: #fed7aa; box-shadow: 0 8px 24px rgba(249,115,22,0.12); }
.payment-card.active-midtrans { border-color: #86efac; box-shadow: 0 8px 24px rgba(34,197,94,0.12); }

.card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
.card-icon-wrap {
    width: 64px; height: 64px; border-radius: 20px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.card-icon-kasir    { background: #fff7ed; color: #f97316; }
.card-icon-midtrans { background: #f0fdf4; color: #22c55e; }

.card-title { font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px; }
.card-desc  { font-size: 13.5px; color: var(--text-light); line-height: 1.5; }

/* ── SWITCH BIG ── */
.switch-big { position: relative; display: inline-block; width: 64px; height: 34px; flex-shrink: 0; }
.switch-big input { opacity: 0; width: 0; height: 0; }
.slider-big {
    position: absolute; cursor: pointer; top:0; left:0; right:0; bottom:0;
    background: #cbd5e1; border-radius: 34px; transition: 0.3s;
}
.slider-big:before {
    position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px;
    background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}
.switch-kasir input:checked + .slider-big { background: #f97316; }
.switch-midtrans input:checked + .slider-big { background: #22c55e; }
input:checked + .slider-big:before { transform: translateX(30px); }

/* ── STATUS BADGE ── */
.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: var(--radius-full);
    font-size: 12px; font-weight: 700;
}
.badge-on-kasir    { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.badge-on-midtrans { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.badge-off         { background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; }
.dot-status        { width: 7px; height: 7px; border-radius: 50%; }
.dot-kasir-on      { background: #f97316; }
.dot-midtrans-on   { background: #22c55e; }
.dot-off           { background: #cbd5e1; }

.card-footer { border-top: 1px solid var(--border-light); padding-top: 16px; }
.card-footer p { font-size: 12px; color: var(--text-light); line-height: 1.6; }
.card-footer strong { color: var(--text-mid); }

/* ── INFO SECTION ── */
.info-card {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 1px solid #7dd3fc; border-radius: var(--radius-xl);
    padding: 24px 28px; display: flex; gap: 16px; align-items: flex-start;
}
.info-card-icon  { color: #0284c7; flex-shrink: 0; margin-top: 2px; }
.info-card-title { font-size: 15px; font-weight: 700; color: #0369a1; margin-bottom: 8px; }
.info-card-body  { font-size: 13.5px; color: #0c4a6e; line-height: 1.7; }

@media (max-width: 640px) {
    .payment-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-title">
        <h1>Kelola Pembayaran</h1>
        <p>Aktifkan atau nonaktifkan metode pembayaran untuk pelanggan.</p>
    </div>
</div>

{{-- ALERT --}}
@if(session('success'))
<div class="alert-success" id="alertMsg">
    <i data-lucide="check-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert-error" id="alertMsg">
    <i data-lucide="alert-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
    {{ session('error') }}
</div>
@endif

@php
    $midtransCodes = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];
    $kasirMethod    = $paymentMethods->firstWhere('kode', 'cash');
    $midtransMethod = $paymentMethods->whereIn('kode', $midtransCodes)->first();
    $kasirAktif     = $kasirMethod && $kasirMethod->aktif;
    $midtransAktif  = $midtransMethod && $midtransMethod->aktif;
@endphp

<!-- 2 Toggle Cards -->
<div class="payment-grid">

    {{-- ── BAYAR DI KASIR ── --}}
    <div class="payment-card {{ $kasirAktif ? 'active-kasir' : '' }}" id="card-kasir">
        <div class="card-top">
            <div>
                <div class="card-icon-wrap card-icon-kasir">
                    <i data-lucide="store" style="width:32px; height:32px;"></i>
                </div>
            </div>
            @if($kasirMethod)
            <form action="{{ route('admin.pembayaran.toggle', $kasirMethod->id) }}" method="POST" style="margin:0;">
                @csrf
                <label class="switch-big switch-kasir" title="{{ $kasirAktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                    <input type="checkbox" {{ $kasirAktif ? 'checked' : '' }} onchange="this.form.submit()">
                    <span class="slider-big"></span>
                </label>
            </form>
            @else
            <label class="switch-big switch-kasir" title="Metode belum dikonfigurasi" style="opacity:0.4;pointer-events:none;">
                <input type="checkbox" disabled>
                <span class="slider-big"></span>
            </label>
            @endif
        </div>

        <div>
            <div class="card-title">Bayar di Kasir</div>
            <div class="card-desc">Pelanggan membayar tunai langsung ke kasir setelah pesanan selesai dibuat.</div>
        </div>

        <div>
            @if($kasirAktif)
            <span class="status-badge badge-on-kasir">
                <span class="dot-status dot-kasir-on"></span> Aktif
            </span>
            @else
            <span class="status-badge badge-off">
                <span class="dot-status dot-off"></span> Nonaktif
            </span>
            @endif
        </div>

        <div class="card-footer">
            <p>Pelanggan akan melihat pilihan <strong>"Bayar di Kasir"</strong> di halaman checkout saat metode ini aktif.</p>
        </div>
    </div>

    {{-- ── BAYAR ONLINE MIDTRANS ── --}}
    <div class="payment-card {{ $midtransAktif ? 'active-midtrans' : '' }}" id="card-midtrans">
        <div class="card-top">
            <div>
                <div class="card-icon-wrap card-icon-midtrans">
                    <i data-lucide="credit-card" style="width:32px; height:32px;"></i>
                </div>
            </div>
            @if($midtransMethod)
            <form action="{{ route('admin.pembayaran.toggle', $midtransMethod->id) }}" method="POST" style="margin:0;">
                @csrf
                <label class="switch-big switch-midtrans" title="{{ $midtransAktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                    <input type="checkbox" {{ $midtransAktif ? 'checked' : '' }} onchange="this.form.submit()">
                    <span class="slider-big"></span>
                </label>
            </form>
            @else
            <label class="switch-big switch-midtrans" title="Metode belum dikonfigurasi" style="opacity:0.4;pointer-events:none;">
                <input type="checkbox" disabled>
                <span class="slider-big"></span>
            </label>
            @endif
        </div>

        <div>
            <div class="card-title">Bayar Online Midtrans</div>
            <div class="card-desc">Pelanggan membayar online via GoPay, OVO, DANA, Virtual Account Bank, dan lainnya melalui Midtrans.</div>
        </div>

        <div>
            @if($midtransAktif)
            <span class="status-badge badge-on-midtrans">
                <span class="dot-status dot-midtrans-on"></span> Aktif
            </span>
            @else
            <span class="status-badge badge-off">
                <span class="dot-status dot-off"></span> Nonaktif
            </span>
            @endif
        </div>

        <div class="card-footer">
            <p>Pelanggan akan melihat pilihan <strong>"Bayar Online Midtrans"</strong> di halaman checkout saat metode ini aktif.</p>
        </div>
    </div>

</div>

<!-- Info Card -->
<div class="info-card">
    <div class="info-card-icon">
        <i data-lucide="lightbulb" style="width:32px; height:32px;"></i>
    </div>
    <div>
        <div class="info-card-title">Cara Kerja Pembayaran</div>
        <div class="info-card-body">
            Di halaman checkout, pelanggan hanya melihat <strong>2 pilihan</strong> sesuai status di atas:<br>
            • <strong>Bayar di Kasir</strong> — muncul jika tombol Bayar di Kasir dalam kondisi <strong>Aktif</strong>.<br>
            • <strong>Bayar Online Midtrans</strong> — muncul jika tombol Bayar Online Midtrans dalam kondisi <strong>Aktif</strong>.<br>
            Jika keduanya aktif, pelanggan bisa memilih salah satu. Jika salah satu dinonaktifkan, pilihan tersebut tidak akan muncul.
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Auto-dismiss alert
var alertEl = document.getElementById('alertMsg');
if (alertEl) {
    setTimeout(function() {
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.remove(); }, 400);
    }, 4000);
}
</script>
@endpush