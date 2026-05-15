{{--
  Komponen Bell Notifikasi Realtime
  =================================
  Include di dalam <head> sebelum </head>:
    @include('components.notification-bell')

  Lalu taruh <div id="notif-bell-container"></div>
  di dalam header / topbar HTML kamu.

  CSS dan HTML sudah include semua di sini.
--}}

{{-- ══════════════════════════════════════════════════ --}}
{{-- CSS BELL                                           --}}
{{-- ══════════════════════════════════════════════════ --}}
<style>
/* ── Bell button ─────────────────────────────────── */
#notif-bell-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
}

#notif-bell-btn {
    position: relative;
    width: 40px; height: 40px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #64748b;
    transition: all .18s;
    outline: none;
    font-size: 18px;
}
#notif-bell-btn:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
}
#notif-bell-btn.has-unread {
    border-color: #6366f1;
    color: #6366f1;
}

/* Bell shake animation */
@keyframes bell-shake {
    0%,100% { transform: rotate(0); }
    15%      { transform: rotate(12deg); }
    30%      { transform: rotate(-10deg); }
    45%      { transform: rotate(8deg); }
    60%      { transform: rotate(-6deg); }
    75%      { transform: rotate(4deg); }
}
#notif-bell-btn.shake {
    animation: bell-shake .5s ease;
}

/* Badge angka */
#notif-badge {
    position: absolute;
    top: -5px; right: -5px;
    min-width: 18px; height: 18px;
    background: #ef4444;
    color: white;
    font-size: 10px; font-weight: 700;
    border-radius: 9px;
    display: none;
    align-items: center; justify-content: center;
    padding: 0 5px;
    border: 2px solid white;
    font-family: 'Inter', sans-serif;
    line-height: 1;
}
#notif-badge.show { display: flex; }

/* ── Dropdown panel ──────────────────────────────── */
#notif-panel {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 360px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 20px 60px rgba(0,0,0,.14), 0 0 0 1px rgba(0,0,0,.04);
    z-index: 9999;
    overflow: hidden;
    opacity: 0;
    transform: translateY(-8px) scale(.97);
    pointer-events: none;
    transition: opacity .2s, transform .2s;
}
#notif-panel.open {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: all;
}

/* Panel header */
.notif-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.notif-panel-header h4 {
    font-size: 14px; font-weight: 700;
    color: #0f172a; margin: 0;
    display: flex; align-items: center; gap: 6px;
}
#notif-mark-all {
    font-size: 12px; font-weight: 600;
    color: #6366f1; cursor: pointer;
    background: none; border: none;
    padding: 4px 8px; border-radius: 6px;
    transition: background .15s;
}
#notif-mark-all:hover { background: #eef2ff; }

/* List container */
#notif-list {
    max-height: 380px;
    overflow-y: auto;
}
#notif-list::-webkit-scrollbar { width: 4px; }
#notif-list::-webkit-scrollbar-track { background: transparent; }
#notif-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

/* Empty state */
.notif-empty {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
    font-size: 13px;
}
.notif-empty .notif-empty-icon {
    font-size: 36px; margin-bottom: 10px;
    display: block;
}

/* Individual item */
.notif-item {
    display: flex;
    gap: 12px;
    padding: 14px 18px;
    border-bottom: 1px solid #f8fafc;
    cursor: pointer;
    transition: background .15s;
    position: relative;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #f8fafc; }
.notif-item.unread { background: #fafbff; }
.notif-item.unread::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: #6366f1;
    border-radius: 0 3px 3px 0;
}

/* Icon per type */
.notif-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.notif-icon.order_new       { background: #dbeafe; }
.notif-icon.order_confirmed { background: #fef3c7; }
.notif-icon.order_done      { background: #dcfce7; }
.notif-icon.order_delivered { background: #ede9fe; }

.notif-body { flex: 1; min-width: 0; }
.notif-title {
    font-size: 13px; font-weight: 700;
    color: #0f172a; margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.notif-msg {
    font-size: 12px; color: #64748b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.notif-time {
    font-size: 11px; color: #94a3b8;
    margin-top: 4px;
}
.notif-badge-pill {
    font-size: 10px; font-weight: 700;
    background: #6366f1; color: white;
    padding: 2px 7px; border-radius: 999px;
    align-self: flex-start; margin-top: 2px;
    white-space: nowrap;
}

/* ── Toast popup ─────────────────────────────────── */
#notif-toast-stack {
    position: fixed;
    bottom: 24px; right: 24px;
    z-index: 99999;
    display: flex; flex-direction: column; gap: 10px;
    pointer-events: none;
}
.notif-toast {
    display: flex; align-items: flex-start; gap: 12px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #6366f1;
    border-radius: 14px;
    padding: 14px 18px;
    box-shadow: 0 8px 30px rgba(0,0,0,.12);
    min-width: 300px; max-width: 360px;
    pointer-events: all;
    animation: toast-in .3s cubic-bezier(.34,1.56,.64,1);
    transition: opacity .3s, transform .3s;
}
.notif-toast.order_new       { border-left-color: #3b82f6; }
.notif-toast.order_confirmed { border-left-color: #f59e0b; }
.notif-toast.order_done      { border-left-color: #10b981; }
.notif-toast.order_delivered { border-left-color: #8b5cf6; }

@keyframes toast-in {
    from { opacity: 0; transform: translateX(20px) scale(.95); }
    to   { opacity: 1; transform: translateX(0) scale(1); }
}
.notif-toast.hiding {
    opacity: 0;
    transform: translateX(20px);
}
.toast-icon {
    font-size: 22px; flex-shrink: 0; margin-top: 1px;
}
.toast-body { flex: 1; min-width: 0; }
.toast-title {
    font-size: 13px; font-weight: 700;
    color: #0f172a; margin-bottom: 3px;
}
.toast-msg {
    font-size: 12px; color: #64748b; line-height: 1.4;
}
.toast-close {
    font-size: 16px; color: #94a3b8;
    cursor: pointer; border: none; background: none;
    padding: 0; line-height: 1; flex-shrink: 0;
    align-self: flex-start;
}
.toast-close:hover { color: #1e293b; }
.toast-time {
    font-size: 10px; color: #94a3b8; margin-top: 3px;
}

/* ── Notification sound indicator ─────────────────── */
#notif-live-dot {
    width: 8px; height: 8px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    margin-left: 4px;
    animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(.8); }
}
</style>

{{-- ══════════════════════════════════════════════════ --}}
{{-- HTML BELL + PANEL                                  --}}
{{-- ══════════════════════════════════════════════════ --}}
<div id="notif-bell-wrap">

    {{-- Bell button --}}
    <button id="notif-bell-btn" onclick="toggleNotifPanel()" title="Notifikasi">
        🔔
        <span id="notif-badge"></span>
    </button>

    {{-- Dropdown panel --}}
    <div id="notif-panel">
        <div class="notif-panel-header">
            <h4>
                Notifikasi
                <span id="notif-live-dot" title="Realtime aktif"></span>
            </h4>
            <button id="notif-mark-all" onclick="markAllRead()">Tandai semua dibaca</button>
        </div>
        <div id="notif-list">
            <div class="notif-empty">
                <span class="notif-empty-icon">🔔</span>
                Belum ada notifikasi
            </div>
        </div>
    </div>
</div>

{{-- Toast stack --}}
<div id="notif-toast-stack"></div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- JAVASCRIPT                                         --}}
{{-- ══════════════════════════════════════════════════ --}}
<script>
(function () {
    // ─── Config ──────────────────────────────────────────
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const ICONS = {
        order_new:       '🛎️',
        order_confirmed: '🔥',
        order_done:      '🍽️',
        order_delivered: '✅',
    };

    // Simpan ID yang sudah ditampilkan agar tidak duplikat
    let knownIds = new Set();
    let panelOpen = false;
    let useSSE = false;
    let eventSource = null;

    // ─── Toggle Panel ─────────────────────────────────────
    window.toggleNotifPanel = function () {
        panelOpen = !panelOpen;
        document.getElementById('notif-panel').classList.toggle('open', panelOpen);

        // Tutup jika klik di luar
        if (panelOpen) {
            setTimeout(() => {
                document.addEventListener('click', closePanelOutside);
            }, 10);
        }
    };

    function closePanelOutside(e) {
        const wrap = document.getElementById('notif-bell-wrap');
        if (!wrap.contains(e.target)) {
            panelOpen = false;
            document.getElementById('notif-panel').classList.remove('open');
            document.removeEventListener('click', closePanelOutside);
        }
    }

    // ─── Render item di panel ─────────────────────────────
    function renderItem(n) {
        const icon = ICONS[n.type] ?? '📢';
        return `
        <div class="notif-item ${n.is_read ? '' : 'unread'}" id="ni-${n.id}" onclick="readItem(${n.id}, this)">
            <div class="notif-icon ${n.type}">${icon}</div>
            <div class="notif-body">
                <div class="notif-title">${n.title}</div>
                <div class="notif-msg">${n.message}</div>
                <div class="notif-time">${n.created_at}</div>
            </div>
            ${n.queue_number ? `<span class="notif-badge-pill">${n.queue_number}</span>` : ''}
        </div>`;
    }

    // ─── Render full list ─────────────────────────────────
    function renderList(notifs) {
        const list = document.getElementById('notif-list');
        if (!notifs.length) {
            list.innerHTML = `<div class="notif-empty">
                <span class="notif-empty-icon">🔔</span>
                Belum ada notifikasi
            </div>`;
            return;
        }
        list.innerHTML = notifs.map(renderItem).join('');
    }

    // ─── Update badge angka ───────────────────────────────
    function updateBadge(count) {
        const badge = document.getElementById('notif-badge');
        const btn   = document.getElementById('notif-bell-btn');
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.add('show');
            btn.classList.add('has-unread');
        } else {
            badge.classList.remove('show');
            btn.classList.remove('has-unread');
        }
    }

    // ─── Toast ────────────────────────────────────────────
    function showToast(n) {
        const icon  = ICONS[n.type] ?? '📢';
        const stack = document.getElementById('notif-toast-stack');
        const id    = 'toast-' + n.id;

        const div = document.createElement('div');
        div.className = `notif-toast ${n.type}`;
        div.id = id;
        div.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-body">
                <div class="toast-title">${n.title}</div>
                <div class="toast-msg">${n.message}</div>
                <div class="toast-time">${n.created_at}</div>
            </div>
            <button class="toast-close" onclick="dismissToast('${id}')">✕</button>`;

        stack.appendChild(div);

        // Bell shake
        const btn = document.getElementById('notif-bell-btn');
        btn.classList.remove('shake');
        void btn.offsetWidth;
        btn.classList.add('shake');

        // Auto dismiss 6s
        setTimeout(() => dismissToast(id), 6000);
    }

    window.dismissToast = function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hiding');
        setTimeout(() => el?.remove(), 300);
    };

    // ─── Mark single read ─────────────────────────────────
    window.readItem = function (id, el) {
        el.classList.remove('unread');
        fetch(`/notifications/${id}/read`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        });
        // Recalc badge
        const unread = document.querySelectorAll('.notif-item.unread').length;
        updateBadge(unread);
    };

    // ─── Mark all read ────────────────────────────────────
    window.markAllRead = function () {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        }).then(() => {
            document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
            updateBadge(0);
        });
    };

    // ─── Proses notif baru (dari SSE atau polling) ────────
    function processNew(notifs) {
        const list = document.getElementById('notif-list');

        // Hapus empty state jika ada
        const empty = list.querySelector('.notif-empty');
        if (empty) empty.remove();

        let newCount = 0;

        notifs.forEach(n => {
            if (knownIds.has(n.id)) return;
            knownIds.add(n.id);

            // Prepend ke panel
            const div = document.createElement('div');
            div.innerHTML = renderItem(n);
            list.insertBefore(div.firstElementChild, list.firstChild);

            // Toast
            if (!n.is_read) {
                showToast(n);
                newCount++;
            }
        });

        if (newCount > 0) {
            const current = parseInt(document.getElementById('notif-badge').textContent || '0') || 0;
            updateBadge(current + newCount);
        }
    }

    // ─── Load awal dari REST ──────────────────────────────
    function loadInitial() {
        fetch('/notifications')
            .then(r => r.json())
            .then(data => {
                renderList(data.notifications);
                updateBadge(data.unread_count);
                data.notifications.forEach(n => knownIds.add(n.id));
            })
            .catch(() => {});
    }

    // ─── SSE (Server-Sent Events) ─────────────────────────
    function connectSSE() {
        if (typeof EventSource === 'undefined') {
            startPolling();
            return;
        }

        useSSE = true;
        eventSource = new EventSource('/notifications/stream');

        eventSource.onmessage = function (e) {
            try {
                const data = JSON.parse(e.data);
                if (data.notifications?.length) {
                    processNew(data.notifications);
                }
            } catch (_) {}
        };

        eventSource.onerror = function () {
            // SSE gagal → fallback ke polling
            useSSE = false;
            eventSource?.close();
            // Reconnect setelah 10 detik
            setTimeout(() => connectSSE(), 10000);
        };
    }

    // ─── Polling fallback ─────────────────────────────────
    let lastPollId = 0;

    function pollNotifs() {
        if (useSSE) return; // SSE aktif, skip polling

        fetch('/notifications')
            .then(r => r.json())
            .then(data => {
                const newOnes = data.notifications.filter(n => !knownIds.has(n.id));
                if (newOnes.length) processNew(newOnes);
                updateBadge(data.unread_count);
            })
            .catch(() => {});
    }

    function startPolling() {
        setInterval(pollNotifs, 6000);
    }

    // ─── Init ─────────────────────────────────────────────
    loadInitial();
    connectSSE();
    // Jalankan polling juga sebagai backup
    startPolling();

})();
</script>
