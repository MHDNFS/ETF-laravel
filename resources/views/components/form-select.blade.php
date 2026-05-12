@props([
    'id' => null,
    'name' => null,
])

<div class="select2-custom">
    <select
        @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes }}
    >
        {{ $slot }}
    </select>
</div>
