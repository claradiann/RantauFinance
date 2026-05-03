<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tidak Dapat Diproses — RantauFinance</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9; color: #1e293b; line-height: 1.6;
        }
        .wrapper { max-width: 600px; margin: 40px auto; padding: 0 16px; }

        .header {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
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

        /* Alasan ditolak */
        .reason-box {
            background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px;
            padding: 1.25rem; margin: 1.25rem 0;
        }
        .reason-box .label {
            font-size: 0.75rem; font-weight: 700; color: #dc2626;
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;
        }
        .reason-box .reason-text {
            font-size: 0.9rem; color: #7f1d1d; font-weight: 500;
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

        /* Steps solusi */
        .solution-box {
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;
            padding: 1.25rem; margin-bottom: 1.25rem;
        }
        .solution-box .title {
            font-size: 0.9rem; font-weight: 700; color: #15803d; margin-bottom: 0.75rem;
        }
        .step-item {
            display: flex; align-items: flex-start; gap: 0.75rem;
            margin-bottom: 0.6rem; font-size: 0.875rem; color: #475569;
        }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%; background: #22c55e; color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 800; flex-shrink: 0; margin-top: 1px;
        }

        /* Rekening */
        .rek-box {
            background: #f5f3ff; border: 1px solid #e0e7ff; border-radius: 12px;
            padding: 1.25rem; margin-bottom: 1.25rem; text-align: center;
        }
        .rek-box .rek-title { font-size: 0.78rem; font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem; }
        .rek-box .bank { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.25rem; }
        .rek-box .nomor { font-size: 1.5rem; font-weight: 900; color: #6366f1;
            letter-spacing: 2px; font-family: 'Courier New', monospace; }
        .rek-box .atas-nama { font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; }

        /* CTA */
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
        <span class="emoji">😔</span>
        <h1>Pembayaran Tidak Dapat Diproses</h1>
        <p>Jangan khawatir, kamu bisa kirim ulang bukti pembayaran.</p>
    </div>

    <div class="body">
        <p class="greeting">Halo, <strong>{{ $user->name }}</strong>,</p>

        <p>
            Kami sudah memeriksa bukti pembayaran yang kamu kirimkan, namun sayangnya
            pembayaran untuk paket <strong>{{ $payment->planLabel() }}</strong> tidak dapat kami proses saat ini.
        </p>

        {{-- Alasan --}}
        <div class="reason-box">
            <div class="label">❌ Alasan Penolakan</div>
            <div class="reason-text">{{ $catatan }}</div>
        </div>

        {{-- Detail Pembayaran --}}
        <div class="info-card">
            <div class="info-row">
                <span class="key">Paket</span>
                <span class="val">{{ $payment->planLabel() }}</span>
            </div>
            <div class="info-row">
                <span class="key">Nominal</span>
                <span class="val">{{ $payment->nominalFormatted() }}</span>
            </div>
            <div class="info-row">
                <span class="key">Metode</span>
                <span class="val">{{ $payment->metode === 'qris' ? 'QRIS' : 'Transfer Bank' }}</span>
            </div>
            <div class="info-row">
                <span class="key">Dikirim</span>
                <span class="val">{{ $payment->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        {{-- Solusi --}}
        <div class="solution-box">
            <div class="title">✅ Cara Mengirim Ulang Pembayaran</div>
            <div class="step-item">
                <span class="step-num">1</span>
                <span>Transfer ke rekening di bawah sesuai nominal yang benar</span>
            </div>
            <div class="step-item">
                <span class="step-num">2</span>
                <span>Screenshot bukti transfer yang jelas (nominal, rekening tujuan, tanggal)</span>
            </div>
            <div class="step-item">
                <span class="step-num">3</span>
                <span>Upload ulang melalui halaman pembayaran di bawah</span>
            </div>
            <div class="step-item">
                <span class="step-num">4</span>
                <span>Admin akan memverifikasi dalam 1×24 jam</span>
            </div>
        </div>

        {{-- Rekening --}}
        <div class="rek-box">
            <div class="rek-title">Informasi Rekening Tujuan</div>
            <div class="bank">🏦 BNI (Bank Negara Indonesia)</div>
            <div class="nomor">1782870396</div>
            <div class="atas-nama">a.n. Clara Dian Ajeng Saputri</div>
            <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid #e0e7ff;">
                <span style="font-size:0.85rem;color:#6b7280;">Nominal: </span>
                <span style="font-size:1rem;font-weight:800;color:#6366f1;">{{ $payment->nominalFormatted() }}</span>
            </div>
        </div>

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ route('payment.show', $user->id) }}" class="cta-btn">
                📤 Upload Ulang Bukti Pembayaran
            </a>
        </div>

        <p style="font-size:0.82rem;color:#94a3b8;text-align:center;">
            Butuh bantuan? Balas email ini atau hubungi admin kami.
        </p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} RantauFinance · Email ini dikirim otomatis.</p>
        <p style="margin-top:0.25rem;">Jika kamu tidak merasa mendaftar, abaikan email ini.</p>
    </div>

</div>
</body>
</html>
