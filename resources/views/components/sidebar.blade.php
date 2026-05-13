@props([
    'active' => 'dashboard',
])

{{--
  Sidebar: explicit section headers and <x-sidebar-item> per link.
  Pass :active from the layout (same keys as route $activeSidebar).
--}}

<nav class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon"><i class="fa-solid fa-chart-line"></i></div>
    <div class="sidebar-logo-text">Quant<span>Edge</span></div>
  </div>

  <div class="sidebar-section">Overview</div>
  <x-sidebar-item label="Dashboard" icon="fa-gauge-high" href="/index" :active="$active === 'dashboard'" />
  <x-sidebar-item label="My Profile" icon="fa-user-circle" href="/profile" :active="$active === 'profile'" />

  <div class="sidebar-section">ETF Module</div>
  <x-sidebar-item label="ETF Calculator" icon="fa-calculator" href="/etf-calculator" :active="$active === 'etf-calculator'" />
  <x-sidebar-item label="Fund Explorer" icon="fa-layer-group" href="/etf-funds" badge="12" :active="$active === 'etf-funds'" />
  <x-sidebar-item label="Customer Management" icon="fa-users" href="/customer-management" :active="$active === 'customer-management'" />

  <div class="sidebar-section">PTF Module</div>
  <x-sidebar-item label="Portfolio Manager" icon="fa-briefcase" href="/ptf-portfolio" :active="$active === 'ptf-portfolio'" />

  <div class="sidebar-section">Reports</div>
  <x-sidebar-item label="Reports & Export" icon="fa-file-export" href="/reports" :active="$active === 'reports'" />
  <x-sidebar-item label="Settings" icon="fa-gear" href="/settings" badge="!" :active="$active === 'settings'" />

  <div class="sidebar-profile">
    <div class="avatar"><span>AA</span><div class="online-dot"></div></div>
    <div class="sidebar-profile-info">
      <div class="sidebar-profile-name">Alex Analyst</div>
      <div class="sidebar-profile-role">Senior Portfolio Manager</div>
    </div>
    <a href="/" class="sidebar-profile-btn" title="Logout"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
  </div>
</nav>
