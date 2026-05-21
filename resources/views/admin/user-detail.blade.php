<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User: {{ $user->name }} — Admin RantauFinance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=1.3">
    <style>
        /* Prevent layout issues if CSS is cached */
        .mobile-header { display: none; }
        @media (max-width: 768px) {
            .mobile-header { display: flex; }
        }
    </style>
</head>
<body>

{{-- Mobile Header --}}
<div class="mobile-header">
    <div class="mobile-header-brand">
        <img src="{{ asset('images/logo_RD.png') }}" alt="Logo" style="height: 32px;"> RantauFinance
        <span class="admin-badge">Admin</span>
    </div>
    <button class="menu-toggle" id="menuToggleBtn">☰</button>
</div>

{{-- Sidebar Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- Sidebar --}}
<aside class="sidebar">
    <button class="sidebar-close" id="sidebarCloseBtn">&times;</button>
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
        <a href="{{ route('admin.users') }}" class="nav-item active">👥 Manajemen User</a>
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

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('admin.index') }}">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.users') }}">Manajemen User</a>
        <span>/</span>
        <span>{{ $user->name }}</span>
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

    <div class="detail-grid">

        {{-- Kolom Kiri --}}
        <div>
            {{-- Info User --}}
            <div class="card">
                <div class="card-header">
                    <h2>👤 Info User</h2>
                    <span class="badge-status {{ $user->status }}">{{ ucfirst($user->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="label">Nama</span>
                        <span class="value">{{ $user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email</span>
                        <span class="value">{{ $user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Plan</span>
                        <span class="value">
                            <span class="badge-plan {{ $user->plan }}">
                                @if($user->plan === 'personal') 🔵
                                @elseif($user->plan === 'profesional') 🟣
                                @else ⚪ @endif
                                {{ $user->planLabel() }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="label">Berlaku Sampai</span>
                        <span class="value">
                            @if($user->plan_expires_at)
                                {{ $user->plan_expires_at->format('d M Y') }}
                                @if($user->plan_expires_at->isPast())
                                    <span style="color:var(--danger);font-size:0.78rem;"> (expired)</span>
                                @endif
                            @else
                                <span style="color:var(--gray);">—</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="label">Terdaftar</span>
                        <span class="value">{{ $user->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Total Transaksi</span>
                        <span class="value">{{ $user->transaksi()->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Aksi Admin --}}
            <div class="card">
                <div class="card-header">
                    <h2>⚡ Tindakan Admin</h2>
                </div>
                <div class="card-body">
                    <div class="action-buttons" style="margin-top:0;">
                        {{-- Ubah Plan --}}
                        <button type="button"
                            class="btn btn-primary"
                            id="btnUbahPlan"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-plan="{{ $user->plan }}">
                            ✏️ Ubah Plan
                        </button>

                        {{-- Reset Password --}}
                        @if($user->status !== 'suspended')
                            <form method="POST" action="{{ route('admin.user.reset-password', $user->id) }}"
                                  onsubmit="return confirm('Reset password {{ $user->name }}? Password baru akan dikirim via email.')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" style="padding:0.5rem 1rem;">🔑 Reset Password</button>
                            </form>
                        @endif

                        {{-- Suspend / Unsuspend --}}
                        @if($user->status !== 'suspended')
                            <form method="POST" action="{{ route('admin.user.suspend', $user->id) }}"
                                  onsubmit="return confirm('Suspend akun {{ $user->name }}?')">
                                @csrf
                                <button type="submit" class="btn btn-danger">🚫 Suspend</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.user.unsuspend', $user->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success">✅ Aktifkan Kembali</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Riwayat Payment --}}
        <div>
            <div class="card">
                <div class="card-header">
                    <h2>💳 Riwayat Payment</h2>
                    <span class="count">{{ $payments->total() }} payment</span>
                </div>

                @if($payments->isEmpty())
                    <div class="empty-state">
                        <div class="icon">💳</div>
                        <p>User ini belum pernah melakukan pembayaran.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paket</th>
                                <th>Nominal</th>
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
                                    <span class="badge-plan {{ $payment->plan }}">
                                        {{ $payment->planLabel() }}
                                    </span>
                                </td>
                                <td style="font-weight:700;color:var(--primary);">{{ $payment->nominalFormatted() }}</td>
                                <td>
                                    <span class="badge-status {{ $payment->status }}">
                                        {{ $payment->statusLabel() }}
                                    </span>
                                </td>
                                <td style="color:var(--gray-2);font-size:0.8rem;">
                                    {{ $payment->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <a href="{{ route('admin.payment.detail', $payment->id) }}" class="btn btn-sm btn-primary">
                                            👁 Detail
                                        </a>
                                        @if($payment->isPending())
                                            <form method="POST" action="{{ route('admin.payment.confirm', $payment->id) }}"
                                                  onsubmit="return confirm('Konfirmasi pembayaran #{{ $payment->id }}? Plan {{ $user->name }} akan di-upgrade ke {{ ucfirst($payment->plan) }}.')">
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
                    <div class="pagination-wrap">{{ $payments->links() }}</div>
                @endif
            </div>
        </div>

    </div>

</main>

{{-- Modal Ubah Plan --}}
<div class="modal-backdrop" id="planModal">
    <div class="modal">
        <h3>✏️ Ubah Plan User</h3>
        <p id="planModalDesc" style="color:var(--gray-2);font-size:0.875rem;margin-bottom:1rem;"></p>
        <form method="POST" id="planModalForm">
            @csrf
            @method('PATCH')
            <div class="filter-group" style="margin-bottom:1rem;">
                <label>Plan Baru</label>
                <select name="plan" id="planModalSelect" style="width:100%;padding:0.5rem;border-radius:8px;border:1px solid var(--border);">
                    <option value="starter">⚪ Starter</option>
                    <option value="personal">🔵 Personal</option>
                    <option value="profesional">🟣 Profesional</option>
                </select>
            </div>
            <div class="filter-group" style="margin-bottom:1.5rem;">
                <label>Berlaku Sampai <span style="color:var(--gray);font-size:0.8rem;">(kosongkan untuk Starter)</span></label>
                <input type="date" name="plan_expires_at" id="planModalExpiry"
                    style="width:100%;padding:0.5rem;border-radius:8px;border:1px solid var(--border);"
                    min="{{ now()->addDay()->format('Y-m-d') }}">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="closePlanModal()">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPlanModal(userId, userName, currentPlan) {
    document.getElementById('planModalDesc').textContent = 'User: ' + userName;
    document.getElementById('planModalSelect').value = currentPlan;
    document.getElementById('planModalForm').action = '/admin/users/' + userId + '/change-plan';

    const expiry = document.getElementById('planModalExpiry');
    if (currentPlan !== 'starter') {
        const d = new Date();
        d.setMonth(d.getMonth() + 1);
        expiry.value = d.toISOString().split('T')[0];
    } else {
        expiry.value = '';
    }

    document.getElementById('planModal').classList.add('show');
}

function closePlanModal() {
    document.getElementById('planModal').classList.remove('show');
}

document.getElementById('btnUbahPlan').addEventListener('click', function() {
    const userId = this.getAttribute('data-id');
    const userName = this.getAttribute('data-name');
    const currentPlan = this.getAttribute('data-plan');
    openPlanModal(userId, userName, currentPlan);
});

document.getElementById('planModalSelect').addEventListener('change', function () {
    const expiry = document.getElementById('planModalExpiry');
    if (this.value === 'starter') {
        expiry.value = '';
        expiry.disabled = true;
    } else {
        expiry.disabled = false;
        if (!expiry.value) {
            const d = new Date();
            d.setMonth(d.getMonth() + 1);
            expiry.value = d.toISOString().split('T')[0];
        }
    }
});

document.getElementById('planModal').addEventListener('click', function (e) {
    if (e.target === this) closePlanModal();
});
</script>

{{-- Mobile Nav Toggle Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const menuToggleBtn = document.getElementById('menuToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (menuToggleBtn && sidebar && sidebarOverlay) {
        menuToggleBtn.addEventListener('click', function() {
            sidebar.classList.add('open');
            sidebarOverlay.classList.add('show');
        });
    }

    function closeSidebar() {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        }
    }

    if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
});
</script>

</body>
</html>

