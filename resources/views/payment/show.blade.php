<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran — RantauFinance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        /* ===== Payment-specific overrides ===== */
        .payment-brand .brand-content h2 {
            font-size: 1.6rem;
        }

        /* Override form panel for payment */
        .payment-form-panel {
            overflow-y: auto;
        }
        .payment-form-panel .auth-form-wrapper {
            max-width: 520px;
        }

        /* Steps */
        .payment-steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 2rem;
            padding: 1rem 1.25rem;
            background: var(--light);
            border-radius: 14px;
            border: 1px solid var(--border);
        }
        .p-step {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            color: var(--gray);
            white-space: nowrap;
        }
        .p-step.active { color: var(--primary); font-weight: 600; }
        .p-step.done { color: var(--success); font-weight: 600; }
        .p-step-num {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: var(--border);
            color: var(--gray);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 700;
            flex-shrink: 0;
        }
        .p-step.active .p-step-num { background: var(--primary); color: white; }
        .p-step.done .p-step-num { background: var(--success); color: white; }
        .p-step-line { flex: 1; height: 2px; background: var(--border); margin: 0 0.4rem; min-width: 12px; }
        .p-step-line.done { background: var(--success); }

        /* Plan summary */
        .plan-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(6,182,212,0.06));
            border: 1px solid rgba(99,102,241,0.15);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        .plan-summary .ps-info .ps-label {
            font-size: 0.72rem;
            color: var(--gray);
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .plan-summary .ps-info .ps-name {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--dark);
        }
        .plan-summary .ps-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary);
        }
        .plan-summary .ps-price span {
            font-size: 0.75rem;
            color: var(--gray);
            font-weight: 500;
        }

        /* Metode tabs */
        .metode-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 1.25rem;
        }
        .tab-btn {
            flex: 1;
            padding: 0.7rem;
            border: 2px solid var(--border);
            border-radius: 12px;
            background: white;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray);
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .tab-btn:hover { border-color: var(--primary-light); }
        .tab-btn.active {
            border-color: var(--primary);
            background: rgba(99,102,241,0.06);
            color: var(--primary);
        }

        /* Instruksi box */
        .instruksi-box {
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .instruksi-box h3 {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--dark-2);
            margin-bottom: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* QRIS */
        .qris-container { text-align: center; }
        .qris-placeholder {
            width: 180px; height: 180px;
            background: white;
            border: 2px dashed var(--gray-light);
            border-radius: 12px;
            margin: 0 auto 1rem;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: var(--gray);
            font-size: 0.82rem;
        }
        .qris-placeholder .qr-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .qris-note { font-size: 0.78rem; color: var(--gray); }

        /* Rekening */
        .rekening-info { display: flex; flex-direction: column; gap: 0.5rem; }
        .rek-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.65rem 0.85rem;
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
        }
        .rek-label { font-size: 0.78rem; color: var(--gray); }
        .rek-value { font-weight: 700; color: var(--dark); font-size: 0.9rem; }
        .rek-value.highlight {
            color: var(--primary);
            font-size: 1rem;
            letter-spacing: 1px;
        }
        .copy-btn {
            background: rgba(99,102,241,0.08);
            border: none;
            border-radius: 6px;
            padding: 0.25rem 0.6rem;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--primary);
            cursor: pointer;
            transition: background 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .copy-btn:hover { background: rgba(99,102,241,0.15); }

        /* Nominal warning */
        .nominal-warning {
            background: linear-gradient(135deg, #fef3c7, #fef9c3);
            border: 1px solid #fbbf24;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            font-size: 0.78rem;
            color: #92400e;
            margin-top: 0.6rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Upload */
        .upload-section { margin-bottom: 0.5rem; }
        .upload-section h3 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.35rem;
        }
        .upload-section > p {
            font-size: 0.78rem;
            color: var(--gray);
            margin-bottom: 0.75rem;
        }
        .upload-area {
            border: 2px dashed var(--gray-light);
            border-radius: 14px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s;
            background: var(--light);
        }
        .upload-area:hover, .upload-area.drag-over {
            border-color: var(--primary);
            background: rgba(99,102,241,0.04);
        }
        .upload-area .upload-icon { font-size: 2rem; margin-bottom: 0.4rem; }
        .upload-area p { font-size: 0.82rem; color: var(--gray); margin: 0; }
        .upload-area strong { color: var(--primary); }
        #buktiInput { display: none; }

        .preview-img {
            max-width: 100%; max-height: 160px;
            border-radius: 10px;
            margin-top: 0.75rem;
            display: none;
            border: 1px solid var(--border);
        }

        /* Submit */
        .btn-submit-payment {
            width: 100%;
            padding: 0.9rem;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            margin-top: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-submit-payment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(99,102,241,0.4);
        }
        .btn-submit-payment:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Pending info box */
        .pending-info-box {
            background: rgba(6,182,212,0.06);
            border: 1px solid rgba(6,182,212,0.2);
            border-radius: 14px;
            padding: 1.25rem;
            font-size: 0.85rem;
            color: #0e7490;
            line-height: 1.7;
        }
        .pending-info-box a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }
        .pending-info-box a:hover { text-decoration: underline; }

        /* Section label */
        .section-label-sm {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--dark-2);
            margin-bottom: 0.65rem;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .payment-form-panel .auth-form-wrapper { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="auth-container">
    {{-- Left: Branding --}}
    <div class="auth-brand payment-brand">
        <div class="brand-content">
            <div class="brand-logo">
                <span>💰</span> RantauFinance
            </div>
            <h2>Satu langkah lagi untuk akun premiummu</h2>
            <p>Transfer pembayaran dan nikmati semua fitur premium Rantau Finance untuk mengelola keuanganmu lebih optimal.</p>

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="feat-icon">🔐</div>
                    Transaksi aman & terenkripsi
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">⚡</div>
                    Aktivasi cepat (maks 1×24 jam)
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">📧</div>
                    Password dikirim via email
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">💎</div>
                    Akses semua fitur premium
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Payment Form --}}
    <div class="auth-form-panel payment-form-panel">
        <div class="auth-form-wrapper">
            <div class="auth-form-header">
                <div class="mobile-logo">💰 RantauFinance</div>
                <h1>Selesaikan Pembayaran 💳</h1>
                <p>Halo, {{ $user->name }}! Transfer sesuai nominal lalu upload buktinya.</p>
            </div>

            {{-- Steps --}}
            <div class="payment-steps">
                <div class="p-step done">
                    <div class="p-step-num">✓</div>
                    <span>Daftar</span>
                </div>
                <div class="p-step-line done"></div>
                <div class="p-step active">
                    <div class="p-step-num">2</div>
                    <span>Bayar</span>
                </div>
                <div class="p-step-line"></div>
                <div class="p-step">
                    <div class="p-step-num">3</div>
                    <span>Verifikasi</span>
                </div>
                <div class="p-step-line"></div>
                <div class="p-step">
                    <div class="p-step-num">4</div>
                    <span>Aktif</span>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert-success">✅ {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        ⚠️ {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            {{-- Sudah ada payment pending --}}
            @if($existingPayment)
                <div class="pending-info-box">
                    ⏳ Bukti pembayaran kamu sudah kami terima dan sedang dalam proses verifikasi.
                    Password akan dikirim ke <strong>{{ $user->email }}</strong> setelah dikonfirmasi (maks 1×24 jam).
                    <br><br>
                    <a href="{{ route('payment.status', $user->id) }}">Pantau status →</a>
                </div>
            @else

            {{-- Plan Summary --}}
            <div class="plan-summary">
                <div class="ps-info">
                    <div class="ps-label">Paket yang dipilih</div>
                    <div class="ps-name">
                        {{ $user->plan === 'personal' ? '🔵' : '🟣' }}
                        {{ ucfirst($user->plan) }}
                    </div>
                </div>
                <div class="ps-price">Rp {{ number_format($nominal, 0, ',', '.') }}<span>/bulan</span></div>
            </div>

            {{-- Form Upload --}}
            <form method="POST" action="{{ route('payment.upload', $user->id) }}" enctype="multipart/form-data" id="paymentForm">
                @csrf

                {{-- Pilih Metode --}}
                <div style="margin-bottom: 1.25rem;">
                    <p class="section-label-sm">Pilih Metode Pembayaran</p>
                    <div class="metode-tabs">
                        <button type="button" class="tab-btn active" onclick="selectMetode('transfer')" id="tab-transfer">
                            🏦 Transfer Bank
                        </button>
                        <button type="button" class="tab-btn" onclick="selectMetode('qris')" id="tab-qris">
                            📱 QRIS
                        </button>
                    </div>
                    <input type="hidden" name="metode" id="metodeInput" value="transfer">
                </div>

                {{-- Instruksi Transfer --}}
                <div class="instruksi-box" id="instruksi-transfer">
                    <h3>📋 Instruksi Transfer Bank BNI</h3>
                    <div class="rekening-info">
                        <div class="rek-row">
                            <span class="rek-label">Bank</span>
                            <span class="rek-value">BNI (Bank Negara Indonesia)</span>
                        </div>
                        <div class="rek-row">
                            <span class="rek-label">No. Rekening</span>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <span class="rek-value highlight" id="noRek">1782870396</span>
                                <button type="button" class="copy-btn" onclick="copyToClipboard('1782870396', this)">Salin</button>
                            </div>
                        </div>
                        <div class="rek-row">
                            <span class="rek-label">Atas Nama</span>
                            <span class="rek-value">Clara Dian Ajeng Saputri</span>
                        </div>
                        <div class="rek-row">
                            <span class="rek-label">Nominal</span>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <span class="rek-value highlight">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                                <button type="button" class="copy-btn" onclick="copyToClipboard('{{ $nominal }}', this)">Salin</button>
                            </div>
                        </div>
                    </div>
                    <div class="nominal-warning">
                        ⚠️ Transfer tepat sesuai nominal di atas agar proses verifikasi lebih cepat.
                    </div>
                </div>

                {{-- Instruksi QRIS --}}
                <div class="instruksi-box" id="instruksi-qris" style="display:none;">
                    <h3>📱 Scan QRIS</h3>
                    <div class="qris-container">
                        <div class="qris-placeholder">
                            <div class="qr-icon">⬛</div>
                            <p>QR Code kamu</p>
                        </div>
                        {{-- Ganti dengan: <img src="{{ asset('images/qris.png') }}" width="180"> --}}
                        <p class="qris-note">Scan menggunakan aplikasi bank atau dompet digital apapun</p>
                        <p class="qris-note" style="margin-top:0.5rem;">Nominal: <strong>Rp {{ number_format($nominal, 0, ',', '.') }}</strong></p>
                    </div>
                </div>

                {{-- Upload Bukti --}}
                <div class="upload-section">
                    <h3>📎 Upload Bukti Pembayaran</h3>
                    <p>Screenshot struk transfer atau bukti pembayaran QRIS (JPG/PNG, maks 3MB)</p>

                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('buktiInput').click()">
                        <div class="upload-icon">🖼️</div>
                        <p><strong>Klik untuk pilih file</strong> atau drag & drop di sini</p>
                        <p style="margin-top:0.25rem;font-size:0.72rem;">JPG, PNG, WEBP — maks 3MB</p>
                    </div>
                    <input type="file" id="buktiInput" name="bukti" accept="image/*" onchange="previewFile(event)">
                    <img id="previewImg" class="preview-img" alt="Preview bukti">
                    <p id="fileName" style="font-size:0.78rem;color:var(--primary);margin-top:0.5rem;display:none;font-weight:600;"></p>

                    @error('bukti')
                        <p style="color:var(--danger);font-size:0.8rem;margin-top:0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-submit-payment" id="submitBtn" disabled>
                    📤 Kirim Bukti Pembayaran
                </button>

                <p style="text-align:center;font-size:0.78rem;color:var(--gray);margin-top:1rem;line-height:1.5;">
                    Password akan dikirim ke <strong>{{ $user->email }}</strong> setelah admin memverifikasi (maks 1×24 jam)
                </p>
            </form>

            @endif {{-- end if existingPayment --}}

            <div class="auth-footer">
                <a href="/">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>

<script>
function selectMetode(metode) {
    document.getElementById('metodeInput').value = metode;

    // Toggle tabs
    document.getElementById('tab-transfer').classList.toggle('active', metode === 'transfer');
    document.getElementById('tab-qris').classList.toggle('active', metode === 'qris');

    // Toggle instruksi
    document.getElementById('instruksi-transfer').style.display = metode === 'transfer' ? 'block' : 'none';
    document.getElementById('instruksi-qris').style.display = metode === 'qris' ? 'block' : 'none';
}

function previewFile(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Tampilkan nama file
    const nameEl = document.getElementById('fileName');
    nameEl.textContent = '✅ ' + file.name;
    nameEl.style.display = 'block';

    // Preview gambar
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('previewImg');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);

    // Enable tombol submit
    document.getElementById('submitBtn').disabled = false;
}

function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        btn.textContent = '✓ Disalin!';
        setTimeout(() => { btn.textContent = 'Salin'; }, 2000);
    });
}

// Drag & drop
const uploadArea = document.getElementById('uploadArea');
if (uploadArea) {
    uploadArea.addEventListener('dragover', e => {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('buktiInput').files = dt.files;
            previewFile({ target: { files: [file] } });
        }
    });
}
</script>

</body>
</html>