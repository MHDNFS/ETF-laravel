@props([
    'id' => null,
    'name' => null,
    'placeholder' => null,
    'rows' => 4,
    'value' => '',
])

<textarea
    @if ($id) id="{{ $id }}" @endif
    @if ($name) name="{{ $name }}" @endif
    class="form-control"
    rows="{{ $rows }}"
    @if ($placeholder) placeholder="{{ $placeholder }}" @endif
    {{ $attributes }}
>{{ $value }}</textarea>
