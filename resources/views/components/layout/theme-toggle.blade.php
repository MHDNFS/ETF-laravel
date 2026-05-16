{{--
  Header theme control: toggles html[data-theme="light"] (see public/assets/css/app.css + public/assets/js/app.js).
  Future Tailwind: keep data-theme on <html> and add matching .dark / class strategy in @custom-variant if you adopt Tailwind color tokens.
--}}

<button
    type="button"
    class="hbtn theme-toggle"
    id="theme-toggle"
    onclick="toggleAppTheme()"
    aria-pressed="false"
    title="Switch to light mode"
>
    <i class="fa-solid fa-sun" id="theme-toggle-icon" aria-hidden="true"></i>
</button>
