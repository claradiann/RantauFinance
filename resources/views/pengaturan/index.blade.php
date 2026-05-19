<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan — Rantau Finance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 1.5rem;
            align-items: start;
        }
        @media (max-width: 768px) {
            .settings-grid { grid-template-columns: 1fr; }
        }
        .settings-nav {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .settings-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray);
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            border-bottom: 1px solid var(--border);
        }
        .settings-nav-item:last-child { border-bottom: none; }
        .settings-nav-item:hover { background: var(--light); color: var(--dark); }
        .settings-nav-item.active {
            background: var(--light);
            color: var(--primary);
            border-left-color: var(--primary);
        }
        .settings-panel { display: none; }
        .settings-panel.active { display: block; }

        .settings-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .settings-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            background: var(--light);
        }
        .settings-card-header h3 {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.2rem;
        }
        .settings-card-header p {
            font-size: 0.8rem;
            color: var(--gray);
        }
        .settings-card-body { padding: 1.5rem; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--dark-2);
            margin-bottom: 0.4rem;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--white);
            outline: none;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
        }
        .form-input:disabled {
            background: var(--light);
            color: var(--gray);
            cursor: not-allowed;
        }
        .btn-save {
            padding: 0.75rem 1.5rem;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }
        .btn-save:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-danger {
            padding: 0.75rem 1.5rem;
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .btn-danger:hover { background: #ef4444; color: white; }

        .alert-success {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            background: #d1fae5;
            border: 1px solid rgba(16,185,129,0.3);
            color: #065f46;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-error {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            background: #fee2e2;
            border: 1px solid rgba(239,68,68,0.3);
            color: #991b1b;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }

        /* Avatar */
        .avatar-section {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .avatar-big {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--gradient-1);
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-info .name { font-size: 1rem; font-weight: 800; color: var(--dark); }
        .avatar-info .email { font-size: 0.8rem; color: var(--gray); }

        /* Plan cards */
        .plan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        @media (max-width: 600px) { .plan-grid { grid-template-columns: 1fr; } }
        .plan-card {
            border: 2px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            text-align: center;
            position: relative;
            transition: all 0.2s;
        }
        .plan-card.current {
            border-color: var(--primary);
            background: rgba(99,102,241,0.04);
        }
        .plan-card.current::before {
            content: 'Plan Kamu';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.75rem;
            border-radius: 99px;
            white-space: nowrap;
        }
        .plan-name {
            font-size: 1rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }
        .plan-price {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.75rem;
        }
        .plan-price span { font-size: 0.75rem; font-weight: 500; color: var(--gray); }
        .plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 1rem;
            text-align: left;
        }
        .plan-features li {
            font-size: 0.78rem;
            color: var(--gray);
            padding: 0.2rem 0;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .plan-features li::before { content: '✓'; color: #10b981; font-weight: 700; }
        .btn-upgrade {
            width: 100%;
            padding: 0.65rem;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
        }
        .btn-upgrade:hover { opacity: 0.9; }
        .btn-upgrade.disabled {
            background: var(--light);
            color: var(--gray);
            cursor: default;
            border: 1px solid var(--border);
        }

        /* Danger zone */
        .danger-zone {
            border: 1px solid #fca5a5;
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            background: #fff5f5;
        }
        .danger-zone h4 { font-size: 0.9rem; font-weight: 700; color: #991b1b; margin-bottom: 0.4rem; }
        .danger-zone p  { font-size: 0.82rem; color: #b91c1c; margin-bottom: 1rem; }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: white;
            border-radius: var(--radius);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            margin: 1rem;
        }
        .modal-box h3 { font-size: 1rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--dark); }
        .modal-box p  { font-size: 0.85rem; color: var(--gray); margin-bottom: 1.25rem; }
        .modal-actions { display: flex; gap: 0.75rem; }
        .btn-cancel-modal {
            flex: 1;
            padding: 0.7rem;
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            color: var(--gray);
        }
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
    @include('partials.sidebar', ['active' => 'pengaturan'])

    <main class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>⚙️ Pengaturan</h1>
                <p>Kelola profil dan akun kamu</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <div class="settings-grid">
            {{-- Nav Kiri --}}
            <div class="settings-nav">
                <div class="settings-nav-item active" onclick="showPanel('profil', this)">
                    👤 Edit Profil
                </div>
                <div class="settings-nav-item" onclick="showPanel('password', this)">
                    🔒 Ganti Password
                </div>
                <div class="settings-nav-item" onclick="showPanel('upgrade', this)">
                    ⭐ Upgrade Plan
                </div>
                <div class="settings-nav-item" onclick="showPanel('akun', this)">
                    🗑 Hapus Akun
                </div>
            </div>

            {{-- Panel Kanan --}}
            <div>

                {{-- Panel: Edit Profil --}}
                <div class="settings-panel active" id="panel-profil">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h3>👤 Edit Profil</h3>
                            <p>Perbarui nama dan email kamu</p>
                        </div>
                        <div class="settings-card-body">
                            <div class="avatar-section">
                                <div class="avatar-big">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="avatar-info">
                                    <div class="name">{{ auth()->user()->name }}</div>
                                    <div class="email">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            <form method="POST" action="/profile">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-input"
                                           value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')<p style="font-size:0.78rem;color:#ef4444;margin-top:0.3rem;">{{ $message }}</p>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-input"
                                           value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')<p style="font-size:0.78rem;color:#ef4444;margin-top:0.3rem;">{{ $message }}</p>@enderror
                                </div>
                                <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Panel: Ganti Password --}}
                <div class="settings-panel" id="panel-password">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h3>🔒 Ganti Password</h3>
                            <p>Pastikan password baru minimal 8 karakter</p>
                        </div>
                        <div class="settings-card-body">
                            @if($errors->has('current_password'))
                                <div class="alert-error">{{ $errors->first('current_password') }}</div>
                            @endif
                            <form method="POST" action="/password">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="password" class="form-input" required minlength="8">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-input" required>
                                </div>
                                <button type="submit" class="btn-save">🔒 Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Panel: Upgrade Plan --}}
                <div class="settings-panel" id="panel-upgrade">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h3>⭐ Upgrade Plan</h3>
                            <p>Plan kamu saat ini: <strong>{{ auth()->user()->planLabel() }}</strong>
                                @if(auth()->user()->plan !== 'starter' && auth()->user()->plan_expires_at)
                                    — berlaku hingga {{ auth()->user()->plan_expires_at->translatedFormat('d F Y') }}
                                @endif
                            </p>
                        </div>
                        <div class="settings-card-body">
                            <div class="plan-grid">
                                {{-- Starter --}}
                                <div class="plan-card {{ auth()->user()->effectivePlan() === 'starter' ? 'current' : '' }}">
                                    <div class="plan-name">Starter</div>
                                    <div class="plan-price">Gratis <span>/selamanya</span></div>
                                    <ul class="plan-features">
                                        <li>Input transaksi (maks 50/bln)</li>
                                        <li>Kategori dasar</li>
                                        <li>Laporan bulanan sederhana</li>
                                        <li>Total in & out</li>
                                    </ul>
                                    <span class="btn-upgrade disabled">Plan Aktif</span>
                                </div>
                                {{-- Personal --}}
                                <div class="plan-card {{ auth()->user()->effectivePlan() === 'personal' ? 'current' : '' }}">
                                    <div class="plan-name">Personal</div>
                                    <div class="plan-price">Rp 12.000 <span>/bulan</span></div>
                                    <ul class="plan-features">
                                        <li>Transaksi unlimited</li>
                                        <li>Dashboard grafik basic</li>
                                        <li>Riwayat & Filter transaksi</li>
                                        <li>Budget & Laporan detail</li>
                                    </ul>
                                    @if(auth()->user()->effectivePlan() === 'personal')
                                        <span class="btn-upgrade disabled">Plan Aktif</span>
                                    @else
                                        <a href="{{ route('payment.upgrade', 'personal') }}" class="btn-upgrade">Upgrade</a>
                                    @endif
                                </div>
                                {{-- Profesional --}}
                                <div class="plan-card {{ auth()->user()->effectivePlan() === 'profesional' ? 'current' : '' }}">
                                    <div class="plan-name">Profesional</div>
                                    <div class="plan-price">Rp 20.000 <span>/bulan</span></div>
                                    <ul class="plan-features">
                                        <li>Semua fitur Personal</li>
                                        <li>Insight & Analisis cerdas</li>
                                        <li>Peringatan budget</li>
                                        <li>Export CSV & PDF</li>
                                        <li>Kategori custom unlimited</li>
                                    </ul>
                                    @if(auth()->user()->effectivePlan() === 'profesional')
                                        <span class="btn-upgrade disabled">Plan Aktif</span>
                                    @else
                                        <a href="{{ route('payment.upgrade', 'profesional') }}" class="btn-upgrade">Upgrade</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel: Hapus Akun --}}
                <div class="settings-panel" id="panel-akun">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h3>🗑 Hapus Akun</h3>
                            <p>Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                        <div class="settings-card-body">
                            <div class="danger-zone">
                                <h4>⚠️ Zona Berbahaya</h4>
                                <p>Menghapus akun akan menghapus semua data transaksi, budget, dan riwayat kamu secara permanen. Tindakan ini tidak bisa dibatalkan.</p>
                                <button type="button" class="btn-danger" onclick="document.getElementById('modalHapus').classList.add('open')">
                                    🗑 Hapus Akun Saya
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

{{-- Modal Konfirmasi Hapus Akun --}}
<div class="modal-overlay" id="modalHapus">
    <div class="modal-box">
        <h3>🗑 Konfirmasi Hapus Akun</h3>
        <p>Masukkan password kamu untuk mengkonfirmasi penghapusan akun. Semua data akan hilang permanen.</p>
        <form method="POST" action="/profile">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Masukkan password..." required>
                @error('password', 'userDeletion')
                    <p style="font-size:0.78rem;color:#ef4444;margin-top:0.3rem;">{{ $message }}</p>
                @enderror
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel-modal" onclick="document.getElementById('modalHapus').classList.remove('open')">
                    Batal
                </button>
                <button type="submit" class="btn-danger" style="flex:1;">🗑 Ya, Hapus Akun</button>
            </div>
        </form>
    </div>
</div>

<script>
function showPanel(name, el) {
    document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.settings-nav-item').forEach(n => n.classList.remove('active'));
    document.getElementById('panel-' + name).classList.add('active');
    
    // Aktifkan tab di nav
    const target = el || (window.event ? window.event.currentTarget : null) || document.querySelector(`[onclick*="${name}"]`);
    if (target && target.classList) target.classList.add('active');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}

// Buka panel password otomatis jika ada error password
const hasPasswordErrors = "{{ $errors->has('current_password') || $errors->has('password') ? '1' : '' }}";
if (hasPasswordErrors === '1') {
    document.addEventListener('DOMContentLoaded', () => showPanel('password'));
}
</script>
</body>
</html>
