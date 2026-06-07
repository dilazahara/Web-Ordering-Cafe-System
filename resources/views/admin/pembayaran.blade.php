@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #1e293b; }

.main { padding: 50px 50px 40px; max-width: 900px; margin: 0 auto; }

.page-header { margin-bottom: 28px; }
.page-header h1 { font-size: 28px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; margin-bottom: 4px; }
.page-header p { font-size: 14px; color: #64748b; }

.alert { display: flex; align-items: center; gap: 10px; padding: 13px 16px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 500; animation: fadeIn 0.3s ease; transition: opacity 0.3s; }
.alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.alert-error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
@keyframes fadeIn { from { opacity:0; transform: translateY(-8px); } to { opacity:1; transform: translateY(0); } }

/* ── PAYMENT TOGGLE CARDS ── */
.payment-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

.payment-card {
    background: white;
    border-radius: 24px;
    border: 2px solid #f1f5f9;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    transition: all 0.3s ease;
}
.payment-card.active-kasir  { border-color: #fed7aa; box-shadow: 0 8px 24px rgba(249,115,22,0.12); }
.payment-card.active-midtrans { border-color: #86efac; box-shadow: 0 8px 24px rgba(34,197,94,0.12); }

.card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
.card-icon-wrap { width: 64px; height: 64px; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; flex-shrink: 0; }
.card-icon-kasir    { background: #fff7ed; }
.card-icon-midtrans { background: #f0fdf4; }

.card-title { font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
.card-desc  { font-size: 13px; color: #64748b; line-height: 1.5; }

/* ── SWITCH BIG ── */
.switch-big { position: relative; display: inline-block; width: 64px; height: 34px; flex-shrink: 0; }
.switch-big input { opacity: 0; width: 0; height: 0; }
.slider-big { position: absolute; cursor: pointer; top:0; left:0; right:0; bottom:0; background: #cbd5e1; border-radius: 34px; transition: 0.3s; }
.slider-big:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
.switch-kasir input:checked + .slider-big { background: #f97316; }
.switch-midtrans input:checked + .slider-big { background: #22c55e; }
input:checked + .slider-big:before { transform: translateX(30px); }

.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
}
.badge-on-kasir    { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.badge-on-midtrans { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.badge-off         { background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; }
.dot-status { width: 7px; height: 7px; border-radius: 50%; }
.dot-kasir-on { background: #f97316; }
.dot-midtrans-on { background: #22c55e; }
.dot-off { background: #cbd5e1; }

.card-footer { border-top: 1px solid #f1f5f9; padding-top: 16px; }
.card-footer p { font-size: 12px; color: #94a3b8; line-height: 1.6; }
.card-footer strong { color: #64748b; }

/* ── INFO SECTION ── */
.info-card {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 1px solid #7dd3fc;
    border-radius: 20px;
    padding: 24px 28px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
}
.info-card-icon { font-size: 32px; flex-shrink: 0; }
.info-card-title { font-size: 15px; font-weight: 700; color: #0369a1; margin-bottom: 8px; }
.info-card-body { font-size: 13px; color: #0c4a6e; line-height: 1.7; }

@media (max-width: 640px) {
    .payment-grid { grid-template-columns: 1fr; }
    .main { padding: 88px 16px 30px; }
}
</style>
@endpush

@section('content')
<div class="main">

    <!-- Page Header -->
    <div class="page-header">
        <h1>Kelola Pembayaran</h1>
        <p>Aktifkan atau nonaktifkan metode pembayaran untuk pelanggan.</p>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success" id="alertMsg">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error" id="alertMsg">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    @php
        $midtransCodes = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];
        $kasirMethod   = $paymentMethods->firstWhere('kode', 'cash');
        $midtransMethod = $paymentMethods->whereIn('kode', $midtransCodes)->first();
        $kasirAktif    = $kasirMethod && $kasirMethod->aktif;
        $midtransAktif = $midtransMethod && $midtransMethod->aktif;

        // Hitung berapa metode midtrans yang aktif
        $midtransAktifCount = $paymentMethods->whereIn('kode', $midtransCodes)->where('aktif', true)->count();
    @endphp

    <!-- 2 Toggle Cards -->
    <div class="payment-grid">

        {{-- ── BAYAR DI KASIR ── --}}
        <div class="payment-card {{ $kasirAktif ? 'active-kasir' : '' }}" id="card-kasir">
            <div class="card-top">
                <div>
                    <div class="card-icon-wrap card-icon-kasir">🏪</div>
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
                    <div class="card-icon-wrap card-icon-midtrans">💳</div>
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
        <div class="info-card-icon">💡</div>
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

</div>{{-- /main --}}
@endsection

@push('scripts')
<script>
var alertEl = document.getElementById('alertMsg');
if (alertEl) {
    setTimeout(function() {
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.remove(); }, 300);
    }, 4000);
}
</script>
@endpush