@props([
    'type' => 'button',
    'icon' => null,
    'fullWidth' => false,
    'variant' => 'blue',
])

<button
    type="{{ $type }}"
    @class([
        'btn',
        'btn-blue' => $variant !== 'outline',
        'btn-outline' => $variant === 'outline',
    ])
    @if ($fullWidth) style="width:100%" @endif
    {{ $attributes }}
>
    @if ($icon)
        <i class="fa-solid {{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>
