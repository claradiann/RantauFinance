<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User — Admin RantauFinance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div>
            💰 RantauFinance
            <span class="admin-badge">Admin Panel</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Menu</div>
        <a href="{{ route('admin.index') }}" class="nav-item">📊 Dashboard</a>
        <a href="{{ route('admin.users') }}" class="nav-item active">👥 Manajemen User</a>
        <div class="nav-section">Akun</div>
        <a href="{{ route('dashboard') }}" class="nav-item">🏠 Ke Aplikasi</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="nav-item">
                🚪 Logout
            </button>
        </form>
    </nav>
    <div class="sidebar-footer">
        Logged in sebagai<br>
        <strong>{{ auth()->user()->name }}</strong>
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
                    <option value="starter"     {{ request('plan') === 'starter'     ? 'selected' : '' }}>Starter</option>
                    <option value="personal"    {{ request('plan') === 'personal'    ? 'selected' : '' }}>Personal</option>
                    <option value="profesional" {{ request('plan') === 'profesional' ? 'selected' : '' }}>Profesional</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua</option>
                    <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
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
            <table>
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
                            <div style="font-weight:600;">{{ $user->name }}</div>
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
                                {{-- Reset Password (hanya untuk user berbayar) --}}
                                @if(in_array($user->plan, ['personal', 'profesional']) && $user->status === 'active')
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
            <div class="pagination-wrap">{{ $users->withQueryString()->links() }}</div>
        @endif
    </div>

</main>

</body>
</html>
