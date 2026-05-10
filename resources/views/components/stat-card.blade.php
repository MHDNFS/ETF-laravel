@props([
  'color' => 'blue',
  'icon',
  'label',
  'value',
  'trend' => 'up',
  'trendText'
])

<div class="stat-card {{ $color }}">
  <div class="stat-icon {{ $color }}"><i class="fa-solid {{ $icon }}"></i></div>
  <div class="stat-label">{{ $label }}</div>
  <div class="stat-value">{{ $value }}</div>
  <div class="stat-change {{ $trend }}">
    <i class="fa-solid fa-arrow-{{ $trend }}"></i> {{ $trendText }}
  </div>
</div>
