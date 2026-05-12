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
          
          
          <!-- {{--
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
          --}} -->


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
        {{-- DataTables builds <thead> from columns[].title (see resources/js/app.js). A static <thead> here
             doubled markup with DataTables’ own sort controls and caused a “ghost” column between Date and Fund. --}}
        <table id="recentTransactionsTable" style="width: 100%;">
          <tbody></tbody>
        </table>
      </div>
    </div>

  </div><!-- /content -->


@endsection

