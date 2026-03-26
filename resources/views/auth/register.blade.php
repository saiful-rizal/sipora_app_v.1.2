<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - SIPORA POLIJE</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/auth-register.css') }}">
</head>
<body
  data-availability-endpoint="{{ route('auth.check-user') }}"
  data-csrf-token="{{ csrf_token() }}"
  data-login-page="{{ route('login') }}"
  data-register-success-message="{{ session('register_success', '') }}"
>
  <div class="bg-pattern"></div>
  <div class="bg-animation"><div class="bg-circle"></div><div class="bg-circle"></div><div class="bg-circle"></div><div class="bg-circle"></div></div>

  <div class="register-container">
    <div class="register-card">
      <div class="register-card-left">
        <div class="register-card-left-content">
          <div class="logo-circle"><img src="{{ asset('assets/logo_polije.png') }}" alt="Logo Polije"></div>
          <h1>Bergabung dengan SIPORA</h1>
          <p>Sistem Informasi Politeknik Negeri Jember Repository Assets</p>
        </div>
      </div>

      <div class="register-card-right">
        @if(session('register_error'))
          <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>{{ session('register_error') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>{{ $errors->first() }}</div>
        @endif

        <form id="registerFormElement" method="POST" action="{{ route('register.submit') }}">
          @csrf
          <input type="hidden" name="action" value="register">

          <div class="form-group">
            <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input" placeholder="Masukkan nama lengkap" required value="{{ old('nama_lengkap') }}" onblur="validateFullname()">
            <div id="namaWarning" class="email-warning hidden"><i class="fas fa-exclamation-triangle"></i><span>Nama sudah terdaftar</span></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="nomor_induk">Nomor Induk</label>
            <input type="text" id="nomor_induk" name="nomor_induk" class="form-input" placeholder="Masukkan NIM / NIP / Nomor Pegawai" required value="{{ old('nomor_induk') }}">
          </div>

          <div class="form-group">
            <label class="form-label" for="reg_username">Username</label>
            <input type="text" id="reg_username" name="username" class="form-input" placeholder="Masukkan username" required value="{{ old('username') }}" onblur="validateUsername()">
            <div id="usernameWarning" class="email-warning hidden"><i class="fas fa-exclamation-triangle"></i><span>Masukan Username anda</span></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Email SSO <span style="color: #dc2626;">*</span></label>
            <input type="email" id="email" name="email" class="form-input" placeholder="Masukkan email akademik (.ac.id)" required value="{{ old('email') }}" onblur="validateEmail()">
            <div id="emailWarning" class="email-warning hidden"><i class="fas fa-exclamation-triangle"></i><span>Hanya email dengan domain .ac.id yang diizinkan</span></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="reg_password">Kata Sandi</label>
            <div class="password-input-container">
              <input type="password" id="reg_password" name="password" class="form-input" placeholder="Minimal 8 karakter" minlength="8" required>
              <button type="button" class="password-toggle" onclick="togglePassword('reg_password')"><i class="bi bi-eye" id="reg_password-icon"></i></button>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="confirm_password">Konfirmasi Kata Sandi</label>
            <div class="password-input-container">
              <input type="password" id="confirm_password" name="confirmPassword" class="form-input" placeholder="Ulangi kata sandi" minlength="8" required>
              <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')"><i class="bi bi-eye" id="confirm_password-icon"></i></button>
            </div>
            <div id="passwordMatchWarning" class="email-warning" style="display:none; margin-top:8px;"><i id="passwordMatchIcon" class="fas fa-exclamation-triangle" style="margin-right:6px;"></i><span id="passwordMatchText">Kata sandi tidak sama</span></div>
          </div>

          <div class="form-options">
            <div class="checkbox-container">
              <input type="checkbox" id="agreeTerms" required>
              <label for="agreeTerms">Saya setuju dengan <a href="#" style="color: var(--primary-blue);">syarat dan ketentuan</a></label>
            </div>
          </div>

          <div class="progress-container">
            <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>
            <div class="progress-text"><span id="progressText">0% Selesai</span><span id="progressStep">Langkah 1 dari 5</span></div>
          </div>

          <button type="submit" id="registerSubmitBtn" class="btn-primary">Daftar</button>
        </form>

        <p class="login-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
      </div>
    </div>
  </div>

  <div id="registerSuccessModal" class="register-success-overlay" style="display:none;">
    <div class="register-success-card" role="dialog" aria-modal="true" aria-labelledby="registerSuccessTitle">
      <h2 id="registerSuccessTitle">Pendaftaran Berhasil</h2>
      <p id="registerSuccessMessage">Akun Anda berhasil didaftarkan dan sedang menunggu persetujuan admin.</p>
      <div class="register-success-actions"><button id="goToLoginBtn" class="btn btn-primary">Masuk</button></div>
    </div>
  </div>

  <div id="passwordMismatchModal" class="register-success-overlay" style="display:none;">
    <div class="register-success-card" role="dialog" aria-modal="true" aria-labelledby="passwordMismatchTitle">
      <h2 id="passwordMismatchTitle" style="color:#dc2626">Kata sandi tidak sama</h2>
      <p id="passwordMismatchMessage">Pastikan kata sandi dan konfirmasi kata sandi sama sebelum melanjutkan.</p>
      <div class="register-success-actions"><button id="pwMismatchOkBtn" class="btn btn-primary">Oke, perbaiki</button></div>
    </div>
  </div>

  <script src="{{ asset('assets/js/auth-register.js') }}"></script>
</body>
</html>
