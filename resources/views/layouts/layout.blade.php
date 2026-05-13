<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Home')</title>
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@300;400;500&family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap"
    rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

  <!-- This line tells Laravel to load the CSS and JS files we just configured with NPM packages! -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body>
  <div id="app">

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    {{-- <nav class="sidebar" id="sidebar">
      <div class="sidebar-logo">
        <div class="sidebar-logo-icon"><i class="fa-solid fa-chart-line"></i></div>
        <div class="sidebar-logo-text">Quant<span>Edge</span></div>
      </div>
      <div class="sidebar-section">Overview</div>
      <a class="nav-item active" href="dashboard.html"><span class="nav-icon"><i
            class="fa-solid fa-gauge-high"></i></span>Dashboard</a>
      <a class="nav-item" href="profile.html"><span class="nav-icon"><i class="fa-solid fa-user-circle"></i></span>My
        Profile</a>
      <div class="sidebar-section">ETF Module</div>
      <a class="nav-item" href="etf-calculator.html"><span class="nav-icon"><i
            class="fa-solid fa-calculator"></i></span>ETF Calculator</a>
      <a class="nav-item" href="etf-funds.html"><span class="nav-icon"><i
            class="fa-solid fa-layer-group"></i></span>Fund Explorer<span class="nav-badge">12</span></a>
      <div class="sidebar-section">PTF Module</div>
      <a class="nav-item" href="ptf-portfolio.html"><span class="nav-icon"><i
            class="fa-solid fa-briefcase"></i></span>Portfolio Manager</a>
      <div class="sidebar-section">Reports</div>
      <a class="nav-item" href="reports.html"><span class="nav-icon"><i
            class="fa-solid fa-file-chart-column"></i></span>Reports &amp; Export</a>
      <a class="nav-item" href="settings.html"><span class="nav-icon"><i
            class="fa-solid fa-gear"></i></span>Settings<span class="nav-badge warn">!</span></a>
      <div class="sidebar-profile">
        <div class="avatar"><span>AA</span>
          <div class="online-dot"></div>
        </div>
        <div class="sidebar-profile-info">
          <div class="sidebar-profile-name">Alex Analyst</div>
          <div class="sidebar-profile-role">Senior Portfolio Manager</div>
        </div>
        <a href="index.html" class="sidebar-profile-btn" title="Logout"><i
            class="fa-solid fa-arrow-right-from-bracket"></i></a>
      </div>
    </nav> --}}

    @php
      // Provide default values if the view does not pass sidebar data.
      // This keeps the layout working for k pages.
      $sidebarItems = $sidebarItems ?? [];
      $activeSidebar = $activeSidebar ?? 'dashboard';
    @endphp

    {{-- Use the reusable sidebar component and send the current active item key. --}}
    <x-sidebar :items="$sidebarItems" :active="$activeSidebar" />

    <!-- Main -->
    <div class="main" id="main-area">

      @include('includes.header.header')
      <!-- Header -->
      {{-- <header class="header">
        <button class="hamburger" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
        <div class="header-breadcrumb">Dashboard</div>
        <div class="header-search">
          <i class="fa-solid fa-magnifying-glass si"></i>
          <input type="text" placeholder="Search funds, tickers…">
        </div>
        <div class="header-actions">
          <div class="hbtn" onclick="showModal('notifications-modal')" title="Notifications">
            <i class="fa-regular fa-bell"></i><span class="badge">3</span>
          </div>
          <div class="hbtn" title="Messages"><i class="fa-regular fa-comment-dots"></i><span class="badge">1</span>
          </div>
          <div class="hbtn" title="Help"><i class="fa-regular fa-circle-question"></i></div>
          <div style="position:relative">
            <div class="header-avatar" onclick="toggleAvatarMenu()">AA</div>
            <div class="header-avatar-menu" id="avatar-menu">
              <div class="avatar-menu-header">
                <div class="avatar" style="width:36px;height:36px;font-size:13px">AA</div>
                <div>
                  <div style="font-size:13px;font-weight:500">Alex Analyst</div>
                  <div style="font-size:11px;color:var(--text3)">analyst@quantedge.io</div>
                </div>
              </div>
              <a class="avatar-menu-item" href="profile.html"><i class="fa-solid fa-user"></i> My Profile</a>
              <a class="avatar-menu-item" href="settings.html"><i class="fa-solid fa-gear"></i> Settings</a>
              <div class="avatar-menu-divider"></div>
              <a class="avatar-menu-item danger" href="index.html"><i class="fa-solid fa-arrow-right-from-bracket"></i>
                Sign Out</a>
            </div>
          </div>
        </div>
      </header> --}}

      <!-- Content -->
      {{-- <div class="content page-animate">

        <div class="page-title">Dashboard Overview</div>
        <div class="page-sub">Monday, 3 May 2026 · Portfolio snapshot across all funds</div>

        <!-- Stats -->
        <div class="stats-grid">
          <div class="stat-card blue">
            <div class="stat-icon blue"><i class="fa-solid fa-coins"></i></div>
            <div class="stat-label">Total AUM</div>
            <div class="stat-value">$48.3M</div>
            <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> +4.2% this month</div>
          </div>
          <div class="stat-card green">
            <div class="stat-icon green"><i class="fa-solid fa-chart-line"></i></div>
            <div class="stat-label">ETF Net Return</div>
            <div class="stat-value">+12.7%</div>
            <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> +1.1% vs benchmark</div>
          </div>
          <div class="stat-card purple">
            <div class="stat-icon purple"><i class="fa-solid fa-briefcase"></i></div>
            <div class="stat-label">PTF Performance</div>
            <div class="stat-value">+9.4%</div>
            <div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> +0.8% vs index</div>
          </div>
          <div class="stat-card amber">
            <div class="stat-icon amber"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="stat-label">Sharpe Ratio</div>
            <div class="stat-value">1.84</div>
            <div class="stat-change down"><i class="fa-solid fa-arrow-down"></i> -0.06 vs last qtr</div>
          </div>
        </div>

        <!-- Charts Row -->
        <div class="grid-6040">
          <div class="card">
            <div class="card-header">
              <span class="card-title">Portfolio Performance</span>
              <select
                style="background:var(--bg3);border:1px solid var(--border2);border-radius:6px;padding:4px 10px;color:var(--text2);font-size:12px;outline:none;cursor:pointer">
                <option>1 Month</option>
                <option>3 Months</option>
                <option selected>1 Year</option>
                <option>3 Years</option>
              </select>
            </div>
            <div class="card-body">
              <div class="chart-container"><canvas id="perf-chart"></canvas></div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <span class="card-title">Asset Allocation</span>
              <span class="badge badge-blue">ETF Split</span>
            </div>
            <div class="card-body">
              <div style="height:180px;position:relative"><canvas id="alloc-chart"></canvas></div>
              <div style="margin-top:14px">
                <div class="alloc-item">
                  <div class="alloc-dot" style="background:#4f8cff"></div>
                  <div class="alloc-name">US Equities</div>
                  <div class="alloc-pct">42%</div>
                  <div class="alloc-bar-wrap">
                    <div class="progress-bar">
                      <div class="progress-fill" style="width:42%;background:#4f8cff"></div>
                    </div>
                  </div>
                </div>
                <div class="alloc-item">
                  <div class="alloc-dot" style="background:#00d4aa"></div>
                  <div class="alloc-name">Bonds</div>
                  <div class="alloc-pct">26%</div>
                  <div class="alloc-bar-wrap">
                    <div class="progress-bar">
                      <div class="progress-fill" style="width:26%;background:#00d4aa"></div>
                    </div>
                  </div>
                </div>
                <div class="alloc-item">
                  <div class="alloc-dot" style="background:#7b5ea7"></div>
                  <div class="alloc-name">Intl Markets</div>
                  <div class="alloc-pct">19%</div>
                  <div class="alloc-bar-wrap">
                    <div class="progress-bar">
                      <div class="progress-fill" style="width:19%;background:#7b5ea7"></div>
                    </div>
                  </div>
                </div>
                <div class="alloc-item">
                  <div class="alloc-dot" style="background:#f59e0b"></div>
                  <div class="alloc-name">Commodities</div>
                  <div class="alloc-pct">13%</div>
                  <div class="alloc-bar-wrap">
                    <div class="progress-bar">
                      <div class="progress-fill" style="width:13%;background:#f59e0b"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
          <div class="card-header">
            <span class="card-title">Recent Transactions</span>
            <button class="btn btn-blue btn-sm" onclick="showModal('add-trade-modal')"><i class="fa-solid fa-plus"></i>
              Add Trade</button>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th class="sort">Date <i class="fa-solid fa-sort"></i></th>
                  <th class="sort">Fund / Ticker</th>
                  <th>Type</th>
                  <th class="sort">Units</th>
                  <th class="sort">Price</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="td-muted td-mono">2026-05-02</td>
                  <td>
                    <div class="fund-name">Vanguard S&amp;P 500</div>
                    <div class="fund-ticker">VOO</div>
                  </td>
                  <td><span class="tag tag-etf">ETF</span></td>
                  <td class="td-mono">120</td>
                  <td class="td-mono">$512.40</td>
                  <td class="td-mono" style="font-weight:500">$61,488</td>
                  <td><span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:7px"></i>
                      Settled</span></td>
                  <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button>
                  </td>
                </tr>
                <tr>
                  <td class="td-muted td-mono">2026-05-01</td>
                  <td>
                    <div class="fund-name">iShares Core MSCI</div>
                    <div class="fund-ticker">IEMG</div>
                  </td>
                  <td><span class="tag tag-etf">ETF</span></td>
                  <td class="td-mono">85</td>
                  <td class="td-mono">$54.22</td>
                  <td class="td-mono" style="font-weight:500">$4,609</td>
                  <td><span class="badge badge-amber"><i class="fa-solid fa-circle" style="font-size:7px"></i>
                      Pending</span></td>
                  <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button>
                  </td>
                </tr>
                <tr>
                  <td class="td-muted td-mono">2026-04-30</td>
                  <td>
                    <div class="fund-name">Custom Growth Fund</div>
                    <div class="fund-ticker">CGF-A</div>
                  </td>
                  <td><span class="tag tag-ptf">PTF</span></td>
                  <td class="td-mono">200</td>
                  <td class="td-mono">$88.75</td>
                  <td class="td-mono" style="font-weight:500">$17,750</td>
                  <td><span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:7px"></i>
                      Settled</span></td>
                  <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button>
                  </td>
                </tr>
                <tr>
                  <td class="td-muted td-mono">2026-04-29</td>
                  <td>
                    <div class="fund-name">SPDR Gold ETF</div>
                    <div class="fund-ticker">GLD</div>
                  </td>
                  <td><span class="tag tag-etf">ETF</span></td>
                  <td class="td-mono">40</td>
                  <td class="td-mono">$224.10</td>
                  <td class="td-mono" style="font-weight:500">$8,964</td>
                  <td><span class="badge badge-red"><i class="fa-solid fa-circle" style="font-size:7px"></i>
                      Cancelled</span></td>
                  <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button>
                  </td>
                </tr>
                <tr>
                  <td class="td-muted td-mono">2026-04-28</td>
                  <td>
                    <div class="fund-name">Tactical Bond PTF</div>
                    <div class="fund-ticker">TBP-2</div>
                  </td>
                  <td><span class="tag tag-ptf">PTF</span></td>
                  <td class="td-mono">500</td>
                  <td class="td-mono">$102.30</td>
                  <td class="td-mono" style="font-weight:500">$51,150</td>
                  <td><span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:7px"></i>
                      Settled</span></td>
                  <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div><!-- /content --> --}}

      @yield('content')



      @include('includes.footer.footer')
      <!-- Footer -->
      {{-- <footer class="footer">s
        <div>© 2026 QuantEdge Capital · ETF &amp; PTF Dashboard v3.2.1</div>
        <div class="footer-links">
          <a href="#">Privacy Policy</a><a href="#">Terms of Service</a>
          <a href="#">API Docs</a><a href="#">Support</a>
        </div>
      </footer> --}}

    </div><!-- /main -->
  </div><!-- /app -->


  <!-- this is the add trade modal -->
  <!-- it is a custom component that we defined in components/modals/add-trade-modal.blade.php -->
  <!-- we use the x-modals.add-trade-modal component to display the modal -->
  <x-modals.add-trade-modal />
  <x-modals.add-etf-fund-modal />

  <!-- Scripts -->
  <!-- why is this here -->
  <!-- for the tables pagination search export etc -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
  <script src="{{ asset('assets/js/app.js') }}"></script>
  <script src="{{ asset('assets/js/charts.js') }}"></script>
  <script src="{{ asset('assets/js/modals.js') }}"></script>



  <script>document.addEventListener('DOMContentLoaded', function () { initETFProjChart(); });</script>
  <script>document.addEventListener('DOMContentLoaded', function () { initPerfChart(); initAllocChart(); });</script>
  {{--
  <script src="{{ assets('assets/js/calculators.js') }}"></script> --}}

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
  <script src="{{ asset('assets/js/calculators.js') }}"></script>
  {{-- Fund list table: DataTables in Vite resources/js/app.js (initEtfFundsDataTable); buildFundTable no longer used --}}


  {{--
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
  <script src="assets/js/app.js"></script>
  <script src="assets/js/modals.js"></script> --}}


</body>

</html>