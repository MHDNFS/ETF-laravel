/* ============================================================
   QuantEdge — ETF & PTF Dashboard
   assets/js/app.js  —  Core UI Logic
   ============================================================ */

/* ─── SIDEBAR TOGGLE ──────────────────────────────────────── */
function toggleSidebar() {
  const sb = document.getElementById('sidebar');
  const ov = document.getElementById('sidebar-overlay');
  const ma = document.getElementById('main-area');
  if (window.innerWidth <= 900) {
    sb.classList.toggle('open');
    ov.classList.toggle('open');
  } else {
    sb.classList.toggle('collapsed');
    if (ma) ma.classList.toggle('expanded');
  }
}

function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  const ov = document.getElementById('sidebar-overlay');
  if (ov) ov.classList.remove('open');
}

/* ─── AVATAR DROPDOWN ─────────────────────────────────────── */
function toggleAvatarMenu() {
  document.getElementById('avatar-menu').classList.toggle('open');
}
function closeAvatarMenu() {
  const m = document.getElementById('avatar-menu');
  if (m) m.classList.remove('open');
}
document.addEventListener('click', function (e) {
  const m = document.getElementById('avatar-menu');
  if (!m) return;
  if (!e.target.closest('.header-avatar') && !e.target.closest('#avatar-menu')) {
    m.classList.remove('open');
  }
});

/* ─── ACTIVE NAV ──────────────────────────────────────────── */
(function () {
  const page = location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-item').forEach(function (el) {
    const href = el.getAttribute('href') || '';
    if (href === page) el.classList.add('active');
  });
})();

/* ─── MODALS ──────────────────────────────────────────────── */
function showModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.add('open');
}
function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('open');
}
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('open');
  }
});

/* ─── TABS ────────────────────────────────────────────────── */
function switchTab(btn, contentId) {
  btn.closest('.tabs').querySelectorAll('.tab-btn').forEach(function (b) {
    b.classList.remove('active');
  });
  btn.classList.add('active');
  document.querySelectorAll('.tab-content').forEach(function (c) {
    c.classList.remove('active');
  });
  const target = document.getElementById(contentId);
  if (target) target.classList.add('active');
}

/* ─── TOAST NOTIFICATIONS ─────────────────────────────────── */
function showToast(type, title, msg) {
  const icons = {
    success: 'fa-circle-check',
    error:   'fa-circle-xmark',
    info:    'fa-circle-info'
  };
  let tc = document.getElementById('toast-container');
  if (!tc) {
    tc = document.createElement('div');
    tc.id = 'toast-container';
    tc.className = 'toast-container';
    document.body.appendChild(tc);
  }
  const t = document.createElement('div');
  t.className = 'toast ' + type;
  t.innerHTML =
    '<i class="toast-icon fa-solid ' + icons[type] + '"></i>' +
    '<div class="toast-text">' +
      '<div class="toast-title">' + title + '</div>' +
      '<div class="toast-msg">' + msg + '</div>' +
    '</div>';
  tc.appendChild(t);
  setTimeout(function () {
    t.style.opacity = '0';
    t.style.transform = 'translateX(20px)';
    t.style.transition = 'all 0.3s';
    setTimeout(function () { t.remove(); }, 300);
  }, 3500);
}

/* ─── PASSWORD TOGGLE ─────────────────────────────────────── */
function togglePw() {
  const f = document.getElementById('pw-field');
  const e = document.getElementById('pw-eye');
  if (!f) return;
  if (f.type === 'password') {
    f.type = 'text';
    if (e) e.className = 'fa-regular fa-eye-slash';
  } else {
    f.type = 'password';
    if (e) e.className = 'fa-regular fa-eye';
  }
}

/* ─── THEME (light / dark via html[data-theme]) + Settings toggle sync ─ */
(function () {
  var THEME_KEY = 'qe-theme';

  function isLight() {
    return document.documentElement.getAttribute('data-theme') === 'light';
  }

  function applyStoredTheme() {
    try {
      if (localStorage.getItem(THEME_KEY) === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
        document.documentElement.style.colorScheme = 'light';
      } else {
        document.documentElement.removeAttribute('data-theme');
        document.documentElement.style.colorScheme = 'dark';
      }
    } catch (e) {}
  }

  function syncThemeToggleUi() {
    var icon = document.getElementById('theme-toggle-icon');
    var btn = document.getElementById('theme-toggle');
    var light = isLight();
    if (icon) {
      icon.className = light ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
    }
    if (btn) {
      btn.setAttribute('aria-pressed', light ? 'true' : 'false');
      btn.setAttribute('title', light ? 'Switch to dark mode' : 'Switch to light mode');
    }
  }

  /** Settings card: checked = dark theme on. */
  function syncSettingsThemeCheckbox() {
    var t = document.getElementById('ui_dark_mode');
    if (t) {
      t.checked = !isLight();
    }
  }

  window.toggleAppTheme = function () {
    var nextLight = !isLight();
    try {
      localStorage.setItem(THEME_KEY, nextLight ? 'light' : 'dark');
    } catch (e) {}
    if (nextLight) {
      document.documentElement.setAttribute('data-theme', 'light');
      document.documentElement.style.colorScheme = 'light';
    } else {
      document.documentElement.removeAttribute('data-theme');
      document.documentElement.style.colorScheme = 'dark';
    }
    syncThemeToggleUi();
    syncSettingsThemeCheckbox();
  };

  function initSettingsThemeCheckbox() {
    var t = document.getElementById('ui_dark_mode');
    if (!t) {
      return;
    }
    t.addEventListener('change', function () {
      var wantDark = t.checked;
      try {
        localStorage.setItem(THEME_KEY, wantDark ? 'dark' : 'light');
      } catch (e) {}
      if (wantDark) {
        document.documentElement.removeAttribute('data-theme');
        document.documentElement.style.colorScheme = 'dark';
      } else {
        document.documentElement.setAttribute('data-theme', 'light');
        document.documentElement.style.colorScheme = 'light';
      }
      syncThemeToggleUi();
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    applyStoredTheme();
    syncThemeToggleUi();
    syncSettingsThemeCheckbox();
    initSettingsThemeCheckbox();
  });
})();
