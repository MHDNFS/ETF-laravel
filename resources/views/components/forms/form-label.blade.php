@props([
    'for' => null,
])

<label @if ($for) for="{{ $for }}" @endif class="form-label">
    {{ $slot }}
</label>
