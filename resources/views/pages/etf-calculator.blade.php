@extends('layouts.layout')
@section('title','EPF CALCULATER')
@section('header_title', 'ETF Calculator')
@section('content')




    <div class="content page-animate">

      <x-ui.page-header title="ETF Calculator" subtitle="Compute NAV, total expense ratio, tracking error, and projected returns" />
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><span class="card-title">ETF Parameters</span></div>
          <div class="card-body">
          
          
          <!-- x-etf.etf-parameters-form is a custom component that we defined in components/etf-parameters-form.blade.php -->
          <!-- we use the x-etf.etf-parameters-form component to display the form -->
          <x-etf.etf-parameters-form />
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Results &amp; Projections</span></div>
          <div class="card-body">
            {{-- Flow: metrics block -> chart block -> footer metric rows --}}
            <x-etf.etf-results-panel />
            <x-etf.etf-projection-chart />
            <x-etf.etf-result-details />
          </div>
        </div>
      </div>
    </div>

@endsection
