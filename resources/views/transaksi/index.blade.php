<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi — Rantau Finance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=1.3">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ===== Transaction Table Styles ===== */
        .filter-bar {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
            margin-bottom: 1.5rem;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        .filter-group label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .filter-group select,
        .filter-group input[type="text"],
        .filter-group input[type="date"] {
            padding: 0.55rem 0.85rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-xs);
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            color: var(--dark);
            background: var(--white);
            transition: border-color 0.2s;
        }
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
        }
        .filter-group input[type="text"] { width: 200px; }

        .btn-filter {
            padding: 0.55rem 1.25rem;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: var(--radius-xs);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .btn-filter:hover { opacity: 0.9; transform: translateY(-1px); }

        .btn-reset {
            padding: 0.55rem 1rem;
            background: var(--light);
            color: var(--gray);
            border: 1px solid var(--border);
            border-radius: var(--radius-xs);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-reset:hover { border-color: var(--primary-light); color: var(--primary); }

        /* Summary cards row */
        .summary-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .summary-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .summary-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
        .summary-icon.income { background: var(--success-bg); }
        .summary-icon.expense { background: var(--danger-bg); }
        .summary-icon.balance { background: var(--primary-bg); }
        .summary-info .s-value {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--dark);
        }
        .summary-info .s-value.positive { color: var(--success); }
        .summary-info .s-value.negative { color: var(--danger); }
        .summary-info .s-label {
            font-size: 0.78rem;
            color: var(--gray);
            margin-top: 2px;
        }

        /* Table */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.85rem 1.5rem;
            border-bottom: 1px solid var(--border);
            background: var(--light);
        }
        .data-table td {
            padding: 0.9rem 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            font-size: 0.875rem;
            vertical-align: middle;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr { transition: background 0.15s; }
        .data-table tbody tr:hover td { background: rgba(99,102,241,0.02); }

        .tx-category-cell {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }
        .tx-cat-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
        }
        .tx-cat-icon.in { background: var(--success-bg); }
        .tx-cat-icon.out { background: var(--danger-bg); }

        .amount-badge {
            font-weight: 700;
            font-size: 0.88rem;
        }
        .amount-badge.positive { color: var(--success); }
        .amount-badge.negative { color: var(--danger); }

        .date-cell { color: var(--gray); font-size: 0.82rem; }

        .pagination-wrap {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
        }

        @media (max-width: 768px) {
            .summary-row { grid-template-columns: 1fr; }
            .filter-bar { flex-direction: column; align-items: stretch; }
            .filter-group input[type="text"] { width: 100%; }
        }
    </style>
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
    {{-- ===== SIDEBAR ===== --}}
    @include('partials.sidebar', ['active' => 'transaksi'])

    {{-- ===== MAIN ===== --}}
    <main class="main-content">
        {{-- Top Bar --}}
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>Transaksi</h1>
                <p>Riwayat semua transaksi pemasukan dan pengeluaranmu</p>
            </div>
            <div class="top-bar-right" style="display:flex;align-items:center;gap:1rem;">
                @include('partials.notifications')
                <a href="/transaksi/create" class="btn-add">
                    <span>+</span> Transaksi Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div style="padding:1rem 1.25rem;border-radius:12px;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);color:var(--success);font-size:0.85rem;font-weight:500;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="summary-row">
            <div class="summary-card">
                <div class="summary-icon income">📈</div>
                <div class="summary-info">
                    <div class="s-value positive">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    <div class="s-label">Total Pemasukan</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon expense">📉</div>
                <div class="summary-info">
                    <div class="s-value negative">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                    <div class="s-label">Total Pengeluaran</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon balance">💰</div>
                <div class="summary-info">
                    <div class="s-value">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</div>
                    <div class="s-label">Saldo</div>
                </div>
            </div>
        </div>

        {{-- Filter Bar (Personal & Profesional) --}}
        @if(auth()->user()->canAccess('filter_cari_transaksi'))
        <form class="filter-bar" method="GET" action="/transaksi">
            <div class="filter-group">
                <label>Cari</label>
                <input type="text" name="search" placeholder="Cari keterangan..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>Tipe</label>
                <select name="tipe">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan" {{ request('tipe') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('tipe') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">Filter</button>
            @if(request()->anyFilled(['search', 'tipe']))
                <a href="/transaksi" class="btn-reset">Reset</a>
            @endif
        </form>
        @else
        <div style="padding: 1rem; background: var(--light); border: 1px dashed var(--border); border-radius: var(--radius); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 1.5rem;">🔒</div>
            <div>
                <div style="font-weight: 600; font-size: 0.9rem;">Fitur Pencarian & Filter Terkunci</div>
                <div style="color: var(--gray); font-size: 0.8rem;">Upgrade ke paket Personal untuk memfilter transaksi.</div>
            </div>
            <a href="/payment/upgrade/personal" class="btn-primary" style="margin-left: auto; padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none; border-radius: 6px;">Upgrade</a>
        </div>
        @endif

        {{-- Transaction Table --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3>📋 Semua Transaksi</h3>
                    <span style="font-size:0.82rem;color:var(--gray);">{{ $transaksi->count() }} transaksi</span>
                </div>
                
                @if(auth()->user()->canAccess('export_csv_pdf'))
                <div style="display:flex; gap:0.5rem;">
                    <a href="/transaksi/export/csv" title="Unduh CSV" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; background: #fff; color: #374151; border-radius: 6px; text-decoration: none; font-weight: 600; border: 1px solid #d1d5db; display: flex; align-items: center; gap: 0.4rem;">
                        <span>📄</span> CSV
                    </a>
                    <a href="/transaksi/export/pdf" title="Unduh PDF" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; background: #fff; color: #374151; border-radius: 6px; text-decoration: none; font-weight: 600; border: 1px solid #d1d5db; display: flex; align-items: center; gap: 0.4rem;">
                        <span>📕</span> PDF
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body" style="padding:0;">
                @if($transaksi->count() > 0)
                    <div class="table-responsive" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="data-table" style="min-width: 800px; width: 100%;">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi as $t)
                            <tr>
                                <td>
                                    <div class="tx-category-cell">
                                        <div class="tx-cat-icon {{ $t->kategori->tipe === 'pemasukan' ? 'in' : 'out' }}">
                                            {{ $t->kategori->tipe === 'pemasukan' ? '💰' : '🛒' }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600;">{{ $t->kategori->nama }}</div>
                                            @if($t->keterangan)
                                                <div style="font-size:0.75rem;color:var(--gray);margin-top:2px;">{{ Str::limit($t->keterangan, 40) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span @style([
                                        'padding: 3px 10px',
                                        'border-radius: 20px',
                                        'font-size: 0.72rem',
                                        'font-weight: 700',
                                        'background: var(--success-bg); color: var(--success)' => $t->kategori->tipe === 'pemasukan',
                                        'background: var(--danger-bg); color: var(--danger)' => $t->kategori->tipe !== 'pemasukan',
                                    ])>
                                        {{ ucfirst($t->kategori->tipe) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="amount-badge {{ $t->kategori->tipe === 'pemasukan' ? 'positive' : 'negative' }}">
                                        {{ $t->kategori->tipe === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="date-cell">
                                    {{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                        <a href="/transaksi/{{ $t->id }}/edit" style="display: inline-block; padding: 0.5rem; color: var(--primary); text-decoration: none; font-size: 1.25rem;" title="Edit">✏️</a>
                                        <form id="form-delete-{{ $t->id }}" action="/transaksi/{{ $t->id }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('form-delete-{{ $t->id }}')" style="display: inline-block; background: none; border: none; cursor: pointer; font-size: 1.25rem; padding: 0.5rem;" title="Hapus">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                @else
                    <div class="empty-state" style="padding:3rem;">
                        <div class="empty-icon">📭</div>
                        <p>Belum ada transaksi. <a href="/transaksi/create" style="color:var(--primary);font-weight:600;">Buat sekarang!</a></p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}

// Animate elements on load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.summary-card, .card').forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = `all 0.5s ease ${i * 0.08}s`;
        requestAnimationFrame(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    });
});

function confirmDelete(formId) {
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
