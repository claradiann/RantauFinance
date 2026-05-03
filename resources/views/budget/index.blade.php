<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget — Rantau Finance</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .budget-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .budget-grid { grid-template-columns: 1fr; }
        }
        .budget-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
        }
        .budget-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
        }
        .budget-item:last-child { border-bottom: none; }
        .budget-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .budget-item-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--dark);
        }
        .budget-item-amounts {
            font-size: 0.78rem;
            color: var(--gray);
            text-align: right;
        }
        .budget-item-amounts span {
            display: block;
        }
        .progress-bar {
            height: 8px;
            background: var(--border);
            border-radius: 99px;
            overflow: hidden;
            margin-bottom: 0.35rem;
        }
        .progress-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.6s ease;
        }
        .progress-fill.safe    { background: #10b981; }
        .progress-fill.warning { background: #f59e0b; }
        .progress-fill.danger  { background: #ef4444; }
        .budget-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
        }
        .budget-meta .sisa.minus { color: #ef4444; font-weight: 700; }
        .budget-meta .sisa.plus  { color: #10b981; font-weight: 700; }
        .btn-delete {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray);
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .btn-delete:hover { background: #fee2e2; color: #ef4444; }
        .empty-budget {
            text-align: center;
            padding: 2rem;
            color: var(--gray);
        }
        .empty-budget .empty-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }

        /* Form styles */
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
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
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
            margin-top: 0.5rem;
            transition: all 0.3s;
        }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
        .filter-bar {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .filter-bar select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            color: var(--dark);
            background: var(--white);
            outline: none;
            cursor: pointer;
        }
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
        .badge-persen {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 99px;
        }
        .badge-persen.safe    { background: #d1fae5; color: #065f46; }
        .badge-persen.warning { background: #fef3c7; color: #92400e; }
        .badge-persen.danger  { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

<div class="mobile-header">
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    <span style="font-weight:700;font-size:1rem;">💰 RantauFinance</span>
    <div style="width:40px;"></div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="app-layout">
    <aside class="sidebar" id="sidebar">
        <a href="/" class="sidebar-logo">
            <div class="logo-icon">💰</div>
            <span class="logo-text">RantauFinance</span>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Menu</div>
                <a href="/dashboard" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
                <a href="/transaksi" class="nav-item"><span class="nav-icon">💳</span> Transaksi</a>
                <a href="/transaksi/create" class="nav-item"><span class="nav-icon">➕</span> Tambah Transaksi</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Lainnya</div>
                <a href="/kategori" class="nav-item"><span class="nav-icon">📁</span> Kategori</a>
                <a href="/budget" class="nav-item active"><span class="nav-icon">🎯</span> Budget</a>
                <a href="/laporan" class="nav-item"><span class="nav-icon">📈</span> Laporan</a>
                <a href="/profile" class="nav-item"><span class="nav-icon">⚙️</span> Pengaturan</a>
            </div>
        </nav>
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="user-info">
                    <div class="name">{{ auth()->user()->name }}</div>
                    <div class="role">{{ ucfirst(auth()->user()->plan ?? 'Starter') }}</div>
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn"><span>🚪</span> Keluar</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>🎯 Budget</h1>
                <p>Atur batas pengeluaran per kategori</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Filter Bulan --}}
        <div class="filter-bar">
            <span style="font-size:0.85rem;font-weight:600;color:var(--dark-2);">Periode:</span>
            <form method="GET" action="/budget" style="display:flex;gap:0.5rem;">
                <select name="bulan" onchange="this.form.submit()">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" onchange="this.form.submit()">
                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="budget-grid">
            {{-- Daftar Budget --}}
            <div class="card" style="grid-column: span 1;">
                <div class="card-header">
                    <h3>📋 Budget Bulan Ini</h3>
                </div>
                <div class="card-body">
                    @if($budgets->count() > 0)
                        @foreach($budgets as $b)
                            @php
                                $status = $b->persen >= 100 ? 'danger' : ($b->persen >= 75 ? 'warning' : 'safe');
                            @endphp
                            <div class="budget-item">
                                <div class="budget-item-header">
                                    <div>
                                        <div class="budget-item-name">{{ $b->kategori->nama }}</div>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:0.5rem;">
                                        <span class="badge-persen {{ $status }}">{{ $b->persen }}%</span>
                                        <form method="POST" action="/budget/{{ $b->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" onclick="return confirm('Hapus budget ini?')">🗑</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill {{ $status }}" style="width: {{ $b->persen }}%"></div>
                                </div>
                                <div class="budget-meta">
                                    <span style="color:var(--gray);">Terpakai: <strong>Rp {{ number_format($b->terpakai, 0, ',', '.') }}</strong></span>
                                    <span class="sisa {{ $b->sisa < 0 ? 'minus' : 'plus' }}">
                                        Sisa: Rp {{ number_format(abs($b->sisa), 0, ',', '.') }}
                                        {{ $b->sisa < 0 ? '⚠️' : '' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-budget">
                            <div class="empty-icon">🎯</div>
                            <p>Belum ada budget untuk bulan ini.</p>
                            <p style="font-size:0.8rem;">Tambahkan budget di form sebelah →</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Form Tambah Budget --}}
            <div class="budget-card">
                <h3 style="font-size:1rem;font-weight:800;margin-bottom:1.25rem;">➕ Tambah / Edit Budget</h3>
                <form method="POST" action="/budget">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Kategori Pengeluaran</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="" disabled selected>Pilih kategori...</option>
                            @foreach($kategoriPengeluaran as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Budget (Rp)</label>
                        <input type="number" name="jumlah" class="form-input" placeholder="Contoh: 500000" min="1" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select" required>
                                @foreach(range(1,12) as $m)
                                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select" required>
                                @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">💾 Simpan Budget</button>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
</script>
</body>
</html>