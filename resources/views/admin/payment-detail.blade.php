<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran #{{ $payment->id }} — Admin RantauFinance</title>
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
        <a href="{{ route('admin.index') }}" class="nav-item">📊 Dashboard</a>
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

<main class="main">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('admin.index') }}">Dashboard</a>
        <span>/</span>
        <span>Detail Pembayaran #{{ $payment->id }}</span>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">⚠️ {!! session('warning') !!}</div>
    @endif

    <div class="detail-grid">

        {{-- Kolom Kiri: Info Pembayaran + Aksi --}}
        <div>
            {{-- Info Pembayaran --}}
            <div class="card">
                <div class="card-header">
                    <h2>💳 Info Pembayaran #{{ $payment->id }}</h2>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="label">Status</span>
                        <span class="value">
                            <span class="badge-status {{ $payment->status }}">
                                {{ $payment->statusLabel() }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="label">Paket</span>
                        <span class="value">
                            <span class="badge-plan {{ $payment->plan }}">
                                {{ $payment->plan === 'personal' ? '🔵' : '🟣' }}
                                {{ $payment->planLabel() }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="label">Nominal</span>
                        <span class="value" style="color:var(--primary);font-size:1.1rem;">{{ $payment->nominalFormatted() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Metode</span>
                        <span class="value">{{ $payment->metode === 'qris' ? '📱 QRIS' : '🏦 Transfer Bank' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Dikirim</span>
                        <span class="value">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($payment->confirmed_at)
                    <div class="info-row">
                        <span class="label">Diproses</span>
                        <span class="value">{{ $payment->confirmed_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($payment->catatan_admin)
                    <div class="info-row">
                        <span class="label">Catatan Admin</span>
                        <span class="value" style="color:var(--danger);">{{ $payment->catatan_admin }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Info User --}}
            <div class="card">
                <div class="card-header">
                    <h2>👤 Info User</h2>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="label">Nama</span>
                        <span class="value">{{ $payment->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email</span>
                        <span class="value">{{ $payment->user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Plan Saat Ini</span>
                        <span class="value">{{ $payment->user->planLabel() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Status Akun</span>
                        <span class="value" style="text-transform:capitalize;">{{ $payment->user->status }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Daftar</span>
                        <span class="value">{{ $payment->user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Aksi —tampilkan hanya jika masih pending --}}
            @if($payment->isPending())
            <div class="card">
                <div class="card-header">
                    <h2>⚡ Tindakan</h2>
                </div>
                <div class="card-body">
                    <p style="font-size:0.875rem;color:var(--gray-2);margin-bottom:1rem;">
                        Pastikan bukti transfer sudah sesuai sebelum mengkonfirmasi.
                        Setelah dikonfirmasi, akun user akan langsung aktif dengan plan baru mereka.
                    </p>
                    <div class="action-buttons">
                        {{-- Konfirmasi --}}
                        <form method="POST" action="{{ route('admin.payment.confirm', $payment->id) }}"
                              onsubmit="return confirm('Konfirmasi pembayaran ini dan aktifkan plan user?')">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                ✅ Konfirmasi & Aktifkan Plan
                            </button>
                        </form>

                        {{-- Tolak --}}
                        <button type="button" class="btn btn-danger" onclick="showRejectModal()">
                            ❌ Tolak Pembayaran
                        </button>

                        <a href="{{ route('admin.index') }}" class="btn btn-ghost">← Kembali</a>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body">
                    <div class="processed-banner {{ $payment->isRejected() ? 'rejected' : '' }}">
                        <div class="icon">{{ $payment->isConfirmed() ? '✅' : '❌' }}</div>
                        <h3>{{ $payment->isConfirmed() ? 'Sudah Dikonfirmasi' : 'Sudah Ditolak' }}</h3>
                        <p style="font-size:0.85rem;margin-top:0.25rem;">
                            {{ $payment->confirmed_at?->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div style="margin-top:1rem;">
                        <a href="{{ route('admin.index') }}" class="btn btn-ghost">← Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Kolom Kanan: Bukti Transfer --}}
        <div>
            <div class="card">
                <div class="card-header">
                    <h2>🖼️ Bukti Pembayaran</h2>
                </div>
                <div class="card-body">
                    @if($payment->bukti_path)
                        <img src="{{ Storage::url($payment->bukti_path) }}"
                             alt="Bukti pembayaran"
                             class="bukti-img"
                             onclick="window.open(this.src,'_blank')">
                        <p style="font-size:0.75rem;color:var(--gray);margin-top:0.5rem;text-align:center;">
                            Klik gambar untuk memperbesar
                        </p>
                    @else
                        <div style="text-align:center;padding:2rem;color:var(--gray);">
                            <div style="font-size:3rem;">📭</div>
                            <p style="margin-top:0.5rem;font-size:0.875rem;">Tidak ada bukti diupload.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</main>

{{-- Modal Tolak --}}
<div class="modal-backdrop" id="rejectModal">
    <div class="modal">
        <h3>❌ Tolak Pembayaran</h3>
        <p>Tuliskan alasan penolakan. Alasan ini akan dikirimkan ke email user.</p>
        <form method="POST" action="{{ route('admin.payment.reject', $payment->id) }}">
            @csrf
            <textarea name="catatan_admin"
                placeholder="Contoh: Nominal tidak sesuai. Mohon transfer ulang sesuai nominal yang tertera."
                rows="4"></textarea>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="hideRejectModal()">Batal</button>
                <button type="submit" class="btn btn-danger">❌ Tolak Pembayaran</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() { document.getElementById('rejectModal').classList.add('show'); }
function hideRejectModal() { document.getElementById('rejectModal').classList.remove('show'); }
// Tutup modal saat klik backdrop
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) hideRejectModal();
});
</script>

</body>
</html>

