<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <x-layout.styles title="Login Page" />
</head>

<body class="login-page">
  <div class="login-card">
    <div class="login-logo">
      <div class="login-logo-icon">
        <img src="/assets/img/company-logo.png" width="28" height="28" alt="" decoding="async">
      </div>
      <div class="login-logo-text">Mara<span>Web</span></div>
    </div>

    <h1 class="login-title">Welcome back</h1>
    <p class="login-sub">Sign in to access your ETF &amp; PTF dashboard</p>

    <div id="login-api-error" class="login-alert login-alert--error" role="alert" hidden></div>

    <form id="login-form" class="login-form" method="post" action="#" autocomplete="on">
      <div class="form-group">
        <label class="form-label" for="email">Email address</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="superadmin@gmail.com"
          autocomplete="username"
          required
          aria-describedby="login-error-email"
        >
        <p id="login-error-email" class="login-field-error" role="alert" hidden></p>
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control"
          placeholder="••••••••"
          autocomplete="current-password"
          required
          aria-describedby="login-error-password"
        >
        <p id="login-error-password" class="login-field-error" role="alert" hidden></p>
      </div>
      <div class="remember-row">
        <label>
          <input type="checkbox" id="remember" name="remember" value="1">
          Remember me
        </label>
      </div>
      <button type="submit" class="btn btn-blue" style="width:100%;padding:12px" id="login-submit">
        <i class="fa-solid fa-right-to-bracket"></i> Sign in
      </button>
    </form>
  </div>

  <script src="/assets/js/auth-api.js"></script>
  <script src="/assets/js/auth-guard.js"></script>
  <script src="/assets/js/auth-login.js"></script>
</body>

</html>

