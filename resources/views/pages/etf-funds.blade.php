@extends('layouts.layout')
@section('title','EPF FUNDS')
@section('header_title', 'ETF Fund Explorer')
@section('content')

    <div class="content page-animate etf-funds-page">

      {{-- TABLE: ETF Fund List (#etfFundsTable) — export CSV/PDF in header; row "Add" is fund-specific (app.js initEtfFundsDataTable). --}}
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; gap: 16px; flex-wrap: wrap;">
        <div>
          <x-ui.page-header title="ETF Fund Explorer" subtitle="Browse, filter, and analyze available exchange-traded funds" />
        </div>
        <x-ui.page-action-toolbar
          :show-export-csv="true"
          :show-export-pdf="true"
          export-csv-id="btn-etf-funds-export-csv"
          export-pdf-id="btn-etf-funds-export-pdf"
        />
      </div>
      {{-- overflow: visible so Tom Select (.select2-custom) dropdowns are not clipped by .card { overflow: hidden } --}}
      <div class="card etf-funds-filters-card">
        <div class="card-body">
          
        <!-- x-etf.etf-funds-filter-form is a custom component that we defined in components/etf-funds-filter-form.blade.php -->
        <!-- we use the x-etf.etf-funds-filter-form component to display the form -->
        <x-etf.etf-funds-filter-form />
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <span class="card-title">Fund List</span>
          <span class="badge badge-blue" id="etf-funds-count">12 Funds</span>
        </div>

        {{-- TABLE: ETF Fund List (#etfFundsTable) — body is filled by DataTables (app.js initEtfFundsDataTable). --}}
        <x-etf.etf-funds-table />
      </div>
    </div>

@endsection
