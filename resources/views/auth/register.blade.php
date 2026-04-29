<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — RantauFinance</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        /* Plan Selector */
        .plan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        @media (max-width: 640px) {
            .plan-grid { grid-template-columns: 1fr; }
        }
        .plan-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px 12px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            background: #fff;
        }
        .plan-card:hover { border-color: #6366f1; }
        .plan-card.selected {
            border-color: #6366f1;
            background: #f5f3ff;
        }
        .plan-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
        }
        .plan-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 99px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-purple { background: #ede9fe; color: #7c3aed; }
        .plan-name    { font-weight: 700; font-size: 15px; margin-bottom: 2px; }
        .plan-price   { font-size: 13px; color: #6b7280; margin-bottom: 10px; }
        .plan-features { list-style: none; padding: 0; margin: 0; }
        .plan-features li {
            font-size: 12px;
            color: #374151;
            padding: 2px 0;
        }
        .plan-features li::before { content: "✓ "; color: #6366f1; font-weight: 700; }
        .plan-card.selected .check-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: #6366f1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
        }
        .check-icon { display: none; }
        .plan-card.selected .check-icon { display: flex; }

        /* Password note untuk plan berbayar */
        .payment-note {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #92400e;
            margin-top: 8px;
            display: none;
        }
        .payment-note.show { display: block; }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card" style="max-width: 600px;">
        <div class="auth-logo">
            <a href="/">💰 RantauFinance</a>
        </div>

        <h2 class="auth-title">Buat Akun Baru</h2>
        <p class="auth-subtitle">Pilih paket yang sesuai kebutuhanmu</p>

        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- PILIH PAKET --}}
            <div class="form-group">
                <label class="form-label">Pilih Paket</label>

                <div class="plan-grid">
                    {{-- STARTER --}}
                    <label class="plan-card {{ old('plan', 'starter') === 'starter' ? 'selected' : '' }}" id="card-starter">
                        <input type="radio" name="plan" value="starter"
                               {{ old('plan', 'starter') === 'starter' ? 'checked' : '' }}
                               onchange="selectPlan('starter')">
                        <div class="check-icon">✓</div>
                        <span class="plan-badge badge-gray">STARTER</span>
                        <div class="plan-name">Starter</div>
                        <div class="plan-price">Gratis</div>
                        <ul class="plan-features">
                            <li>Input transaksi (maks 30/bln)</li>
                            <li>Kategori dasar</li>
                            <li>Laporan bulanan simpel</li>
                            <li>Riwayat transaksi</li>
                            <li>Export CSV & PDF</li>
                        </ul>
                    </label>

                    {{-- PERSONAL --}}
                    <label class="plan-card {{ old('plan') === 'personal' ? 'selected' : '' }}" id="card-personal">
                        <input type="radio" name="plan" value="personal"
                               {{ old('plan') === 'personal' ? 'checked' : '' }}
                               onchange="selectPlan('personal')">
                        <div class="check-icon">✓</div>
                        <span class="plan-badge badge-blue">PERSONAL</span>
                        <div class="plan-name">Personal</div>
                        <div class="plan-price">Rp 29.000/bulan</div>
                        <ul class="plan-features">
                            <li>Input transaksi unlimited</li>
                            <li>Dashboard & grafik</li>
                            <li>Filter & cari transaksi</li>
                            <li>Budget planner</li>
                            <li>Notifikasi in-app</li>
                        </ul>
                    </label>

                    {{-- PROFESIONAL --}}
                    <label class="plan-card {{ old('plan') === 'profesional' ? 'selected' : '' }}" id="card-profesional">
                        <input type="radio" name="plan" value="profesional"
                               {{ old('plan') === 'profesional' ? 'checked' : '' }}
                               onchange="selectPlan('profesional')">
                        <div class="check-icon">✓</div>
                        <span class="plan-badge badge-purple">PRO</span>
                        <div class="plan-name">Profesional</div>
                        <div class="plan-price">Rp 35.000/bulan</div>
                        <ul class="plan-features">
                            <li>Semua fitur Personal</li>
                            <li>Laporan tahunan</li>
                            <li>Analisis per kategori</li>
                            <li>Kategori custom unlimited</li>
                            <li>Notif email & Telegram</li>
                        </ul>
                    </label>
                </div>

                @error('plan')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                <div class="payment-note {{ in_array(old('plan'), ['personal', 'profesional']) ? 'show' : '' }}" id="payment-note">
                    ⚠️ Setelah daftar, kamu akan diarahkan ke halaman pembayaran. Password login akan dikirim ke email kamu setelah pembayaran dikonfirmasi.
                </div>
            </div>

            {{-- NAMA --}}
            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap</label>
                <input id="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       type="text" name="name" value="{{ old('name') }}"
                       placeholder="Masukkan nama lengkap" required autofocus autocomplete="name">
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       type="email" name="email" value="{{ old('email') }}"
                       placeholder="nama@email.com" required autocomplete="username">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input id="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                       type="password" name="password"
                       placeholder="Minimal 8 karakter" required autocomplete="new-password">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" class="form-input"
                       type="password" name="password_confirmation"
                       placeholder="Ulangi password" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn-auth">
                Daftar Sekarang →
            </button>

            <p class="auth-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk di sini</a>
            </p>
        </form>
    </div>
</div>

<script>
function selectPlan(plan) {
    // Reset semua card
    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));

    // Aktifkan card yang dipilih
    document.getElementById('card-' + plan).classList.add('selected');

    // Tampilkan catatan payment kalau pilih berbayar
    const note = document.getElementById('payment-note');
    if (plan === 'personal' || plan === 'profesional') {
        note.classList.add('show');
    } else {
        note.classList.remove('show');
    }
}
</script>

</body>
</html>