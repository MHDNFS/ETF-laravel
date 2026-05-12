{{--
  <x-profile-activity-item /> — one row in Recent Activity.
  CSS: public/assets/css/app.css → .notif-item, .notif-icon, .notif-text, .notif-time
  Props: icon (e.g. fa-check, fa-solid added here), iconStyle (inline icon box), time, text (HTML line; demo-only trusted strings).
--}}




@props([
    'icon',
    'iconStyle',
    'time',
    'text',
])

<div class="notif-item">
    <div class="notif-icon" style="{{ $iconStyle }}"><i class="fa-solid {{ $icon }}"></i></div>
    <div>
        <div class="notif-text">{!! $text !!}</div>
        <div class="notif-time">{{ $time }}</div>
    </div>
</div>
