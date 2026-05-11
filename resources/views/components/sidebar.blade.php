@props([
    'items' => [],
    'active' => 'dashboard',
])

{{--
  Sidebar component props:
  - items: an array of links and section labels
  - active: the key of the currently selected link
--}}

@php
$items = $items ?: [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'href' => '/index'],
    ['key' => 'profile', 'label' => 'My Profile', 'icon' => 'fa-user-circle', 'href' => '/profile'],
    ['section' => 'ETF Module'],
    ['key' => 'etf-calculator', 'label' => 'ETF Calculator', 'icon' => 'fa-calculator', 'href' => '/etf-calculator'],
    ['key' => 'fund-explorer', 'label' => 'Fund Explorer', 'icon' => 'fa-layer-group', 'href' => '/etf-funds', 'badge' => '12'],
    ['key' => 'customer-management', 'label' => 'Customer Management', 'icon' => 'fa-users', 'href' => '/customer-management'],
    ['section' => 'PTF Module'],
    ['key' => 'ptf-calculator', 'label' => 'PTF Calculator', 'icon' => 'fa-sliders', 'href' => '/ptf-calculator'],
    ['key' => 'ptf-portfolio', 'label' => 'Portfolio Manager', 'icon' => 'fa-briefcase', 'href' => '/ptf-portfolio'],
    ['section' => 'Reports'],
    ['key' => 'reports', 'label' => 'Reports & Export', 'icon' => 'fa-file-chart-column', 'href' => '/reports'],
    ['key' => 'settings', 'label' => 'Settings', 'icon' => 'fa-gear', 'href' => '/settings', 'badge' => '!'],
];
@endphp

<nav class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon"><i class="fa-solid fa-chart-line"></i></div>
    <div class="sidebar-logo-text">Quant<span>Edge</span></div>
  </div>

  @foreach ($items as $item)
    @if (isset($item['section']))
      {{-- When item has a section key, render a section header instead of a link --}}
      <div class="sidebar-section">{{ $item['section'] }}</div>
    @else
      {{-- Render a reusable sidebar button component for each normal item --}}
      <x-sidebar-item
        :label="$item['label']"
        :icon="$item['icon']"
        :href="$item['href']"
        :badge="$item['badge'] ?? null"
        :active="$active === $item['key']"
      />
    @endif
  @endforeach

  <div class="sidebar-profile">
    <div class="avatar"><span>AA</span><div class="online-dot"></div></div>
    <div class="sidebar-profile-info">
      <div class="sidebar-profile-name">Alex Analyst</div>
      <div class="sidebar-profile-role">Senior Portfolio Manager</div>
    </div>
    <a href="/" class="sidebar-profile-btn" title="Logout"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
  </div>
</nav>
