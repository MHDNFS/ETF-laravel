{{--
  Styled select wrapper (`.select2-custom`). On the front-end, `resources/js/app.js` upgrades
  these to Tom Select (search in the dropdown) unless `searchable` is false.

  Props:
    - id, name: forwarded to `<select>` when set.
    - searchable (default true): set false for a native-only select (no Tom Select).
--}}
@props([
    'id' => null,
    'name' => null,
    'searchable' => true,
])

<div
    class="select2-custom"
    @if (! $searchable) data-searchable="false" @endif
>
    <select
        @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes }}
    >
        {{ $slot }}
    </select>
</div>
