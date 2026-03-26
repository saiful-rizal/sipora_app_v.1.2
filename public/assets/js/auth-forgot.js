(function () {
  function initForgotForm() {
    const emailInput = document.getElementById('email');
    const emailWarning = document.getElementById('emailWarning');
    const resetForm = document.getElementById('resetForm');
    const submitBtn = document.getElementById('submitBtn');

    if (!emailInput || !emailWarning || !resetForm || !submitBtn) return;

    function validateEmail() {
      const email = emailInput.value.trim().toLowerCase();
      const isAcId = email.endsWith('.ac.id');

      if (email && !isAcId) {
        emailInput.classList.add('error');
        emailWarning.classList.add('show');
        return false;
      }

      emailInput.classList.remove('error');
      emailWarning.classList.remove('show');
      return true;
    }

    emailInput.addEventListener('blur', validateEmail);
    emailInput.addEventListener('input', function () {
      if (emailInput.classList.contains('error')) {
        emailInput.classList.remove('error');
        emailWarning.classList.remove('show');
      }
    });

    resetForm.addEventListener('submit', function (event) {
      if (!validateEmail()) {
        event.preventDefault();
        return false;
      }

      submitBtn.classList.add('loading');
      submitBtn.disabled = true;
      return true;
    });
  }

  document.addEventListener('DOMContentLoaded', initForgotForm);
})();