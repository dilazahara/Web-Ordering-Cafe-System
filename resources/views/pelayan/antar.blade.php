@extends('layouts.pelayan')

@section('title', 'Pelayan — Antar Makanan')

@section('content')
    @if (session('success'))
        <div class="alert-flash success">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="page-head">
        <div>
            <div class="page-title">Antar Makanan</div>
            <div class="page-sub" id="pageSubtitle">Memuat sistem...</div>
        </div>
    </div>

    <div class="swipe-hint">
        <i class="ti ti-hand-finger" aria-hidden="true"></i>
        Geser kartu ke kanan untuk mengonfirmasi pengantaran pesanan
    </div>

    <div class="section-bar">
        <div class="section-label">
            <i class="ti ti-arrow-right" aria-hidden="true"></i>
            Order Siap Diantar
        </div>
        <span class="count-pill" id="countPill">0 pesanan</span>
    </div>

    <div id="ordersGrid" class="grid"></div>
@endsection

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        let lastIds = [];
        let notifCtx = null;

        // Perlindungan interaksi: cegah re-render UI saat koki menahan/menggeser kartu
        let isUserInteracting = false;
        window.addEventListener('mousedown', () => isUserInteracting = true);
        window.addEventListener('mouseup', () => isUserInteracting = false);
        window.addEventListener('touchstart', () => isUserInteracting = true, {
            passive: true
        });
        window.addEventListener('touchend', () => isUserInteracting = false);


        /* ── TOAST ── */
        function showToast(msg, icon = 'ti-check') {
            const t = document.getElementById('toast');
            if (!t) return;
            t.innerHTML = `<i class="ti ${icon}" aria-hidden="true"></i>${msg}`;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        /* ── BEEP ── */
        function playBeep(freq = 520) {
            try {
                if (!notifCtx) notifCtx = new(window.AudioContext || window.webkitAudioContext)();
                const o = notifCtx.createOscillator(),
                    g = notifCtx.createGain();
                o.connect(g);
                g.connect(notifCtx.destination);
                o.frequency.value = freq;
                g.gain.setValueAtTime(0.3, notifCtx.currentTime);
                g.gain.exponentialRampToValueAtTime(0.001, notifCtx.currentTime + 0.45);
                o.start();
                o.stop(notifCtx.currentTime + 0.45);
            } catch (e) {}
        }

        /* ── HELPERS ── */
        function minsAgo(iso) {
            const diff = Math.floor((Date.now() - new Date(iso)) / 60000);
            return diff < 1 ? 'Baru saja' : diff + ' menit lalu';
        }

        function isLate(iso) {
            return (Date.now() - new Date(iso)) / 60000 > 5;
        }

        function nowStr() {
            return new Date().toLocaleString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        /* ── SUBMIT DIANTAR ── */
        function submitDiantar(orderId) {
            return fetch(`/pelayan/antar/${orderId}/diantar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `_token=${encodeURIComponent(CSRF)}&_method=PATCH`
            });
        }

        /* ── COLLAPSE & REMOVE CARD ── */
        function collapseCard(outer) {
            const h = outer.offsetHeight;
            outer.style.overflow = 'hidden';
            outer.style.maxHeight = h + 'px';
            requestAnimationFrame(() => {
                outer.style.transition = 'max-height .38s ease, opacity .28s ease, margin-bottom .38s ease';
                outer.style.maxHeight = '0';
                outer.style.opacity = '0';
                outer.style.marginBottom = '-16px'; // collapse gap
            });
            setTimeout(() => {
                outer.remove();
                checkEmpty();
            }, 420);
        }

        function checkEmpty() {
            const grid = document.getElementById('ordersGrid');
            if (!grid.querySelector('.swipe-outer')) {
                grid.innerHTML = `
      <div class="empty">
        <div class="empty-icon"><i class="ti ti-clipboard-check" aria-hidden="true"></i></div>
        <h3>Antrean Pengantaran Kosong</h3>
        <p>Seluruh pesanan telah berhasil diantar. Menunggu pembaruan pesanan baru dari dapur secara otomatis.</p>
      </div>`;
            }
            updateCounts();
        }

        function updateCounts() {
            const n = document.querySelectorAll('.swipe-outer').length;
            const badgeCount = document.getElementById('badgeCount');
            if (badgeCount) badgeCount.textContent = n + ' Siap Diantar';

            const countPill = document.getElementById('countPill');
            if (countPill) countPill.textContent = n + ' pesanan';
        }

        /* ── SWIPE LOGIC ── */
        const THRESHOLD = 0.45; // 45% lebar card = confirm

        function attachSwipe(outer) {
            const card = outer.querySelector('.card');
            const bar = outer.querySelector('.swipe-bar');
            const confirm = outer.querySelector('.swipe-confirm');
            const orderId = outer.dataset.orderId;

            let startX = 0,
                curX = 0,
                dragging = false,
                cardW = 0;

            const setTransform = pct => {
                card.style.transform = `translateX(${pct * 100}%)`;
            };

            function onStart(x) {
                if (outer.dataset.done) return;
                startX = x;
                curX = 0;
                dragging = true;
                cardW = card.offsetWidth;
                card.style.transition = 'none';
            }

            function onMove(x) {
                if (!dragging) return;
                curX = Math.max(0, x - startX);
                const pct = Math.min(curX / cardW, 1.0);
                const filled = Math.min(pct / THRESHOLD, 1);

                setTransform(pct);
                bar.style.width = (filled * 100) + '%';
                bar.style.background = filled >= 1 ? '#10b981' : '#94a3b8';
                confirm.style.opacity = filled;
            }

            function onMouseMove(e) {
                if (dragging) onMove(e.clientX);
            }

            function onMouseUp() {
                if (dragging) onEnd();
            }

            function onEnd() {
                if (!dragging) return;
                dragging = false;

                window.removeEventListener('mousemove', onMouseMove);
                window.removeEventListener('mouseup', onMouseUp);

                const pct = Math.min(curX / cardW, 1.0);

                if (pct >= THRESHOLD) {
                    // ── CONFIRMED ──
                    outer.dataset.done = '1';
                    card.style.transition = 'transform .28s ease-in, opacity .28s';
                    card.style.opacity = '0';
                    setTransform(1.1);
                    playBeep(600);
                    showToast('Pesanan berhasil diantar', 'ti-circle-check');

                    submitDiantar(orderId)
                        .then(r => {
                            if (!r.ok) throw new Error();
                            setTimeout(() => collapseCard(outer), 260);
                        })
                        .catch(() => {
                            // rollback
                            delete outer.dataset.done;
                            card.style.transition = 'transform .35s cubic-bezier(.25,.46,.45,.94), opacity .2s';
                            card.style.transform = '';
                            card.style.opacity = '1';
                            bar.style.width = '0%';
                            confirm.style.opacity = '0';
                            showToast('Gagal memproses sistem. Silakan coba lagi.', 'ti-alert-triangle');
                        });

                } else {
                    // ── SNAP BACK ──
                    card.style.transition = 'transform .35s cubic-bezier(.25,.46,.45,.94)';
                    card.style.transform = '';
                    bar.style.width = '0%';
                    confirm.style.opacity = '0';
                }
            }

            // Touch
            card.addEventListener('touchstart', e => onStart(e.touches[0].clientX), {
                passive: true
            });
            card.addEventListener('touchmove', e => {
                e.preventDefault();
                onMove(e.touches[0].clientX);
            }, {
                passive: false
            });
            card.addEventListener('touchend', () => onEnd());
            card.addEventListener('touchcancel', () => onEnd());

            // Mouse (desktop)
            card.addEventListener('mousedown', e => {
                e.preventDefault();
                onStart(e.clientX);
                window.addEventListener('mousemove', onMouseMove);
                window.addEventListener('mouseup', onMouseUp);
            });
        }

        /* ── RENDER ── */
        function renderGrid(orders) {
            const sub = document.getElementById('pageSubtitle');
            const grid = document.getElementById('ordersGrid');

            sub.textContent = nowStr() + ' · ' +
                (orders.length === 0 ? 'Antrean saat ini kosong' : 'Menunggu tindakan pengantaran');

            if (orders.length === 0) {
                grid.innerHTML = `
      <div class="empty">
        <div class="empty-icon"><i class="ti ti-clipboard-check" aria-hidden="true"></i></div>
        <h3>Antrean Pengantaran Kosong</h3>
        <p>Seluruh pesanan telah berhasil diantar. Menunggu pembaruan pesanan baru dari dapur secara otomatis.</p>
      </div>`;
                updateCounts();
                return;
            }

            // Hapus element kosong jika ada pesanan
            const emptyState = grid.querySelector('.empty');
            if (emptyState) emptyState.remove();

            // Hapus card lama yang sudah tidak ada di orders
            grid.querySelectorAll('.swipe-outer').forEach(el => {
                if (!orders.find(o => String(o.id) === el.dataset.orderId)) {
                    el.remove();
                }
            });

            // Tambah/Update card baru
            orders.forEach(o => {
                const existingCard = grid.querySelector(`.swipe-outer[data-order-id="${o.id}"]`);
                const late = isLate(o.updated_at);

                if (existingCard) {
                    const wtTime = existingCard.querySelector('.wt-time');
                    if (wtTime) {
                        wtTime.textContent = minsAgo(o.updated_at);
                        if (late) wtTime.classList.add('late');
                        else wtTime.classList.remove('late');
                    }

                    const statusPill = existingCard.querySelector('.status-pill');
                    if (statusPill) {
                        if (late) {
                            statusPill.className = 'status-pill sp-red';
                            statusPill.textContent = 'Terlambat';
                            existingCard.querySelector('.card').classList.add('late-card');
                            existingCard.querySelector('.table-dot').classList.add('late-dot');

                            const waitInfo = existingCard.querySelector('.wait-info');
                            if (waitInfo && !waitInfo.querySelector('.late-tag')) {
                                waitInfo.insertAdjacentHTML('beforeend',
                                    '<span class="late-tag">Segera antar!</span>');
                            }
                        } else {
                            statusPill.className = 'status-pill sp-green';
                            statusPill.textContent = 'Siap Diantar';
                            existingCard.querySelector('.card').classList.remove('late-card');
                            existingCard.querySelector('.table-dot').classList.remove('late-dot');

                            const lateTag = existingCard.querySelector('.late-tag');
                            if (lateTag) lateTag.remove();
                        }
                    }
                    return;
                }

                // Pastikan menangani struktur i.menu dengan aman seperti revisi sebelumnya
                const items = o.items?.map(i => `
      <li class="item-row">
        <span class="item-name">${i.menu ? (i.menu.name ?? i.menu.nama) : 'Menu #' + i.menu_id}</span>
        <span class="item-qty">x${i.qty}</span>
      </li>`).join('') || '';

                const outer = document.createElement('div');
                outer.className = 'swipe-outer';
                outer.dataset.orderId = String(o.id);

                outer.innerHTML = `
      <div class="swipe-confirm">
        <span>Pesanan Selesai</span>
        <i class="ti ti-circle-check" aria-hidden="true"></i>
      </div>

      <div class="card ${late ? 'late-card' : ''}">
        <div class="swipe-bar"></div>

        <div class="card-head">
          <div class="ch-left">
            <div class="table-dot ${late ? 'late-dot' : ''}">🪑</div>
            <div class="ch-info">
              <h3>🍽️ Meja ${o.table_number ?? '—'}</h3>
              <p>${o.queue_number} · ${o.items?.length || 0} item</p>
            </div>
          </div>
          <div class="ch-right">
            <span class="order-time">
              ${new Date(o.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
            </span>
            <span class="status-pill ${late ? 'sp-red' : 'sp-green'}">
              ${late ? 'Terlambat' : 'Siap Diantar'}
            </span>
          </div>
        </div>

        <div class="card-body">
          <div class="wait-info">
            <i class="ti ti-clock" aria-hidden="true"></i>
            Selesai dimasak
            <span class="wt-time ${late ? 'late' : ''}">${minsAgo(o.updated_at)}</span>
            ${late ? '<span class="late-tag">Segera antar!</span>' : ''}
          </div>
          <ul class="item-list">${items}</ul>
          ${o.note ? `<div class="order-note">
                        <i class="ti ti-notes" aria-hidden="true"></i>${o.note}
                      </div>` : ''}
        </div>
      </div>`;

                grid.appendChild(outer);
                attachSwipe(outer);
            });

            checkEmpty();
        }

        /* ── POLLING ── */
        function poll() {
            fetch('/pelayan/poll', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(orders => {
                    const ids = orders.map(o => o.id);
                    const adaBaru = ids.some(id => !lastIds.includes(id));

                    if (adaBaru && lastIds.length > 0) {
                        playBeep(700);
                        showToast('Terdapat pesanan baru yang siap diantar.', 'ti-bell-ringing');
                    }
                    lastIds = ids;

                    if (!isUserInteracting && !document.querySelector('.swipe-outer[data-done="1"]')) {
                        renderGrid(orders);
                    }
                })
                .catch(() => {});
        }

        // Inisialisasi awal
        poll();
        setInterval(poll, 5000);
    </script>
@endpush
