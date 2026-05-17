@props([
    'title' => 'Portfolio Performance',
    'canvasId' => 'perf-chart',
])

{{--
  Portfolio Performance line chart (ETF / PTF / Benchmark).

  WHERE USED
  - resources/views/pages/index.blade.php — <x-dashboard.portfolio-performance-chart />

  CHART LOGIC (keep in sync)
  - public/assets/js/charts.js → initPerfChart() targets #{{ $canvasId }}
  - pages/index.blade.php @push('scripts') calls initPerfChart() on DOMContentLoaded
--}}
<div class="card">
  <div class="card-header">
    <span class="card-title">{{ $title }}</span>
    <select class="chart-period-select" aria-label="Performance period">
      <option>1 Month</option>
      <option>3 Months</option>
      <option selected>1 Year</option>
      <option>3 Years</option>
    </select>
  </div>
  <div class="card-body">
    <div class="chart-container"><canvas id="{{ $canvasId }}"></canvas></div>
  </div>
</div>
