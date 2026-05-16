@extends('layouts.layout')
@section('title', 'Home')
@section('header_title', 'Dashboard')
@section('content')

  <div class="content page-animate">

    <x-ui.page-header title="Dashboard Overview" subtitle="Monday, 3 May 2026 · Portfolio snapshot across all funds" />

{{-- components start --}}
    <!-- Stats -->
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
{{-- components end --}}



    {{-- Charts row: portfolio performance component + asset allocation card. --}}
    <div class="grid-6040">
      <x-dashboard.portfolio-performance-chart />
      <div class="card">
        <div class="card-header">
          <span class="card-title">Asset Allocation</span>
          <span class="badge badge-blue">ETF Split</span>
        </div>
        <div class="card-body">
          <div style="height:180px;position:relative"><canvas id="alloc-chart"></canvas></div>
          
          
          {{--
            Asset allocation rows: resources/views/components/asset-allocation-row.blade.php
            Keep labels/percents/colors in sync with public/assets/js/charts.js → initAllocChart().
          --}}
          <div style="margin-top:14px">
            <x-dashboard.asset-allocation-row label="US Equities" :percent="42" color="#4f8cff" />
            <x-dashboard.asset-allocation-row label="Bonds" :percent="26" color="#00d4aa" />
            <x-dashboard.asset-allocation-row label="Intl Markets" :percent="19" color="#7b5ea7" />
            <x-dashboard.asset-allocation-row label="Commodities" :percent="13" color="#f59e0b" />
          </div>
        </div>
      </div>
    </div>



    <!-- TABLE: Recent Transactions (#recentTransactionsTable) — DataTables + export CSV/PDF (see app.js initRecentTransactionsDataTable). -->
    <div class="card">
      <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <span class="card-title">Recent Transactions</span>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
          <x-ui.page-action-toolbar
            :show-export-csv="true"
            :show-export-pdf="true"
            export-csv-id="btn-recent-tx-export-csv"
            export-pdf-id="btn-recent-tx-export-pdf"
          >
            <button type="button" class="btn btn-blue btn-sm" onclick="showModal('add-trade-modal')"><i class="fa-solid fa-plus"></i> Add Trade</button>
          </x-ui.page-action-toolbar>
        </div>
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

