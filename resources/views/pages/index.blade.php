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
          
          
          {{--
            Asset allocation ROWS (list under the donut chart)

            COMPONENT FILE: resources/views/components/asset-allocation-row.blade.php
            USAGE TAG:      <x-asset-allocation-row :label="..." :percent="N" :color="'#hex'" />
            Rows for THIS PAGE: edit $assetAllocationRows below (one array = one loop).

            CONNECTED TO:
            - public/assets/js/charts.js → initAllocChart() labels/data/colors for
              the donut; keep in sync when you change labels, percents, or colors.
            - public/assets/css/app.css → .alloc-* and .progress-* classes.

            ANOTHER ANONYMOUS COMPONENT: new file under resources/views/components/
            CLASS-BASED (optional): php artisan make:component AssetAllocationRow --no-interaction
          --}}


          @php
            $assetAllocationRows = [
                ['label' => 'US Equities', 'percent' => 42, 'color' => '#4f8cff'],
                ['label' => 'Bonds', 'percent' => 26, 'color' => '#00d4aa'],
                ['label' => 'Intl Markets', 'percent' => 19, 'color' => '#7b5ea7'],
                ['label' => 'Commodities', 'percent' => 13, 'color' => '#f59e0b'],
            ];
          @endphp
          <div style="margin-top:14px">
            @foreach ($assetAllocationRows as $row)
              <x-asset-allocation-row :label="$row['label']" :percent="$row['percent']" :color="$row['color']" />
            @endforeach
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

