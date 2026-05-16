@props([
    'title' => 'Portfolio Performance',
    'canvasId' => 'perf-chart',
])

{{--
  Portfolio Performance line chart (ETF / PTF / Benchmark).

  WHERE USED
  - resources/views/pages/index.blade.php — <x-portfolio-performance-chart />

  CHART LOGIC (keep in sync)
  - public/assets/js/charts.js → initPerfChart() targets #{{ $canvasId }}
  - layouts/layout.blade.php loads Chart.js + charts.js and calls initPerfChart() on DOMContentLoaded
--}}
<div class="card">
  <div class="card-header">
    <span class="card-title">{{ $title }}</span>
    <select
      class="portfolio-performance-period"
      aria-label="Performance period"
      style="background:var(--bg3);border:1px solid var(--border2);border-radius:6px;padding:4px 10px;color:var(--text2);font-size:12px;outline:none;cursor:pointer"
    >
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
