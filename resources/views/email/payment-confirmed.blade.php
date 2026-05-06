<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($is_reset) && $is_reset ? 'Password Baru' : 'Pembayaran Dikonfirmasi' }} — RantauFinance</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9; color: #1e293b; line-height: 1.6;
        }
        .wrapper { max-width: 600px; margin: 40px auto; padding: 0 16px; }

        /* Header */
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
            border-radius: 20px 20px 0 0; padding: 2.5rem 2rem; text-align: center; color: white;
        }
        .header .emoji { font-size: 3rem; margin-bottom: 0.75rem; display: block; }
        .header h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.25rem; }
        .header p { opacity: 0.85; font-size: 0.95rem; }

        /* Body */
        .body {
            background: white; padding: 2rem; border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
        }

        .greeting { font-size: 1rem; margin-bottom: 1rem; color: #1e293b; }

        p { font-size: 0.9rem; color: #475569; margin-bottom: 1rem; }

        /* Password box */
        .password-box {
            background: linear-gradient(135deg, #f5f3ff, #eff6ff);
            border: 2px solid #c7d2fe; border-radius: 16px;
            padding: 1.5rem; text-align: center; margin: 1.5rem 0;
        }
        .password-box .label {
            font-size: 0.78rem; font-weight: 700; color: #6b7280;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;
        }
        .password-box .password {
            font-size: 2rem; font-weight: 900; color: #6366f1;
            letter-spacing: 4px; font-family: 'Courier New', monospace;
        }
        .password-box .note {
            font-size: 0.78rem; color: #94a3b8; margin-top: 0.5rem;
        }

        /* Info card */
        .info-card {
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;
            padding: 1.25rem; margin-bottom: 1.25rem;
        }
        .info-row {
            display: flex; justify-content: space-between;
            padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .key { color: #64748b; }
        .info-row .val { font-weight: 700; color: #1e293b; }

        /* CTA Button */
        .cta-wrap { text-align: center; margin: 1.5rem 0; }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: white; text-decoration: none;
            padding: 0.9rem 2rem; border-radius: 12px;
            font-weight: 700; font-size: 1rem;
        }

        /* Warning box */
        .warning-box {
            background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px;
            padding: 1rem 1.25rem; font-size: 0.82rem; color: #92400e; margin-bottom: 1.25rem;
        }
        .warning-box strong { font-weight: 700; }

        /* Steps */
        .steps { margin: 1.25rem 0; }
        .step-item {
            display: flex; align-items: flex-start; gap: 0.75rem;
            margin-bottom: 0.75rem; font-size: 0.875rem; color: #475569;
        }
        .step-num {
            width: 24px; height: 24px; border-radius: 50%; background: #6366f1; color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 800; flex-shrink: 0; margin-top: 1px;
        }

        /* Footer */
        .footer {
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 0 0 20px 20px; padding: 1.5rem 2rem; text-align: center;
        }
        .footer p { font-size: 0.78rem; color: #94a3b8; margin: 0; }
        .footer a { color: #6366f1; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <span class="emoji">{{ isset($is_reset) && $is_reset ? '🔑' : '🎉' }}</span>
        <h1>
            @if(isset($is_reset) && $is_reset)
                Password Kamu Direset
            @else
                Pembayaran Dikonfirmasi!
            @endif
        </h1>
        <p>
            @if(isset($is_reset) && $is_reset)
                Berikut password baru untuk akun RantauFinance kamu.
            @else
                Selamat datang di RantauFinance {{ $user->planLabel() }}!
            @endif
        </p>
    </div>

    {{-- Body --}}
    <div class="body">
        <p class="greeting">Halo, <strong>{{ $user->name }}</strong>! 👋</p>

        @if(isset($is_reset) && $is_reset)
            <p>
                Password akun RantauFinance kamu baru saja direset oleh admin.
                Berikut password baru kamu — simpan baik-baik ya!
            </p>

            {{-- Password Box --}}
            <div class="password-box">
                <div class="label">Password Baru Kamu</div>
                <div class="password">{{ $plainPassword }}</div>
                <div class="note">Salin password ini sebelum login</div>
            </div>

            {{-- Warning --}}
            <div class="warning-box">
                ⚠️ <strong>Penting:</strong> Segera ganti password setelah login pertama.
                Jangan bagikan password ini kepada siapapun.
            </div>
        @else
            <p>
                Pembayaran untuk paket <strong>{{ $user->planLabel() }}</strong> sudah kami verifikasi dan
                akun kamu sekarang sudah aktif.
            </p>

            <div class="password-box" style="background: #f0fdf4; border-color: #bbf7d0;">
                <div class="label" style="color: #166534;">Status Akun</div>
                <div class="password" style="font-size: 1.5rem; color: #15803d; letter-spacing: 1px; font-family: inherit;">✅ AKTIF</div>
                <div class="note" style="color: #166534;">Gunakan password yang kamu buat saat mendaftar</div>
            </div>
        @endif

        {{-- Info Akun --}}
        <div class="info-card">
            <div class="info-row">
                <span class="key">Email Login</span>
                <span class="val">{{ $user->email }}</span>
            </div>
            @if(isset($is_reset) && $is_reset)
            <div class="info-row">
                <span class="key">Password Baru</span>
                <span class="val" style="color:#6366f1;">{{ $plainPassword }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="key">Paket</span>
                <span class="val">{{ $user->planLabel() }}</span>
            </div>
            @if($user->plan_expires_at)
            <div class="info-row">
                <span class="key">Berlaku Sampai</span>
                <span class="val">{{ $user->plan_expires_at->format('d M Y') }}</span>
            </div>
            @endif
        </div>

        {{-- Langkah Login --}}
        <p style="font-weight:700;color:#1e293b;margin-bottom:0.75rem;">Cara Login:</p>
        <div class="steps">
            <div class="step-item">
                <span class="step-num">1</span>
                <span>Kunjungi halaman login RantauFinance</span>
            </div>
            <div class="step-item">
                <span class="step-num">2</span>
                <span>Masukkan email <strong>{{ $user->email }}</strong></span>
            </div>
            <div class="step-item">
                <span class="step-num">3</span>
                <span>Masukkan password di atas</span>
            </div>
            <div class="step-item">
                <span class="step-num">4</span>
                <span>Ganti password di menu Profil untuk keamanan</span>
            </div>
        </div>

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ url('/login') }}" class="cta-btn">🚀 Login Sekarang</a>
        </div>

        <p style="font-size:0.82rem;color:#94a3b8;">
            Ada masalah atau pertanyaan? Balas email ini atau hubungi admin.
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>© {{ date('Y') }} RantauFinance · Email ini dikirim otomatis, jangan reply.</p>
        <p style="margin-top:0.25rem;">
            Jika kamu tidak merasa mendaftar, abaikan email ini.
        </p>
    </div>

</div>
</body>
</html>
