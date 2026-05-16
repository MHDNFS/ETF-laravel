@extends('layouts.layout')
@section('title', 'Reports')

@php
    $reportsPageTitle = 'Reports & Export';
@endphp
@section('header_title', 'Reports & Export')
@section('content')

    <div class="content page-animate">

      <x-ui.page-header :title="$reportsPageTitle" subtitle="Generate, download, and schedule fund performance reports" />
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><span class="card-title">Generate Report</span></div>
          <div class="card-body">
            <!-- {{-- Generate Report: composed form — see components/report-generate-form.blade.php --}} -->
            <x-reports.report-generate-form />
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Recent Reports</span></div>
          <div class="card-body">
            <!-- Recent Reports: composed items — see components/recent-report-item.blade.php -->
            <x-reports.recent-report-item
              icon="fa-file-pdf"
              icon-style="background:rgba(79,140,255,0.12);color:var(--accent)"
              title="ETF Q1 2026 Summary"
              meta="Generated 3 May 2026 · PDF · 2.4 MB"
            />
            <x-reports.recent-report-item
              icon="fa-file-excel"
              icon-style="background:rgba(0,212,170,0.12);color:var(--accent3)"
              title="PTF Annual Performance"
              meta="Generated 1 May 2026 · XLSX · 1.1 MB"
            />
            <x-reports.recent-report-item
              icon="fa-file-lines"
              icon-style="background:rgba(245,158,11,0.12);color:var(--accent4)"
              title="Tax Report FY2025"
              meta="Generated 15 Apr 2026 · PDF · 3.8 MB"
            />
          </div>
        </div>
      </div>
    </div>

@endsection

