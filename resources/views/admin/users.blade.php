<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User — Admin RantauFinance</title>
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
        .pagination-wrap svg { width: 20px; height: 20px; }
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

        <div class="page-header">
            <h1>👥 Manajemen User</h1>
            <p>Kelola semua user, suspend akun, dan reset password.</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success">✅ {!! session('success') !!}</div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning">⚠️ {!! session('warning') !!}</div>
        @endif

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.users') }}">
            <div class="filter-bar">
                <div class="filter-group">
                    <label>Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email...">
                </div>
                <div class="filter-group">
                    <label>Plan</label>
                    <select name="plan">
                        <option value="">Semua</option>
                        <option value="starter" {{ request('plan') === 'starter'     ? 'selected' : '' }}>Starter</option>
                        <option value="personal" {{ request('plan') === 'personal'    ? 'selected' : '' }}>Personal</option>
                        <option value="profesional" {{ request('plan') === 'profesional' ? 'selected' : '' }}>Profesional</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua</option>
                        <option value="active" {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">🔍 Filter</button>
                <a href="{{ route('admin.users') }}" class="btn-reset">Reset</a>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h2>Daftar User</h2>
                <span class="count">Total: {{ $users->total() }} user</span>
            </div>

            @if($users->isEmpty())
            <div class="empty-state">
                <div class="icon">👤</div>
                <p>Tidak ada user yang cocok dengan filter.</p>
            </div>
            @else
            <div class="table-responsive" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table style="min-width: 850px; width: 100%;">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Berlaku Sampai</th>
                            <th>Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('admin.user.detail', $user->id) }}" style="font-weight:600;color:var(--dark);text-decoration:none;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text)'">{{ $user->name }}</a>
                                <div style="color:var(--gray);font-size:0.78rem;">{{ $user->email }}</div>
                            </td>
                            <td>
                                <span class="badge-plan {{ $user->plan }}">
                                    @if($user->plan === 'personal') 🔵
                                    @elseif($user->plan === 'profesional') 🟣
                                    @else ⚪ @endif
                                    {{ $user->planLabel() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status {{ $user->status }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td style="color:var(--gray-2);font-size:0.8rem;">
                                @if($user->plan_expires_at)
                                {{ $user->plan_expires_at->format('d M Y') }}
                                @if($user->plan_expires_at->isPast())
                                <span style="color:var(--danger);"> (expired)</span>
                                @endif
                                @else
                                <span style="color:var(--gray);">—</span>
                                @endif
                            </td>
                            <td style="color:var(--gray-2);font-size:0.8rem;">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-actions">
                                    {{-- Ubah Plan --}}
                                    <button type="button"
                                        class="btn btn-sm btn-secondary btn-ubah-plan"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-plan="{{ $user->plan }}">
                                        ✏️ Ubah Plan
                                    </button>

                                    {{-- Reset Password — semua user kecuali yang suspended --}}
                                    @if($user->status !== 'suspended')
                                    <form method="POST" action="{{ route('admin.user.reset-password', $user->id) }}"
                                        onsubmit="return confirm('Reset password {{ $user->name }}? Password baru akan dikirim via email.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">🔑 Reset PW</button>
                                    </form>
                                    @endif

                                    {{-- Suspend / Unsuspend --}}
                                    @if($user->status !== 'suspended')
                                    <form method="POST" action="{{ route('admin.user.suspend', $user->id) }}"
                                        onsubmit="return confirm('Suspend akun {{ $user->name }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">🚫 Suspend</button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('admin.user.unsuspend', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">✅ Aktifkan</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $users->withQueryString()->links() }}</div>
            @endif
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

            // Set default expiry +1 bulan jika bukan starter
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

        // Kosongkan expiry otomatis saat pilih Starter
        document.getElementById('planModalSelect').addEventListener('change', function() {
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

        document.getElementById('planModal').addEventListener('click', function(e) {
            if (e.target === this) closePlanModal();
        });

        // Event listener untuk tombol Ubah Plan
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-ubah-plan').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');
                    const currentPlan = this.getAttribute('data-plan');
                    openPlanModal(userId, userName, currentPlan);
                });
            });
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