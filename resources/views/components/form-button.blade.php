@props([
    'type' => 'button',
    'icon' => null,
    'fullWidth' => false,
])

<button
    type="{{ $type }}"
    class="btn btn-blue"
    @if ($fullWidth) style="width:100%" @endif
    {{ $attributes }}
>
    @if ($icon)
        <i class="fa-solid {{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>
