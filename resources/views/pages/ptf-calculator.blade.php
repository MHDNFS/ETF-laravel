@extends('layouts.layout')
@section('title','PTF CALCULATOR')
@section('content')

    <div class="content page-animate">

      <div class="page-title">PTF Calculator</div>
      <div class="page-sub">Portfolio tax fee calculation — compute fees, returns, and tax liabilities</div>
      <div class="tabs">
        <button class="tab-btn active" onclick="switchTab(this,'ptf-basic')">Basic Calculation</button>
        <button class="tab-btn" onclick="switchTab(this,'ptf-advanced')">Advanced / Multi-Asset</button>
        <button class="tab-btn" onclick="switchTab(this,'ptf-tax')">Tax &amp; Compliance</button>
      </div>
      <!-- BASIC TAB -->
      <div class="tab-content active" id="ptf-basic">
        <div class="grid-2">
          <div class="card">
            <div class="card-header"><span class="card-title">PTF Input Parameters</span></div>
            <div class="card-body">
              <div class="form-group"><label class="form-label">Portfolio Strategy</label>
                <div class="select2-custom"><select><option>Growth Equity (Aggressive)</option><option>Balanced (Moderate)</option><option>Income / Conservative</option><option>Custom Strategy</option></select></div>
              </div>
              <div class="form-row">
                <div class="form-group"><label class="form-label">Portfolio Value ($)</label>
                  <div class="input-icon-wrap"><i class="fa-solid fa-dollar-sign icon"></i>
                  <input type="number" class="form-control" id="ptf-value" value="250000" style="padding-left:30px"></div>
                </div>
                <div class="form-group"><label class="form-label">Management Fee (%)</label>
                  <input type="number" class="form-control" id="ptf-fee" value="1.5" step="0.1">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group"><label class="form-label">Expected Return (%)</label><input type="number" class="form-control" id="ptf-return" value="10.5"></div>
                <div class="form-group"><label class="form-label">Time Horizon (yrs)</label><input type="number" class="form-control" id="ptf-horizon" value="10"></div>
              </div>
              <div class="form-group">
                <label class="form-label">Risk Tolerance</label>
                <input type="range" min="1" max="5" value="3" id="ptf-risk" oninput="document.getElementById('risk-val').textContent=['Very Low','Low','Moderate','High','Very High'][this.value-1]">
                <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text3);margin-top:4px">
                  <span>Very Low</span><span>Low</span><span>Moderate</span><span>High</span><span>Very High</span>
                </div>
                <div style="text-align:center;margin-top:6px"><span class="badge badge-blue" id="risk-val">Moderate</span></div>
              </div>
              <div class="form-group"><label class="form-label">Performance Benchmark</label>
                <div class="select2-custom"><select><option>S&amp;P 500 Total Return</option><option>MSCI World</option><option>Bloomberg Aggregate</option><option>Custom Benchmark</option></select></div>
              </div>
              <button class="btn btn-blue" style="width:100%" onclick="calcPTF()"><i class="fa-solid fa-calculator"></i> Calculate PTF</button>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><span class="card-title">PTF Results</span></div>
            <div class="card-body">
              <div class="calc-result">
                <div class="calc-result-title">Portfolio Metrics</div>
                <div class="calc-result-grid">
                  <div class="calc-result-item"><div class="rlabel">Gross Return (10yr)</div><div class="rvalue green" id="ptf-r-gross">+171.6%</div></div>
                  <div class="calc-result-item"><div class="rlabel">Net Return</div><div class="rvalue green" id="ptf-r-net">+141.2%</div></div>
                  <div class="calc-result-item"><div class="rlabel">Total Fees Paid</div><div class="rvalue amber" id="ptf-r-fees">$47,820</div></div>
                  <div class="calc-result-item"><div class="rlabel">Fee Drag</div><div class="rvalue" id="ptf-r-drag">-1.5%</div></div>
                  <div class="calc-result-item"><div class="rlabel">Final Value</div><div class="rvalue green" id="ptf-r-final">$603,000</div></div>
                  <div class="calc-result-item"><div class="rlabel">Net Profit</div><div class="rvalue green" id="ptf-r-profit">+$353,000</div></div>
                </div>
              </div>
              <div style="margin-top:20px"><div class="chart-container" style="height:180px"><canvas id="ptf-chart"></canvas></div></div>
              <div style="margin-top:14px">
                <div class="metric-row"><span class="metric-lbl">Volatility (σ)</span><span class="metric-val">14.2%</span></div>
                <div class="metric-row"><span class="metric-lbl">Sharpe Ratio</span><span class="metric-val">1.68</span></div>
                <div class="metric-row"><span class="metric-lbl">Max Drawdown</span><span class="metric-val td-down">-18.4%</span></div>
                <div class="metric-row"><span class="metric-lbl">VaR (95%)</span><span class="metric-val td-down">-3.2%</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- ADVANCED TAB -->
      <div class="tab-content" id="ptf-advanced">
        <div class="card">
          <div class="card-header"><span class="card-title">Multi-Asset Allocation</span>
            <button class="btn btn-outline btn-sm" onclick="showModal('add-asset-modal')"><i class="fa-solid fa-plus"></i> Add Asset</button>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Asset</th><th>Weight (%)</th><th>Expected Return (%)</th><th>Volatility (%)</th><th>Contribution</th><th></th></tr></thead>
              <tbody>
                <tr><td><div class="fund-name">US Equity</div></td><td><input type="number" class="form-control" value="40" style="width:80px;padding:6px 10px"></td><td><input type="number" class="form-control" value="12.0" step="0.1" style="width:80px;padding:6px 10px"></td><td><input type="number" class="form-control" value="16.0" step="0.1" style="width:80px;padding:6px 10px"></td><td><span class="badge badge-green">+4.80%</span></td><td><button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button></td></tr>
                <tr><td><div class="fund-name">Fixed Income</div></td><td><input type="number" class="form-control" value="30" style="width:80px;padding:6px 10px"></td><td><input type="number" class="form-control" value="4.5" step="0.1" style="width:80px;padding:6px 10px"></td><td><input type="number" class="form-control" value="5.0" step="0.1" style="width:80px;padding:6px 10px"></td><td><span class="badge badge-blue">+1.35%</span></td><td><button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button></td></tr>
                <tr><td><div class="fund-name">Intl Equity</div></td><td><input type="number" class="form-control" value="20" style="width:80px;padding:6px 10px"></td><td><input type="number" class="form-control" value="9.2" step="0.1" style="width:80px;padding:6px 10px"></td><td><input type="number" class="form-control" value="19.0" step="0.1" style="width:80px;padding:6px 10px"></td><td><span class="badge badge-green">+1.84%</span></td><td><button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button></td></tr>
                <tr><td><div class="fund-name">Commodities</div></td><td><input type="number" class="form-control" value="10" style="width:80px;padding:6px 10px"></td><td><input type="number" class="form-control" value="6.8" step="0.1" style="width:80px;padding:6px 10px"></td><td><input type="number" class="form-control" value="22.0" step="0.1" style="width:80px;padding:6px 10px"></td><td><span class="badge badge-amber">+0.68%</span></td><td><button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button></td></tr>
              </tbody>
            </table>
          </div>
          <div class="card-body" style="border-top:1px solid var(--border)">
            <div style="display:flex;gap:16px;flex-wrap:wrap">
              <div class="calc-result-item"><div class="rlabel">Portfolio Return</div><div class="rvalue green">8.67%</div></div>
              <div class="calc-result-item"><div class="rlabel">Total Weight</div><div class="rvalue">100%</div></div>
              <div class="calc-result-item"><div class="rlabel">Portfolio Volatility</div><div class="rvalue amber">12.8%</div></div>
              <div class="calc-result-item"><div class="rlabel">Sharpe Ratio</div><div class="rvalue">1.72</div></div>
            </div>
          </div>
        </div>
      </div>
      <!-- TAX TAB -->
      <div class="tab-content" id="ptf-tax">
        <div class="card">
          <div class="card-header"><span class="card-title">Tax &amp; Compliance Calculator</span></div>
          <div class="card-body">
            <div class="form-row three">
              <div class="form-group"><label class="form-label">Jurisdiction</label>
                <div class="select2-custom"><select><option>United States</option><option>United Kingdom</option><option>European Union</option><option>Sri Lanka</option><option>Singapore</option></select></div>
              </div>
              <div class="form-group"><label class="form-label">Investor Type</label>
                <div class="select2-custom"><select><option>Individual</option><option>Corporate</option><option>Pension Fund</option></select></div>
              </div>
              <div class="form-group"><label class="form-label">Holding Type</label>
                <div class="select2-custom"><select><option>Long-Term (&gt;1yr)</option><option>Short-Term</option></select></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">Capital Gain ($)</label>
                <div class="input-icon-wrap"><i class="fa-solid fa-dollar-sign icon"></i><input type="number" class="form-control" value="85000" style="padding-left:30px"></div>
              </div>
              <div class="form-group"><label class="form-label">Tax Rate (%)</label><input type="number" class="form-control" value="20"></div>
            </div>
            <div class="calc-result">
              <div class="calc-result-title">Tax Liability</div>
              <div class="calc-result-grid">
                <div class="calc-result-item"><div class="rlabel">Capital Gains Tax</div><div class="rvalue amber">$17,000</div></div>
                <div class="calc-result-item"><div class="rlabel">Net After Tax</div><div class="rvalue green">$68,000</div></div>
                <div class="calc-result-item"><div class="rlabel">Effective Rate</div><div class="rvalue">20.0%</div></div>
                <div class="calc-result-item"><div class="rlabel">Stamp Duty</div><div class="rvalue">$425</div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>





@endsection
