<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran — RantauFinance</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        body { background: #f8fafc; }
        .status-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .status-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 520px;
            padding: 2.5rem;
            text-align: center;
        }
        .status-logo {
            font-size: 1.2rem;
            font-weight: 800;
            color: #6366f1;
            margin-bottom: 2rem;
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
        }
        .status-icon-wrap.pending   { background: #fef3c7; }
        .status-icon-wrap.confirmed { background: #d1fae5; }
        .status-icon-wrap.rejected  { background: #fee2e2; }

        .status-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .status-desc {
            font-size: 0.9rem;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Info rows */
        .info-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.25rem;
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: #9ca3af; }
        .info-row .value { font-weight: 600; color: #1e293b; }
        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 99px;
            font-size: 0.75rem;
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
            border: 1px solid #e5e7eb;
        }
        .bukti-preview img {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
        }

        /* Action buttons */
        .btn-primary {
            display: block;
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            margin-bottom: 0.75rem;
            transition: all 0.2s;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-outline {
            display: block;
            width: 100%;
            padding: 0.9rem;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
        }
        .btn-outline:hover { border-color: #6366f1; color: #6366f1; }

        /* Catatan admin */
        .catatan-box {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 10px;
            padding: 1rem;
            text-align: left;
            font-size: 0.85rem;
            color: #92400e;
            margin-bottom: 1.25rem;
        }
        .catatan-box strong { display: block; margin-bottom: 0.25rem; }

        /* Auto refresh note */
        .refresh-note {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>

<div class="status-wrapper">
    <div class="status-card">
        <div class="status-logo">💰 RantauFinance</div>

        @if(!$payment)
            {{-- Belum ada payment --}}
            <div class="status-icon-wrap" style="background:#f3f4f6;">⚠️</div>
            <div class="status-title">Belum Ada Pembayaran</div>
            <div class="status-desc">
                Kamu belum mengupload bukti pembayaran.
            </div>
            <a href="{{ route('payment.show', $user->id) }}" class="btn-primary">Upload Bukti Sekarang →</a>

        @elseif($payment->isPending())
            {{-- Pending --}}
            <div class="status-icon-wrap pending">⏳</div>
            <div class="status-title">Menunggu Konfirmasi</div>
            <div class="status-desc">
                Bukti pembayaran kamu sudah kami terima.<br>
                Admin akan memverifikasi dan mengirimkan <strong>password login</strong> ke email kamu dalam <strong>1×24 jam</strong>.
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
                        <img src="{{ Storage::url($payment->bukti_path) }}" alt="Bukti pembayaran">
                    </div>
                @endif
            </div>

            <p style="font-size:0.85rem;color:#6b7280;margin-bottom:1.25rem;">
                Password akan dikirim ke: <strong>{{ $user->email }}</strong>
            </p>

            <div class="refresh-note">
                Halaman ini akan otomatis refresh setiap 60 detik
            </div>

            <script>setTimeout(() => location.reload(), 60000);</script>

        @elseif($payment->isConfirmed())
            {{-- Confirmed --}}
            <div class="status-icon-wrap confirmed">✅</div>
            <div class="status-title">Pembayaran Dikonfirmasi!</div>
            <div class="status-desc">
                Selamat! Paket <strong>{{ $payment->planLabel() }}</strong> kamu sudah aktif.<br>
                Password login sudah dikirim ke <strong>{{ $user->email }}</strong>.
                Cek inbox (atau folder spam) kamu.
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

            <a href="{{ route('login') }}" class="btn-primary">Login Sekarang →</a>
            <p style="font-size:0.8rem;color:#9ca3af;text-align:center;">
                Belum dapat email? <a href="mailto:admin@rantaufinance.com" style="color:#6366f1;">Hubungi kami</a>
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

            <a href="{{ route('payment.show', $user->id) }}" class="btn-primary">Upload Ulang →</a>
            <a href="mailto:admin@rantaufinance.com" class="btn-outline">Hubungi Admin</a>

        @endif

    </div>
</div>

</body>
</html>