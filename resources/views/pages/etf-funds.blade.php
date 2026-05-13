@extends('layouts.layout')
@section('title','EPF FUNDS')
@section('header_title', 'ETF Fund Explorer')
@section('content')

    <div class="content page-animate">

      <x-page-header title="ETF Fund Explorer" subtitle="Browse, filter, and analyze available exchange-traded funds" />
      <div class="card">
        <div class="card-body">
          
        <!-- x-etf-funds-filter-form is a custom component that we defined in components/etf-funds-filter-form.blade.php -->
        <!-- we use the x-etf-funds-filter-form component to display the form -->
        <x-etf-funds-filter-form />
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <span class="card-title">Fund List</span>
          <span class="badge badge-blue" id="etf-funds-count">12 Funds</span>
        </div>

        <!-- this is the table that displays the funds
        this is a custom component that we defined in components/etf-funds-table.blade.php -->
        <x-etf-funds-table />
      </div>
    </div>

@endsection
