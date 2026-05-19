<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Baru — RantauFinance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9; color: #1e293b; line-height: 1.6;
        }
        .wrapper { max-width: 600px; margin: 40px auto; padding: 0 16px; }

        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            border-radius: 20px 20px 0 0; padding: 2.5rem 2rem; text-align: center; color: white;
        }
        .header .emoji { font-size: 3rem; margin-bottom: 0.75rem; display: block; }
        .header h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.25rem; }
        .header p { opacity: 0.85; font-size: 0.95rem; }

        .body {
            background: white; padding: 2rem;
            border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;
        }

        .greeting { font-size: 1rem; margin-bottom: 1rem; color: #1e293b; }
        p { font-size: 0.9rem; color: #475569; margin-bottom: 1rem; }

        .password-box {
            background: linear-gradient(135deg, #fef3c7, #fff7ed);
            border: 2px solid #fde68a; border-radius: 16px;
            padding: 1.5rem; text-align: center; margin: 1.5rem 0;
        }
        .password-box .label {
            font-size: 0.78rem; font-weight: 700; color: #92400e;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;
        }
        .password-box .password {
            font-size: 2rem; font-weight: 900; color: #d97706;
            letter-spacing: 4px; font-family: 'Courier New', monospace;
        }
        .password-box .note {
            font-size: 0.78rem; color: #a16207; margin-top: 0.5rem;
        }

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

        .warning-box {
            background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px;
            padding: 1rem 1.25rem; font-size: 0.82rem; color: #991b1b; margin-bottom: 1.25rem;
        }
        .warning-box strong { font-weight: 700; }

        .steps { margin: 1.25rem 0; }
        .step-item {
            display: flex; align-items: flex-start; gap: 0.75rem;
            margin-bottom: 0.75rem; font-size: 0.875rem; color: #475569;
        }
        .step-num {
            width: 24px; height: 24px; border-radius: 50%; background: #f59e0b; color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 800; flex-shrink: 0; margin-top: 1px;
        }

        .cta-wrap { text-align: center; margin: 1.5rem 0; }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: white; text-decoration: none;
            padding: 0.9rem 2rem; border-radius: 12px;
            font-weight: 700; font-size: 1rem;
        }

        .footer {
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 0 0 20px 20px; padding: 1.5rem 2rem; text-align: center;
        }
        .footer p { font-size: 0.78rem; color: #94a3b8; margin: 0; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <span class="emoji">🔑</span>
        <h1>Password Kamu Direset</h1>
        <p>Berikut password baru untuk akun RantauFinance kamu.</p>
    </div>

    <div class="body">
        <p class="greeting">Halo, <strong>{{ $user->name }}</strong>! 👋</p>

        <p>
            Password akun RantauFinance kamu baru saja direset oleh admin.
            Berikut password baru kamu — simpan baik-baik ya!
        </p>

        <div class="password-box">
            <div class="label">Password Baru Kamu</div>
            <div class="password">{{ $plainPassword }}</div>
            <div class="note">Salin password ini sebelum login</div>
        </div>

        <div class="warning-box">
            ⚠️ <strong>Penting:</strong> Segera ganti password setelah login pertama.
            Jangan bagikan password ini kepada siapapun.
        </div>

        <div class="info-card">
            <div class="info-row">
                <span class="key">Email Login</span>
                <span class="val">{{ $user->email }}</span>
            </div>
            <div class="info-row">
                <span class="key">Password Baru</span>
                <span class="val" style="color:#d97706;">{{ $plainPassword }}</span>
            </div>
            <div class="info-row">
                <span class="key">Paket</span>
                <span class="val">{{ $user->planLabel() }}</span>
            </div>
        </div>

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
                <span>Masukkan password baru di atas</span>
            </div>
            <div class="step-item">
                <span class="step-num">4</span>
                <span>Segera ganti password di menu <strong>Profil → Ubah Password</strong></span>
            </div>
        </div>

        <div class="cta-wrap">
            <a href="{{ url('/login') }}" class="cta-btn">🚀 Login Sekarang</a>
        </div>

        <p style="font-size:0.82rem;color:#94a3b8;">
            Tidak merasa melakukan reset password? Hubungi admin segera.
        </p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} RantauFinance · Email ini dikirim otomatis.</p>
        <p style="margin-top:0.25rem;">
            Jika kamu tidak merasa mendaftar, abaikan email ini.
        </p>
    </div>

</div>
</body>
</html>

