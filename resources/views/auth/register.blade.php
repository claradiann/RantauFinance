<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — RantauFinance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        /* ===== Register-specific: Plan Selector ===== */
        .plan-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 16px;
        }
        .plan-card {
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            background: var(--white);
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .plan-card:hover {
            border-color: var(--primary-light);
            background: #fafaff;
        }
        .plan-card.selected {
            border-color: var(--primary);
            background: linear-gradient(135deg, rgba(99,102,241,0.04) 0%, rgba(6,182,212,0.04) 100%);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
        }
        .plan-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Radio visual indicator */
        .plan-radio {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--border);
            flex-shrink: 0;
            margin-top: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }
        .plan-card.selected .plan-radio {
            border-color: var(--primary);
            background: var(--primary);
        }
        .plan-radio::after {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: white;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .plan-card.selected .plan-radio::after {
            opacity: 1;
        }

        .plan-info { flex: 1; min-width: 0; }
        .plan-top-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .plan-badge {
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 99px;
            display: inline-block;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .badge-gray   { background: #f1f5f9; color: #64748b; }
        .badge-blue   { background: #dbeafe; color: #2563eb; }
        .badge-purple { background: #ede9fe; color: #7c3aed; }

        .plan-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--dark);
        }
        .plan-price {
            font-size: 0.8rem;
            color: var(--gray);
            margin-bottom: 6px;
        }
        .plan-features {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 2px 12px;
        }
        .plan-features li {
            font-size: 0.75rem;
            color: #475569;
            padding: 1px 0;
            white-space: nowrap;
        }
        .plan-features li::before {
            content: "✓ ";
            color: var(--primary);
            font-weight: 700;
        }

        /* Payment note */
        .payment-note {
            background: linear-gradient(135deg, #fef3c7, #fef9c3);
            border: 1px solid #fbbf24;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.8rem;
            color: #92400e;
            margin-top: 4px;
            display: none;
            line-height: 1.5;
        }
        .payment-note.show { display: block; }

        /* Form row for inline fields */
        .form-row-inline {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        @media (max-width: 480px) {
            .form-row-inline { grid-template-columns: 1fr; }
        }

        /* Stepper indicator */
        .register-steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 2rem;
        }
        .step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            transition: all 0.3s;
            flex-shrink: 0;
        }
        .step-dot.active {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        .step-dot.inactive {
            background: var(--border);
            color: var(--gray);
        }
        .step-line {
            flex: 1;
            height: 2px;
            background: var(--border);
        }
        .step-line.active {
            background: var(--gradient-1);
        }

        /* Register brand features — compact */
        .register-brand .brand-feature {
            font-size: 0.85rem;
            padding: 0.7rem 1rem;
        }

        /* Override auth-form-wrapper max-width for register */
        .register-form-panel .auth-form-wrapper {
            max-width: 480px;
        }

        /* Scrollable form panel */
        .register-form-panel {
            overflow-y: auto;
        }
    </style>
</head>
<body>

<div class="auth-container">
    {{-- Left: Branding --}}
    <div class="auth-brand register-brand">
        <div class="brand-content">
            <div class="brand-logo">
                <span>💰</span> RantauFinance
            </div>
            <h2>Mulai kelola keuanganmu sekarang</h2>
            <p>Buat akun gratis dan akses semua fitur dasar untuk mengelola keuangan bulananmu dengan lebih cerdas.</p>

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="feat-icon">🚀</div>
                    Daftar gratis dalam 30 detik
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">📊</div>
                    Dashboard & laporan real-time
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">🎯</div>
                    Budget planner cerdas
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">🔒</div>
                    Data terenkripsi & aman
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Form --}}
    <div class="auth-form-panel register-form-panel">
        <div class="auth-form-wrapper">
            <div class="auth-form-header">
                <div class="mobile-logo">💰 RantauFinance</div>
                <h1>Buat Akun Baru ✨</h1>
                <p>Pilih paket yang sesuai dan mulai perjalanan finansialmu</p>
            </div>

            {{-- Step Indicator --}}
            <div class="register-steps">
                <div class="step-dot active">1</div>
                <div class="step-line active"></div>
                <div class="step-dot active">2</div>
                <div class="step-line"></div>
                <div class="step-dot inactive">3</div>
            </div>

            @if(session('info'))
                <div class="alert-success">
                    ✅ {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    ⚠️ {{ $errors->first() }}
                </div>
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
                            <div class="plan-radio"></div>
                            <div class="plan-info">
                                <div class="plan-top-row">
                                    <span class="plan-name">Starter</span>
                                    <span class="plan-badge badge-gray">GRATIS</span>
                                </div>
                                <div class="plan-price">Rp 0 — Selamanya gratis</div>
                                <ul class="plan-features">
                                    <li>Input transaksi (maks 30/bln)</li>
                                    <li>Kategori dasar</li>
                                    <li>Laporan bulanan</li>
                                    <li>Export CSV & PDF</li>
                                </ul>
                            </div>
                        </label>

                        {{-- PERSONAL --}}
                        <label class="plan-card {{ old('plan') === 'personal' ? 'selected' : '' }}" id="card-personal">
                            <input type="radio" name="plan" value="personal"
                                   {{ old('plan') === 'personal' ? 'checked' : '' }}
                                   onchange="selectPlan('personal')">
                            <div class="plan-radio"></div>
                            <div class="plan-info">
                                <div class="plan-top-row">
                                    <span class="plan-name">Personal</span>
                                    <span class="plan-badge badge-blue">POPULER</span>
                                </div>
                                <div class="plan-price">Rp 29.000/bulan</div>
                                <ul class="plan-features">
                                    <li>Transaksi unlimited</li>
                                    <li>Dashboard & grafik</li>
                                    <li>Budget planner</li>
                                    <li>Notifikasi in-app</li>
                                </ul>
                            </div>
                        </label>

                        {{-- PROFESIONAL --}}
                        <label class="plan-card {{ old('plan') === 'profesional' ? 'selected' : '' }}" id="card-profesional">
                            <input type="radio" name="plan" value="profesional"
                                   {{ old('plan') === 'profesional' ? 'checked' : '' }}
                                   onchange="selectPlan('profesional')">
                            <div class="plan-radio"></div>
                            <div class="plan-info">
                                <div class="plan-top-row">
                                    <span class="plan-name">Profesional</span>
                                    <span class="plan-badge badge-purple">PRO</span>
                                </div>
                                <div class="plan-price">Rp 35.000/bulan</div>
                                <ul class="plan-features">
                                    <li>Semua fitur Personal</li>
                                    <li>Laporan tahunan</li>
                                    <li>Analisis per kategori</li>
                                    <li>Notif email & Telegram</li>
                                </ul>
                            </div>
                        </label>
                    </div>

                    @error('plan')
                        <p class="form-error" style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</p>
                    @enderror

                    <div class="payment-note {{ in_array(old('plan'), ['personal', 'profesional']) ? 'show' : '' }}" id="payment-note">
                        ⚠️ Setelah daftar, kamu akan diarahkan ke halaman pembayaran. Password login akan dikirim ke email kamu setelah pembayaran dikonfirmasi.
                    </div>
                </div>

                {{-- NAMA --}}
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">👤</span>
                        <input id="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                               type="text" name="name" value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap" required autofocus autocomplete="name">
                    </div>
                    @error('name')
                        <p style="color: var(--danger); font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">📧</span>
                        <input id="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                               type="email" name="email" value="{{ old('email') }}"
                               placeholder="nama@email.com" required autocomplete="username">
                    </div>
                    @error('email')
                        <p style="color: var(--danger); font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PASSWORD FIELDS INLINE --}}
                <div class="form-row-inline">
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-icon-wrapper">
                            <span class="input-icon">🔒</span>
                            <input id="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                                   type="password" name="password"
                                   placeholder="Min. 8 karakter" required autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePw('password')">👁️</button>
                        </div>
                        @error('password')
                            <p style="color: var(--danger); font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi</label>
                        <div class="input-icon-wrapper">
                            <span class="input-icon">🔒</span>
                            <input id="password_confirmation" class="form-input"
                                   type="password" name="password_confirmation"
                                   placeholder="Ulangi password" required autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePw('password_confirmation')">👁️</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit" style="margin-top: 0.5rem;">
                    Daftar Sekarang →
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<script>
function selectPlan(plan) {
    // Reset semua card
    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));

    // Aktifkan card yang dipilih
    document.getElementById('card-' + plan).classList.add('selected');

    // Update step indicator
    const steps = document.querySelectorAll('.step-dot');
    const lines = document.querySelectorAll('.step-line');
    // Step 3 becomes active when paid plan selected
    if (plan === 'personal' || plan === 'profesional') {
        steps[2].classList.remove('inactive');
        steps[2].classList.add('active');
        lines[1].classList.add('active');
    } else {
        steps[2].classList.remove('active');
        steps[2].classList.add('inactive');
        lines[1].classList.remove('active');
    }

    // Tampilkan catatan payment kalau pilih berbayar
    const note = document.getElementById('payment-note');
    if (plan === 'personal' || plan === 'profesional') {
        note.classList.add('show');
    } else {
        note.classList.remove('show');
    }
}

function togglePw(id) {
    const pw = document.getElementById(id);
    pw.type = pw.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>