/**
 * MaraWeb — API authentication (Sanctum Bearer tokens).
 * Layer 1: client-side checks before fetch.
 * Layer 2: Laravel validates POST /api/auth/login.
 * Layer 3: GET /api/auth/me confirms token before dashboard access.
 */
window.MaraWebAuth = (function () {
  const TOKEN_KEY = 'api_token';
  const USER_KEY = 'auth_user';

  function getToken() {
    return localStorage.getItem(TOKEN_KEY) || '';
  }

  function setToken(token) {
    if (token) {
      localStorage.setItem(TOKEN_KEY, token);
    }
  }

  function clearAuth() {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
  }

  function setUser(user) {
    if (user) {
      localStorage.setItem(USER_KEY, JSON.stringify(user));
    }
  }

  function getCachedUser() {
    try {
      const raw = localStorage.getItem(USER_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch (e) {
      return null;
    }
  }

  function isValidUser(user) {
    return !!(user && user.id && user.email);
  }

  /**
   * Step 2 — Frontend validation (runs before calling the API).
   *
   * @returns {{ valid: boolean, errors: Record<string, string>, email: string, password: string }}
   */
  function validateLoginForm(email, password) {
    const errors = {};
    const trimmedEmail = (email || '').trim();
    const pwd = password || '';

    if (!trimmedEmail) {
      errors.email = 'Email is required.';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(trimmedEmail)) {
      errors.email = 'Enter a valid email address.';
    }

    if (!pwd) {
      errors.password = 'Password is required.';
    }

    return {
      valid: Object.keys(errors).length === 0,
      errors: errors,
      email: trimmedEmail,
      password: pwd,
    };
  }

  function createAuthError(message, fieldErrors) {
    const err = new Error(message || 'Request failed.');
    err.fieldErrors = fieldErrors || null;
    return err;
  }

  async function apiFetch(path, options) {
    const opts = options || {};
    const headers = Object.assign(
      {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
      opts.headers || {}
    );

    const token = getToken();
    if (token) {
      headers.Authorization = 'Bearer ' + token;
    }

    const response = await fetch(path, {
      method: opts.method || 'GET',
      headers: headers,
      body: opts.body !== undefined ? JSON.stringify(opts.body) : undefined,
    });

    let data = {};
    try {
      data = await response.json();
    } catch (e) {
      data = {};
    }

    return { response: response, data: data };
  }

  function fieldErrorsFromResponse(data) {
    if (!data || !data.errors) {
      return null;
    }

    const out = {};
    Object.keys(data.errors).forEach(function (key) {
      if (data.errors[key] && data.errors[key][0]) {
        out[key] = data.errors[key][0];
      }
    });

    return Object.keys(out).length ? out : null;
  }

  function extractErrorMessage(data, fallback) {
    if (data && data.message && typeof data.message === 'string') {
      return data.message;
    }
    if (data && data.errors) {
      const firstKey = Object.keys(data.errors)[0];
      if (firstKey && data.errors[firstKey][0]) {
        return data.errors[firstKey][0];
      }
    }
    return fallback || 'Request failed.';
  }

  async function login(email, password, remember) {
    const validation = validateLoginForm(email, password);
    if (!validation.valid) {
      throw createAuthError('Please fix the errors below.', validation.errors);
    }

    const result = await apiFetch('/api/auth/login', {
      method: 'POST',
      body: {
        email: validation.email,
        password: validation.password,
        remember: !!remember,
        device_name: 'web-client',
      },
    });

    if (!result.response.ok) {
      throw createAuthError(
        extractErrorMessage(result.data, 'Invalid email or password.'),
        fieldErrorsFromResponse(result.data)
      );
    }

    if (!result.data.token) {
      throw createAuthError('Sign-in failed: no token received from server.');
    }

    if (!isValidUser(result.data.user)) {
      throw createAuthError('Sign-in failed: invalid user data from server.');
    }

    setToken(result.data.token);
    setUser(result.data.user);

    return result.data;
  }

  async function fetchMe() {
    const result = await apiFetch('/api/auth/me', { method: 'GET' });

    if (result.response.status === 401) {
      clearAuth();
      throw createAuthError('Unauthenticated');
    }

    if (!result.response.ok) {
      throw createAuthError(extractErrorMessage(result.data, 'Could not load user.'));
    }

    if (!isValidUser(result.data.user)) {
      clearAuth();
      throw createAuthError('Invalid session.');
    }

    setUser(result.data.user);

    return result.data.user;
  }

  /**
   * Step 3 — Confirm token with backend before showing dashboard.
   */
  async function ensureAuthenticated() {
    if (!getToken()) {
      throw createAuthError('No token');
    }

    return fetchMe();
  }

  async function logout() {
    const token = getToken();
    if (token) {
      try {
        await apiFetch('/api/auth/logout', { method: 'POST', body: {} });
      } catch (e) {
        /* still clear local session */
      }
    }
    clearAuth();
  }

  function isLoginPage() {
    const path = window.location.pathname.replace(/\/+$/, '') || '/';
    return path === '/' || path === '/login';
  }

  function isProtectedPage() {
    return !isLoginPage();
  }

  function redirectToLogin() {
    if (!isLoginPage()) {
      window.location.replace('/');
    }
  }

  function redirectToDashboard() {
    if (isLoginPage()) {
      window.location.replace('/dashboard');
    }
  }

  return {
    TOKEN_KEY: TOKEN_KEY,
    getToken: getToken,
    setToken: setToken,
    clearAuth: clearAuth,
    getCachedUser: getCachedUser,
    validateLoginForm: validateLoginForm,
    isValidUser: isValidUser,
    apiFetch: apiFetch,
    login: login,
    fetchMe: fetchMe,
    ensureAuthenticated: ensureAuthenticated,
    logout: logout,
    isLoginPage: isLoginPage,
    isProtectedPage: isProtectedPage,
    redirectToLogin: redirectToLogin,
    redirectToDashboard: redirectToDashboard,
  };
})();
