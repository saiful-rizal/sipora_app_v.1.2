(function () {
  function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const passwordIcon = document.getElementById(inputId + '-icon');
    if (!passwordInput || !passwordIcon) return;

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      passwordIcon.classList.remove('bi-eye');
      passwordIcon.classList.add('bi-eye-slash');
    } else {
      passwordInput.type = 'password';
      passwordIcon.classList.remove('bi-eye-slash');
      passwordIcon.classList.add('bi-eye');
    }
  }

  function redirectToLogin() {
    const loginUrl = document.body.dataset.loginUrl || '/login';
    window.location.href = loginUrl;
  }

  function initResetPassword() {
    const showSuccessModal = document.body.dataset.showSuccessModal === '1';
    if (showSuccessModal) {
      const modal = document.getElementById('successModal');
      if (modal) modal.classList.add('show');
    }

    document.querySelectorAll('.form-input').forEach((input) => {
      input.addEventListener('input', () => {
        const alertBox = document.querySelector('.alert-error');
        if (alertBox) alertBox.style.display = 'none';
      });
    });
  }

  window.togglePassword = togglePassword;
  window.redirectToLogin = redirectToLogin;

  document.addEventListener('DOMContentLoaded', initResetPassword);
})();