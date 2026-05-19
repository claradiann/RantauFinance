<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — RantauFinance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <div>
            <img src="{{ asset('images/logo_RD.png') }}" style="height: 48px; vertical-align: middle;"> RantauFinance
            <span class="admin-badge">Admin Panel</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Menu</div>
        <a href="{{ route('admin.index') }}" class="nav-item active">📊 Dashboard</a>
        <a href="{{ route('admin.payments') }}" class="nav-item">💳 Riwayat Payment</a>
        <a href="{{ route('admin.users') }}" class="nav-item">👥 Manajemen User</a>
        <div class="nav-section">Analitik</div>
        <a href="{{ route('admin.revenue') }}" class="nav-item">📈 Laporan Revenue</a>
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

{{-- Main Content --}}
<main class="main">
    <div class="page-header">
        <h1>Dashboard Admin</h1>
        <p>Pantau pembayaran masuk dan kelola user RantauFinance.</p>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">✅ {!! session('success') !!}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">⚠️ {!! session('warning') !!}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card orange">
            <div class="label">Menunggu Konfirmasi</div>
            <div class="value">{{ $stats['pending'] }}</div>
            <div class="sub">pembayaran pending</div>
        </div>
        <div class="stat-card green">
            <div class="label">Dikonfirmasi Hari Ini</div>
            <div class="value">{{ $stats['confirmed_today'] }}</div>
            <div class="sub">pembayaran</div>
        </div>
        <div class="stat-card blue">
            <div class="label">Total User</div>
            <div class="value">{{ $stats['total_users'] }}</div>
            <div class="sub">akun terdaftar</div>
        </div>
        <div class="stat-card purple">
            <div class="label">Revenue Bulan Ini</div>
            <div class="value" style="font-size:1.4rem;">
                Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}
            </div>
            <div class="sub">{{ $stats['active_paid'] }} user berbayar aktif</div>
        </div>
    </div>

    {{-- Pembayaran Pending --}}
    <div class="card">
        <div class="card-header">
            <h2>⏳ Pembayaran Menunggu Konfirmasi</h2>
            @if($stats['pending'] > 0)
                <span class="badge">{{ $stats['pending'] }} pending</span>
            @endif
        </div>

        @if($pendingPayments->isEmpty())
            <div class="empty-state">
                <div class="icon">🎉</div>
                <p>Tidak ada pembayaran yang menunggu konfirmasi.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Paket</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Dikirim</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingPayments as $payment)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $payment->user->name }}</div>
                            <div style="color:var(--gray);font-size:0.8rem;">{{ $payment->user->email }}</div>
                        </td>
                        <td>
                            <span class="badge-plan {{ $payment->plan }}">
                                {{ $payment->plan === 'admin' ? '🔵' : '🟣' }}
                                {{ $payment->planLabel() }}
                            </span>
                        </td>
                        <td style="font-weight:700;color:var(--primary);">{{ $payment->nominalFormatted() }}</td>
                        <td style="text-transform:capitalize;">
                            {{ $payment->metode === 'qris' ? '📱 QRIS' : '🏦 Transfer' }}
                        </td>
                        <td style="color:var(--gray-2);">{{ $payment->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.payment.detail', $payment->id) }}" class="btn btn-primary">
                                👁 Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrap">{{ $pendingPayments->links() }}</div>
        @endif
    </div>

    {{-- Baru Dikonfirmasi --}}
    @if($recentConfirmed->count() > 0)
    <div class="card">
        <div class="card-header">
            <h2>✅ Baru Dikonfirmasi</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Paket</th>
                    <th>Nominal</th>
                    <th>Dikonfirmasi</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentConfirmed as $payment)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $payment->user->name }}</div>
                        <div style="color:var(--gray);font-size:0.8rem;">{{ $payment->user->email }}</div>
                    </td>
                    <td>
                        <span class="badge-plan {{ $payment->plan }}">{{ $payment->planLabel() }}</span>
                    </td>
                    <td style="font-weight:700;">{{ $payment->nominalFormatted() }}</td>
                    <td style="color:var(--gray-2);">{{ $payment->confirmed_at->diffForHumans() }}</td>
                    <td style="color:var(--gray-2);">{{ $payment->confirmedBy?->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</main>

</body>
</html>

