@extends('layouts.layout')
@section('title', 'Home')
@section('header_title', 'Dashboard')
@section('content')

  <div class="content page-animate dashboard-page">

    <x-ui.page-header title="Dashboard Overview" subtitle="Monday, 3 May 2026 · Portfolio snapshot across all funds" />

    <section class="dashboard-stats" aria-label="Key metrics">
      <div class="stats-grid">
        <x-ui.stat-card color="blue" icon="fa-coins" label="Total AUM" value="$48.3M" trend="up"
          trendText="+4.2% this month" />
        <x-ui.stat-card color="green" icon="fa-chart-line" label="ETF Net Return" value="+12.7%" trend="up"
          trendText="+1.1% vs benchmark" />
        <x-ui.stat-card color="purple" icon="fa-briefcase" label="PTF Performance" value="+9.4%" trend="up"
          trendText="+0.8% vs index" />
        <x-ui.stat-card color="amber" icon="fa-shield-halved" label="Sharpe Ratio" value="1.84" trend="down"
          trendText="-0.06 vs last qtr" />
      </div>
    </section>

    <section class="dashboard-charts grid-6040" aria-label="Charts">
      <x-dashboard.portfolio-performance-chart />
      <div class="card dashboard-alloc-card">
        <div class="card-header">
          <span class="card-title">Asset Allocation</span>
          <span class="badge badge-blue">ETF Split</span>
        </div>
        <div class="card-body">
          <div class="chart-container chart-container--donut dashboard-alloc-chart">
            <canvas id="alloc-chart"></canvas>
          </div>
          <div class="dashboard-alloc-list">
            <x-dashboard.asset-allocation-row label="US Equities" :percent="42" color="#4f8cff" />
            <x-dashboard.asset-allocation-row label="Bonds" :percent="26" color="#00d4aa" />
            <x-dashboard.asset-allocation-row label="Intl Markets" :percent="19" color="#7b5ea7" />
            <x-dashboard.asset-allocation-row label="Commodities" :percent="13" color="#f59e0b" />
          </div>
        </div>
      </div>
    </section>

    <section class="card dashboard-transactions-card" aria-label="Recent transactions">
      <div class="card-header dashboard-tx-header">
        <span class="card-title">Recent Transactions</span>
        <div class="card-header-actions dashboard-tx-actions">
          <x-ui.page-action-toolbar
            :show-export-csv="true"
            :show-export-pdf="true"
            export-csv-id="btn-recent-tx-export-csv"
            export-pdf-id="btn-recent-tx-export-pdf"
          >
            <button type="button" class="btn btn-blue btn-sm dashboard-tx-add-btn" onclick="showModal('add-trade-modal')">
              <i class="fa-solid fa-plus"></i>
              <span class="btn-label">Add Trade</span>
            </button>
          </x-ui.page-action-toolbar>
        </div>
      </div>
      <div class="table-wrap table-wrap--scroll-hint">
        <table id="recentTransactionsTable" style="width: 100%;">
          <tbody></tbody>
        </table>
      </div>
    </section>

  </div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof initPerfChart === 'function') initPerfChart();
    if (typeof initAllocChart === 'function') initAllocChart();
  });
</script>
@endpush

@endsection
