@extends('layouts.layout')
@section('title','EPF FUNDS')
@section('header_title', 'ETF Fund Explorer')
@section('content')

    <div class="content page-animate etf-funds-page">

      <header class="etf-funds-head page-head-row">
        <div>
          <x-ui.page-header title="ETF Fund Explorer" subtitle="Browse, filter, and analyze available exchange-traded funds" />
        </div>
        <x-ui.page-action-toolbar
          class="etf-funds-toolbar"
          :show-export-csv="true"
          :show-export-pdf="true"
          export-csv-id="btn-etf-funds-export-csv"
          export-pdf-id="btn-etf-funds-export-pdf"
        />
      </header>

      <section class="card etf-funds-filters-card" aria-label="Fund filters">
        <div class="card-body">
          <x-etf.etf-funds-filter-form />
        </div>
      </section>

      <section class="card etf-funds-list-card" aria-label="Fund list">
        <div class="card-header etf-funds-list-header">
          <span class="card-title">Fund List</span>
          <span class="badge badge-blue" id="etf-funds-count">12 Funds</span>
        </div>
        <x-etf.etf-funds-table />
      </section>

    </div>

@endsection
