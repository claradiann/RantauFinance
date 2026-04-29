<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran — RantauFinance</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        body { background: #f8fafc; }
        .payment-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .payment-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 680px;
            overflow: hidden;
        }

        /* Header */
        .payment-header {
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            padding: 2rem 2.5rem;
            color: white;
        }
        .payment-header .logo {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        .payment-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }
        .payment-header p { opacity: 0.85; font-size: 0.9rem; }

        /* Steps */
        .steps {
            display: flex;
            gap: 0;
            padding: 1.25rem 2.5rem;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #9ca3af;
            flex: 1;
        }
        .step.active { color: #6366f1; font-weight: 600; }
        .step-num {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #9ca3af;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
            flex-shrink: 0;
        }
        .step.active .step-num { background: #6366f1; color: white; }
        .step-line { flex: 1; height: 1px; background: #e5e7eb; margin: 0 0.5rem; }

        /* Body */
        .payment-body { padding: 2rem 2.5rem; }

        /* Plan summary */
        .plan-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f5f3ff;
            border: 1px solid #e0e7ff;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.75rem;
        }
        .plan-summary .plan-info .label {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 2px;
        }
        .plan-summary .plan-info .name {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e293b;
        }
        .plan-summary .plan-price {
            font-size: 1.4rem;
            font-weight: 800;
            color: #6366f1;
        }

        /* Metode tabs */
        .metode-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 1.5rem;
        }
        .tab-btn {
            flex: 1;
            padding: 0.7rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .tab-btn.active {
            border-color: #6366f1;
            background: #f5f3ff;
            color: #6366f1;
        }

        /* Instruksi box */
        .instruksi-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .instruksi-box h3 {
            font-size: 0.85rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* QRIS display */
        .qris-container {
            text-align: center;
        }
        .qris-placeholder {
            width: 200px;
            height: 200px;
            background: white;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            margin: 0 auto 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.85rem;
        }
        .qris-placeholder .qr-icon { font-size: 3rem; margin-bottom: 0.5rem; }
        .qris-note {
            font-size: 0.8rem;
            color: #6b7280;
            text-align: center;
        }

        /* Rekening display */
        .rekening-info { display: flex; flex-direction: column; gap: 0.75rem; }
        .rek-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
        }
        .rek-row .rek-label {
            font-size: 0.8rem;
            color: #9ca3af;
        }
        .rek-row .rek-value {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
        }
        .rek-row .rek-value.highlight {
            color: #6366f1;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }
        .copy-btn {
            background: #f3f4f6;
            border: none;
            border-radius: 6px;
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6366f1;
            cursor: pointer;
            transition: background 0.2s;
        }
        .copy-btn:hover { background: #e0e7ff; }

        /* Nominal warning */
        .nominal-warning {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.82rem;
            color: #92400e;
            margin-top: 0.75rem;
            display: flex;
            gap: 0.5rem;
        }

        /* Upload section */
        .upload-section h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .upload-section p {
            font-size: 0.82rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 14px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f9fafb;
        }
        .upload-area:hover, .upload-area.drag-over {
            border-color: #6366f1;
            background: #f5f3ff;
        }
        .upload-area .upload-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .upload-area p {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0;
        }
        .upload-area strong { color: #6366f1; }
        #buktiInput { display: none; }

        /* Preview */
        .preview-img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 10px;
            margin-top: 0.75rem;
            display: none;
        }

        /* Submit btn */
        .btn-submit-payment {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            margin-top: 1.5rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .btn-submit-payment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(99,102,241,0.4);
        }
        .btn-submit-payment:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert */
        .alert-info {
            padding: 0.85rem 1rem;
            border-radius: 12px;
            background: rgba(6,182,212,0.08);
            border: 1px solid rgba(6,182,212,0.3);
            color: #0e7490;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .alert-error {
            padding: 0.85rem 1rem;
            border-radius: 12px;
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #dc2626;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 640px) {
            .payment-body { padding: 1.5rem; }
            .payment-header { padding: 1.5rem; }
            .steps { padding: 1rem 1.5rem; }
        }
    </style>
</head>
<body>

<div class="payment-wrapper">
    <div class="payment-card">

        {{-- Header --}}
        <div class="payment-header">
            <div class="logo">💰 RantauFinance</div>
            <h1>Selesaikan Pembayaran</h1>
            <p>Halo, {{ $user->name }}! Transfer sesuai nominal lalu upload buktinya.</p>
        </div>

        {{-- Steps --}}
        <div class="steps">
            <div class="step active">
                <div class="step-num">1</div>
                <span>Pilih Metode</span>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-num">2</div>
                <span>Transfer</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-num">3</div>
                <span>Konfirmasi Admin</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-num">4</div>
                <span>Akun Aktif</span>
            </div>
        </div>

        <div class="payment-body">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert-info">✅ {{ session('success') }}</div>
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
                <div class="alert-info">
                    ⏳ Bukti pembayaran kamu sudah kami terima dan sedang dalam proses verifikasi.
                    Password akan dikirim ke <strong>{{ $user->email }}</strong> setelah dikonfirmasi (maks 1x24 jam).
                    <br><br>
                    <a href="{{ route('payment.status', $user->id) }}" style="color: #0e7490; font-weight:700;">Pantau status →</a>
                </div>
            @else

            {{-- Plan Summary --}}
            <div class="plan-summary">
                <div class="plan-info">
                    <div class="label">Paket yang dipilih</div>
                    <div class="name">
                        {{ $user->plan === 'personal' ? '🔵' : '🟣' }}
                        {{ ucfirst($user->plan) }}
                    </div>
                </div>
                <div class="plan-price">Rp {{ number_format($nominal, 0, ',', '.') }}<span style="font-size:0.8rem;color:#6b7280;font-weight:500;">/bulan</span></div>
            </div>

            {{-- Form Upload --}}
            <form method="POST" action="{{ route('payment.upload', $user->id) }}" enctype="multipart/form-data" id="paymentForm">
                @csrf

                {{-- Pilih Metode --}}
                <div style="margin-bottom: 1.5rem;">
                    <p style="font-size:0.85rem;font-weight:700;color:#374151;margin-bottom:0.75rem;">Pilih Metode Pembayaran</p>
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
                        {{-- Ganti dengan: <img src="{{ asset('images/qris.png') }}" width="200"> --}}
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
                        <p style="margin-top:0.25rem;font-size:0.75rem;">JPG, PNG, WEBP — maks 3MB</p>
                    </div>
                    <input type="file" id="buktiInput" name="bukti" accept="image/*" onchange="previewFile(event)">
                    <img id="previewImg" class="preview-img" alt="Preview bukti">
                    <p id="fileName" style="font-size:0.8rem;color:#6366f1;margin-top:0.5rem;display:none;"></p>

                    @error('bukti')
                        <p style="color:#dc2626;font-size:0.82rem;margin-top:0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-submit-payment" id="submitBtn" disabled>
                    📤 Kirim Bukti Pembayaran
                </button>

                <p style="text-align:center;font-size:0.8rem;color:#9ca3af;margin-top:1rem;">
                    Password akan dikirim ke <strong>{{ $user->email }}</strong> setelah admin memverifikasi (maks 1×24 jam)
                </p>
            </form>

            @endif {{-- end if existingPayment --}}

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