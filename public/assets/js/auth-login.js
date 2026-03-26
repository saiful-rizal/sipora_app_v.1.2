(function () {
  function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    if (!input || !icon) return;

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('bi-eye');
      icon.classList.add('bi-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('bi-eye-slash');
      icon.classList.add('bi-eye');
    }
  }

  function handleGoogleSignIn(response, config) {
    fetch(config.googleAuthEndpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': config.csrfToken,
      },
      body: JSON.stringify({ token: response.credential }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          window.location.href = config.dashboardUrl;
        } else {
          alert('Login gagal: ' + data.message);
        }
      })
      .catch(() => alert('Terjadi kesalahan saat login dengan Google'));
  }

  function initSplash() {
    const splashScreen = document.getElementById('splash-screen');
    const loginContainer = document.querySelector('.login-container');

    if (!splashScreen || !loginContainer) return;

    const logo = splashScreen.querySelector('.splash-logo');
    const title = splashScreen.querySelector('.splash-title');
    const subtitle = splashScreen.querySelector('.splash-subtitle');
    const progressBar = splashScreen.querySelector('.splash-progress-bar');

    setTimeout(() => logo && logo.classList.add('show'), 100);
    setTimeout(() => title && title.classList.add('show'), 200);
    setTimeout(() => subtitle && subtitle.classList.add('show'), 400);
    setTimeout(() => {
      if (progressBar) progressBar.style.width = '100%';
    }, 500);

    setTimeout(() => {
      splashScreen.style.opacity = '0';
      loginContainer.style.opacity = '1';
      setTimeout(() => splashScreen.remove(), 500);
    }, 2500);
  }

  function initGoogle(config) {
    if (typeof google === 'undefined') return;

    const clientId = 'MASUKKAN_CLIENT_ID_ANDA_DISINI';
    google.accounts.id.initialize({
      client_id: clientId,
      callback: (response) => handleGoogleSignIn(response, config),
      auto_select: false,
      cancel_on_tap_outside: false,
    });

    const signInContainer = document.getElementById('googleSignInButton');
    if (!signInContainer) return;

    google.accounts.id.renderButton(signInContainer, {
      theme: 'outline',
      size: 'large',
      text: 'signin_with',
      width: 250,
      logo_alignment: 'center',
    });

    setTimeout(() => google.accounts.id.prompt(), 1000);
  }

  window.togglePassword = togglePassword;

  window.addEventListener('load', function () {
    const body = document.body;
    const config = {
      googleAuthEndpoint: body.dataset.googleAuthEndpoint || '',
      csrfToken: body.dataset.csrfToken || '',
      dashboardUrl: body.dataset.dashboardUrl || '/dashboard',
    };

    initSplash();
    initGoogle(config);
  });
})();
