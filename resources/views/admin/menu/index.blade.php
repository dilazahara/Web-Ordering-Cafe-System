@extends('layouts.admin')

@section('title', 'Manajemen Menu')

@push('styles')
<style>
/* =======================
   TOOLBAR
======================= */
.toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.search-wrap { position: relative; display: flex; align-items: center; }
.search-wrap i { position: absolute; left: 11px; color: #9CA3AF; width: 15px; height: 15px; }
.search-input {
    padding: 10px 12px 10px 34px;
    border: 1.5px solid var(--border); border-radius: var(--radius-lg);
    font-family: var(--font); font-size: var(--text-base); color: var(--text-base-c);
    outline: none; width: 210px; transition: border-color 0.2s;
    background: white;
}
.search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.search-input::placeholder { color: #9CA3AF; }

.btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: var(--radius-lg);
    background: var(--primary); color: white; border: none;
    font-size: var(--text-base); font-weight: 600; cursor: pointer;
    text-decoration: none; font-family: var(--font);
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
   CARD & TOOLS
======================= */
.card {
    background: white; border-radius: var(--radius-3xl);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.card-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; background: #fafbfc;
    border-bottom: 1px solid var(--border-light); flex-wrap: wrap; gap: 10px;
}
.card-toolbar-left  { display: flex; align-items: center; gap: 10px; }
.card-toolbar-right { display: flex; align-items: center; gap: 8px; }
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

.pagination-info-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px; background: var(--bg);
    border-bottom: 1px solid var(--border-light);
    flex-wrap: wrap; gap: 8px;
}
.pagination-info-text {
    font-size: var(--text-base); color: var(--text-light); font-weight: 400;
    display: flex; align-items: center; gap: 6px;
}
.pagination-info-text strong { color: var(--text-mid); font-weight: 600; }
.pagination-info-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: #ede9fe; color: #5b21b6;
    border-radius: var(--radius-sm); padding: 3px 10px;
    font-size: var(--text-sm); font-weight: 600;
}

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
    white-space: nowrap; border-bottom: 2px solid var(--border);
}
td { padding: 16px 16px; font-size: var(--text-md); color: var(--text-mid); border-bottom: 1px solid var(--border-light); }
tbody tr { transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--bg); }

.menu-img {
    width: 52px; height: 52px; object-fit: cover;
    border-radius: var(--radius-md); display: block; border: 1px solid var(--border-light);
}
.menu-name { font-weight: 600; color: var(--text-dark); font-size: var(--text-md); }
.menu-desc {
    font-size: var(--text-sm); color: var(--text-muted); margin-top: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px;
}

.cat-badge {
    display: inline-block; padding: 4px 10px; border-radius: var(--radius-sm);
    background: #ede9fe; color: #5b21b6;
    font-size: var(--text-sm); font-weight: 600;
}

.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: 20px; font-size: var(--text-sm); font-weight: 600;
}
.status-active   { background: #f0fdf4; color: #15803d; }
.status-inactive { background: #f3f4f6; color: #6b7280; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }
.dot-active   { background: #22c55e; }
.dot-inactive { background: #9ca3af; }

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
.act-edit { background: #eff6ff; color: #2563eb; border: 1.5px solid #dbeafe; }
.act-edit:hover { background: #dbeafe; border-color: #93c5fd; }
.act-delete { background: #fef2f2; color: #dc2626; border: 1.5px solid #fee2e2; }
.act-delete:hover { background: #fee2e2; border-color: #fca5a5; }

/* =======================
   MOBILE CARDS
======================= */
.mobile-list { display: none; padding: 14px; }

@media (max-width: 768px) {
    .tbl-wrap { display: none; }
    .mobile-list { display: flex; flex-direction: column; gap: 10px; }
    .search-input { width: 150px; }
    .pagination-info-bar { flex-direction: column; align-items: flex-start; gap: 6px; }
    .card-toolbar { flex-direction: column; align-items: flex-start; }
}

.m-card {
    background: white; border-radius: var(--radius-xl);
    border: 1px solid var(--border-light); padding: 12px;
    display: flex; gap: 12px;
    box-shadow: var(--shadow-sm);
}
.m-card-img {
    width: 64px; height: 64px; object-fit: cover;
    border-radius: var(--radius-md); flex-shrink: 0; border: 1px solid var(--border-light);
}
.m-card-body { flex: 1; min-width: 0; }
.m-card-name { font-size: var(--text-md); font-weight: 700; color: var(--text-dark); }
.m-card-cat  { font-size: var(--text-xs); color: var(--text-light); margin-top: 2px; }
.m-card-desc { font-size: var(--text-sm); color: var(--text-muted); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.m-card-foot { display: flex; justify-content: space-between; align-items: center; margin-top: 8px; }
.m-price { font-size: var(--text-md); font-weight: 700; color: var(--primary); }

/* =======================
   PAGINATION
======================= */
.pagination-wrap { padding: 16px 20px; border-top: 1px solid var(--border-light); }

/* Empty state */
.empty-state { text-align: center; padding: 56px 20px; }
.empty-state p { color: var(--text-muted); font-size: var(--text-md); margin-top: 10px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Manajemen Menu</h1>
        <p>Kelola semua menu yang tersedia di cafe</p>
    </div>
    @if($menus->isNotEmpty())
    <div class="toolbar">
        <form method="GET" action="/admin/menu" id="searchForm">
            <input type="hidden" name="per_page" id="hiddenPerPage" value="{{ request('per_page', 10) }}">
            <div class="search-wrap">
                <i data-lucide="search" style="width:15px;height:15px;"></i>
                <input type="text" name="search" id="searchInput" class="search-input"
                    placeholder="Cari menu..."
                    value="{{ request('search') }}"
                    autocomplete="off">
            </div>
        </form>
        <a href="/admin/menu/create" class="btn-add">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            Tambah Menu
        </a>
    </div>
    @else
    <div class="toolbar">
        <a href="/admin/menu/create" class="btn-add">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            Tambah Menu
        </a>
    </div>
    @endif
</div>

@if(session('success'))
<div class="alert-success" id="alertSuccess">
    <i data-lucide="check-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
    {{ session('success') }}
</div>
@endif

<div class="card">

    {{-- CARD TOOLBAR --}}
    @if($menus->isNotEmpty())
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="toolbar-label">Tampilkan</span>
            <select class="per-page-select" id="perPageSelect" onchange="onPerPageChange()">
                @foreach([5, 10, 15, 25, 50, 100] as $n)
                <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
            <span class="toolbar-label">data </span>
        </div>
        <div class="card-toolbar-right">
            <span class="toolbar-label">
                Total: <strong style="color:var(--text-dark);">{{ $menus->total() }} menu</strong>
            </span>
        </div>
    </div>
    @endif

    {{-- INFO BAR --}}
    @if($menus->isNotEmpty())
    <div class="pagination-info-bar">
        <div class="pagination-info-text">
            <i data-lucide="list" style="width:15px;height:15px;color:var(--text-muted);"></i>
            Menampilkan
            <strong>{{ $menus->firstItem() }}</strong>
            &ndash;
            <strong>{{ $menus->lastItem() }}</strong>
            dari
            <strong>{{ $menus->total() }}</strong>
            menu
            @if(request('search'))
                <span style="color:var(--text-muted);">untuk pencarian "<em>{{ request('search') }}</em>"</span>
            @endif
        </div>
        <span class="pagination-info-badge">
            <i data-lucide="layers" style="width:12px;height:12px;"></i>
            Halaman {{ $menus->currentPage() }} / {{ $menus->lastPage() }}
        </span>
    </div>
    @endif

    <div class="tbl-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:44px;">No</th>
                <th style="width:70px;">Foto</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Status</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($menus as $menu)
        <tr>
            <td style="color:var(--text-muted); font-size:var(--text-base);">
                {{ $menus->firstItem() + $loop->index }}
            </td>
            <td>
                <img class="menu-img"
                    src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://via.placeholder.com/60' }}"
                    alt="{{ $menu->name }}">
            </td>
            <td>
                <p class="menu-name">{{ $menu->name }}</p>
                <p class="menu-desc">{{ $menu->description ?? '-' }}</p>
            </td>
            <td>
                <span class="cat-badge">{{ $menu->kategori->name ?? '-' }}</span>
            </td>
            <td style="font-weight:700; color:var(--primary);">
                Rp {{ number_format($menu->price, 0, ',', '.') }}
            </td>
            <td>
                @if($menu->status)
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
                    <a href="/admin/menu/edit/{{ $menu->id }}" class="act-btn act-edit" title="Edit menu">
                        <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                    </a>
                    <button type="button" class="act-btn act-delete" title="Hapus menu"
                        onclick="openDeleteMenu({{ $menu->id }}, '{{ addslashes($menu->name) }}')">
                        <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">
                <div class="empty-state">
                    <i data-lucide="utensils" style="width:40px;height:40px;color:var(--border);"></i>
                    <p>Belum ada menu yang ditambahkan</p>
                </div>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>

    <div class="mobile-list">
    @forelse($menus as $menu)
    <div class="m-card">
        <img class="m-card-img"
            src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://via.placeholder.com/64' }}"
            alt="{{ $menu->name }}">
        <div class="m-card-body">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:6px;">
                <p class="m-card-name">{{ $menu->name }}</p>
                @if($menu->status)
                <span class="status-badge status-active" style="font-size:11px; padding:3px 8px; flex-shrink:0;">
                    <span class="status-dot dot-active"></span> Aktif
                </span>
                @else
                <span class="status-badge status-inactive" style="font-size:11px; padding:3px 8px; flex-shrink:0;">
                    <span class="status-dot dot-inactive"></span> Nonaktif
                </span>
                @endif
            </div>
            <p class="m-card-cat">{{ $menu->kategori->name ?? '-' }}</p>
            <p class="m-card-desc">{{ $menu->description ?? '-' }}</p>
            <div class="m-card-foot">
                <span class="m-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                <div class="action-wrap">
                    <a href="/admin/menu/edit/{{ $menu->id }}" class="act-btn act-edit" title="Edit">
                        <i data-lucide="pencil-line" style="width:15px;height:15px;"></i>
                    </a>
                    <button type="button" class="act-btn act-delete" title="Hapus"
                        onclick="openDeleteMenu({{ $menu->id }}, '{{ addslashes($menu->name) }}')">
                        <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i data-lucide="utensils" style="width:40px;height:40px;color:var(--border);"></i>
        <p>Belum ada menu yang ditambahkan</p>
    </div>
    @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($menus->isNotEmpty())
    <div class="pagination-wrap" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <div style="font-size:var(--text-base); color:var(--text-light);">
            Menampilkan <strong style="color:var(--text-mid);">{{ $menus->firstItem() }}</strong>
            &ndash;
            <strong style="color:var(--text-mid);">{{ $menus->lastItem() }}</strong>
            dari <strong style="color:var(--text-mid);">{{ $menus->total() }}</strong> data
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            {{-- Tombol Previous --}}
            @if($menus->onFirstPage())
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;border:1.5px solid var(--border);background:var(--bg);color:var(--text-muted);cursor:not-allowed;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><polyline points="15 18 9 12 15 6"/></svg>
                </span>
            @else
                <a href="{{ $menus->appends(request()->query())->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;border:1.5px solid var(--border);background:white;color:var(--text-mid);text-decoration:none;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-mid)'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
            @endif

            {{-- Nomor halaman --}}
            @foreach($menus->appends(request()->query())->getUrlRange(1, $menus->lastPage()) as $page => $url)
                @if($page == $menus->currentPage())
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--primary-hover));color:white;font-size:var(--text-base);font-weight:700;">{{ $page }}</span>
                @elseif($page == 1 || $page == $menus->lastPage() || abs($page - $menus->currentPage()) <= 1)
                    <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;border:1.5px solid var(--border);background:white;color:var(--text-mid);font-size:var(--text-base);font-weight:600;text-decoration:none;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-mid)'">{{ $page }}</a>
                @elseif(abs($page - $menus->currentPage()) == 2)
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;color:var(--text-muted);font-size:var(--text-base);">…</span>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if($menus->hasMorePages())
                <a href="{{ $menus->appends(request()->query())->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;border:1.5px solid var(--border);background:white;color:var(--text-mid);text-decoration:none;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-mid)'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            @else
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;border:1.5px solid var(--border);background:var(--bg);color:var(--text-muted);cursor:not-allowed;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><polyline points="9 18 15 12 9 6"/></svg>
                </span>
            @endif
        </div>
    </div>
    @endif

</div>

{{-- MODAL: HAPUS MENU --}}
<div class="modal-backdrop" id="modalHapusMenu">
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
        <h3 class="mh-title">Hapus Menu?</h3>
        <p class="mh-sub">Kamu akan menghapus menu:</p>
        <p class="mh-name" id="deleteMenuNama"></p>
        <div class="mh-footer">
            <button type="button" class="mh-btn-batal" onclick="closeDeleteMenu()">Batal</button>
            <form id="deleteMenuForm" action="" method="POST" style="margin:0;">
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

@push('styles')
<style>
/* MODAL STYLES (Dipindahkan ke push styles) */
.modal-backdrop {
    display: none; position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,0,0,0.45); backdrop-filter: blur(3px);
    align-items: center; justify-content: center; padding: 20px;
}
.modal-backdrop.open { display: flex; }
.modal-hapus {
    background: white; border-radius: 24px; width: 100%; max-width: 400px;
    padding: 32px 28px 28px; text-align: center;
    box-shadow: 0 24px 60px rgba(0,0,0,0.2);
    animation: mhIn 0.3s cubic-bezier(0.34,1.56,0.64,1);
}
@keyframes mhIn {
    from { opacity:0; transform: scale(0.88) translateY(20px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
.mh-icon-wrap {
    width: 64px; height: 64px; border-radius: 18px;
    background: #fef2f2; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
}
.mh-icon-wrap svg { width: 28px; height: 28px; color: #dc2626; }
.mh-title { font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
.mh-sub { font-size: var(--text-md); color: var(--text-light); margin-bottom: 4px; }
.mh-name { font-weight: 700; color: #dc2626; font-size: 15px; margin-bottom: 8px; }
.mh-warn { font-size: var(--text-base); color: var(--text-muted); margin-bottom: 24px; }
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

@push('scripts')
<script>
// Modal helpers
function openDeleteMenu(id, nama) {
    document.getElementById('deleteMenuNama').textContent = nama;
    document.getElementById('deleteMenuForm').action = '/admin/menu/delete/' + id;
    document.getElementById('modalHapusMenu').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDeleteMenu() {
    document.getElementById('modalHapusMenu').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('modalHapusMenu').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteMenu();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteMenu();
});
var alertEl = document.getElementById('alertSuccess');
if (alertEl) {
    setTimeout(function() {
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.remove(); }, 400);
    }, 4000);
}

// Search & Debounce
(function () {
    var searchEl = document.getElementById('searchInput');
    var formEl   = document.getElementById('searchForm');
    if (!searchEl || !formEl) return;

    var debounceTimer = null;

    searchEl.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            formEl.submit();
        }, 500);
    });

    searchEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            clearTimeout(debounceTimer);
            formEl.submit();
        }
    });
})();

// Per-page change
function onPerPageChange() {
    var val      = document.getElementById('perPageSelect').value;
    var hidden   = document.getElementById('hiddenPerPage');
    var searchEl = document.getElementById('searchInput');
    var formEl   = document.getElementById('searchForm');

    if (!formEl) {
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
        return;
    }

    if (hidden) hidden.value = val;

    var pageInput = formEl.querySelector('input[name="page"]');
    if (!pageInput) {
        pageInput = document.createElement('input');
        pageInput.type  = 'hidden';
        pageInput.name  = 'page';
        formEl.appendChild(pageInput);
    }
    pageInput.value = 1;

    formEl.submit();
}
</script>
@endpush