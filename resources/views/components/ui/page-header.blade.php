{{--
  Page heading: .page-title + optional .page-sub (same pattern on dashboard, reports, profile, etc.).
  Props:
    - title (required): main heading text.
    - subtitle (optional): full subheading line (e.g. "Monday, 3 May 2026 · Portfolio snapshot…" or a single tagline).
  Later you can build subtitle in the route/controller (e.g. now()->format(...) . ' · ' . $tagline) and pass it here.
--}}

@props([
    'title',
    'subtitle' => null,
])

<div class="page-title">{{ $title }}</div>
@if (filled($subtitle))
    <div class="page-sub">{{ $subtitle }}</div>
@endif
