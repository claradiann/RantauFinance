<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi — Rantau Finance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        /* ===== Form Styles ===== */
        .form-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            max-width: 100%;
        }
        .form-card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border);
            background: var(--light);
        }
        .form-card-header h2 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }
        .form-card-header p {
            font-size: 0.85rem;
            color: var(--gray);
        }
        .form-card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--dark-2);
            margin-bottom: 0.5rem;
        }
        .form-label .required {
            color: var(--danger);
        }
        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--white);
            transition: all 0.25s;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }
        .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8.825L1.175 4 2.238 2.938 6 6.7l3.763-3.763L10.825 4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }
        .form-input::placeholder { color: var(--gray-light); }
        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
        }
        .form-input.error { border-color: var(--danger); }

        .form-error {
            font-size: 0.78rem;
            color: var(--danger);
            margin-top: 0.35rem;
            font-weight: 500;
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--gray);
            margin-top: 0.35rem;
        }

        /* Amount input with prefix */
        .amount-input-wrapper {
            position: relative;
        }
        .amount-input-wrapper .prefix {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--gray);
            pointer-events: none;
        }
        .amount-input-wrapper .form-input {
            padding-left: 2.5rem;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Inline row */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 480px) {
            .form-row { grid-template-columns: 1fr; }
        }

        /* Submit buttons */
        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
        .btn-submit {
            flex: 1;
            padding: 0.85rem 1.5rem;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(99,102,241,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(99,102,241,0.35);
        }
        .btn-cancel {
            padding: 0.85rem 1.5rem;
            background: var(--light);
            color: var(--gray);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            transition: all 0.2s;
            text-align: center;
        }
        .btn-cancel:hover {
            border-color: var(--primary-light);
            color: var(--primary);
        }

        /* Type selector pills */
        .type-pills {
            display: flex;
            gap: 0.5rem;
        }
        .type-pill {
            flex: 1;
            padding: 0.65rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--white);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray);
            transition: all 0.25s;
            text-align: center;
        }
        .type-pill:hover { border-color: var(--primary-light); }
        .type-pill.active-income {
            border-color: var(--success);
            background: var(--success-bg);
            color: var(--success);
        }
        .type-pill.active-expense {
            border-color: var(--danger);
            background: var(--danger-bg);
            color: var(--danger);
        }
    </style>
</head>
<body>

{{-- Mobile Header --}}
<div class="mobile-header">
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    <div style="display:flex;align-items:center;gap:8px;"><img src="{{ asset('images/logo_RD.png') }}" style="height: 54px;"> <span style="font-weight:700;font-size:1rem;">RantauFinance</span></div>
    <div style="width:40px;"></div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="app-layout">
    @include('partials.sidebar', ['active' => 'transaksi'])

    {{-- ===== MAIN ===== --}}
    <main class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>Edit Transaksi</h1>
                <p>Ubah detail transaksi kamu</p>
            </div>
            <div class="top-bar-right">
                <a href="/transaksi" class="btn-add" style="background:var(--white);color:var(--dark-3);border:1px solid var(--border);box-shadow:none;">
                    ← Kembali
                </a>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <h2>📝 Edit Detail Transaksi</h2>
                <p>Perbarui informasi transaksi yang sudah dicatat</p>
            </div>

            <div class="form-card-body">
                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="transactionForm">
                    @csrf
                    @method('PUT')

                    {{-- Tipe Transaksi --}}
                    <div class="form-group">
                        <label class="form-label">Tipe Transaksi</label>
                        <div class="type-pills">
                            <button type="button" class="type-pill {{ $transaksi->kategori->tipe === 'pemasukan' ? 'active-income' : '' }}" id="pill-pemasukan" onclick="filterKategori('pemasukan')">
                                📈 Pemasukan
                            </button>
                            <button type="button" class="type-pill {{ $transaksi->kategori->tipe === 'pengeluaran' ? 'active-expense' : '' }}" id="pill-pengeluaran" onclick="filterKategori('pengeluaran')">
                                📉 Pengeluaran
                            </button>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="form-group">
                        <label class="form-label">
                            Jenis Transaksi <span class="required">*</span>
                        </label>
                        <select name="kategori_id" id="selectKategori" class="form-select {{ $errors->has('kategori_id') ? 'error' : '' }}" required>
                            <option value="" disabled>Pilih jenis...</option>
                        </select>
                        @error('kategori_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah & Tanggal --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                Jumlah <span class="required">*</span>
                            </label>
                            <div class="amount-input-wrapper">
                                <span class="prefix">Rp</span>
                                <input type="number" name="jumlah" class="form-input {{ $errors->has('jumlah') ? 'error' : '' }}"
                                       value="{{ old('jumlah', $transaksi->jumlah) }}" placeholder="0" min="1" required>
                            </div>
                            @error('jumlah')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Tanggal <span class="required">*</span>
                            </label>
                            <input type="date" name="tanggal" class="form-input {{ $errors->has('tanggal') ? 'error' : '' }}"
                                   value="{{ old('tanggal', \Carbon\Carbon::parse($transaksi->tanggal)->format('Y-m-d')) }}" required>
                            @error('tanggal')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-input"
                               value="{{ old('keterangan', $transaksi->keterangan) }}" placeholder="Contoh: Gaji bulan Mei, Belanja mingguan, dll...">
                        <p class="form-hint">Opsional — tambahkan catatan untuk referensi</p>
                    </div>

                    {{-- Actions --}}
                    <div class="form-actions">
                        <a href="/transaksi" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-submit">
                            💾 Perbarui Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

{{-- Data Kategori untuk JS --}}
<div id="kategori-data-container" 
     data-pemasukan='@json($kategori->where("tipe", "pemasukan")->values())'
     data-pengeluaran='@json($kategori->where("tipe", "pengeluaran")->values())'
     data-current-id="{{ $transaksi->kategori_id }}"
     data-current-tipe="{{ $transaksi->kategori->tipe }}"
     style="display:none;"></div>

<script>
// Ambil data dari data-attribute untuk menghindari error parser di IDE
const kategoriContainer = document.getElementById('kategori-data-container');
const kategoriData = {
    pemasukan: JSON.parse(kategoriContainer.dataset.pemasukan),
    pengeluaran: JSON.parse(kategoriContainer.dataset.pengeluaran)
};

const currentKategoriId = kategoriContainer.dataset.currentId;
const currentTipe = kategoriContainer.dataset.currentTipe;

function filterKategori(tipe, selectedId = null) {
    document.getElementById('pill-pemasukan').className =
        'type-pill' + (tipe === 'pemasukan' ? ' active-income' : '');
    document.getElementById('pill-pengeluaran').className =
        'type-pill' + (tipe === 'pengeluaran' ? ' active-expense' : '');

    const select = document.getElementById('selectKategori');
    select.innerHTML = '<option value="" disabled>Pilih kategori...</option>';
    kategoriData[tipe].forEach(k => {
        const opt = document.createElement('option');
        opt.value = k.id;
        opt.textContent = k.nama;
        if (selectedId && k.id == selectedId) {
            opt.selected = true;
        } else if (!selectedId && k.id == currentKategoriId && tipe === currentTipe) {
            opt.selected = true;
        }
        select.appendChild(opt);
    });
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}

document.addEventListener('DOMContentLoaded', () => {
    filterKategori(currentTipe, currentKategoriId);

    const card = document.querySelector('.form-card');
    if (card) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        requestAnimationFrame(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        });
    }
});
</script>
</body>
</html>

