const authConfig = document.body?.dataset || {};
const availabilityEndpoint = authConfig.availabilityEndpoint || '/auth/check-user-exists';
const googleAuthEndpoint = authConfig.googleAuthEndpoint || '/auth/google-auth';
const csrfToken = authConfig.csrfToken || '';
const dashboardUrl = authConfig.dashboardUrl || '/dashboard';
const openRegisterTab = authConfig.openRegister === '1';
const registerSuccessMessage = authConfig.registerSuccess || '';

    window.addEventListener('load', function() {
      const splashScreen = document.getElementById('splash-screen');
      const loginContainer = document.querySelector('.login-container');
      const logo = splashScreen.querySelector('.splash-logo');
      const title = splashScreen.querySelector('.splash-title');
      const subtitle = splashScreen.querySelector('.splash-subtitle');
      const progressBar = splashScreen.querySelector('.splash-progress-bar');

      const splashDuration = 2500;

      setTimeout(() => {
        logo.classList.add('show');
      }, 100);

      setTimeout(() => {
        title.classList.add('show');
      }, 200);

      setTimeout(() => {
        subtitle.classList.add('show');
      }, 400);

      setTimeout(() => {
        progressBar.style.width = '100%';
      }, 500);

      setTimeout(() => {
        splashScreen.style.opacity = '0';
        loginContainer.style.opacity = '1';

        setTimeout(() => {
          splashScreen.remove();
        }, 500);
      }, splashDuration);
    });

    let currentStep = 0;
    const totalSteps = 5;
    const formFields = [
      'nama_lengkap',
      'nomor_induk',
      'reg_username',
      'email',
      'reg_password',
      'confirm_password'
    ];

    function updateProgress() {
      const form = document.getElementById('registerFormElement');
      let filledFields = 0;

      formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && field.value.trim() !== '') {
          filledFields++;
        }
      });

      const agreeTerms = document.getElementById('agreeTerms');
      if (agreeTerms && agreeTerms.checked) {
        filledFields++;
      }

      const progress = Math.round((filledFields / totalSteps) * 100);

      const progressFill = document.getElementById('progressFill');
      const progressText = document.getElementById('progressText');
      const progressStep = document.getElementById('progressStep');

      progressFill.style.width = `${progress}%`;
      progressText.textContent = `${progress}% Selesai`;
      progressStep.textContent = `Langkah ${filledFields} dari ${totalSteps}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
      formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
          field.addEventListener('input', updateProgress);
        }
      });

      const agreeTerms = document.getElementById('agreeTerms');
      if (agreeTerms) {
        agreeTerms.addEventListener('change', updateProgress);
      }

      if (openRegisterTab) {
        switchTab('register');
      }
    });

    function checkPasswordMatch() {
      const pwd = document.getElementById('reg_password');
      const confirm = document.getElementById('confirm_password');
      const warning = document.getElementById('passwordMatchWarning');
      const text = document.getElementById('passwordMatchText');
      const submitBtn = document.getElementById('registerSubmitBtn');

      if (!pwd || !confirm || !warning || !submitBtn) return;

      const a = pwd.value.trim();
      const b = confirm.value.trim();

      if (b === '') {
        warning.style.display = 'none';
        updateRegisterButtonState();
        return;
      }

      if (a !== b) {
        warning.style.display = 'flex';
        warning.style.color = '#dc2626';
        text.textContent = 'Kata sandi tidak sama';
        const iconEl = document.getElementById('passwordMatchIcon');
        if (iconEl) {
          iconEl.className = 'fas fa-exclamation-triangle';
        }
        updateRegisterButtonState();
      } else {
        warning.style.display = 'flex';
        warning.style.color = '#16a34a';
        text.textContent = 'Kata sandi sama';
        const iconEl = document.getElementById('passwordMatchIcon');
        if (iconEl) {
          iconEl.className = 'fas fa-check-circle';
        }
        updateRegisterButtonState();
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      const pwd = document.getElementById('reg_password');
      const confirm = document.getElementById('confirm_password');
      const registerForm = document.getElementById('registerFormElement');

      if (pwd) {
        pwd.addEventListener('input', function() {
          checkPasswordMatch();
        });
      }

      if (confirm) {
        confirm.addEventListener('input', checkPasswordMatch);
      }

      if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
          const pwdVal = document.getElementById('reg_password').value.trim();
          const confVal = document.getElementById('confirm_password').value.trim();

          if (pwdVal !== confVal) {
            e.preventDefault();
            checkPasswordMatch();
            showPasswordMismatchModal();
            return false;
          }

          if (pwdVal.length < 8) {
            e.preventDefault();
            alert('Password minimal 8 karakter');
            return false;
          }

          return true;
        });
      }
    });

    function switchTab(tab) {
      const loginTab = document.getElementById('loginTab');
      const registerTab = document.getElementById('registerTab');
      const loginForm = document.getElementById('loginForm');
      const registerForm = document.getElementById('registerForm');

      if (tab === 'login') {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
      } else {
        loginTab.classList.remove('active');
        registerTab.classList.add('active');
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        updateProgress();
      }
    }

    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(inputId + '-icon');

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

    let usernameTaken = false;
    let emailTaken = false;
    let nameTaken = false;

    async function checkAvailability(payload) {
      try {
        const res = await fetch(availabilityEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(payload)
        });
        return await res.json();
      } catch (err) {
        console.error('Check availability failed', err);
        return { success: false, exists: false };
      }
    }

    function updateRegisterButtonState() {
      const submitBtn = document.getElementById('registerSubmitBtn');
      const pwd = document.getElementById('reg_password');
      const conf = document.getElementById('confirm_password');
      const pwMismatch = (pwd && conf) ? (pwd.value.trim() !== conf.value.trim()) : false;
      const weakPassword = pwd ? (pwd.value.trim().length < 8) : true;

      if (!submitBtn) return;
      submitBtn.disabled = usernameTaken || emailTaken || nameTaken || pwMismatch || weakPassword;
      submitBtn.style.opacity = submitBtn.disabled ? 0.6 : 1;
    }

    async function validateUsername() {
      const usernameEl = document.getElementById('reg_username');
      const warning = document.getElementById('usernameWarning');
      if (!usernameEl) return false;

      const username = usernameEl.value.trim();

      if (username.length < 3) {
        usernameTaken = false;
        warning.classList.remove('hidden');
        warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Masukan Username anda</span>';
        updateRegisterButtonState();
        return false;
      }

      if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        usernameTaken = false;
        warning.classList.remove('hidden');
        warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Username hanya boleh mengandung huruf, angka, dan underscore</span>';
        updateRegisterButtonState();
        return false;
      }

      const result = await checkAvailability({ username });
      if (result && result.success && result.exists && result.fields && result.fields.includes('username')) {
        usernameTaken = true;
        warning.classList.remove('hidden');
        warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Username sudah terdaftar. Silakan pilih username lain.</span>';
      } else {
        usernameTaken = false;
        warning.classList.add('hidden');
      }

      updateRegisterButtonState();
      return !usernameTaken;
    }

    async function validateFullname() {
      const nameEl = document.getElementById('nama_lengkap');
      const warning = document.getElementById('namaWarning');
      if (!nameEl) return false;

      const fullname = nameEl.value.trim();

      if (fullname.length < 3) {
        nameTaken = false;
        warning.classList.remove('hidden');
        warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Masukan Nama lengkap anda</span>';
        updateRegisterButtonState();
        return false;
      }

      const result = await checkAvailability({ nama_lengkap: fullname });
      if (result && result.success && result.exists && result.fields && result.fields.includes('nama_lengkap')) {
        nameTaken = true;
        warning.classList.remove('hidden');
        warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Nama lengkap sudah terdaftar. Periksa data sebelumnya.</span>';
      } else {
        nameTaken = false;
        warning.classList.add('hidden');
      }

      updateRegisterButtonState();
      return !nameTaken;
    }

    async function validateEmail() {
      const emailEl = document.getElementById('email');
      const warning = document.getElementById('emailWarning');
      if (!emailEl) return false;

      const email = emailEl.value.trim();

      if (!email.endsWith('.ac.id')) {
        emailTaken = false;
        warning.classList.remove('hidden');
        warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Hanya email dengan domain .ac.id yang diizinkan</span>';
        updateRegisterButtonState();
        return false;
      }

      const result = await checkAvailability({ email });
      if (result && result.success && result.exists && result.fields && result.fields.includes('email')) {
        emailTaken = true;
        warning.classList.remove('hidden');
        warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Email sudah terdaftar. Gunakan email lain atau login.</span>';
      } else {
        emailTaken = false;
        warning.classList.add('hidden');
      }

      updateRegisterButtonState();
      return !emailTaken;
    }

    window.onload = function() {
      const clientId = 'MASUKKAN_CLIENT_ID_ANDA_DISINI';

      if (typeof google !== 'undefined') {
        google.accounts.id.initialize({
          client_id: clientId,
          callback: handleGoogleSignIn,
          auto_select: false,
          cancel_on_tap_outside: false
        });

        google.accounts.id.renderButton(
          document.getElementById('googleSignInButton'),
          {
            theme: 'outline',
            size: 'large',
            text: 'signin_with',
            width: 250,
            logo_alignment: 'center'
          }
        );

        setTimeout(function() {
          google.accounts.id.prompt();
        }, 1000);
      }
    };

    function handleGoogleSignIn(response) {
      fetch(googleAuthEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          token: response.credential
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.href = dashboardUrl;
        } else {
          alert('Login gagal: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat login dengan Google');
      });
    }

    function showRegistrationSuccessModal(message) {
      const modal = document.getElementById('registerSuccessModal');
      const msg = document.getElementById('registerSuccessMessage');
      if (!modal) return;
      if (msg && message) msg.textContent = message;
      modal.style.display = 'flex';
      setTimeout(() => modal.classList.add('show'), 10);

      setTimeout(() => {
        modal.classList.remove('show');
        setTimeout(() => {
          modal.style.display = 'none';
          switchTab('login');
        }, 180);
      }, 5000);

      const goLogin = document.getElementById('goToLoginBtn');

      if (goLogin) goLogin.onclick = () => {
        if (modal) {
          modal.classList.remove('show');
          setTimeout(() => modal.style.display = 'none', 180);
        }
        try { switchTab('login'); } catch (e) {}
      };
    }

    function showPasswordMismatchModal() {
      const modal = document.getElementById('passwordMismatchModal');
      if (!modal) return;
      modal.style.display = 'flex';
      setTimeout(() => modal.classList.add('show'), 10);

      const okBtn = document.getElementById('pwMismatchOkBtn');
      if (okBtn) okBtn.onclick = () => {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 160);
        const conf = document.getElementById('confirm_password');
        if (conf) conf.focus();
      };
    }

    if (registerSuccessMessage) {
      document.addEventListener('DOMContentLoaded', function() {
        showRegistrationSuccessModal(registerSuccessMessage);
      });
    }