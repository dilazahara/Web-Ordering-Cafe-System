@extends('layouts.dapur')

@section('title', 'Dapur — Sedang Diproses')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
  <div>
    <div class="page-title">Sedang Diproses</div>
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
        <div class="stat-label">Pesanan aktif</div>
      </div>
    </div>

    <div class="stat-card" id="statLateCard" style="display:none;">
      <div class="stat-icon red">⏰</div>
      <div>
        <div class="stat-val" id="statLateNum">0</div>
        <div class="stat-label">Lewat 10 menit</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green">⚡</div>
      <div>
        <div class="stat-val" id="statFastest">--:--</div>
        <div class="stat-label">Order terbaru</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon amber">🧾</div>
      <div>
        <div class="stat-val">{{ $orders->sum(fn($o) => $o->items->sum('qty')) }}</div>
        <div class="stat-label">Total item</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green">✅</div>
      <div>
        <div class="stat-val" id="statSelesai">{{ $totalSelesaiHariIni ?? 0 }}</div>
        <div class="stat-label">Order selesai</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon purple">⚡</div>
      <div>
        <div class="stat-val" id="statAvgTime">{{ $rataRataWaktu ?? '0' }} mnt</div>
        <div class="stat-label">Rata-rata waktu</div>
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
        <span>Tadaa! Sudah Selesai!</span>
        <span class="swipe-confirm-icon">✅</span>
      </div>

      {{-- THE CARD --}}
      <div class="order-card">
        <div class="swipe-bar"></div>

        {{-- HEADER CARD --}}
        <div class="order-card-top">
          <div class="oc-left">
            <div class="table-badge">👨‍🍳</div>
            <div class="oc-info">
              <h3>🍽️ Meja {{ $order->table_number ?? '-' }}</h3>
              <p>
                {{ $order->queue_number }}
                &nbsp;·&nbsp;
                @if($order->payment_method === 'qris')
                  <span class="pill pill-green">📱 QRIS (Auto)</span>
                @else
                  <span class="pill pill-amber">💵 Cash</span>
                @endif
                &nbsp;·&nbsp;
                <span class="pill pill-blue">Diproses</span>
              </p>
            </div>
          </div>
          <div class="oc-time time" data-time="{{ $order->process_at ?? $order->created_at }}">00:00</div>
        </div>

        {{-- PROGRESS --}}
        <div class="progress-wrap">
          <div class="progress-top">
            <span class="progress-label">⏱ Waktu proses</span>
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
              <div class="item-qty">{{ $item->qty }}x</div>
              <div>
                <div class="item-name">{{ $item->menu->name ?? ($item->menu->nama ?? 'Menu #'.$item->menu_id) }}</div>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        {{-- NOTE --}}
        @if($order->note)
        <div style="margin:0 20px 14px;padding:10px 14px;background:var(--amber-bg);border:1px solid #fde68a;border-radius:10px;font-size:12.5px;color:var(--amber-text);">
          📝 <strong>Catatan:</strong> {{ $order->note }}
        </div>
        @endif

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
          <div class="tip-desc">Timer otomatis mulai hitung mundur saat pesanan masuk ke dapur</div>
        </div>
      </div>

      <div class="empty-tip">
        <span class="tip-icon">✅</span>
        <div class="tip-body">
          <div class="tip-title">Tandai selesai dengan mudah</div>
          <div class="tip-desc">Geser kartu pesanan ke kanan untuk menandai sudah selesai dimasak</div>
        </div>
      </div>

    </div>

    <div class="empty-status">
      <span class="empty-status-dot"></span>
      Siap menerima pesanan baru
    </div>

  </div>

@endif

{{-- ══════════════════════════════════════
     TOAST — WAJIB ADA di HTML
     (sebelumnya tidak ada, menyebabkan showToast() error)
══════════════════════════════════════ --}}
<div id="toast" style="
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%) translateY(80px);
  background: #1e293b;
  color: white;
  padding: 13px 22px;
  border-radius: 14px;
  font-size: 14px;
  font-weight: 700;
  font-family: 'Plus Jakarta Sans', sans-serif;
  display: flex;
  align-items: center;
  gap: 10px;
  z-index: 9999;
  box-shadow: 0 8px 32px rgba(0,0,0,0.22);
  opacity: 0;
  transition: opacity 0.3s ease, transform 0.3s ease;
  pointer-events: none;
  white-space: nowrap;
">
  <span class="toast-icon">🔔</span>
  <span id="toastText">Notifikasi</span>
</div>

@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════
   KONSTANTA
═══════════════════════════════════════════════ */
var CSRF_TOKEN = (function() {
  var meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.content : '';
})();

var TARGET           = 600;   // 10 menit dalam detik
var SWIPE_THRESHOLD  = 0.45;  // 45% lebar card = konfirmasi

/* ═══════════════════════════════════════════════
   SOUND EFFECT
═══════════════════════════════════════════════ */
var notifCtx = null;

function playBeep(freq) {
  freq = freq || 520;
  try {
    if (!notifCtx) notifCtx = new (window.AudioContext || window.webkitAudioContext)();
    var o = notifCtx.createOscillator();
    var g = notifCtx.createGain();
    o.connect(g);
    g.connect(notifCtx.destination);
    o.frequency.value = freq;
    g.gain.setValueAtTime(0.3, notifCtx.currentTime);
    g.gain.exponentialRampToValueAtTime(0.001, notifCtx.currentTime + 0.45);
    o.start();
    o.stop(notifCtx.currentTime + 0.45);
  } catch(e) {}
}

/* ═══════════════════════════════════════════════
   TOAST
   FIX: sebelumnya elemen #toast tidak ada di HTML
        sehingga getElementById mengembalikan null
        dan menyebabkan crash "Cannot read properties of null"
═══════════════════════════════════════════════ */
var _toastTimer = null;

function showToast(msg, icon) {
  icon = icon || '🔔';
  var toast     = document.getElementById('toast');
  var toastText = document.getElementById('toastText');
  var toastIcon = toast ? toast.querySelector('.toast-icon') : null;

  if (!toast || !toastText) return;

  if (toastIcon) toastIcon.textContent = icon;
  toastText.textContent = msg;

  /* Masuk */
  toast.style.opacity   = '1';
  toast.style.transform = 'translateX(-50%) translateY(0)';

  /* Auto hilang setelah 3.5 detik */
  clearTimeout(_toastTimer);
  _toastTimer = setTimeout(function() {
    toast.style.opacity   = '0';
    toast.style.transform = 'translateX(-50%) translateY(80px)';
  }, 3500);
}

/* ═══════════════════════════════════════════════
   SWIPE LOGIC
   FIX: sebelumnya setiap attachSwipe() menambahkan
        mousemove + mouseup ke window, sehingga event
        menumpuk dan semua card merespons drag secara
        bersamaan. Sekarang pakai satu variabel global.
═══════════════════════════════════════════════ */
var _activeDrag = null; /* { outer, card, bar, confirm, startX, cardW } */

/* Handler global — didaftarkan SEKALI di window */
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

  /* ── Touch ── */
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

  /* ── Mouse ── */
  card.addEventListener('mousedown', function(e) {
    if (outer.dataset.done) return;
    e.preventDefault();
    onDragStart(outer, card, bar, confirm, e.clientX);
  });
}

function onDragStart(outer, card, bar, confirm, clientX) {
  _activeDrag = {
    outer  : outer,
    card   : card,
    bar    : bar,
    confirm: confirm,
    startX : clientX,
    cardW  : card.offsetWidth,
    curX   : 0
  };
  card.style.transition = 'none';
}

function onDragMove(drag, clientX) {
  drag.curX      = Math.max(0, clientX - drag.startX);
  var pct        = Math.min(drag.curX / drag.cardW, 1.0);
  var filled     = Math.min(pct / SWIPE_THRESHOLD, 1);

  drag.card.style.transform = 'translateX(' + (pct * 100) + '%)';
  drag.bar.style.width      = (filled * 100) + '%';
  drag.bar.style.background = filled >= 1 ? '#10b981' : '#94a3b8';
  drag.confirm.style.opacity = filled;
}

function onDragEnd(drag) {
  var pct = Math.min(drag.curX / drag.cardW, 1.0);

  if (pct >= SWIPE_THRESHOLD) {
    /* ── CONFIRMED ── */
    drag.outer.dataset.done    = '1';
    drag.card.style.transition = 'transform .28s ease-in, opacity .28s';
    drag.card.style.opacity    = '0';
    drag.card.style.transform  = 'translateX(110%)';

    playBeep(600);
    showToast('Pesanan sudah selesai! ✓', '✅');

    /* Update stat selesai langsung */
    var statSelesai = document.getElementById('statSelesai');
    if (statSelesai) {
      statSelesai.textContent = (parseInt(statSelesai.textContent) || 0) + 1;
    }

    var orderId = drag.outer.dataset.orderId;

    submitSelesai(orderId)
      .then(function(r) {
        if (!r.ok) throw new Error('server error');
      })
      .catch(function() {
        /* Rollback */
        delete drag.outer.dataset.done;
        drag.card.style.transition = 'transform .35s cubic-bezier(.25,.46,.45,.94), opacity .2s';
        drag.card.style.transform  = '';
        drag.card.style.opacity    = '1';
        drag.bar.style.width       = '0%';
        drag.confirm.style.opacity = '0';
        showToast('Gagal menyimpan, coba lagi', '⚠️');
        if (statSelesai) {
          statSelesai.textContent = Math.max(0, (parseInt(statSelesai.textContent) || 1) - 1);
        }
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

/* ═══════════════════════════════════════════════
   SUBMIT KE SERVER
═══════════════════════════════════════════════ */
function submitSelesai(orderId) {
  return fetch('/dapur/selesai/' + orderId, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    body: '_token=' + encodeURIComponent(CSRF_TOKEN)
  });
}

/* ═══════════════════════════════════════════════
   COLLAPSE & REMOVE CARD
═══════════════════════════════════════════════ */
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

  setTimeout(function() {
    outer.remove();
    checkIfEmpty();
  }, 420);
}

function checkIfEmpty() {
  var grid = document.getElementById('orderGrid');
  if (grid && !grid.querySelector('.swipe-outer')) {
    setTimeout(function() { window.location.reload(); }, 500);
  }
}

/* ═══════════════════════════════════════════════
   POLLING — deteksi pesanan baru tiap 5 detik
═══════════════════════════════════════════════ */
var lastOrderIds = null; /* null = belum inisialisasi (first load) */

function pollNewOrders() {
  fetch('/dapur/poll-orders')
    .then(function(r) { return r.json(); })
    .then(function(data) {
      var orders     = data.orders || [];
      var currentIds = orders.map(function(o) { return o.id; });

      if (lastOrderIds !== null) {
        /* Pesanan baru */
        var newOrders = currentIds.filter(function(id) {
          return lastOrderIds.indexOf(id) === -1;
        });

        if (newOrders.length > 0) {
          playBeep(700);
          showToast(newOrders.length + ' pesanan baru masuk! 🔥', '🍳');

          /* Refresh konten tanpa full reload */
          fetch(window.location.href)
            .then(function(res) { return res.text(); })
            .then(function(html) {
              var parser  = new DOMParser();
              var doc     = parser.parseFromString(html, 'text/html');
              var newGrid = doc.getElementById('orderGrid');
              var curGrid = document.getElementById('orderGrid');

              if (newGrid && curGrid) {
                curGrid.innerHTML = newGrid.innerHTML;
                curGrid.querySelectorAll('.swipe-outer').forEach(function(outer) {
                  attachSwipe(outer);
                });
              }
            })
            .catch(function() {});
        }
      }

      lastOrderIds = currentIds;

      /* Update stat dari response poll jika tersedia */
      if (data.totalSelesai !== undefined) {
        var statSelesai = document.getElementById('statSelesai');
        if (statSelesai) statSelesai.textContent = data.totalSelesai;
      }
      if (data.rataRataWaktu !== undefined) {
        var statAvg = document.getElementById('statAvgTime');
        if (statAvg) statAvg.textContent = data.rataRataWaktu + ' mnt';
      }
    })
    .catch(function() {
      /* Gagal fetch — coba lagi di interval berikutnya */
    });
}

/* ═══════════════════════════════════════════════
   TIMER — update setiap detik
═══════════════════════════════════════════════ */
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
      if (pct >= 100) {
        el.style.background = 'linear-gradient(90deg,#ef4444,#dc2626)';
      }
    }
  });
}

/* ═══════════════════════════════════════════════
   STATS BANNER — update setiap detik
═══════════════════════════════════════════════ */
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

  /* Order terbaru */
  var statFastest = document.getElementById('statFastest');
  if (statFastest && minDiff !== Infinity) {
    var m = String(Math.floor(minDiff / 60)).padStart(2, '0');
    var s = String(minDiff % 60).padStart(2, '0');
    statFastest.textContent = m + ':' + s;
  }

  /* Stat card: lewat 10 menit */
  var statLateCard = document.getElementById('statLateCard');
  var statLateNum  = document.getElementById('statLateNum');
  if (statLateCard && statLateNum) {
    statLateNum.textContent = lateCount;
    if (lateCount > 0) {
      statLateCard.style.display = 'flex';
      statLateCard.classList.add('urgent-card');
    } else {
      statLateCard.style.display = 'none';
      statLateCard.classList.remove('urgent-card');
    }
  }

  /* Urgent alert banner */
  var urgentAlert      = document.getElementById('urgentAlert');
  var urgentAlertText  = document.getElementById('urgentAlertText');
  var urgentAlertCount = document.getElementById('urgentAlertCount');
  if (urgentAlert) {
    if (lateCount > 0) {
      urgentAlert.classList.add('visible');
      if (urgentAlertCount) urgentAlertCount.textContent = lateCount;
      if (urgentAlertText) {
        urgentAlertText.textContent = lateCount === 1
          ? '1 pesanan sudah melewati 10 menit — segera selesaikan!'
          : lateCount + ' pesanan sudah melewati batas waktu — prioritaskan sekarang!';
      }
    } else {
      urgentAlert.classList.remove('visible');
    }
  }
}

/* ═══════════════════════════════════════════════
   INIT — jalankan saat DOM siap
═══════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function() {
  /* Pasang swipe ke semua card */
  document.querySelectorAll('.swipe-outer').forEach(function(outer) {
    attachSwipe(outer);
  });

  /* Jalankan timer & stats */
  updateTimers();
  updateStats();
  setInterval(updateTimers, 1000);
  setInterval(updateStats,  1000);

  /* Mulai polling */
  pollNewOrders();
  setInterval(pollNewOrders, 5000);
});
</script>
@endpush