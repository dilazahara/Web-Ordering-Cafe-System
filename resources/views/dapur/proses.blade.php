@extends('layouts.dapur')

@section('title', 'Dapur — Sedang Diproses')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
  <div>
    <div class="page-title">🍳 Sedang Diproses</div>
    <div class="page-sub">
      @if($orders->count() > 0)
        {{ $orders->count() }} pesanan sedang dikerjakan
      @else
        Tidak ada pesanan aktif saat ini
      @endif
    </div>
  </div>
  <div class="live-update-badge">
    <span class="live-dot"></span> Live update tiap 5 detik
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

@if($orders->count() > 0)

  {{-- ── SWIPE HINT ── --}}
  <div class="swipe-hint">
    <span class="swipe-hint-icon">👉</span>
    <span>Geser kartu ke kanan untuk tandai pesanan sudah selesai</span>
  </div>

  {{-- ── STATS BANNER ── --}}
  <div class="stats-banner">

    <div class="stat-card">
      <div class="stat-icon blue">🍽️</div>
      <div>
        <div class="stat-val">{{ $orders->count() }}</div>
        <div class="stat-label">Pesanan Aktif</div>
      </div>
    </div>

    <div class="stat-card" id="statLateCard" style="display:none;">
      <div class="stat-icon red">⏰</div>
      <div>
        <div class="stat-val" id="statLateNum">0</div>
        <div class="stat-label">Lewat 10 Menit</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green">⚡</div>
      <div>
        <div class="stat-val" id="statFastest">--:--</div>
        <div class="stat-label">Order Terbaru</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon amber">🧾</div>
      <div>
        <div class="stat-val">{{ $orders->sum(fn($o) => $o->items->sum('qty')) }}</div>
        <div class="stat-label">Total Item</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green">✅</div>
      <div>
        <div class="stat-val" id="statSelesai">{{ $totalSelesaiHariIni ?? 0 }}</div>
        <div class="stat-label">Order Selesai</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon purple">⚡</div>
      <div>
        <div class="stat-val" id="statAvgTime">{{ $rataRataWaktu ?? '0' }} mnt</div>
        <div class="stat-label">Rata-rata Waktu</div>
      </div>
    </div>

  </div>

  {{-- ── URGENT ALERT ── --}}
  <div class="urgent-alert" id="urgentAlert">
    <span class="urgent-alert-icon">🚨</span>
    <span class="urgent-alert-text" id="urgentAlertText">Ada pesanan yang melewati batas waktu!</span>
    <span class="urgent-alert-count" id="urgentAlertCount">0</span>
  </div>

  {{-- ── ORDER GRID ── --}}
  <div class="order-grid" id="orderGrid">

    @foreach($orders as $order)
    <div class="swipe-outer" data-order-id="{{ $order->id }}">

      {{-- GREEN CONFIRMATION LAYER --}}
      <div class="swipe-confirm">
        <span>Selesai!</span>
        <span class="swipe-confirm-icon">✅</span>
      </div>

      {{-- THE CARD --}}
      <div class="order-card">
        <div class="swipe-bar"></div>

        {{-- STATUS INDICATOR STRIP (atas card, warna dinamis via JS) --}}
        <div class="status-strip" data-time="{{ $order->process_at ?? $order->created_at }}">
          <span class="status-strip-dot"></span>
          <span class="status-strip-label">Memuat...</span>
        </div>

        {{-- HEADER CARD --}}
        <div class="order-card-top">
          <div class="oc-meta">

            {{-- Baris 1: Nomor meja + waktu --}}
            <div class="oc-row-main">
              <div class="oc-table-info">
                <div class="table-badge">🍽️</div>
                <div>
                  <div class="oc-table-name">Meja {{ $order->table_number ?? '-' }}</div>
                  @if($order->customer_name)
                    <div class="oc-customer">👤 {{ $order->customer_name }}</div>
                  @endif
                </div>
              </div>
              <div class="oc-time time" data-time="{{ $order->process_at ?? $order->created_at }}">00:00</div>
            </div>

            {{-- Baris 2: antrian + metode + status --}}
            <div class="oc-row-pills">
              <span class="pill pill-gray">{{ $order->queue_number }}</span>
              @php
                $midtransMethods = ['gopay','ovo','dana','shopeepay','bca','bni','bri','mandiri','permata','credit_card','midtrans'];
                $midtransLabel = match($order->payment_method) {
                    'gopay'       => '💚 GoPay',
                    'ovo'         => '🟣 OVO',
                    'dana'        => '🔵 DANA',
                    'shopeepay'   => '🟠 ShopeePay',
                    'bca'         => '🏦 VA BCA',
                    'bni'         => '🏦 VA BNI',
                    'bri'         => '🏦 VA BRI',
                    'mandiri'     => '🏦 Mandiri',
                    'permata'     => '🏦 Permata',
                    'credit_card' => '💳 Kartu Kredit',
                    'midtrans'    => '💳 Midtrans',
                    default       => null,
                };
              @endphp
              @if($order->payment_method === 'qris')
                <span class="pill pill-green">📱 QRIS</span>
              @elseif(in_array($order->payment_method, $midtransMethods))
                <span class="pill pill-green">✅ {{ $midtransLabel }}</span>
              @else
                <span class="pill pill-green">✅ Cash</span>
              @endif
              <span class="pill pill-blue">Diproses</span>
            </div>

          </div>
        </div>

        {{-- PROGRESS --}}
        <div class="progress-wrap">
          <div class="progress-top">
            <span class="progress-label">⏱ Waktu Proses</span>
            <span class="progress-time time" data-time="{{ $order->process_at ?? $order->created_at }}">00:00</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" data-time="{{ $order->process_at ?? $order->created_at }}" style="width:0%"></div>
          </div>
        </div>

        {{-- ITEMS --}}
        <div class="order-items">
          @foreach($order->items as $item)
          <div class="item-row">
            <div class="item-left">
              <div class="item-qty">{{ $item->qty }}×</div>
              @if($item->menu?->image)
                <img src="{{ asset('storage/' . $item->menu?->image) }}"
                     alt="{{ $item->menu?->name ?? '' }}"
                     class="item-img">
              @else
                <div class="item-img-placeholder">🍽️</div>
              @endif
              <div class="item-detail">
                <div class="item-name">{{ $item->menu?->name ?? $item->name ?? 'Menu #'.$item->menu_id }}</div>
                @if($item->notes)
                  <div class="item-notes">📝 {{ $item->notes }}</div>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>

        {{-- NOTE --}}
        @if($order->note)
        <div class="order-note">
          📝 <strong>Catatan:</strong> {{ $order->note }}
        </div>
        @endif

        {{-- SWIPE CTA --}}
        <div class="swipe-cta">
          <span class="swipe-cta-arrow">›››</span>
          <span>Geser untuk selesaikan</span>
        </div>

      </div>{{-- .order-card --}}
    </div>{{-- .swipe-outer --}}
    @endforeach

  </div>{{-- #orderGrid --}}

@else

  {{-- EMPTY STATE --}}
  <div class="empty-state">
    <div class="empty-icon-wrap">
      <div class="empty-glow"></div>
      <span class="empty-icon">🧑‍🍳</span>
    </div>
    <div class="empty-title">Dapur Bersih, Semua Kelar!</div>
    <div class="empty-sub">Tidak ada pesanan yang sedang diproses saat ini 🎉</div>
    <div class="empty-tips">
      <div class="empty-tip">
        <span class="tip-icon">📋</span>
        <div class="tip-body">
          <div class="tip-title">Pesanan baru muncul otomatis</div>
          <div class="tip-desc">Halaman ini memperbarui diri ketika order baru masuk dari kasir</div>
        </div>
      </div>
      <div class="empty-tip">
        <span class="tip-icon">⏱️</span>
        <div class="tip-body">
          <div class="tip-title">Pantau waktu proses</div>
          <div class="tip-desc">Timer otomatis mulai hitung saat pesanan masuk ke dapur</div>
        </div>
      </div>
      <div class="empty-tip">
        <span class="tip-icon">✅</span>
        <div class="tip-body">
          <div class="tip-title">Tandai selesai dengan mudah</div>
          <div class="tip-desc">Geser kartu ke kanan untuk menandai pesanan sudah selesai</div>
        </div>
      </div>
    </div>
    <div class="empty-status">
      <span class="empty-status-dot"></span>
      Siap menerima pesanan baru
    </div>
  </div>

@endif

{{-- TOAST --}}
<div id="toast" style="
  position:fixed; bottom:24px; left:50%;
  transform:translateX(-50%) translateY(80px);
  background:#1e293b; color:white;
  padding:13px 22px; border-radius:14px;
  font-size:14px; font-weight:700;
  display:flex; align-items:center; gap:10px;
  z-index:9999; box-shadow:0 8px 32px rgba(0,0,0,0.22);
  opacity:0; transition:opacity 0.3s ease, transform 0.3s ease;
  pointer-events:none; white-space:nowrap;
">
  <span class="toast-icon">🔔</span>
  <span id="toastText">Notifikasi</span>
</div>

@endsection

@push('styles')
<style>

/* ══════════════════════════════════════
   STATUS INDICATOR STRIP
══════════════════════════════════════ */
.status-strip {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 18px;
  font-size: 12px;
  font-weight: 700;
  font-family: 'Inter', sans-serif;
  letter-spacing: 0.3px;
  border-bottom: 1px solid transparent;
  transition: background 0.4s, color 0.4s, border-color 0.4s;
}

/* 🟡 Baru Masuk (0–2 menit) */
.status-strip.s-new {
  background: #fffbeb;
  color: #92400e;
  border-color: #fde68a;
}
.status-strip.s-new .status-strip-dot {
  background: #f59e0b;
  box-shadow: 0 0 0 3px rgba(245,158,11,0.2);
  animation: pulse-dot 1.2s ease-in-out infinite;
}

/* 🔵 Sedang Dimasak (2–10 menit) */
.status-strip.s-cooking {
  background: #eff6ff;
  color: #1e40af;
  border-color: #bfdbfe;
}
.status-strip.s-cooking .status-strip-dot {
  background: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
  animation: pulse-dot 1.5s ease-in-out infinite;
}

/* 🔴 Butuh Perhatian (>10 menit) */
.status-strip.s-late {
  background: #fef2f2;
  color: #991b1b;
  border-color: #fecaca;
  animation: late-flash 2s ease-in-out infinite;
}
.status-strip.s-late .status-strip-dot {
  background: #ef4444;
  box-shadow: 0 0 0 3px rgba(239,68,68,0.25);
  animation: pulse-dot 0.8s ease-in-out infinite;
}

.status-strip-dot {
  width: 9px; height: 9px;
  border-radius: 50%;
  flex-shrink: 0;
  transition: background 0.4s;
}

@keyframes pulse-dot {
  0%,100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.35); opacity: 0.7; }
}

@keyframes late-flash {
  0%,100% { background: #fef2f2; }
  50% { background: #fee2e2; }
}

/* ══════════════════════════════════════
   ORDER CARD REDESIGN
══════════════════════════════════════ */
.order-card-top {
  padding: 16px 18px;
  border-bottom: 1px solid var(--border);
  background: var(--surface);
}

.oc-meta { width: 100%; }

.oc-row-main {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 10px;
}

.oc-table-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.table-badge {
  width: 44px; height: 44px;
  border-radius: 12px;
  background: var(--blue-bg);
  border: 1px solid #bfdbfe;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
}

.oc-table-name {
  font-size: 16px;
  font-weight: 800;
  color: var(--text-primary);
  line-height: 1.2;
}

.oc-customer {
  font-size: 12px;
  color: var(--text-muted);
  margin-top: 2px;
  font-family: 'Inter', sans-serif;
}

.oc-time {
  font-size: 14px;
  font-weight: 800;
  color: var(--text-secondary);
  font-family: 'Inter', sans-serif;
  background: var(--surface-2);
  padding: 6px 12px;
  border-radius: 10px;
  border: 1px solid var(--border);
  flex-shrink: 0;
  font-variant-numeric: tabular-nums;
}
.oc-time.warning { color: var(--red); background: var(--red-bg); border-color: #fecaca; }

.oc-row-pills {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}

.pill-gray {
  background: var(--surface-2);
  color: var(--text-secondary);
  border: 1px solid var(--border);
}

/* Items */
.order-items { padding: 12px 18px; }
.item-row {
  display: flex;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid var(--border);
}
.item-row:last-child { border-bottom: none; }
.item-left { display: flex; align-items: center; gap: 10px; flex: 1; }
.item-qty {
  min-width: 36px; height: 36px;
  border-radius: 10px;
  background: #eff6ff; color: #2563eb;
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 13px; flex-shrink: 0;
}
.item-img {
  width: 48px; height: 48px;
  object-fit: cover; border-radius: 10px;
  flex-shrink: 0; border: 1px solid var(--border);
}
.item-img-placeholder {
  width: 48px; height: 48px;
  border-radius: 10px; background: var(--surface-2);
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; flex-shrink: 0;
}
.item-detail { flex: 1; min-width: 0; }
.item-name {
  font-size: 15px; font-weight: 700; color: var(--text-primary);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.item-notes { font-size: 11px; color: var(--text-muted); margin-top: 2px; font-style: italic; }

/* Order note */
.order-note {
  margin: 0 18px 12px;
  padding: 10px 14px;
  background: var(--amber-bg);
  border: 1px solid #fde68a;
  border-radius: 10px;
  font-size: 12.5px;
  color: var(--amber-text);
}

/* Swipe CTA */
.swipe-cta {
  display: flex; align-items: center; justify-content: center;
  gap: 8px; padding: 10px;
  background: var(--surface-2);
  border-top: 1px solid var(--border);
  font-size: 12px; font-weight: 700;
  color: var(--text-muted); letter-spacing: 0.5px;
}
.swipe-cta-arrow {
  color: var(--green); font-size: 14px;
  animation: arrow-bounce 1.5s ease-in-out infinite;
}
@keyframes arrow-bounce {
  0%,100% { transform: translateX(0); }
  50% { transform: translateX(6px); }
}

/* Card accent top */
.order-card::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, #3b82f6, #8b5cf6);
  z-index: 5;
}
</style>
@endpush

@push('scripts')
<script>
var CSRF_TOKEN = (function() {
  var meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.content : '';
})();

var TARGET          = 600;
var SWIPE_THRESHOLD = 0.45;

/* ── SOUND ── */
var notifCtx = null;
function playBeep(freq) {
  freq = freq || 520;
  try {
    if (!notifCtx) notifCtx = new (window.AudioContext || window.webkitAudioContext)();
    var o = notifCtx.createOscillator();
    var g = notifCtx.createGain();
    o.connect(g); g.connect(notifCtx.destination);
    o.frequency.value = freq;
    g.gain.setValueAtTime(0.3, notifCtx.currentTime);
    g.gain.exponentialRampToValueAtTime(0.001, notifCtx.currentTime + 0.45);
    o.start(); o.stop(notifCtx.currentTime + 0.45);
  } catch(e) {}
}

/* ── TOAST ── */
var _toastTimer = null;
function showToast(msg, icon) {
  icon = icon || '🔔';
  var toast     = document.getElementById('toast');
  var toastText = document.getElementById('toastText');
  var toastIcon = toast ? toast.querySelector('.toast-icon') : null;
  if (!toast || !toastText) return;
  if (toastIcon) toastIcon.textContent = icon;
  toastText.textContent = msg;
  toast.style.opacity   = '1';
  toast.style.transform = 'translateX(-50%) translateY(0)';
  clearTimeout(_toastTimer);
  _toastTimer = setTimeout(function() {
    toast.style.opacity   = '0';
    toast.style.transform = 'translateX(-50%) translateY(80px)';
  }, 3500);
}

/* ══════════════════════════════════════
   STATUS STRIP UPDATER
   Dipanggil setiap detik bersama updateTimers()
══════════════════════════════════════ */
function updateStatusStrips() {
  document.querySelectorAll('.status-strip[data-time]').forEach(function(strip) {
    var created = new Date(strip.dataset.time);
    if (isNaN(created.getTime())) return;

    var diff    = Math.floor((Date.now() - created) / 1000); // detik
    var menit   = Math.floor(diff / 60);
    var label   = strip.querySelector('.status-strip-label');

    strip.classList.remove('s-new', 's-cooking', 's-late');

    if (diff < 120) {
      /* 0–2 menit: Baru Masuk */
      strip.classList.add('s-new');
      if (label) label.textContent = '🟡 Baru Masuk — menunggu diproses';
    } else if (diff < 600) {
      /* 2–10 menit: Sedang Dimasak */
      strip.classList.add('s-cooking');
      if (label) label.textContent = '🔵 Sedang Dimasak — ' + menit + ' menit berlalu';
    } else {
      /* >10 menit: Butuh Perhatian */
      strip.classList.add('s-late');
      if (label) label.textContent = '🔴 Butuh Perhatian — sudah ' + menit + ' menit!';
    }
  });
}

/* ── SWIPE ── */
var _activeDrag = null;

window.addEventListener('mousemove', function(e) {
  if (!_activeDrag) return;
  onDragMove(_activeDrag, e.clientX);
});
window.addEventListener('mouseup', function() {
  if (!_activeDrag) return;
  onDragEnd(_activeDrag);
  _activeDrag = null;
});

function attachSwipe(outer) {
  var card    = outer.querySelector('.order-card');
  var bar     = outer.querySelector('.swipe-bar');
  var confirm = outer.querySelector('.swipe-confirm');
  if (!card || !bar || !confirm) return;

  card.addEventListener('touchstart', function(e) {
    if (outer.dataset.done) return;
    onDragStart(outer, card, bar, confirm, e.touches[0].clientX);
  }, { passive: true });

  card.addEventListener('touchmove', function(e) {
    if (!_activeDrag || _activeDrag.outer !== outer) return;
    e.preventDefault();
    onDragMove(_activeDrag, e.touches[0].clientX);
  }, { passive: false });

  card.addEventListener('touchend', function() {
    if (!_activeDrag || _activeDrag.outer !== outer) return;
    onDragEnd(_activeDrag);
    _activeDrag = null;
  });

  card.addEventListener('touchcancel', function() {
    if (!_activeDrag || _activeDrag.outer !== outer) return;
    snapBack(_activeDrag);
    _activeDrag = null;
  });

  card.addEventListener('mousedown', function(e) {
    if (outer.dataset.done) return;
    e.preventDefault();
    onDragStart(outer, card, bar, confirm, e.clientX);
  });
}

function onDragStart(outer, card, bar, confirm, clientX) {
  _activeDrag = { outer: outer, card: card, bar: bar, confirm: confirm, startX: clientX, cardW: card.offsetWidth, curX: 0 };
  card.style.transition = 'none';
}

function onDragMove(drag, clientX) {
  drag.curX  = Math.max(0, clientX - drag.startX);
  var pct    = Math.min(drag.curX / drag.cardW, 1.0);
  var filled = Math.min(pct / SWIPE_THRESHOLD, 1);
  drag.card.style.transform  = 'translateX(' + (pct * 100) + '%)';
  drag.bar.style.width       = (filled * 100) + '%';
  drag.bar.style.background  = filled >= 1 ? '#10b981' : '#94a3b8';
  drag.confirm.style.opacity = filled;
}

function onDragEnd(drag) {
  var pct = Math.min(drag.curX / drag.cardW, 1.0);
  if (pct >= SWIPE_THRESHOLD) {
    drag.outer.dataset.done    = '1';
    drag.card.style.transition = 'transform .28s ease-in, opacity .28s';
    drag.card.style.opacity    = '0';
    drag.card.style.transform  = 'translateX(110%)';
    playBeep(600);
    showToast('Pesanan sudah selesai! ✓', '✅');
    var statSelesai = document.getElementById('statSelesai');
    if (statSelesai) statSelesai.textContent = (parseInt(statSelesai.textContent) || 0) + 1;
    var orderId = drag.outer.dataset.orderId;
    submitSelesai(orderId)
      .then(function(r) { if (!r.ok) throw new Error('server error'); })
      .catch(function() {
        delete drag.outer.dataset.done;
        drag.card.style.transition = 'transform .35s cubic-bezier(.25,.46,.45,.94), opacity .2s';
        drag.card.style.transform  = '';
        drag.card.style.opacity    = '1';
        drag.bar.style.width       = '0%';
        drag.confirm.style.opacity = '0';
        showToast('Gagal menyimpan, coba lagi', '⚠️');
        if (statSelesai) statSelesai.textContent = Math.max(0, (parseInt(statSelesai.textContent) || 1) - 1);
      });
    setTimeout(function() { collapseCard(drag.outer); }, 260);
  } else {
    snapBack(drag);
  }
}

function snapBack(drag) {
  drag.card.style.transition = 'transform .35s cubic-bezier(.25,.46,.45,.94)';
  drag.card.style.transform  = '';
  drag.bar.style.width       = '0%';
  drag.confirm.style.opacity = '0';
}

function submitSelesai(orderId) {
  return fetch('/dapur/selesai/' + orderId, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF_TOKEN },
    body: '_token=' + encodeURIComponent(CSRF_TOKEN)
  });
}

function collapseCard(outer) {
  var h = outer.offsetHeight;
  outer.style.overflow  = 'hidden';
  outer.style.maxHeight = h + 'px';
  requestAnimationFrame(function() {
    outer.style.transition = 'max-height .38s ease, opacity .28s ease, margin .38s ease';
    outer.style.maxHeight  = '0';
    outer.style.opacity    = '0';
    outer.style.margin     = '0';
  });
  setTimeout(function() { outer.remove(); checkIfEmpty(); }, 420);
}

function checkIfEmpty() {
  var grid = document.getElementById('orderGrid');
  if (grid && !grid.querySelector('.swipe-outer')) {
    setTimeout(function() { window.location.reload(); }, 500);
  }
}

/* ── POLLING ── */
var lastOrderIds = null;
function pollNewOrders() {
  fetch('/dapur/poll-orders')
    .then(function(r) { return r.json(); })
    .then(function(data) {
      var orders     = data.orders || [];
      var currentIds = orders.map(function(o) { return o.id; });
      if (lastOrderIds !== null) {
        var newOrders = currentIds.filter(function(id) { return lastOrderIds.indexOf(id) === -1; });
        if (newOrders.length > 0) {
          playBeep(700);
          showToast(newOrders.length + ' pesanan baru masuk! 🔥', '🍳');
          fetch(window.location.href)
            .then(function(res) { return res.text(); })
            .then(function(html) {
              var parser  = new DOMParser();
              var doc     = parser.parseFromString(html, 'text/html');
              var newGrid = doc.getElementById('orderGrid');
              var curGrid = document.getElementById('orderGrid');
              if (newGrid && curGrid) {
                curGrid.innerHTML = newGrid.innerHTML;
                curGrid.querySelectorAll('.swipe-outer').forEach(function(outer) { attachSwipe(outer); });
              }
            }).catch(function() {});
        }
      }
      lastOrderIds = currentIds;
      if (data.totalSelesai !== undefined) {
        var statSelesai = document.getElementById('statSelesai');
        if (statSelesai) statSelesai.textContent = data.totalSelesai;
      }
      if (data.rataRataWaktu !== undefined) {
        var statAvg = document.getElementById('statAvgTime');
        if (statAvg) statAvg.textContent = data.rataRataWaktu + ' mnt';
      }
    }).catch(function() {});
}

/* ── TIMER ── */
function updateTimers() {
  document.querySelectorAll('[data-time]').forEach(function(el) {
    var created = new Date(el.dataset.time);
    if (isNaN(created.getTime())) return;
    var diff = Math.floor((Date.now() - created) / 1000);
    var m    = String(Math.floor(diff / 60)).padStart(2, '0');
    var s    = String(diff % 60).padStart(2, '0');
    if (el.classList.contains('time')) {
      el.textContent = m + ':' + s;
      el.classList.toggle('warning', diff >= TARGET);
    }
    if (el.classList.contains('progress-fill')) {
      var pct = Math.min((diff / TARGET) * 100, 100);
      el.style.width = pct + '%';
      if (pct >= 100) el.style.background = 'linear-gradient(90deg,#ef4444,#dc2626)';
    }
  });
}

/* ── STATS ── */
function updateStats() {
  var fills = document.querySelectorAll('.progress-fill[data-time]');
  if (!fills.length) return;
  var lateCount = 0;
  var minDiff   = Infinity;
  fills.forEach(function(el) {
    var created = new Date(el.dataset.time);
    if (isNaN(created.getTime())) return;
    var diff = Math.floor((Date.now() - created) / 1000);
    if (diff >= TARGET) lateCount++;
    if (diff < minDiff) minDiff = diff;
  });
  var statFastest = document.getElementById('statFastest');
  if (statFastest && minDiff !== Infinity) {
    statFastest.textContent = String(Math.floor(minDiff/60)).padStart(2,'0') + ':' + String(minDiff%60).padStart(2,'0');
  }
  var statLateCard = document.getElementById('statLateCard');
  var statLateNum  = document.getElementById('statLateNum');
  if (statLateCard && statLateNum) {
    statLateNum.textContent = lateCount;
    statLateCard.style.display = lateCount > 0 ? 'flex' : 'none';
    if (lateCount > 0) statLateCard.classList.add('urgent-card');
    else statLateCard.classList.remove('urgent-card');
  }
  var urgentAlert      = document.getElementById('urgentAlert');
  var urgentAlertText  = document.getElementById('urgentAlertText');
  var urgentAlertCount = document.getElementById('urgentAlertCount');
  if (urgentAlert) {
    if (lateCount > 0) {
      urgentAlert.classList.add('visible');
      if (urgentAlertCount) urgentAlertCount.textContent = lateCount;
      if (urgentAlertText) urgentAlertText.textContent = lateCount === 1
        ? '1 pesanan sudah melewati 10 menit — segera selesaikan!'
        : lateCount + ' pesanan sudah melewati batas waktu!';
    } else {
      urgentAlert.classList.remove('visible');
    }
  }
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.swipe-outer').forEach(function(outer) { attachSwipe(outer); });
  updateTimers(); updateStats(); updateStatusStrips();
  setInterval(updateTimers,       1000);
  setInterval(updateStats,        1000);
  setInterval(updateStatusStrips, 1000);
  pollNewOrders();
  setInterval(pollNewOrders, 5000);
});
</script>
@endpush