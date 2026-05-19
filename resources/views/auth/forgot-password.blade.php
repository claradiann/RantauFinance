<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Rantau Finance</title>
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
            <h2>Amankan kembali akunmu</h2>
            <p>Jangan khawatir jika kamu lupa password. Kami akan membantu mereset password agar kamu dapat kembali memantau keuanganmu.</p>

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="feat-icon">📧</div>
                    Pemulihan via Email
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">🔒</div>
                    Keamanan tingkat tinggi
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Form --}}
    <div class="auth-form-panel">
        <div class="auth-form-wrapper">
            <div class="auth-form-header">
                <div class="mobile-logo" style="display:flex;align-items:center;justify-content:center;gap:8px;"><img src="{{ asset('images/logo_RD.png') }}" style="height: 54px;"> RantauFinance</div>
                <h1>Lupa Password? 🔑</h1>
                <p>Masukkan alamat email yang terdaftar dan kami akan mengirimkan link reset password kepada kamu.</p>
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

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-icon-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                               placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Kirim Link Reset Password
                </button>
            </form>

            <div class="auth-footer">
                Ingat password kamu? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
