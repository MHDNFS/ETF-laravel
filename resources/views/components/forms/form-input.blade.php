@props([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => null,
    'step' => null,
    'icon' => null,
])

@if ($icon)
    <div class="input-icon-wrap">
        <i class="fa-solid {{ $icon }} icon"></i>
        <input
            @if ($id) id="{{ $id }}" @endif
            @if ($name) name="{{ $name }}" @endif
            type="{{ $type }}"
            class="form-control"
            value="{{ $value }}"
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            @if ($step !== null) step="{{ $step }}" @endif
            style="padding-left:30px"
            {{ $attributes }}
        >
    </div>
@else
    <input
        @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif
        type="{{ $type }}"
        class="form-control"
        value="{{ $value }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($step !== null) step="{{ $step }}" @endif
        {{ $attributes }}
    >
@endif
