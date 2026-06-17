@extends('layouts.admin')

@section('title', 'Admin - Addons')

@push('styles')
<style>
/* =======================
   BUTTON
======================= */
.btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: var(--radius-lg);
    background: var(--primary); color: white; border: none;
    font-size: var(--text-base); font-weight: 600; cursor: pointer;
    transition: all 0.2s; white-space: nowrap;
}
.btn-add:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.35); }
.btn-add:active { transform: scale(0.97); }

/* =======================
   ALERT
======================= */
.alert-success {
    background: #f0fdf4; color: #15803d;
    border: 1px solid #bbf7d0; border-radius: var(--radius-lg);
    padding: 12px 16px; margin-bottom: 20px;
    font-size: var(--text-md); font-weight: 500;
    display: flex; align-items: center; gap: 8px;
    transition: opacity 0.4s;
}

/* =======================
   CARD
======================= */
.card {
    background: var(--bg-white); border-radius: var(--radius-3xl);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-sm); overflow: hidden;
}

/* =======================
   TOOLBAR
======================= */
.table-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; flex-wrap: wrap; gap: 12px;
    border-bottom: 1px solid var(--border-light); background: #fafbfc;
}
.toolbar-left  { display: flex; align-items: center; gap: 10px; }
.toolbar-right { display: flex; align-items: center; gap: 8px; }
.toolbar-label { font-size: var(--text-base); color: var(--text-light); font-weight: 500; }
.per-page-select {
    padding: 7px 32px 7px 12px; border: 1.5px solid var(--border);
    border-radius: 9px; font-size: var(--text-base); font-family: var(--font);
    outline: none; color: var(--text-base-c); cursor: pointer; background: white;
    transition: border-color 0.2s; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
}
.per-page-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
.search-wrap { position: relative; display: flex; align-items: center; }
.search-wrap svg { position: absolute; left: 11px; width: 15px; height: 15px; color: var(--text-muted); pointer-events: none; }
.search-input {
    padding: 9px 14px 9px 34px; border: 1.5px solid var(--border);
    border-radius: var(--radius-md); font-size: var(--text-base); font-family: var(--font);
    outline: none; width: 230px; color: var(--text-base-c); transition: border-color 0.2s;
}
.search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }

/* =======================
   TABLE
======================= */
.tbl-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead { background: var(--bg); }
th {
    padding: 16px 16px; text-align: left;
    font-size: var(--text-sm); font-weight: 600; color: var(--text-light);
    text-transform: uppercase; letter-spacing: 0.6px;
    border-bottom: 2px solid var(--border); white-space: nowrap;
}
td { padding: 16px 16px; font-size: var(--text-md); color: var(--text-mid); border-bottom: 1px solid var(--border-light); }
tbody tr { transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--bg); }

/* =======================
   BADGES
======================= */
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: 20px;
    font-size: var(--text-sm); font-weight: 600;
}
.status-active   { background: #f0fdf4; color: #15803d; }
.status-inactive { background: #f3f4f6; color: #6b7280; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }
.dot-active   { background: #22c55e; }
.dot-inactive { background: #9ca3af; }
.group-badge {
    display: inline-block; padding: 4px 10px; border-radius: 8px;
    background: #ede9fe; color: #5b21b6;
    font-size: var(--text-sm); font-weight: 600;
}

/* =======================
   ACTION BUTTONS
======================= */
.action-wrap { display: flex; align-items: center; gap: 6px; }
.act-btn {
    width: 34px; height: 34px; border-radius: 9px; border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s; text-decoration: none; flex-shrink: 0;
}
.act-btn:active { transform: scale(0.9); }
.act-edit   { background: #eff6ff; color: #2563eb; border: 1.5px solid #dbeafe; }
.act-edit:hover   { background: #dbeafe; border-color: #93c5fd; }
.act-delete { background: #fef2f2; color: #dc2626; border: 1.5px solid #fee2e2; }
.act-delete:hover { background: #fee2e2; border-color: #fca5a5; }

/* =======================
   EMPTY STATE
======================= */
.empty-state { text-align: center; padding: 56px 20px; }
.empty-state p { color: var(--text-muted); font-size: var(--text-md); margin-top: 10px; }

/* =======================
   PAGINATION
======================= */
.pagination-wrap {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-top: 1px solid var(--border-light);
    flex-wrap: wrap; gap: 12px;
}
.pagination-info { font-size: var(--text-base); color: var(--text-light); }
.pagination-info span { font-weight: 700; color: var(--text-dark); }
.pagination-btns { display: flex; align-items: center; gap: 6px; }
.page-btn {
    min-width: 38px; height: 38px; border-radius: 10px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1.5px solid var(--border); background: white;
    font-size: var(--text-base); font-weight: 600; color: var(--text-light);
    cursor: pointer; transition: all 0.2s; padding: 0 12px;
    font-family: var(--font);
}
.page-btn:hover:not(:disabled):not(.active) { background: var(--border-light); border-color: #cbd5e1; color: var(--text-base-c); }
.page-btn.active { background: var(--primary); border-color: var(--primary); color: white; box-shadow: 0 3px 8px rgba(99,102,241,0.3); }
.page-btn:disabled { opacity: 0.4; cursor: not-allowed; }

/* =======================
   MODAL
======================= */
.modal-backdrop {
    display: none;
    position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,0,0,0.45); backdrop-filter: blur(3px);
    align-items: center; justify-content: center; padding: 20px;
}
.modal-backdrop.open { display: flex; }
.modal-hapus {
    background: white; border-radius: 24px; width: 100%; max-width: 400px;
    padding: 32px 28px 28px; text-align: center;
    box-shadow: 0 24px 60px rgba(0,0,0,0.2);
    animation: none;
}
.modal-backdrop.open .modal-hapus {
    animation: mhIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes mhIn {
    from { opacity: 0; transform: scale(0.88) translateY(20px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.mh-icon-wrap {
    width: 64px; height: 64px; border-radius: 18px;
    background: #fef2f2; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
}
.mh-icon-wrap svg { width: 28px; height: 28px; color: #dc2626; }
.mh-title { font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
.mh-sub   { font-size: var(--text-md); color: var(--text-light); margin-bottom: 4px; }
.mh-name  { font-weight: 700; color: #dc2626; font-size: 15px; margin-bottom: 8px; }
.mh-warn  { font-size: var(--text-base); color: var(--text-muted); margin-bottom: 24px; }
.mh-footer { display: flex; gap: 12px; justify-content: center; }
.mh-btn-batal {
    padding: 10px 22px; border-radius: 11px; border: 1.5px solid var(--border);
    background: var(--border-light); color: var(--text-light); font-size: 13.5px; font-weight: 600;
    cursor: pointer; font-family: var(--font); transition: all 0.2s;
}
.mh-btn-batal:hover { background: var(--border); }
.mh-btn-hapus {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 11px; border: none;
    background: #dc2626; color: white; font-size: 13.5px; font-weight: 600;
    cursor: pointer; font-family: var(--font); transition: all 0.2s;
}
.mh-btn-hapus:hover { background: #b91c1c; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-title">
        <h1>Data Add-ons</h1>
        <p>Kelola semua tambahan pilihan menu cafe</p>
    </div>
    <a href="{{ route('admin.addons.create') }}" class="btn-add">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Tambah Add-on
    </a>
</div>

{{-- ALERT --}}
@if(session('success'))
<div class="alert-success" id="alertSuccess">
    <i data-lucide="check-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
    {{ session('success') }}
</div>
@endif

{{-- TABLE CARD --}}
<div class="card">
    {{-- TOOLBAR --}}
    <div class="table-toolbar">
        <div class="toolbar-left">
            <span class="toolbar-label">Tampilkan</span>
            <select class="per-page-select" id="perPageSelect" onchange="onPerPageChange()">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="toolbar-label">data</span>
        </div>
        <div class="toolbar-right">
            <span class="toolbar-label">Cari:</span>
            <div class="search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" class="search-input" id="searchInput"
                    placeholder="Cari nama, group, status..." oninput="onSearch()">
            </div>
        </div>
    </div>

    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Group</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="addonTableBody">
                @forelse($addons as $i => $addon)
                <tr data-search="{{ strtolower($addon->name . ' ' . $addon->description . ' ' . $addon->group->name . ' ' . ($addon->status ? 'aktif' : 'nonaktif')) }}">
                    <td class="row-no" style="color:var(--text-muted); font-size:var(--text-base);">{{ $i + 1 }}</td>
                    <td style="font-weight:600; color:var(--text-dark);">{{ $addon->name }}</td>
                    <td style="color:var(--text-light);">{{ $addon->description }}</td>
                    <td><span class="group-badge">{{ $addon->group->name }}</span></td>
                    <td style="font-weight:700; color:var(--primary);">Rp {{ number_format($addon->price) }}</td>
                    <td>
                        @if($addon->status)
                        <span class="status-badge status-active">
                            <span class="status-dot dot-active"></span> Aktif
                        </span>
                        @else
                        <span class="status-badge status-inactive">
                            <span class="status-dot dot-inactive"></span> Nonaktif
                        </span>
                        @endif
                    </td>
                    <td>
                        <div class="action-wrap" style="justify-content:center;">
                            <a href="{{ route('admin.addons.edit', $addon->id) }}" class="act-btn act-edit" title="Edit">
                                <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                            </a>
                            <button type="button" class="act-btn act-delete" title="Hapus"
                                onclick="openDeleteAddon({{ $addon->id }}, '{{ addslashes($addon->name) }}')">
                                <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyServerRow">
                    <td colspan="7">
                        <div class="empty-state">
                            <i data-lucide="plus-circle" style="width:40px;height:40px;color:var(--border);"></i>
                            <p>Belum ada add-on yang ditambahkan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="pagination-wrap" id="paginationWrap">
        <div class="pagination-info" id="paginationInfo"></div>
        <div class="pagination-btns" id="paginationBtns"></div>
    </div>
</div>

{{-- MODAL HAPUS ADDON --}}
<div class="modal-backdrop" id="modalHapusAddon">
    <div class="modal-hapus">
        <div class="mh-icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/><path d="M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>
        <h3 class="mh-title">Hapus Add-on?</h3>
        <p class="mh-sub">Kamu akan menghapus add-on:</p>
        <p class="mh-name" id="deleteAddonNama"></p>
        <p class="mh-warn">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="mh-footer">
            <button type="button" class="mh-btn-batal" onclick="closeDeleteAddon()">Batal</button>
            <form id="deleteAddonForm" action="" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="mh-btn-hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="width:15px;height:15px;">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6"/><path d="M14 11v6"/>
                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// (Script logic tidak diubah, persis seperti aslinya)
function openDeleteAddon(id, nama) {
    document.getElementById('deleteAddonNama').textContent = nama;
    document.getElementById('deleteAddonForm').action = '/admin/addons/delete/' + id;
    document.getElementById('modalHapusAddon').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDeleteAddon() {
    document.getElementById('modalHapusAddon').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('modalHapusAddon').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteAddon();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteAddon();
});
var alertEl = document.getElementById('alertSuccess');
if (alertEl) {
    setTimeout(function() {
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.remove(); }, 400);
    }, 4000);
}

(function () {
    var perPage     = 10;
    var currentPage = 1;
    var keyword     = '';

    var tbody     = document.getElementById('addonTableBody');
    var infoEl    = document.getElementById('paginationInfo');
    var btnsEl    = document.getElementById('paginationBtns');
    var searchEl  = document.getElementById('searchInput');
    var perPageEl = document.getElementById('perPageSelect');

    if (!tbody) return;

    var allRows = Array.from(tbody.querySelectorAll('tr[data-search]'));
    if (allRows.length === 0) return;

    function getFiltered() {
        if (!keyword) return allRows;
        return allRows.filter(function (r) {
            return r.getAttribute('data-search').indexOf(keyword) !== -1;
        });
    }

    function render() {
        var filtered   = getFiltered();
        var total      = filtered.length;
        var totalPages = Math.max(1, Math.ceil(total / perPage));
        if (currentPage > totalPages) currentPage = totalPages;

        var start = (currentPage - 1) * perPage;
        var end   = start + perPage;

        allRows.forEach(function (r) { r.style.display = 'none'; });
        var visNo = 1;
        filtered.forEach(function (r, idx) {
            if (idx >= start && idx < end) {
                r.style.display = '';
                var noCell = r.querySelector('.row-no');
                if (noCell) noCell.textContent = start + visNo;
                visNo++;
            }
        });

        var noRow = tbody.querySelector('.no-results-row');
        if (total === 0) {
            if (!noRow) {
                var tr = document.createElement('tr');
                tr.className = 'no-results-row';
                tr.innerHTML = '<td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);font-size:var(--text-md);">Tidak ada data yang cocok dengan pencarian "<strong>' + escapeHtml(keyword) + '</strong>"</td>';
                tbody.appendChild(tr);
            } else {
                noRow.querySelector('td').innerHTML = 'Tidak ada data yang cocok dengan pencarian "<strong>' + escapeHtml(keyword) + '</strong>"';
            }
        } else {
            if (noRow) noRow.remove();
        }

        if (infoEl) {
            if (total === 0) {
                infoEl.innerHTML = 'Menampilkan <span>0</span> dari <span>0</span> data';
            } else {
                var from = start + 1;
                var to   = Math.min(end, total);
                infoEl.innerHTML = 'Menampilkan <span>' + from + '–' + to + '</span> dari <span>' + total + '</span> data';
            }
        }

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (!btnsEl) return;
        btnsEl.innerHTML = '';

        btnsEl.appendChild(makeBtn('‹', currentPage === 1, false, function () {
            currentPage--; render();
        }));

        buildPageRange(currentPage, totalPages).forEach(function (p) {
            if (p === '...') {
                var dots = document.createElement('span');
                dots.textContent = '…';
                dots.style.cssText = 'padding:0 6px;color:var(--text-muted);font-size:var(--text-base);align-self:center;';
                btnsEl.appendChild(dots);
            } else {
                btnsEl.appendChild(makeBtn(p, false, p === currentPage, (function (pg) {
                    return function () { currentPage = pg; render(); };
                })(p)));
            }
        });

        btnsEl.appendChild(makeBtn('›', currentPage === totalPages, false, function () {
            currentPage++; render();
        }));
    }

    function buildPageRange(cur, total) {
        if (total <= 7) return Array.from({ length: total }, function (_, i) { return i + 1; });
        var pages = [1];
        if (cur > 3) pages.push('...');
        for (var i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) pages.push(i);
        if (cur < total - 2) pages.push('...');
        pages.push(total);
        return pages;
    }

    function makeBtn(label, disabled, active, onClick) {
        var btn = document.createElement('button');
        btn.className = 'page-btn' + (active ? ' active' : '');
        btn.textContent = label;
        btn.disabled = disabled;
        btn.addEventListener('click', onClick);
        return btn;
    }

    function escapeHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    window.onSearch = function () {
        keyword     = searchEl ? searchEl.value.trim().toLowerCase() : '';
        currentPage = 1;
        render();
    };

    window.onPerPageChange = function () {
        perPage     = parseInt(perPageEl ? perPageEl.value : 10, 10);
        currentPage = 1;
        render();
    };

    render();
})();
</script>
@endpush