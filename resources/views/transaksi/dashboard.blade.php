<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Rantau Finance</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

{{-- Mobile Header --}}
<div class="mobile-header">
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    <span style="font-weight:700;font-size:1rem;">💰 RantauFinance</span>
    <div style="width:40px;"></div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="app-layout">
    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar">
        <a href="/" class="sidebar-logo">
            <div class="logo-icon">💰</div>
            <span class="logo-text">RantauFinance</span>
        </a>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Menu</div>
                <a href="/dashboard" class="nav-item active">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
                <a href="/transaksi" class="nav-item">
                    <span class="nav-icon">💳</span> Transaksi
                    @if($totalTransaksi > 0)
                        <span class="nav-badge">{{ $totalTransaksi }}</span>
                    @endif
                </a>
                <a href="/transaksi/create" class="nav-item">
                    <span class="nav-icon">➕</span> Tambah Transaksi
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Lainnya</div>
                <a href="/kategori" class="nav-item">
                    <span class="nav-icon">📁</span> Kategori
                </a>
                <a href="/budget" class="nav-item">
                    <span class="nav-icon">🎯</span> Budget
                </a>
                <a href="#" class="nav-item">
                    <span class="nav-icon">📈</span> Laporan
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="user-info">
                    <div class="name">{{ auth()->user()->name }}</div>
                    <div class="role">
                        {{ auth()->user()->is_admin ? '⚙️ Administrator' : auth()->user()->planLabel() }}
                    </div>
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn">
                    <span>🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <main class="main-content">
        {{-- Flash Message --}}
        @if(session('success'))
            <div style="
                margin: 1rem 1.5rem 0;
                padding: 1rem 1.25rem;
                border-radius: 10px;
                background: #d1fae5;
                border: 1px solid rgba(16,185,129,0.3);
                color: #065f46;
                font-size: 0.875rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            ">  
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Top Bar --}}
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>Dashboard</h1>
                <p>Selamat datang kembali, {{ auth()->user()->name }}! 👋</p>
            </div>
            <div class="top-bar-right">
                <button class="btn-icon" title="Notifikasi">
                    🔔 <span class="notif-dot"></span>
                </button>
                <a href="/transaksi/create" class="btn-add">
                    <span>+</span> Transaksi Baru
                </a>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="stats-row">
            <div class="stat-card saldo">
                <div class="stat-header">
                    <div class="stat-icon saldo">💰</div>
                </div>
                <div class="stat-value">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
                <div class="stat-label">Saldo Tersedia</div>
            </div>
            <div class="stat-card income">
                <div class="stat-header">
                    <div class="stat-icon income">📈</div>
                    <div class="stat-trend up">▲ Bulan Ini</div>
                </div>
                <div class="stat-value">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</div>
                <div class="stat-label">Pemasukan Bulan Ini</div>
            </div>
            <div class="stat-card expense">
                <div class="stat-header">
                    <div class="stat-icon expense">📉</div>
                    <div class="stat-trend down">▼ Bulan Ini</div>
                </div>
                <div class="stat-value">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</div>
                <div class="stat-label">Pengeluaran Bulan Ini</div>
            </div>
            <div class="stat-card count">
                <div class="stat-header">
                    <div class="stat-icon count">📋</div>
                </div>
                <div class="stat-value">{{ $totalTransaksi }}</div>
                <div class="stat-label">Total Transaksi Bulan Ini</div>
            </div>
        </div>

        {{-- Chart + Category --}}
        <div class="content-grid">
            {{-- Chart --}}
            <div class="card">
                <div class="card-header">
                    <h3>📊 Grafik Keuangan 6 Bulan</h3>
                    <a href="#" class="card-action">Lihat Detail →</a>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        @php
                            $maxVal = max(1, collect($chartData)->flatMap(fn($d) => [$d['pemasukan'], $d['pengeluaran']])->max());
                        @endphp
                        <div class="chart-bars">
                            @foreach($chartData as $data)
                                <div class="chart-group">
                                    <div class="chart-bar-pair">
                                        <div class="chart-bar income-bar"
                                             style="height: {{ ($data['pemasukan'] / $maxVal) * 170 }}px;">
                                            <div class="tooltip">Rp {{ number_format($data['pemasukan'], 0, ',', '.') }}</div>
                                        </div>
                                        <div class="chart-bar expense-bar"
                                             style="height: {{ ($data['pengeluaran'] / $maxVal) * 170 }}px;">
                                            <div class="tooltip">Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <span class="chart-label">{{ $data['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="chart-legend">
                            <div class="chart-legend-item">
                                <div class="chart-legend-dot income"></div> Pemasukan
                            </div>
                            <div class="chart-legend-item">
                                <div class="chart-legend-dot expense"></div> Pengeluaran
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Category Breakdown --}}
            <div class="card">
                <div class="card-header">
                    <h3>📁 Pengeluaran per Kategori</h3>
                    <span class="card-action" style="cursor:default;color:var(--gray);">Bulan Ini</span>
                </div>
                <div class="card-body">
                    @if($pengeluaranPerKategori->count() > 0)
                        @php $maxCat = $pengeluaranPerKategori->max('total') ?: 1; @endphp
                        <div class="category-list">
                            @foreach($pengeluaranPerKategori as $cat)
                                <div class="category-item">
                                    <div class="cat-icon">📌</div>
                                    <div class="cat-info">
                                        <div class="cat-name">{{ $cat->nama }}</div>
                                        <div class="cat-progress-bar">
                                            <div class="cat-progress-fill" style="width: {{ ($cat->total / $maxCat) * 100 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="cat-amount">Rp {{ number_format($cat->total, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <p>Belum ada pengeluaran bulan ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Transactions + Quick Actions --}}
        <div class="content-grid">
            {{-- Recent Transactions --}}
            <div class="card">
                <div class="card-header">
                    <h3>🕐 Transaksi Terbaru</h3>
                    <a href="/transaksi" class="card-action">Lihat Semua →</a>
                </div>
                <div class="card-body">
                    @if($transaksiTerbaru->count() > 0)
                        <div class="tx-list">
                            @foreach($transaksiTerbaru as $tx)
                                <div class="tx-item">
                                    <div class="tx-icon {{ $tx->kategori->tipe === 'pemasukan' ? 'in' : 'out' }}">
                                        {{ $tx->kategori->tipe === 'pemasukan' ? '💰' : '🛒' }}
                                    </div>
                                    <div class="tx-details">
                                        <div class="tx-name">{{ $tx->kategori->nama }}</div>
                                        <div class="tx-date">{{ \Carbon\Carbon::parse($tx->tanggal)->translatedFormat('d M Y') }}</div>
                                    </div>
                                    <div class="tx-amount {{ $tx->kategori->tipe === 'pemasukan' ? 'positive' : 'negative' }}">
                                        {{ $tx->kategori->tipe === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <p>Belum ada transaksi. <a href="/transaksi/create" style="color:var(--primary);">Buat sekarang!</a></p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h3>⚡ Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="/transaksi/create" class="quick-action">
                            <div class="qa-icon">➕</div>
                            <span>Tambah Transaksi</span>
                        </a>
                        <a href="/transaksi" class="quick-action">
                            <div class="qa-icon">📋</div>
                            <span>Semua Transaksi</span>
                        </a>
                        <a href="/laporan" class="quick-action">
                            <div class="qa-icon">📊</div>
                            <span>Laporan</span>
                        </a>
                        <a href="/profile" class="quick-action">
                            <div class="qa-icon">⚙️</div>
                            <span>Pengaturan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}

// Animate stat values on load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.stat-card, .card').forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = `all 0.5s ease ${i * 0.08}s`;
        requestAnimationFrame(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    });
});
</script>
</body>
</html>