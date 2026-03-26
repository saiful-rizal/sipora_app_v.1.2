<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Kata Sandi - SIPORA POLIJE</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/auth-reset.css') }}">
</head>
<body data-login-url="{{ route('login') }}" data-show-success-modal="{{ session('reset_success') ? '1' : '0' }}">
  <div class="bg-pattern"></div>
  <div class="bg-animation">
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
  </div>

  <div class="login-container">
    <div class="login-card">
      <div class="login-card-left">
        <div class="login-card-left-content">
          <div class="logo-container">
            <div class="logo-circle">
              <img src="{{ asset('assets/logo_polije.png') }}" alt="Logo Polije">
            </div>
          </div>
          <h1>Reset Kata Sandi</h1>
          <p>Buat kata sandi baru yang kuat untuk melindungi akun SIPORA Anda.</p>
        </div>
      </div>

      <div class="login-card-right">
        @if($errorMessage)
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $errorMessage }}</span>
          </div>
        @endif

        @if(session('reset_error'))
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('reset_error') }}</span>
          </div>
        @endif

        @if($isValid)
          <h1 style="font-size:24px;font-weight:700;margin-bottom:10px;color:var(--text-primary);">Buat Kata Sandi Baru</h1>
          <p style="font-size:14px;color:var(--text-secondary);margin-bottom:8px;">Masukkan kata sandi baru yang kuat untuk akun Anda.</p>
          <p style="font-size:14px;color:var(--text-secondary);margin-bottom:25px;"><strong>Untuk email:</strong> {{ $email }}</p>

          <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
              <label class="form-label" for="password">Kata Sandi Baru</label>
              <div class="password-input-container">
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-input"
                  placeholder="Minimal 8 karakter"
                  required
                  minlength="8"
                >
                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                  <i class="bi bi-eye" id="password-icon"></i>
                </button>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
              <div class="password-input-container">
                <input
                  type="password"
                  id="password_confirmation"
                  name="password_confirmation"
                  class="form-input"
                  placeholder="Ulangi kata sandi baru"
                  required
                  minlength="8"
                >
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                  <i class="bi bi-eye" id="password_confirmation-icon"></i>
                </button>
              </div>
            </div>

            <button type="submit" class="btn-primary">
              <i class="bi bi-key-fill"></i> Reset Kata Sandi
            </button>
          </form>
        @else
          <div class="info-box">
            <div class="info-box-title">
              <i class="bi bi-exclamation-triangle"></i>
              Permintaan Tidak Valid
            </div>
            <div class="info-box-content">
              <p>Link reset kata sandi tidak valid atau telah kadaluarsa.</p>
              <p>Silakan ajukan permintaan baru dari halaman <a href="{{ route('password.forgot') }}">Lupa Kata Sandi</a>.</p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="modal-overlay" id="successModal">
    <div class="modal-content">
      <div class="modal-icon">
        <i class="bi bi-check-lg"></i>
      </div>
      <h2 class="modal-title">Berhasil!</h2>
      <p class="modal-message">Kata sandi Anda berhasil diubah. Silakan login dengan kata sandi baru Anda.</p>
      <button class="modal-btn" onclick="redirectToLogin()">OK</button>
    </div>
  </div>

  <script src="{{ asset('assets/js/auth-reset.js') }}"></script>
</body>
</html>
