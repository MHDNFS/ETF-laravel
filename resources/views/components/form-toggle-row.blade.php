{{--
  Label + helper text on the left, .switch on the right (Settings / preferences pattern).
  CSS: public/assets/css/app.css → .form-toggle-row, .form-toggle-row-text, .form-toggle-row-title, .form-toggle-row-desc
  Props: title, description, name (input name), id (optional; defaults to name), checked, disabled (booleans).
--}}

@props([
    'title',
    'description',
    'name',
    'id' => null,
    'checked' => false,
    'disabled' => false,
])

@php
    $inputId = $id ?? $name;
@endphp

<div class="form-toggle-row">
    <div class="form-toggle-row-text">
        <div class="form-toggle-row-title">{{ $title }}</div>
        <div class="form-toggle-row-desc">{{ $description }}</div>
    </div>
    <label class="switch">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $inputId }}"
            @checked($checked)
            @disabled($disabled)
        >
        <span class="switch-slider"></span>
    </label>
</div>
