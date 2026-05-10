@extends('layouts.layout')
@section('title', 'Home')
@section('content')

  <div class="content page-animate">

    <div class="page-title">Dashboard Overview</div>
    <div class="page-sub">Monday, 3 May 2026 · Portfolio snapshot across all funds</div>

{{-- components start --}}
    <!-- Stats -->
    <div class="stats-grid">
      <x-stat-card color="blue" icon="fa-coins" label="Total AUM" value="$48.3M" trend="up"
        trendText="+4.2% this month" />
      <x-stat-card color="green" icon="fa-chart-line" label="ETF Net Return" value="+12.7%" trend="up"
        trendText="+1.1% vs benchmark" />
      <x-stat-card color="purple" icon="fa-briefcase" label="PTF Performance" value="+9.4%" trend="up"
        trendText="+0.8% vs index" />
      <x-stat-card color="amber" icon="fa-shield-halved" label="Sharpe Ratio" value="1.84" trend="down"
        trendText="-0.06 vs last qtr" />
    </div>
{{-- components end --}}



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
        <button class="btn btn-blue btn-sm" onclick="showModal('add-trade-modal')"><i class="fa-solid fa-plus"></i> Add
          Trade</button>
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
              <td><span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:7px"></i> Settled</span>
              </td>
              <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button></td>
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
              <td><span class="badge badge-amber"><i class="fa-solid fa-circle" style="font-size:7px"></i> Pending</span>
              </td>
              <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button></td>
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
              <td><span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:7px"></i> Settled</span>
              </td>
              <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button></td>
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
              <td><span class="badge badge-red"><i class="fa-solid fa-circle" style="font-size:7px"></i> Cancelled</span>
              </td>
              <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button></td>
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
              <td><span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:7px"></i> Settled</span>
              </td>
              <td><button class="btn btn-outline btn-sm" onclick="showModal('trade-detail-modal')">View</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div><!-- /content -->




@endsection

