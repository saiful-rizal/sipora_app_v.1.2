(function () {
  const body = document.body;
  const availabilityEndpoint = body.dataset.availabilityEndpoint || '';
  const csrfToken = body.dataset.csrfToken || '';
  const loginPage = body.dataset.loginPage || '/login';
  const registerSuccessMessage = body.dataset.registerSuccessMessage || '';

  let usernameTaken = false;
  let emailTaken = false;
  let nameTaken = false;

  const formFields = ['nama_lengkap', 'nomor_induk', 'reg_username', 'email', 'reg_password', 'confirm_password'];
  const totalSteps = 5;

  function updateProgress() {
    let filledFields = 0;
    formFields.forEach((fieldId) => {
      const field = document.getElementById(fieldId);
      if (field && field.value.trim() !== '') filledFields++;
    });

    const agreeTerms = document.getElementById('agreeTerms');
    if (agreeTerms && agreeTerms.checked) filledFields++;

    const progress = Math.round((filledFields / totalSteps) * 100);
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const progressStep = document.getElementById('progressStep');

    if (progressFill) progressFill.style.width = `${progress}%`;
    if (progressText) progressText.textContent = `${progress}% Selesai`;
    if (progressStep) progressStep.textContent = `Langkah ${filledFields} dari ${totalSteps}`;
  }

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

  async function checkAvailability(payload) {
    try {
      const res = await fetch(availabilityEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(payload),
      });
      return await res.json();
    } catch (err) {
      return { success: false, exists: false, fields: [] };
    }
  }

  function updateRegisterButtonState() {
    const submitBtn = document.getElementById('registerSubmitBtn');
    const pwd = document.getElementById('reg_password');
    const conf = document.getElementById('confirm_password');
    if (!submitBtn || !pwd || !conf) return;

    const pwMismatch = pwd.value.trim() !== conf.value.trim();
    const weakPassword = pwd.value.trim().length < 8;
    submitBtn.disabled = usernameTaken || emailTaken || nameTaken || pwMismatch || weakPassword;
    submitBtn.style.opacity = submitBtn.disabled ? '0.6' : '1';
  }

  function checkPasswordMatch() {
    const pwd = document.getElementById('reg_password');
    const confirm = document.getElementById('confirm_password');
    const warning = document.getElementById('passwordMatchWarning');
    const text = document.getElementById('passwordMatchText');
    const icon = document.getElementById('passwordMatchIcon');
    if (!pwd || !confirm || !warning || !text || !icon) return;

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
      icon.className = 'fas fa-exclamation-triangle';
    } else {
      warning.style.display = 'flex';
      warning.style.color = '#16a34a';
      text.textContent = 'Kata sandi sama';
      icon.className = 'fas fa-check-circle';
    }

    updateRegisterButtonState();
  }

  async function validateUsername() {
    const usernameEl = document.getElementById('reg_username');
    const warning = document.getElementById('usernameWarning');
    if (!usernameEl || !warning) return false;

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
    if (result.success && result.exists && result.fields.includes('username')) {
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
    if (!nameEl || !warning) return false;

    const fullname = nameEl.value.trim();
    if (fullname.length < 3) {
      nameTaken = false;
      warning.classList.remove('hidden');
      warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Masukan Nama lengkap anda</span>';
      updateRegisterButtonState();
      return false;
    }

    const result = await checkAvailability({ nama_lengkap: fullname });
    if (result.success && result.exists && result.fields.includes('nama_lengkap')) {
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
    if (!emailEl || !warning) return false;

    const email = emailEl.value.trim();
    if (!email.endsWith('.ac.id')) {
      emailTaken = false;
      warning.classList.remove('hidden');
      warning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>Hanya email dengan domain .ac.id yang diizinkan</span>';
      updateRegisterButtonState();
      return false;
    }

    const result = await checkAvailability({ email });
    if (result.success && result.exists && result.fields.includes('email')) {
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

  function showPasswordMismatchModal() {
    const modal = document.getElementById('passwordMismatchModal');
    const okBtn = document.getElementById('pwMismatchOkBtn');
    const confirm = document.getElementById('confirm_password');
    if (!modal || !okBtn || !confirm) return;

    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);

    okBtn.onclick = () => {
      modal.classList.remove('show');
      setTimeout(() => (modal.style.display = 'none'), 160);
      confirm.focus();
    };
  }

  function showRegistrationSuccessModal(message) {
    const modal = document.getElementById('registerSuccessModal');
    const msgEl = document.getElementById('registerSuccessMessage');
    const goBtn = document.getElementById('goToLoginBtn');
    if (!modal || !msgEl || !goBtn) return;

    if (message) msgEl.textContent = message;

    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
    goBtn.onclick = () => {
      window.location.href = loginPage;
    };

    setTimeout(() => {
      window.location.href = loginPage;
    }, 5000);
  }

  function bindFormEvents() {
    document.querySelector('.register-container').style.opacity = '1';

    formFields.forEach((fieldId) => {
      const field = document.getElementById(fieldId);
      if (field) field.addEventListener('input', updateProgress);
    });

    const agreeTerms = document.getElementById('agreeTerms');
    if (agreeTerms) agreeTerms.addEventListener('change', updateProgress);

    const passwordInput = document.getElementById('reg_password');
    const confirmInput = document.getElementById('confirm_password');
    const registerForm = document.getElementById('registerFormElement');

    if (passwordInput) passwordInput.addEventListener('input', checkPasswordMatch);
    if (confirmInput) confirmInput.addEventListener('input', checkPasswordMatch);

    if (registerForm) {
      registerForm.addEventListener('submit', function (e) {
        const pwdVal = passwordInput ? passwordInput.value.trim() : '';
        const confVal = confirmInput ? confirmInput.value.trim() : '';

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

    if (registerSuccessMessage) {
      showRegistrationSuccessModal(registerSuccessMessage);
    }
  }

  window.togglePassword = togglePassword;
  window.validateUsername = validateUsername;
  window.validateFullname = validateFullname;
  window.validateEmail = validateEmail;

  document.addEventListener('DOMContentLoaded', bindFormEvents);
})();
