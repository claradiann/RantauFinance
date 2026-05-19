<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran — RantauFinance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        /* ===== Status page overrides ===== */
        .status-form-panel {
            overflow-y: auto;
        }
        .status-form-panel .auth-form-wrapper {
            max-width: 480px;
        }

        /* Status icon */
        .status-icon-wrap {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1.25rem;
            animation: popIn 0.5s ease forwards;
        }
        @keyframes popIn {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .status-icon-wrap.pending   { background: #fef3c7; }
        .status-icon-wrap.confirmed { background: #d1fae5; }
        .status-icon-wrap.rejected  { background: #fee2e2; }

        .status-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .status-desc {
            font-size: 0.88rem;
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: 2rem;
            text-align: center;
        }
        .status-desc strong { color: var(--dark-2); }

        /* Info box */
        .info-box {
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.55rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            font-size: 0.85rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: var(--gray); }
        .info-row .value { font-weight: 600; color: var(--dark); }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            border-radius: 99px;
            font-size: 0.72rem;
            font-weight: 700;
        }
        .badge-orange { background: #fef3c7; color: #d97706; }
        .badge-green  { background: #d1fae5; color: #059669; }
        .badge-red    { background: #fee2e2; color: #dc2626; }

        /* Bukti preview */
        .bukti-preview {
            margin-top: 1rem;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
        }
        .bukti-preview img {
            width: 100%;
            max-height: 350px;
            object-fit: contain;
            background: #f8fafc;
            display: block;
            cursor: pointer;
        }

        /* Buttons */
        .btn-action {
            display: block;
            width: 100%;
            padding: 0.9rem;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            margin-bottom: 0.75rem;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(99,102,241,0.4);
        }
        .btn-action-outline {
            display: block;
            width: 100%;
            padding: 0.9rem;
            border: 2px solid var(--border);
            color: var(--gray);
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.25s;
            font-family: 'Inter', sans-serif;
            background: transparent;
        }
        .btn-action-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Catatan admin */
        .catatan-box {
            background: linear-gradient(135deg, #fef3c7, #fef9c3);
            border: 1px solid #fbbf24;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            text-align: left;
            font-size: 0.85rem;
            color: #92400e;
            margin-bottom: 1.25rem;
            line-height: 1.6;
        }
        .catatan-box strong { display: block; margin-bottom: 0.35rem; }

        /* Refresh note */
        .refresh-note {
            font-size: 0.75rem;
            color: var(--gray);
            margin-top: 1.5rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .refresh-note .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--success);
            animation: pulse 2s ease infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Brand content adjustments for status context */
        .status-brand .brand-content h2 {
            font-size: 1.6rem;
        }
    </style>
</head>
<body>

<div class="auth-container">
    {{-- Left: Branding --}}
    <div class="auth-brand status-brand">
        <div class="brand-content">
            <div class="brand-logo">
                <img src="{{ asset('images/logo_RD.png') }}" style="height: 54px; margin-right: 8px;"> RantauFinance
            </div>
            @if(!$payment)
                <h2>Belum ada pembayaran ditemukan</h2>
                <p>Upload bukti pembayaran untuk melanjutkan proses aktivasi akun premiummu.</p>
            @elseif($payment->isPending())
                <h2>Pembayaranmu sedang diproses</h2>
                <p>Tim kami sedang memverifikasi bukti pembayaran. Akun kamu akan diaktifkan segera setelah pembayaran dikonfirmasi.</p>
            @elseif($payment->isConfirmed())
                <h2>Selamat! Akunmu sudah aktif 🎉</h2>
                <p>Kamu sekarang memiliki akses penuh ke semua fitur premium Rantau Finance. Mulai kelola keuanganmu sekarang!</p>
            @elseif($payment->isRejected())
                <h2>Maaf, pembayaran ditolak</h2>
                <p>Silakan periksa kembali dan upload ulang bukti pembayaran yang valid.</p>
            @endif

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="feat-icon">📊</div>
                    Dashboard real-time & analitik
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">🎯</div>
                    Budget planner otomatis
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">🔒</div>
                    Data terenkripsi & aman
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">📱</div>
                    Akses dari mana saja
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Status Content --}}
    <div class="auth-form-panel status-form-panel">
        <div class="auth-form-wrapper">
            <div class="auth-form-header" style="text-align: center;">
                <div class="mobile-logo" style="display:flex;align-items:center;justify-content:center;gap:8px;"><img src="{{ asset('images/logo_RD.png') }}" style="height: 54px;"> RantauFinance</div>
            </div>

            @if(!$payment)
                {{-- Belum ada payment --}}
                <div class="status-icon-wrap" style="background:#f1f5f9;">⚠️</div>
                <div class="status-title">Belum Ada Pembayaran</div>
                <div class="status-desc">
                    Kamu belum mengupload bukti pembayaran.
                </div>
                <a href="{{ route('payment.show') }}" class="btn-action">Upload Bukti Sekarang →</a>

            @elseif($payment->isPending())
                {{-- Pending --}}
                <div class="status-icon-wrap pending">⏳</div>
                <div class="status-title">Menunggu Konfirmasi</div>
                <div class="status-desc">
                    Bukti pembayaran kamu sudah kami terima.<br>
                    Admin akan memverifikasi pembayaran kamu. Akun kamu akan aktif sepenuhnya dalam <strong>1×24 jam</strong>.
                </div>

                <div class="info-box">
                    <div class="info-row">
                        <span class="label">Paket</span>
                        <span class="value">{{ $payment->planLabel() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Nominal</span>
                        <span class="value">{{ $payment->nominalFormatted() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Metode</span>
                        <span class="value">{{ strtoupper($payment->metode) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Dikirim</span>
                        <span class="value">{{ $payment->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Status</span>
                        <span class="badge badge-orange">⏳ Menunggu</span>
                    </div>

                    @if($payment->bukti_path)
                        <div class="bukti-preview">
                            <img src="{{ Storage::url($payment->bukti_path) }}" alt="Bukti pembayaran" onclick="window.open(this.src,'_blank')" title="Klik untuk memperbesar">
                        </div>
                        <p style="font-size:0.7rem;color:var(--gray);text-align:center;margin-top:0.35rem;margin-bottom:0;">
                            Klik gambar untuk memperbesar
                        </p>
                    @endif
                </div>

                <p style="font-size:0.85rem;color:var(--gray);margin-bottom:1.25rem;text-align:center;">
                    Akun aktif dengan email: <strong style="color:var(--dark-2);">{{ $user->email }}</strong>
                </p>

                <a href="/dashboard" class="btn-action">Lanjutkan ke Dashboard →</a>
                <p style="font-size:0.75rem;color:var(--gray);text-align:center;margin-bottom:1.5rem;">
                    Sambil menunggu, kamu bisa mulai menggunakan RantauFinance dengan akses <strong>Starter</strong> secara gratis.
                </p>

                <div class="refresh-note">
                    <div class="dot"></div>
                    Halaman ini akan otomatis refresh setiap 60 detik
                </div>

                <script>setTimeout(() => location.reload(), 60000);</script>

            @elseif($payment->isConfirmed())
                {{-- Confirmed --}}
                <div class="status-icon-wrap confirmed">✅</div>
                <div class="status-title">Pembayaran Dikonfirmasi!</div>
                <div class="status-desc">
                    Selamat! Paket <strong>{{ $payment->planLabel() }}</strong> kamu sudah aktif.<br>
                    Silakan masuk menggunakan email <strong>{{ $user->email }}</strong> dan password pendaftaran kamu.
                </div>

                <div class="info-box">
                    <div class="info-row">
                        <span class="label">Paket</span>
                        <span class="value">{{ $payment->planLabel() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Dikonfirmasi</span>
                        <span class="value">{{ $payment->confirmed_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Status</span>
                        <span class="badge badge-green">✅ Aktif</span>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="btn-action">Login Sekarang →</a>
                <p style="font-size:0.8rem;color:var(--gray);text-align:center;">
                    Belum dapat email? <a href="mailto:admin@rantaufinance.com" style="color:var(--primary);font-weight:600;text-decoration:none;">Hubungi kami</a>
                </p>

            @elseif($payment->isRejected())
                {{-- Rejected --}}
                <div class="status-icon-wrap rejected">❌</div>
                <div class="status-title">Pembayaran Ditolak</div>
                <div class="status-desc">
                    Maaf, bukti pembayaran kamu tidak dapat dikonfirmasi.
                    Silakan upload ulang bukti yang valid.
                </div>

                @if($payment->catatan_admin)
                    <div class="catatan-box">
                        <strong>📝 Catatan Admin:</strong>
                        {{ $payment->catatan_admin }}
                    </div>
                @endif

                <a href="{{ route('payment.show') }}" class="btn-action">Upload Ulang →</a>
                <a href="mailto:admin@rantaufinance.com" class="btn-action-outline">Hubungi Admin</a>

            @endif

            <div class="auth-footer">
                @if(Auth::check())
                    <a href="/dashboard">← Kembali ke Dashboard</a>
                @else
                    <a href="/">← Kembali ke Beranda</a>
                @endif
            </div>
        </div>
    </div>
</div>

</body>
</html>
