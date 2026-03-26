<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Kata Sandi - SIPORA POLIJE</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/auth-forgot.css') }}">
</head>
<body>
  <div class="bg-pattern"></div>
  <div class="bg-animation">
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
  </div>

  <div class="login-container show">
    <div class="login-card">
      <div class="login-card-left">
        <div class="login-card-left-content">
          <div class="logo-container">
            <div class="logo-circle">
              <img src="{{ asset('assets/logo_polije.png') }}" alt="Logo Polije">
            </div>
          </div>
          <h1>Lupa Kata Sandi?</h1>
          <p>Jangan khawatir, kami akan mengirimkan link untuk mereset kata sandi Anda.</p>
        </div>
      </div>

      <div class="login-card-right">
        <a href="{{ route('login') }}" class="back-to-login">
          <i class="bi bi-arrow-left"></i>
          Kembali ke Halaman Login
        </a>

        @if(session('forgot_error'))
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('forgot_error') }}
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
          </div>
        @endif

        @if(session('forgot_success'))
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('forgot_success') }}
          </div>
          @if(session('reset_link'))
            <div class="reset-link-box">
              <strong>Link reset (dev/testing):</strong>
              <a href="{{ session('reset_link') }}">{{ session('reset_link') }}</a>
            </div>
          @endif
        @endif

        <div class="info-box">
          <div class="info-box-title">
            <i class="bi bi-info-circle"></i>
            Informasi Penting
          </div>
          <div class="info-box-content">
            <p>Hanya email dengan domain <strong>.ac.id</strong> yang dapat menggunakan fitur ini.</p>
            <p>Link reset kata sandi berlaku selama 60 menit.</p>
          </div>
        </div>

        <form method="POST" action="{{ route('password.email') }}" id="resetForm">
          @csrf
          <div class="form-group">
            <label class="form-label" for="email">Email Akun</label>
            <input
              type="email"
              id="email"
              name="email"
              class="form-input"
              placeholder="Masukkan email akun Anda (.ac.id)"
              required
              value="{{ old('email') }}"
            >
            <div class="email-warning" id="emailWarning">
              <i class="fas fa-exclamation-triangle"></i>
              <span>Hanya email dengan domain .ac.id yang diizinkan</span>
            </div>
          </div>

          <button type="submit" class="btn-primary" id="submitBtn">
            <span class="btn-text"><i class="bi bi-envelope-fill"></i> Kirim Link Reset</span>
            <span class="loading"></span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/js/auth-forgot.js') }}"></script>
</body>
</html>
