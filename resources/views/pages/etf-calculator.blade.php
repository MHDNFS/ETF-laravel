@extends('layouts.layout')
@section('title','EPF CALCULATER')
@section('content')




    <div class="content page-animate">

      <div class="page-title">ETF Calculator</div>
      <div class="page-sub">Compute NAV, total expense ratio, tracking error, and projected returns</div>
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><span class="card-title">ETF Parameters</span></div>
          <div class="card-body">
          
          
          <!-- x-etf-parameters-form is a custom component that we defined in components/etf-parameters-form.blade.php -->
          <!-- we use the x-etf-parameters-form component to display the form -->
          <x-etf-parameters-form />
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Results &amp; Projections</span></div>
          <div class="card-body">
            <div class="calc-result">
              <div class="calc-result-title">Computed Metrics</div>
              <div class="calc-result-grid">
                <div class="calc-result-item"><div class="rlabel">Premium / Discount</div><div class="rvalue" id="etf-r-prem">+0.14%</div></div>
                <div class="calc-result-item"><div class="rlabel">Tracking Error</div><div class="rvalue amber" id="etf-r-te">0.02%</div></div>
                <div class="calc-result-item"><div class="rlabel">Net Return (5yr)</div><div class="rvalue green" id="etf-r-ret">+62.3%</div></div>
                <div class="calc-result-item"><div class="rlabel">Cost Drag</div><div class="rvalue" id="etf-r-drag">$75</div></div>
                <div class="calc-result-item"><div class="rlabel">Projected Value</div><div class="rvalue green" id="etf-r-proj">$88,700</div></div>
                <div class="calc-result-item"><div class="rlabel">Total Gain</div><div class="rvalue green" id="etf-r-gain">+$38,700</div></div>
              </div>
            </div>
            <div style="margin-top:20px">
              <div style="font-size:12px;color:var(--text3);margin-bottom:10px;text-transform:uppercase;letter-spacing:0.5px">5-Year Projection</div>
              <div class="chart-container" style="height:180px"><canvas id="etf-proj-chart"></canvas></div>
            </div>
            <div style="margin-top:16px">
              <div class="metric-row"><span class="metric-lbl">Expense Ratio (TER)</span><span class="metric-val">0.03%</span></div>
              <div class="metric-row"><span class="metric-lbl">Annual Cost</span><span class="metric-val">$15.00</span></div>
              <div class="metric-row"><span class="metric-lbl">Alpha vs Benchmark</span><span class="metric-val td-up">+0.22%</span></div>
              <div class="metric-row"><span class="metric-lbl">Beta</span><span class="metric-val">0.98</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection
