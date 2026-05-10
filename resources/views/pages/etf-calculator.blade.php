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
            <div class="form-group">
              <label class="form-label">Fund Selection</label>
              <div class="select2-custom"><select id="etf-fund-select">
                <option>Vanguard S&amp;P 500 ETF (VOO)</option>
                <option>iShares MSCI Emerging (IEMG)</option>
                <option>SPDR Gold Shares (GLD)</option>
                <option>Invesco QQQ Trust (QQQ)</option>
                <option>iShares Core US Aggregate (AGG)</option>
              </select></div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Investment Amount ($)</label>
                <div class="input-icon-wrap"><i class="fa-solid fa-dollar-sign icon"></i>
                <input type="number" class="form-control" id="etf-amount" value="50000" style="padding-left:30px"></div>
              </div>
              <div class="form-group">
                <label class="form-label">Number of Units</label>
                <input type="number" class="form-control" id="etf-units" value="100">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">Current NAV ($)</label><input type="number" class="form-control" id="etf-nav" value="512.40"></div>
              <div class="form-group"><label class="form-label">Market Price ($)</label><input type="number" class="form-control" id="etf-price" value="513.10"></div>
            </div>
            <div class="form-row three">
              <div class="form-group"><label class="form-label">TER (%)</label><input type="number" class="form-control" id="etf-ter" value="0.03" step="0.01"></div>
              <div class="form-group"><label class="form-label">Benchmark Return (%)</label><input type="number" class="form-control" id="etf-bench" value="12.5"></div>
              <div class="form-group"><label class="form-label">Holding Period (yrs)</label><input type="number" class="form-control" id="etf-years" value="5"></div>
            </div>
            <div class="form-group">
              <label class="form-label">Dividend Reinvestment</label>
              <div class="select2-custom"><select><option>Reinvest Dividends (DRIP)</option><option>Cash Payout</option><option>No Dividends</option></select></div>
            </div>
            <button class="btn btn-blue" style="width:100%" onclick="calcETF()"><i class="fa-solid fa-calculator"></i> Calculate ETF Metrics</button>
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
