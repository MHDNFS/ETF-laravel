@props([
    'label',
    'value',
    'valueClass' => '',
])

<div class="metric-row">
    <span class="metric-lbl">{{ $label }}</span>
    <span class="metric-val {{ $valueClass }}">{{ $value }}</span>
</div>
