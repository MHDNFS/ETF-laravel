/**
 * Login page — client validation, then POST /api/auth/login via fetch().
 */
document.addEventListener('DOMContentLoaded', function () {
  const Auth = window.MaraWebAuth;
  const form = document.getElementById('login-form');
  if (!form || !Auth) {
    return;
  }

  const errorEl = document.getElementById('login-api-error');
  const submitBtn = document.getElementById('login-submit');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const rememberInput = document.getElementById('remember');

  function fieldErrorEl(field) {
    return document.getElementById('login-error-' + field);
  }

  function clearFieldErrors() {
    ['email', 'password'].forEach(function (field) {
      const el = fieldErrorEl(field);
      const input = field === 'email' ? emailInput : passwordInput;
      if (el) {
        el.textContent = '';
        el.hidden = true;
      }
      if (input) {
        input.classList.remove('is-invalid');
        input.setAttribute('aria-invalid', 'false');
      }
    });
  }

  function showFieldErrors(errors) {
    if (!errors) {
      return;
    }

    Object.keys(errors).forEach(function (field) {
      const el = fieldErrorEl(field);
      const input = field === 'email' ? emailInput : passwordInput;
      if (el) {
        el.textContent = errors[field];
        el.hidden = false;
      }
      if (input) {
        input.classList.add('is-invalid');
        input.setAttribute('aria-invalid', 'true');
      }
    });
  }

  function showApiError(message) {
    if (errorEl) {
      errorEl.textContent = message || 'Unable to sign in.';
      errorEl.hidden = false;
    }
  }

  function hideApiError() {
    if (errorEl) {
      errorEl.hidden = true;
      errorEl.textContent = '';
    }
  }

  form.addEventListener('submit', async function (event) {
    event.preventDefault();
    hideApiError();
    clearFieldErrors();

    const validation = Auth.validateLoginForm(
      emailInput ? emailInput.value : '',
      passwordInput ? passwordInput.value : ''
    );

    if (!validation.valid) {
      showFieldErrors(validation.errors);
      showApiError('Please fix the errors below.');
      return;
    }

    if (submitBtn) {
      submitBtn.disabled = true;
    }

    try {
      await Auth.login(
        validation.email,
        validation.password,
        rememberInput ? rememberInput.checked : false
      );
      window.location.replace('/dashboard');
    } catch (error) {
      if (error.fieldErrors) {
        showFieldErrors(error.fieldErrors);
      }
      showApiError(error.message || 'Unable to sign in.');
      if (submitBtn) {
        submitBtn.disabled = false;
      }
    }
  });
});
