<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan — Rantau Finance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .summary-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 640px) {
            .summary-row { grid-template-columns: 1fr; }
        }
        .summary-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
        }
        .summary-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.4rem;
        }
        .summary-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--dark);
        }
        .summary-value.income  { color: #10b981; }
        .summary-value.expense { color: #ef4444; }
        .summary-value.positive { color: #10b981; }
        .summary-value.negative { color: #ef4444; }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }

        .kat-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
        }
        .kat-item:last-child { border-bottom: none; }
        .kat-bar-wrap { flex: 1; margin: 0 0.75rem; }
        .kat-bar {
            height: 6px;
            border-radius: 99px;
            background: var(--border);
            overflow: hidden;
        }
        .kat-bar-fill { height: 100%; border-radius: 99px; }
        .kat-bar-fill.income  { background: #10b981; }
        .kat-bar-fill.expense { background: #ef4444; }

        /* Tabel transaksi */
        .tx-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        .tx-table th {
            text-align: left;
            padding: 0.6rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--gray);
            text-transform: uppercase;
            border-bottom: 2px solid var(--border);
        }
        .tx-table td {
            padding: 0.65rem 0.75rem;
            border-bottom: 1px solid var(--border);
            color: var(--dark);
        }
        .tx-table tr:last-child td { border-bottom: none; }
        .tx-table tr:hover td { background: var(--light); }
        .badge-in  { background: #d1fae5; color: #065f46; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 99px; }
        .badge-out { background: #fee2e2; color: #991b1b; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 99px; }

        /* Chart tahunan */
        .yearly-chart {
            display: flex;
            align-items: flex-end;
            gap: 0.4rem;
            height: 120px;
            padding-bottom: 1.5rem;
            position: relative;
        }
        .yearly-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            height: 100%;
            justify-content: flex-end;
        }
        .yearly-bar {
            width: 100%;
            border-radius: 4px 4px 0 0;
            min-height: 2px;
            transition: height 0.5s ease;
        }
        .yearly-bar.income  { background: #10b981; }
        .yearly-bar.expense { background: #ef4444; }
        .yearly-label {
            font-size: 0.65rem;
            color: var(--gray);
            margin-top: 4px;
            position: absolute;
            bottom: 0;
        }
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
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--gray);
            font-size: 0.85rem;
        }
        .empty-state .empty-icon { font-size: 2rem; margin-bottom: 0.5rem; }
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
    @include('partials.sidebar', ['active' => 'laporan'])

    <main class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>📈 Laporan Keuangan</h1>
                <p>Ringkasan pemasukan & pengeluaran kamu</p>
            </div>
            <div class="top-bar-right" style="display:flex;align-items:center;gap:1rem;">
                @if(auth()->user()->canAccess('export_csv_pdf'))
                <div style="display:flex; gap:0.5rem;">
                    <a href="{{ route('transaksi.export.csv') }}" title="Unduh CSV" style="padding: 0.45rem 0.9rem; font-size: 0.78rem; background: #fff; color: #374151; border-radius: 8px; text-decoration: none; font-weight: 700; border: 1px solid #d1d5db; display: flex; align-items: center; gap: 0.4rem; transition: all 0.2s;">
                        <span style="font-size: 1rem;">📄</span> CSV
                    </a>
                    <a href="{{ route('transaksi.export.pdf') }}" title="Unduh PDF" style="padding: 0.45rem 0.9rem; font-size: 0.78rem; background: #fff; color: #374151; border-radius: 8px; text-decoration: none; font-weight: 700; border: 1px solid #d1d5db; display: flex; align-items: center; gap: 0.4rem; transition: all 0.2s;">
                        <span style="font-size: 1rem;">📕</span> PDF
                    </a>
                </div>
                @endif
                @include('partials.notifications')
            </div>
        </div>

        {{-- Filter --}}
        <div class="filter-bar">
            <span style="font-size:0.85rem;font-weight:600;color:var(--dark-2);">Periode:</span>
            <form method="GET" action="/laporan" style="display:flex;gap:0.5rem;">
                <select name="bulan" onchange="this.form.submit()">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" onchange="this.form.submit()">
                    @foreach(range(date('Y')-2, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="summary-row">
            <div class="summary-card">
                <div class="summary-label">📈 Pemasukan</div>
                <div class="summary-value income">Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">📉 Pengeluaran</div>
                <div class="summary-value expense">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">💰 Selisih</div>
                <div class="summary-value {{ $selisih >= 0 ? 'positive' : 'negative' }}">
                    {{ $selisih >= 0 ? '+' : '-' }}Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Grafik Tahunan --}}
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-header">
                <h3>📊 Grafik Tahunan {{ $tahun }}</h3>
                <div style="display:flex;gap:1rem;font-size:0.75rem;">
                    <span style="color:#10b981;font-weight:700;">■ Pemasukan</span>
                    <span style="color:#ef4444;font-weight:700;">■ Pengeluaran</span>
                </div>
            </div>
            <div class="card-body">
                @php
                    $maxVal = max(1, collect($laporanTahunan)->flatMap(fn($d) => [$d['pemasukan'], $d['pengeluaran']])->max());
                @endphp
                <div style="display:flex;align-items:flex-end;gap:0.3rem;height:140px;padding-bottom:1.5rem;position:relative;">
                    @foreach($laporanTahunan as $d)
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;position:relative;">
                        <div style="display:flex;align-items:flex-end;gap:2px;justify-content:center;width:100%;">
                            <div @style([
                                'width: 45%',
                                'height: ' . (($d['pemasukan']/$maxVal)*110) . 'px',
                                'background: #10b981',
                                'border-radius: 3px 3px 0 0',
                                'min-height: ' . ($d['pemasukan'] > 0 ? 2 : 0) . 'px'
                            ])></div>
                            <div @style([
                                'width: 45%',
                                'height: ' . (($d['pengeluaran']/$maxVal)*110) . 'px',
                                'background: #ef4444',
                                'border-radius: 3px 3px 0 0',
                                'min-height: ' . ($d['pengeluaran'] > 0 ? 2 : 0) . 'px'
                            ])></div>
                        </div>
                        <span style="font-size:0.62rem;color:var(--gray);margin-top:4px;">{{ $d['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Per Kategori --}}
        <div class="two-col" style="margin-bottom:1.5rem;">
            {{-- Pemasukan per Kategori --}}
            <div class="card">
                <div class="card-header"><h3>📈 Pemasukan per Kategori</h3></div>
                <div class="card-body">
                    @if($pemasukanKategori->count() > 0)
                        @php $maxInc = $pemasukanKategori->max('total') ?: 1; @endphp
                        @foreach($pemasukanKategori as $k)
                        <div class="kat-item">
                            <span style="min-width:80px;font-weight:600;">{{ $k->nama }}</span>
                            <div class="kat-bar-wrap">
                                <div class="kat-bar">
                                    <div class="kat-bar-fill income" @style(['width: ' . ($k->total/$maxInc)*100 . '%'])></div>
                                </div>
                            </div>
                            <span style="font-weight:700;color:#10b981;white-space:nowrap;">Rp {{ number_format($k->total, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state"><div class="empty-icon">📭</div><p>Tidak ada pemasukan bulan ini</p></div>
                    @endif
                </div>
            </div>

            {{-- Pengeluaran per Kategori --}}
            <div class="card">
                <div class="card-header"><h3>📉 Pengeluaran per Kategori</h3></div>
                <div class="card-body">
                    @if($pengeluaranKategori->count() > 0)
                        @php $maxExp = $pengeluaranKategori->max('total') ?: 1; @endphp
                        @foreach($pengeluaranKategori as $k)
                        <div class="kat-item">
                            <span style="min-width:80px;font-weight:600;">{{ $k->nama }}</span>
                            <div class="kat-bar-wrap">
                                <div class="kat-bar">
                                    <div class="kat-bar-fill expense" @style(['width: ' . ($k->total/$maxExp)*100 . '%'])></div>
                                </div>
                            </div>
                            <span style="font-weight:700;color:#ef4444;white-space:nowrap;">Rp {{ number_format($k->total, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state"><div class="empty-icon">📭</div><p>Tidak ada pengeluaran bulan ini</p></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabel Detail Transaksi --}}
        <div class="card">
            <div class="card-header">
                <h3>🧾 Detail Transaksi — {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</h3>
                <span style="font-size:0.8rem;color:var(--gray);">{{ $transaksi->count() }} transaksi</span>
            </div>
            <div class="card-body" style="overflow-x:auto;">
                @if($transaksi->count() > 0)
                <table class="tx-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Keterangan</th>
                            <th style="text-align:right;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $tx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tx->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>{{ $tx->kategori->nama }}</td>
                            <td>
                                @if($tx->kategori->tipe === 'pemasukan')
                                    <span class="badge-in">Pemasukan</span>
                                @else
                                    <span class="badge-out">Pengeluaran</span>
                                @endif
                            </td>
                            <td style="color:var(--gray);">{{ $tx->keterangan ?? '-' }}</td>
                            <td @style([
                                'text-align: right',
                                'font-weight: 700',
                                'color: ' . ($tx->kategori->tipe === 'pemasukan' ? '#10b981' : '#ef4444')
                            ])>
                                {{ $tx->kategori->tipe === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <p>Tidak ada transaksi pada periode ini</p>
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
</script>
</body>
</html>
