{{--
  Blade component: <x-dashboard.asset-allocation-row />

  WHAT THIS IS
  Single row under the Asset Allocation donut chart: colored dot, name,
  percentage text, and a mini progress bar (same visual as before refactor).

  WHY A COMPONENT
  One row template: the page passes label/percent/color (explicit
  <x-dashboard.asset-allocation-row /> tags in index.blade.php) so markup stays identical.

  WHERE IT IS USED
  - resources/views/pages/index.blade.php — Asset Allocation card, below
    <canvas id="alloc-chart"> (rows built via <x-dashboard.asset-allocation-row />).

  FILES THAT STAY IN SYNC WHEN YOU CHANGE DATA OR STYLES
  - This file — row HTML structure and which props exist.
  - public/assets/css/app.css — classes .alloc-item, .alloc-dot, .alloc-name,
    .alloc-pct, .alloc-bar-wrap, .progress-bar, .progress-fill (layout/colors).
  - public/assets/js/charts.js — function that builds the donut chart
    (labels + segment colors). If you change labels/percents/colors here,
    update the chart data there so the legend matches the graphic.

  HOW TO ADD ANOTHER ANONYMOUS COMPONENT (same pattern as x-ui.stat-card)
  1. Create resources/views/components/your-component-name.blade.php
  2. Optionally add @props([...]) at the top for attributes.
  3. Use <x-your-component-name prop="value" /> in any Blade view.

  OPTIONAL: class-based component (Artisan) — use when you need PHP logic,
  validation, or dependency injection:
  php artisan make:component AssetAllocationRow --no-interaction

  PROPS
  - label   (string)  Row title, e.g. "US Equities".
  - percent (int)     Displayed as "N%" and used as the bar width (0–100).
  - color   (string)  CSS color for dot + bar, e.g. "#4f8cff"; should match
                      the corresponding chart segment color.
--}}
@props([
    'label',
    'percent',
    'color',
])

<div class="alloc-item">
    <div class="alloc-dot" style="background: {{ $color }}"></div>
    <div class="alloc-name">{{ $label }}</div>
    <div class="alloc-pct">{{ $percent }}%</div>
    <div class="alloc-bar-wrap">
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $percent }}%; background: {{ $color }}"></div>
        </div>
    </div>
</div>
