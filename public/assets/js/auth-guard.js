/**
 * Step 3 — Route guard: token in localStorage is not enough.
 * Calls GET /api/auth/me before dashboard is shown or auto-redirect from login.
 */
(function () {
  const Auth = window.MaraWebAuth;
  if (!Auth) {
    return;
  }

  function setText(id, value) {
    const el = document.getElementById(id);
    if (el) {
      el.textContent = value || '';
    }
  }

  function hydrateUserUi(user) {
    if (!user) {
      return;
    }

    const initials = user.initials || 'U';
    const name = user.name || 'User';
    const role = user.role_label || user.role || '';
    const email = user.email || '';

    setText('auth-user-initials', initials);
    setText('auth-user-initials-menu', initials);
    setText('auth-user-name', name);
    setText('auth-user-name-menu', name);
    setText('auth-user-role', role);
    setText('auth-user-email-menu', email);

    document.querySelectorAll('[data-auth-initials]').forEach(function (el) {
      el.textContent = initials;
    });
    document.querySelectorAll('[data-auth-name]').forEach(function (el) {
      el.textContent = name;
    });
    document.querySelectorAll('[data-auth-role]').forEach(function (el) {
      el.textContent = role;
    });
  }

  function markAuthReady() {
    document.body.classList.add('auth-ready');
    document.body.classList.remove('auth-pending');
  }

  function bindLogoutButtons() {
    document.querySelectorAll('[data-auth-logout]').forEach(function (btn) {
      btn.addEventListener('click', async function (event) {
        event.preventDefault();
        await Auth.logout();
        Auth.redirectToLogin();
      });
    });
  }

  document.addEventListener('DOMContentLoaded', async function () {
    bindLogoutButtons();

    if (Auth.isLoginPage()) {
      if (!Auth.getToken()) {
        return;
      }

      try {
        const user = await Auth.ensureAuthenticated();
        if (Auth.isValidUser(user)) {
          Auth.redirectToDashboard();
        }
      } catch (e) {
        Auth.clearAuth();
      }

      return;
    }

    if (!Auth.isProtectedPage()) {
      return;
    }

    if (!Auth.getToken()) {
      Auth.redirectToLogin();
      return;
    }

    try {
      const user = await Auth.ensureAuthenticated();
      hydrateUserUi(user);
      markAuthReady();
    } catch (e) {
      Auth.clearAuth();
      Auth.redirectToLogin();
    }
  });
})();
