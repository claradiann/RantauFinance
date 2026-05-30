<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Rantau Finance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=1.3">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    {{-- Mobile Header --}}
    <div class="mobile-header">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div style="display:flex;align-items:center;gap:8px;"><img src="{{ asset('images/logo_RD.png') }}" style="height: 54px;"> <span style="font-weight:700;font-size:1rem;">RantauFinance</span></div>
        <div style="width:40px;"></div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="app-layout">
        @include('partials.sidebar', ['active' => 'dashboard'])

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

            {{-- Upgrade Required Notification --}}
            @if(session('upgrade_required'))
            @php $ur = session('upgrade_required'); @endphp
            <div style="
                margin: 1rem 1.5rem 0;
                padding: 1.15rem 1.5rem;
                border-radius: 12px;
                background: linear-gradient(135deg, #fef3c7, #fefce8);
                border: 1px solid rgba(245,158,11,0.3);
                color: #92400e;
                font-size: 0.875rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            ">
                <span style="font-size:1.3rem;">🔒</span>
                <div style="flex:1;">
                    <strong>{{ $ur['message'] }}</strong>
                    <div style="font-size:0.78rem;opacity:0.85;margin-top:2px;">Paket saat ini: {{ $ur['current'] }}</div>
                </div>
                <a href="/profile" style="
                    padding: 0.45rem 1rem;
                    background: linear-gradient(135deg, #f59e0b, #d97706);
                    color: white;
                    border-radius: 8px;
                    font-size: 0.78rem;
                    font-weight: 700;
                    text-decoration: none;
                    white-space: nowrap;
                ">Upgrade Sekarang</a>
            </div>
            @endif

            {{-- Top Bar --}}
            <div class="top-bar">
                <div class="top-bar-left">
                    <h1>Dashboard</h1>
                    <p>Selamat datang kembali, {{ auth()->user()->name }}! 👋</p>
                </div>
                <div class="top-bar-right">
                    @include('partials.notifications')
                    <a href="/transaksi/create" class="btn-add">
                        <span>+</span> Transaksi Baru
                    </a>
                </div>
            </div>

            @php
            $pendingPayment = auth()->user()->payments()->where('status', 'pending')->latest()->first();
            @endphp
            @if($pendingPayment)
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                <div>
                    <strong style="color: #1e40af; display: block; margin-bottom: 0.25rem;">⏳ Pembayaran sedang diproses</strong>
                    <span style="color: #3b82f6; font-size: 0.85rem;">Admin sedang memverifikasi pembayaran kamu untuk paket <strong>{{ ucfirst($pendingPayment->plan) }}</strong>. Sambil menunggu, kamu bisa menggunakan paket Starter secara gratis.</span>
                </div>
                <a href="{{ URL::signedRoute('payment.status', ['user' => auth()->id()]) }}" style="background: white; border: 1px solid #bfdbfe; padding: 0.5rem 1rem; border-radius: 8px; color: #1d4ed8; font-size: 0.8rem; font-weight: 600; text-decoration: none; white-space: nowrap;">Cek Status</a>
            </div>
            @endif

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
                        <a href="{{ route('laporan.index') }}" class="card-action">Lihat Detail</a>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->canAccess('dashboard_grafik_basic'))
                        <div class="chart-container">
                            @php
                            $maxVal = max(1, collect($chartData)->flatMap(fn($d) => [$d['pemasukan'], $d['pengeluaran']])->max());
                            @endphp
                            <div class="chart-bars">
                                @foreach($chartData as $data)
                                <div class="chart-group">
                                    <div class="chart-bar-pair">
                                        <div class="chart-bar income-bar"
                                            @style(['height: ' . (($data['pemasukan'] / $maxVal) * 170) . 'px' ])>
                                            <div class="tooltip">Rp {{ number_format($data['pemasukan'], 0, ',', '.') }}</div>
                                        </div>
                                        <div class="chart-bar expense-bar"
                                            @style(['height: ' . (($data['pengeluaran'] / $maxVal) * 170) . 'px' ])>
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
                        @else
                        <div style="padding: 3rem 1rem; text-align: center; background: var(--light); border-radius: var(--radius); border: 1px dashed var(--border);">
                            <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">🔒</div>
                            <h4 style="margin-bottom: 0.5rem; color: var(--dark);">Grafik Analisis Terkunci</h4>
                            <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem; max-width: 300px; margin-left: auto; margin-right: auto;">
                                Fitur grafik keuangan 6 bulan terakhir hanya tersedia mulai dari paket Personal.
                            </p>
                            <a href="/payment/upgrade/personal" style="display: inline-block; padding: 0.6rem 1.25rem; background: var(--primary); color: white; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.85rem;">
                                Upgrade Sekarang
                            </a>
                        </div>
                        @endif
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
                                        <div class="cat-progress-fill" @style(['width: ' . (($cat->total / $maxCat) * 100) . '%'])></div>
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
                        <a href="/transaksi" class="card-action">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @if($transaksiTerbaru->count() > 0)
                        <div class="tx-list">
                            @foreach($transaksiTerbaru as $tx)
                            <div class="tx-item" style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                                <div class="tx-icon {{ $tx->kategori->tipe === 'pemasukan' ? 'in' : 'out' }}">
                                    {{ $tx->kategori->tipe === 'pemasukan' ? '💰' : '🛒' }}
                                </div>
                                <div class="tx-details" style="flex: 1; min-width: 0;">
                                    <div class="tx-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $tx->kategori->nama }}</div>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span class="tx-date">{{ \Carbon\Carbon::parse($tx->tanggal)->translatedFormat('d M Y') }}</span>
                                        @if($tx->keterangan)
                                            <span style="font-size: 0.75rem; color: var(--gray); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">• {{ Str::limit($tx->keterangan, 15) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div class="tx-amount {{ $tx->kategori->tipe === 'pemasukan' ? 'positive' : 'negative' }}" style="white-space: nowrap;">
                                        {{ $tx->kategori->tipe === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                                    </div>
                                    <div class="tx-actions" style="display: flex; gap: 0.25rem; align-items: center;">
                                        <a href="/transaksi/{{ $tx->id }}/edit?return_to={{ urlencode(request()->fullUrl()) }}" style="text-decoration: none; font-size: 1.1rem; padding: 4px; transition: transform 0.2s;" title="Edit Transaksi">✏️</a>
                                        <form id="form-delete-dash-{{ $tx->id }}" action="/transaksi/{{ $tx->id }}?return_to={{ urlencode(request()->fullUrl()) }}" method="POST" style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteDash('form-delete-dash-{{ $tx->id }}')" style="background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 4px; display: inline-block; line-height: 1;" title="Hapus Transaksi">🗑️</button>
                                        </form>
                                    </div>
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
                            @if(auth()->user()->canAccess('laporan_bulanan_detail'))
                            <a href="/laporan" class="quick-action">
                                <div class="qa-icon">📊</div>
                                <span>Laporan</span>
                            </a>
                            @else
                            <div class="quick-action" style="opacity:0.4;cursor:not-allowed;" onclick="showUpgradeToast('Laporan', 'Personal')">
                                <div class="qa-icon">🔒</div>
                                <span>Laporan</span>
                            </div>
                            @endif
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

        function confirmDeleteDash(formId) {
            if (typeof Swal === 'undefined') {
                if (confirm('Hapus transaksi ini? Data yang dihapus tidak dapat dikembalikan.')) {
                    document.getElementById(formId).submit();
                }
                return;
            }
            
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>
</body>

</html>