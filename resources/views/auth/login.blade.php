<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - SIPORA POLIJE</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <link rel="stylesheet" href="{{ asset('assets/css/auth-login.css') }}">
</head>
<body data-google-auth-endpoint="{{ route('auth.google') }}" data-csrf-token="{{ csrf_token() }}" data-dashboard-url="{{ route('dashboard') }}">
  <div class="bg-pattern"></div>
  <div class="bg-animation"><div class="bg-circle"></div><div class="bg-circle"></div><div class="bg-circle"></div><div class="bg-circle"></div></div>

  <div id="splash-screen">
    <div class="splash-logo"><img src="{{ asset('assets/logo_polije.png') }}" alt="Logo Polije"></div>
    <h1 class="splash-title">SIPORA</h1>
    <p class="splash-subtitle">Sistem Informasi Politeknik Negeri Jember Repository Assets</p>
    <div class="splash-progress"><div class="splash-progress-bar"></div></div>
  </div>

  <div class="login-container">
    <div class="login-card">
      <div class="login-card-left">
        <div class="login-card-left-content">
          <div class="logo-container"><div class="logo-circle"><img src="{{ asset('assets/logo_polije.png') }}" alt="Logo Polije"></div></div>
          <h1>Masuk ke SIPORA</h1>
          <p>Sistem Informasi Politeknik Negeri Jember Repository Assets</p>
        </div>
      </div>

      <div class="login-card-right">
        @if(session('login_error'))
          <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>{{ session('login_error') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>{{ $errors->first() }}</div>
        @endif
        @if(session('reset_success'))
          <div class="alert alert-success"><i class="fas fa-check-circle"></i>{{ session('reset_success') }}</div>
        @endif

        <form id="loginFormElement" method="POST" action="{{ route('login.submit') }}">
          @csrf
          <input type="hidden" name="action" value="login">
          <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username / email / NIM" required value="{{ old('username', request()->cookie('username', '')) }}">
          </div>

          <div class="form-group">
            <label class="form-label" for="password">Kata Sandi</label>
            <div class="password-input-container">
              <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan kata sandi" required>
              <button type="button" class="password-toggle" onclick="togglePassword('password')"><i class="bi bi-eye" id="password-icon"></i></button>
            </div>
          </div>

          <div class="form-options">
            <div class="checkbox-container">
              <input type="checkbox" id="remember" name="remember" {{ request()->cookie('username') ? 'checked' : '' }}>
              <label for="remember">Ingat saya</label>
            </div>
            <a href="{{ route('password.forgot') }}" class="forgot-password">Lupa kata sandi?</a>
          </div>

          <button type="submit" class="btn-primary">Masuk</button>
        </form>

        <div class="divider"><span>Atau masuk dengan</span></div>
        <div class="social-login"><div id="googleSignInButton"></div></div>

        <p class="register-link" style="margin-top:20px;text-align:center;">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/js/auth-login.js') }}"></script>
</body>
</html>
