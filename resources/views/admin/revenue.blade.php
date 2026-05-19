<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Revenue — Admin RantauFinance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div>
            <img src="{{ asset('images/logo_RD.png') }}" style="height: 48px; vertical-align: middle;"> RantauFinance
            <span class="admin-badge">Admin Panel</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Menu</div>
        <a href="{{ route('admin.index') }}" class="nav-item">📊 Dashboard</a>
        <a href="{{ route('admin.payments') }}" class="nav-item">💳 Riwayat Payment</a>
        <a href="{{ route('admin.users') }}" class="nav-item">👥 Manajemen User</a>
        <div class="nav-section">Analitik</div>
        <a href="{{ route('admin.revenue') }}" class="nav-item active">📈 Laporan Revenue</a>
        <div class="nav-section">Akun</div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="nav-item">
                🚪 Logout
            </button>
        </form>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">⚙️ Administrator</div>
            </div>
        </div>
    </div>
</aside>

<main class="main">

    <div class="page-header">
        <h1>📈 Laporan Revenue</h1>
        <p>Analisis pendapatan, tren bulanan, dan distribusi per paket.</p>
    </div>

    {{-- Stats Ringkas --}}
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="label">Total Revenue</div>
            <div class="value" style="font-size:1.4rem;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="sub">{{ $totalConfirmed }} transaksi terkonfirmasi</div>
        </div>
        <div class="stat-card green">
            <div class="label">Revenue Bulan Ini</div>
            <div class="value" style="font-size:1.4rem;">Rp {{ number_format($thisMonth, 0, ',', '.') }}</div>
            <div class="sub">
                @if($growth > 0)
                    <span style="color:var(--success);">↑ {{ $growth }}%</span> dari bulan lalu
                @elseif($growth < 0)
                    <span style="color:var(--danger);">↓ {{ abs($growth) }}%</span> dari bulan lalu
                @else
                    — sama dengan bulan lalu
                @endif
            </div>
        </div>
        <div class="stat-card blue">
            <div class="label">Bulan Lalu</div>
            <div class="value" style="font-size:1.4rem;">Rp {{ number_format($lastMonth, 0, ',', '.') }}</div>
            <div class="sub">sebagai perbandingan</div>
        </div>
        <div class="stat-card orange">
            <div class="label">Rata-rata / Transaksi</div>
            <div class="value" style="font-size:1.4rem;">Rp {{ number_format($avgPerPayment, 0, ',', '.') }}</div>
            <div class="sub">per pembayaran</div>
        </div>
    </div>

    {{-- Chart Revenue Bulanan --}}
    <div class="card">
        <div class="card-header">
            <h2>📊 Tren Revenue Bulanan (12 Bulan)</h2>
        </div>
        <div class="card-body">
            <div style="position:relative;height:320px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="detail-grid">
        {{-- Revenue Per Plan --}}
        <div>
            <div class="card">
                <div class="card-header">
                    <h2>📦 Revenue Per Paket</h2>
                </div>
                <div class="card-body">
                    <div style="position:relative;height:240px;max-width:240px;margin:0 auto 1.5rem;">
                        <canvas id="planChart"></canvas>
                    </div>

                    @php
                        $plans = ['personal', 'profesional'];
                    @endphp
                    @foreach($plans as $plan)
                        @php
                            $data = $revenueByPlan[$plan] ?? null;
                        @endphp
                        <div class="info-row">
                            <span class="label">
                                <span class="badge-plan {{ $plan }}">
                                    {{ $plan === 'personal' ? '🔵 Personal' : '🟣 Profesional' }}
                                </span>
                            </span>
                            <span class="value">
                                @if($data)
                                    Rp {{ number_format($data->total, 0, ',', '.') }}
                                    <span style="color:var(--gray-2);font-weight:400;font-size:0.78rem;">
                                        ({{ $data->jumlah }}x)
                                    </span>
                                @else
                                    <span style="color:var(--gray-2);">—</span>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top Users --}}
        <div>
            <div class="card">
                <div class="card-header">
                    <h2>🏆 Top User (Spending)</h2>
                </div>

                @if($topUsers->isEmpty())
                    <div class="empty-state">
                        <div class="icon">🏆</div>
                        <p>Belum ada data pembayaran.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topUsers as $i => $user)
                            <tr>
                                <td style="font-weight:800;color:var(--gray-2);">
                                    @if($i === 0) 🥇
                                    @elseif($i === 1) 🥈
                                    @elseif($i === 2) 🥉
                                    @else {{ $i + 1 }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.user.detail', $user->id) }}" style="font-weight:600;color:var(--dark);text-decoration:none;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text)'">
                                        {{ $user->name }}
                                    </a>
                                    <div style="color:var(--gray);font-size:0.78rem;">{{ $user->email }}</div>
                                </td>
                                <td style="font-weight:700;color:var(--primary);">
                                    Rp {{ number_format($user->total_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

</main>

<script>
// Data dari backend
const monthLabels = @json($months->pluck('bulan'));
const monthTotals = @json($months->pluck('total'));
const monthCounts = @json($months->pluck('jumlah'));

// Chart Revenue Bulanan — Bar + Line combo
const ctx1 = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: monthLabels,
        datasets: [
            {
                label: 'Revenue (Rp)',
                data: monthTotals,
                backgroundColor: 'rgba(99, 102, 241, 0.15)',
                borderColor: '#6366f1',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                yAxisID: 'y',
            },
            {
                label: 'Jumlah Transaksi',
                data: monthCounts,
                type: 'line',
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#06b6d4',
                pointRadius: 4,
                pointHoverRadius: 6,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { family: 'Inter', size: 12 } } },
            tooltip: {
                backgroundColor: '#1e293b',
                titleFont: { family: 'Inter', weight: '700' },
                bodyFont: { family: 'Inter' },
                padding: 12,
                cornerRadius: 10,
                callbacks: {
                    label: function(context) {
                        if (context.datasetIndex === 0) {
                            return 'Revenue: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                        return 'Transaksi: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 } } },
            y: {
                position: 'left',
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: {
                    font: { family: 'Inter', size: 11 },
                    callback: v => 'Rp ' + (v / 1000) + 'k'
                }
            },
            y1: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: { font: { family: 'Inter', size: 11 }, stepSize: 1 },
                beginAtZero: true,
            }
        }
    }
});

// Chart Revenue Per Plan — Doughnut
const personalTotal  = {{ $revenueByPlan['personal']->total ?? 0 }};
const profesionalTotal = {{ $revenueByPlan['profesional']->total ?? 0 }};

const ctx2 = document.getElementById('planChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Personal', 'Profesional'],
        datasets: [{
            data: [personalTotal, profesionalTotal],
            backgroundColor: ['#6366f1', '#a855f7'],
            hoverBackgroundColor: ['#4f46e5', '#9333ea'],
            borderWidth: 0,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16, font: { family: 'Inter', size: 12, weight: '600' } } },
            tooltip: {
                backgroundColor: '#1e293b',
                titleFont: { family: 'Inter', weight: '700' },
                bodyFont: { family: 'Inter' },
                padding: 12,
                cornerRadius: 10,
                callbacks: {
                    label: function(context) {
                        return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>

