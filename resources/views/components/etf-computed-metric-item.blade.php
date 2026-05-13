@props([
    'label',
    'value',
    'valueId' => null,
    'tone' => null,
])

<div class="calc-result-item">
    <div class="rlabel">{{ $label }}</div>
    <div class="rvalue @if ($tone) {{ $tone }} @endif" @if ($valueId) id="{{ $valueId }}" @endif>{{ $value }}</div>
</div>
