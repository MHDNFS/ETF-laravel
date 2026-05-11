{{--
  <x-profile-stat /> — one metric in the profile banner stats row.
  Styles: public/assets/css/app.css → .profile-stat, .val, .lbl
  Props: value (main number/text), label (caption below).
--}}




@props([
    'value',
    'label',
])

<div class="profile-stat">
    <div class="val">{{ $value }}</div>
    <div class="lbl">{{ $label }}</div>
</div>
