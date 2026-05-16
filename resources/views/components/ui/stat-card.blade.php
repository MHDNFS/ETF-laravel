@props([
    'color' => 'blue',
    'icon',
    'label',
    'value',
    'trend' => 'up',
    'trendText',
    /** Optional Font Awesome icon class (e.g. fa-triangle-exclamation) instead of the default arrow for the trend row. */
    'trendIcon' => null,
])

<div class="stat-card {{ $color }}">
    <div class="stat-icon {{ $color }}"><i class="fa-solid {{ $icon }}"></i></div>
    <div class="stat-label">{{ $label }}</div>
    <div class="stat-value">{{ $value }}</div>
    <div class="stat-change {{ $trend }}">
        @if (filled($trendIcon))
            <i class="fa-solid {{ $trendIcon }}"></i>
        @else
            <i class="fa-solid fa-arrow-{{ $trend === 'down' ? 'down' : 'up' }}"></i>
        @endif
        {{ $trendText }}
    </div>
</div>
