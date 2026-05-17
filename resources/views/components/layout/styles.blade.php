{{-- Global head assets: theme flash, title, fonts, app CSS, Vite bundle. --}}
<script>
  (function () {
    try {
      if (localStorage.getItem('qe-theme') === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
        document.documentElement.style.colorScheme = 'light';
      } else {
        document.documentElement.removeAttribute('data-theme');
        document.documentElement.style.colorScheme = 'dark';
      }
    } catch (e) {}
  })();
</script>
<title>@yield('title', 'Home')</title>
<link
  href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@300;400;500&family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap"
  rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
