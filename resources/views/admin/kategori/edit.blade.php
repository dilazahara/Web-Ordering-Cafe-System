<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Kategori</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
body { background: #f3f4f6; min-height: 100vh; padding: 50px 20px; }

.container { max-width: 480px; margin: auto; }

.back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #6b7280; text-decoration: none; margin-bottom: 20px; transition: color .15s; }
.back-link:hover { color: #111827; }

.card { background: white; padding: 32px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

.title { margin-bottom: 28px; }
.title h2 { font-size: 26px; color: #111827; font-weight: 800; }
.title p  { margin-top: 5px; color: #6b7280; font-size: 13.5px; }

.divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; }
.divider span { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .8px; white-space: nowrap; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }

.form-group { margin-bottom: 16px; }
.form-label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 700; color: #111827; }
.form-input { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #e5e7eb; background: #f9fafb; font-size: 14px; color: #111827; outline: none; transition: .2s; font-family: 'Plus Jakarta Sans', sans-serif; }
.form-input:focus { border-color: #6366f1; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.form-input::placeholder { color: #9ca3af; }

/* INFO BOX */
.info-box { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 14px 16px; margin-top: 4px; }
.info-row { display: flex; justify-content: space-between; font-size: 13px; color: #6b7280; padding: 3px 0; }
.info-row span { font-weight: 700; color: #111827; }

.alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 12px; padding: 12px 16px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }

.button-group { display: flex; gap: 12px; margin-top: 28px; }
.btn { flex: 1; padding: 13px; border-radius: 14px; border: none; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; transition: all .2s; display: inline-flex; align-items: center; justify-content: center; gap: 7px; font-family: 'Plus Jakarta Sans', sans-serif; }
.btn i { width: 16px; height: 16px; }
.btn-save { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; box-shadow: 0 8px 20px rgba(99,102,241,0.25); }
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 12px 24px rgba(99,102,241,0.3); }
.btn-back { background: white; color: #374151; border: 1.5px solid #e5e7eb; }
.btn-back:hover { background: #f9fafb; }
.btn-delete-full { width: 100%; margin-top: 12px; padding: 12px; border-radius: 14px; border: 1.5px solid #fee2e2; background: white; color: #dc2626; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all .18s; }
.btn-delete-full:hover { background: #fef2f2; border-color: #fca5a5; }

@media(max-width:480px) { .button-group { flex-direction: column; } }
</style>
</head>
<body>
<div class="container">

    <a href="/admin/kategori" class="back-link">
    </a>

    <div class="card">
        <div class="title">
            <h2>Edit Kategori</h2>
            <p>Perbarui nama kategori menu</p>
        </div>

        @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="/admin/kategori/update/{{ $kategori->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="divider"><span>Informasi Kategori</span></div>

        <div class="form-group">
            <label class="form-label">Nama Kategori <span style="color:#ef4444;">*</span></label>
            <input type="text" name="nama" class="form-input"
                value="{{ old('nama', $kategori->name) }}"
                placeholder="Contoh: Minuman, Makanan Utama..." required>
        </div>

        <div class="divider"><span>Detail</span></div>

        <div class="info-box">
            <div class="info-row">ID Kategori <span>#{{ $kategori->id }}</span></div>
            <div class="info-row">Dibuat <span>{{ $kategori->created_at->format('d M Y') }}</span></div>
            <div class="info-row">Diperbarui <span>{{ $kategori->updated_at->format('d M Y') }}</span></div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-save">
                <i data-lucide="save"></i>
                Update Kategori
            </button>
            <a href="/admin/kategori" class="btn btn-back">Batal</a>
        </div>

        </form>
    </div>
</div>

<script>
lucide.createIcons();
function confirmDelete() {
    if (confirm('Yakin ingin menghapus kategori ini? Tindakan ini tidak bisa dibatalkan.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
</body>
</html>