<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori — Rantau Finance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .kategori-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .kategori-grid { grid-template-columns: 1fr; }
        }
        .kategori-section-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border);
        }
        .kategori-section-title.income { color: #10b981; border-color: #10b981; }
        .kategori-section-title.expense { color: #ef4444; border-color: #ef4444; }

        .kategori-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            background: var(--light);
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }
        .kategori-item:hover { background: var(--border); }
        .kategori-item-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .kategori-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .kategori-dot.income  { background: #10b981; }
        .kategori-dot.expense { background: #ef4444; }
        .kategori-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
        }
        .kategori-count {
            font-size: 0.75rem;
            color: var(--gray);
        }
        .btn-delete {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray);
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .btn-delete:hover { background: #fee2e2; color: #ef4444; }
        .btn-delete:disabled { opacity: 0.3; cursor: not-allowed; }

        /* Form */
        .form-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
        }
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--dark-2);
            margin-bottom: 0.4rem;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--white);
            outline: none;
            transition: all 0.2s;
        }
        .form-input:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
        }
        .btn-submit {
            width: 100%;
            padding: 0.8rem;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            margin-top: 0.25rem;
            transition: all 0.3s;
        }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }

        .alert-success {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            background: #d1fae5;
            border: 1px solid rgba(16,185,129,0.3);
            color: #065f46;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-error {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            background: #fee2e2;
            border: 1px solid rgba(239,68,68,0.3);
            color: #991b1b;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .empty-state {
            text-align: center;
            padding: 1.5rem;
            color: var(--gray);
            font-size: 0.85rem;
        }
        .type-pills {
            display: flex;
            gap: 0.5rem;
        }
        .type-pill {
            flex: 1;
            padding: 0.6rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--white);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray);
            transition: all 0.2s;
            text-align: center;
        }
        .type-pill.active-income  { border-color: #10b981; background: #d1fae5; color: #065f46; }
        .type-pill.active-expense { border-color: #ef4444; background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

<div class="mobile-header">
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    <div style="display:flex;align-items:center;gap:8px;"><img src="{{ asset('images/logo_RD.png') }}" style="height: 54px;"> <span style="font-weight:700;font-size:1rem;">RantauFinance</span></div>
    <div style="width:40px;"></div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="app-layout">
    @include('partials.sidebar', ['active' => 'kategori'])

    <main class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>📁 Kategori</h1>
                <p>Kelola kategori pemasukan dan pengeluaran</p>
            </div>
            <div class="top-bar-right">
                @include('partials.notifications')
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">⚠️ {{ session('error') }}</div>
        @endif

        <div class="kategori-grid">

            {{-- Daftar Kategori --}}
            <div style="display:flex;flex-direction:column;gap:1.5rem;">

                {{-- Pemasukan --}}
                <div class="card">
                    <div class="card-body">
                        <div class="kategori-section-title income">📈 Pemasukan ({{ $pemasukan->count() }})</div>
                        @forelse($pemasukan as $k)
                            <div class="kategori-item">
                                <div class="kategori-item-left">
                                    <div class="kategori-dot income"></div>
                                    <div>
                                        <div class="kategori-name">{{ $k->nama }}</div>
                                        <div class="kategori-count">{{ $k->transaksi->count() }} transaksi</div>
                                    </div>
                                </div>
                                @if(auth()->user()->canAccess('kategori_custom_unlimited'))
                                <form method="POST" action="/kategori/{{ $k->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete"
                                        {{ $k->transaksi->count() > 0 ? 'disabled title=Tidak bisa dihapus, masih digunakan' : 'onclick=return confirm(\'Hapus kategori ini?\')' }}>
                                        🗑
                                    </button>
                                </form>
                                @endif
                            </div>
                        @empty
                            <div class="empty-state">Belum ada kategori pemasukan</div>
                        @endforelse
                    </div>
                </div>

                {{-- Pengeluaran --}}
                <div class="card">
                    <div class="card-body">
                        <div class="kategori-section-title expense">📉 Pengeluaran ({{ $pengeluaran->count() }})</div>
                        @forelse($pengeluaran as $k)
                            <div class="kategori-item">
                                <div class="kategori-item-left">
                                    <div class="kategori-dot expense"></div>
                                    <div>
                                        <div class="kategori-name">{{ $k->nama }}</div>
                                        <div class="kategori-count">{{ $k->transaksi->count() }} transaksi</div>
                                    </div>
                                </div>
                                @if(auth()->user()->canAccess('kategori_custom_unlimited'))
                                <form method="POST" action="/kategori/{{ $k->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete"
                                        {{ $k->transaksi->count() > 0 ? 'disabled title=Tidak bisa dihapus, masih digunakan' : 'onclick=return confirm(\'Hapus kategori ini?\')' }}>
                                        🗑
                                    </button>
                                </form>
                                @endif
                            </div>
                        @empty
                            <div class="empty-state">Belum ada kategori pengeluaran</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Form Tambah Kategori --}}
            <div class="form-card" style="align-self: start;">
                @if(auth()->user()->canAccess('kategori_custom_unlimited'))
                    <h3 style="font-size:1rem;font-weight:800;margin-bottom:1.25rem;">➕ Tambah Kategori Baru</h3>
                    <form method="POST" action="/kategori">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Tipe</label>
                            <div class="type-pills">
                                <button type="button" class="type-pill active-income" id="pill-pemasukan"
                                    onclick="setTipe('pemasukan')">📈 Pemasukan</button>
                                <button type="button" class="type-pill" id="pill-pengeluaran"
                                    onclick="setTipe('pengeluaran')">📉 Pengeluaran</button>
                            </div>
                            <input type="hidden" name="tipe" id="inputTipe" value="pemasukan">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama" class="form-input"
                                   placeholder="Contoh: Bonus, Nongkrong, Olahraga..."
                                   value="{{ old('nama') }}" required maxlength="50">
                            @error('nama')
                                <p style="font-size:0.78rem;color:#ef4444;margin-top:0.3rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-submit">💾 Simpan Kategori</button>
                    </form>
                @else
                    <div style="text-align: center; padding: 2rem 0;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">🔒</div>
                        <h4 style="margin-bottom: 0.5rem; color: var(--dark);">Fitur Terkunci</h4>
                        <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem;">Pembuatan kategori custom unlimited hanya tersedia di paket Profesional.</p>
                        <a href="/payment/upgrade/profesional" class="btn-submit" style="display:inline-block; text-align:center; text-decoration:none; box-sizing:border-box;">Upgrade ke Profesional</a>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<script>
function setTipe(tipe) {
    document.getElementById('inputTipe').value = tipe;
    document.getElementById('pill-pemasukan').className =
        'type-pill' + (tipe === 'pemasukan' ? ' active-income' : '');
    document.getElementById('pill-pengeluaran').className =
        'type-pill' + (tipe === 'pengeluaran' ? ' active-expense' : '');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
</script>
</body>
</html>
