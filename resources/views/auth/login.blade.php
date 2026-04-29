<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Rantau Finance</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

<div class="auth-container">
    {{-- Left: Branding --}}
    <div class="auth-brand">
        <div class="brand-content">
            <div class="brand-logo">
                <span>💰</span> RantauFinance
            </div>
            <h2>Kelola keuanganmu dengan lebih cerdas</h2>
            <p>Platform manajemen keuangan bulanan yang intuitif untuk memantau pemasukan, pengeluaran, dan mencapai tujuan finansialmu.</p>

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

    {{-- Right: Form --}}
    <div class="auth-form-panel">
        <div class="auth-form-wrapper">
            <div class="auth-form-header">
                <div class="mobile-logo">💰 RantauFinance</div>
                <h1>Selamat Datang! 👋</h1>
                <p>Masuk ke akunmu untuk melanjutkan</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            @if(session('status'))
                <div class="alert-success">
                    ✅ {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                               placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" class="form-input"
                               placeholder="Masukkan password" required>
                        <button type="button" class="toggle-password" onclick="togglePw()">👁️</button>
                    </div>
                </div>

                <div class="form-row">
                    <label class="form-checkbox">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                    <a href="#" class="form-link">Lupa password?</a>
                </div>

                <button type="submit" class="btn-submit">
                    Masuk →
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="/register">Daftar gratis</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw() {
    const pw = document.getElementById('password');
    pw.type = pw.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>