@props([
    'label',
    'icon',
    'href' => '#',
    'badge' => null,
    'active' => false,
])

{{--
  Sidebar item component:
  - label: text shown in the sidebar button
  - icon: Font Awesome icon class
  - href: the link target for the item
  - badge: optional small badge text
  - active: whether this item is the current page
--}}

<a class="nav-item {{ $active ? 'active' : '' }}" href="{{ $href }}">
  <span class="nav-icon"><i class="fa-solid {{ $icon }}"></i></span>
  {{ $label }}
  @if($badge)
    <span class="nav-badge">{{ $badge }}</span>
  @endif
</a>
