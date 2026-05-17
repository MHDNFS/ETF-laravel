@extends('layouts.layout')
@section('title','PTF PORTFOLIO')
@section('header_title', 'Portfolio Manager')
@section('content')

    <div class="content page-animate ptf-portfolio-page">

      <x-ui.page-header title="Portfolio Manager" subtitle="Manage holdings, rebalance, and monitor PTF positions" />

      <section class="ptf-portfolio-stats" aria-label="Portfolio summary">
        <div class="stats-grid">
          <x-ui.stat-card color="blue" icon="fa-wallet" label="Total Portfolio Value" value="$603K" trend="up" trendText="+2.3% today" />
          <x-ui.stat-card color="green" icon="fa-arrow-trend-up" label="Unrealised P&L" value="+$41.2K" trend="up" trendText="+7.3%" />
          <x-ui.stat-card
            color="amber"
            icon="fa-rotate"
            label="Rebalance Drift"
            value="3.8%"
            trend="down"
            trendIcon="fa-triangle-exclamation"
            trendText="Action needed"
          />
        </div>
      </section>

      <section class="card ptf-holdings-card" aria-label="Portfolio holdings">
        <div class="card-header ptf-holdings-header">
          <span class="card-title">Holdings</span>
          <div class="card-header-actions ptf-holdings-actions">
            <button type="button" class="btn btn-outline btn-sm ptf-rebalance-btn" onclick="showModal('rebalance-modal')">
              <i class="fa-solid fa-rotate"></i>
              <span class="btn-label">Rebalance</span>
            </button>
            <button type="button" class="btn btn-blue btn-sm ptf-add-position-btn" onclick="showModal('add-trade-modal')">
              <i class="fa-solid fa-plus"></i>
              <span class="btn-label">Add Position</span>
            </button>
          </div>
        </div>
        <div class="table-wrap table-wrap--scroll-hint">
          <table id="ptfHoldingsTable" style="width: 100%;">
            <tbody></tbody>
          </table>
        </div>
      </section>

    </div>

@endsection

