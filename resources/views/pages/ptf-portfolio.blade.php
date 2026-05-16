@extends('layouts.layout')
@section('title','PTF PORTFOLIO')
@section('header_title', 'Portfolio Manager')
@section('content')


    <div class="content page-animate">

      <x-page-header title="Portfolio Manager" subtitle="Manage holdings, rebalance, and monitor PTF positions" />
      <div class="stats-grid">
        <x-stat-card color="blue" icon="fa-wallet" label="Total Portfolio Value" value="$603K" trend="up" trendText="+2.3% today" />
        <x-stat-card color="green" icon="fa-arrow-trend-up" label='Unrealised P&L' value="+$41.2K" trend="up" trendText="+7.3%" />
        <x-stat-card
          color="amber"
          icon="fa-rotate"
          label="Rebalance Drift"
          value="3.8%"
          trend="down"
          trendIcon="fa-triangle-exclamation"
          trendText="Action needed"
        />
      </div>
      <div class="card">
        <div class="card-header">
          <span class="card-title">Holdings</span>
          <div style="display:flex;gap:8px">
            <button class="btn btn-outline btn-sm" onclick="showModal('rebalance-modal')"><i class="fa-solid fa-rotate"></i> Rebalance</button>
            <button class="btn btn-blue btn-sm" onclick="showModal('add-trade-modal')"><i class="fa-solid fa-plus"></i> Add Position</button>
          </div>
        </div>
        {{-- TABLE: Portfolio Holdings (#ptfHoldingsTable) — DataTables (resources/js/app.js → initPtfPortfolioHoldingsDataTable). No static thead (matches other pages). --}}
        <div class="table-wrap">
          <table id="ptfHoldingsTable" style="width: 100%;">
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>




@endsection
