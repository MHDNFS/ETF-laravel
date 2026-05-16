<!-- {{--
  <x-recent-report-item /> — one row in Recent Reports (Reports page).
  CSS: public/assets/css/app.css → .report-item, .report-item-body, .report-download-btn; reuses .notif-icon, .notif-text, .notif-time
  Props: icon (e.g. fa-file-pdf), iconStyle (inline icon box), title (plain text, shown bold), meta (subtitle line).
  Download control is visual only: type="button" with no handler (pointer + hover styling; no download yet).
--}} -->

@props([
    'icon',
    'iconStyle',
    'title',
    'meta',
])

<div class="report-item">
    <div class="notif-icon" style="{{ $iconStyle }}"><i class="fa-solid {{ $icon }}"></i></div>
    <div class="report-item-body">
        <div class="notif-text"><strong>{{ $title }}</strong></div>
        <div class="notif-time">{{ $meta }}</div>
    </div>
    <button
        type="button"
        class="report-download-btn"
        aria-disabled="true"
        title="Download coming soon"
        aria-label="Download {{ $title }} (coming soon)"
    ><i class="fa-solid fa-download"></i></button>
</div>
