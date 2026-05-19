<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Payment — Admin RantauFinance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
        <a href="{{ route('admin.payments') }}" class="nav-item active">💳 Riwayat Payment</a>
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

<main class="main">

    <div class="page-header">
        <h1>💳 Riwayat Payment</h1>
        <p>Lihat semua riwayat pembayaran: pending, dikonfirmasi, dan ditolak.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {!! session('success') !!}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">⚠️ {!! session('warning') !!}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- Stats ringkas --}}
    <div class="stats-grid">
        <div class="stat-card orange">
            <div class="label">Pending</div>
            <div class="value">{{ $stats['pending'] }}</div>
            <div class="sub">menunggu konfirmasi</div>
        </div>
        <div class="stat-card green">
            <div class="label">Dikonfirmasi</div>
            <div class="value">{{ $stats['confirmed'] }}</div>
            <div class="sub">pembayaran berhasil</div>
        </div>
        <div class="stat-card" style="--stat-color: var(--danger);">
            <div class="label">Ditolak</div>
            <div class="value" style="color:var(--danger);">{{ $stats['rejected'] }}</div>
            <div class="sub">pembayaran ditolak</div>
        </div>
        <div class="stat-card blue">
            <div class="label">Total</div>
            <div class="value">{{ $stats['total'] }}</div>
            <div class="sub">semua payment</div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.payments') }}">
        <div class="filter-bar">
            <div class="filter-group">
                <label>Cari User</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email...">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua</option>
                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>✅ Dikonfirmasi</option>
                    <option value="rejected"  {{ request('status') === 'rejected'  ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Plan</label>
                <select name="plan">
                    <option value="">Semua</option>
                    <option value="personal"    {{ request('plan') === 'personal'    ? 'selected' : '' }}>Personal</option>
                    <option value="profesional" {{ request('plan') === 'profesional' ? 'selected' : '' }}>Profesional</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">🔍 Filter</button>
            <a href="{{ route('admin.payments') }}" class="btn-reset">Reset</a>
        </div>
    </form>

    {{-- Tabel Payment --}}
    <div class="card">
        <div class="card-header">
            <h2>Daftar Payment</h2>
            <span class="count">Total: {{ $payments->total() }} payment</span>
        </div>

        @if($payments->isEmpty())
            <div class="empty-state">
                <div class="icon">💳</div>
                <p>Tidak ada payment yang cocok dengan filter.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Paket</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td style="font-weight:700;color:var(--gray-2);">#{{ $payment->id }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $payment->user->name ?? '—' }}</div>
                            <div style="color:var(--gray);font-size:0.78rem;">{{ $payment->user->email ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="badge-plan {{ $payment->plan }}">
                                @if($payment->plan === 'personal') 🔵
                                @elseif($payment->plan === 'profesional') 🟣
                                @else ⚪ @endif
                                {{ $payment->planLabel() }}
                            </span>
                        </td>
                        <td style="font-weight:700;color:var(--primary);">{{ $payment->nominalFormatted() }}</td>
                        <td style="text-transform:capitalize;">
                            {{ $payment->metode === 'qris' ? '📱 QRIS' : '🏦 Transfer' }}
                        </td>
                        <td>
                            <span class="badge-status {{ $payment->status }}">
                                {{ $payment->statusLabel() }}
                            </span>
                        </td>
                        <td style="color:var(--gray-2);font-size:0.8rem;">
                            {{ $payment->created_at->format('d M Y') }}
                            <div style="font-size:0.72rem;color:var(--gray);">{{ $payment->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <div class="btn-actions">
                                <a href="{{ route('admin.payment.detail', $payment->id) }}" class="btn btn-sm btn-primary">
                                    👁 Detail
                                </a>
                                @if($payment->isPending())
                                    <form method="POST" action="{{ route('admin.payment.confirm', $payment->id) }}"
                                          onsubmit="return confirm('Konfirmasi pembayaran #{{ $payment->id }}? Plan user akan diaktifkan.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">✅ Konfirmasi</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrap">{{ $payments->withQueryString()->links() }}</div>
        @endif
    </div>

</main>

</body>
</html>

