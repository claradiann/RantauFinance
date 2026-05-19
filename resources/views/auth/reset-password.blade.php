<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Rantau Finance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

<div class="auth-container">
    {{-- Left: Branding --}}
    <div class="auth-brand">
        <div class="brand-content">
            <div class="brand-logo">
                <img src="{{ asset('images/logo_RD.png') }}" style="height: 54px; margin-right: 8px;"> RantauFinance
            </div>
            <h2>Buat Password Baru</h2>
            <p>Pastikan kamu membuat password yang kuat dan mudah diingat untuk mengamankan akun RantauFinance kamu.</p>

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="feat-icon">🛡️</div>
                    Proteksi data enkripsi
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">✅</div>
                    Akses aman setiap saat
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Form --}}
    <div class="auth-form-panel">
        <div class="auth-form-wrapper">
            <div class="auth-form-header">
                <div class="mobile-logo" style="display:flex;align-items:center;justify-content:center;gap:8px;"><img src="{{ asset('images/logo_RD.png') }}" style="height: 54px;"> RantauFinance</div>
                <h1>Reset Password 🔐</h1>
                <p>Silakan masukkan password baru untuk akunmu.</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                               value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password Baru</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                               placeholder="Masukkan password baru" required autocomplete="new-password">
                        <button type="button" class="toggle-password" onclick="togglePw('password')">👁️</button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                               placeholder="Ulangi password baru" required autocomplete="new-password">
                        <button type="button" class="toggle-password" onclick="togglePw('password_confirmation')">👁️</button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function togglePw(id) {
    const pw = document.getElementById(id);
    pw.type = pw.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>
