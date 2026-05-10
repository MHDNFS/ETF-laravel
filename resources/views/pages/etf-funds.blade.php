@extends('layouts.layout')
@section('title','EPF FUNDS')
@section('content')

    <div class="content page-animate">

      <div class="page-title">ETF Fund Explorer</div>
      <div class="page-sub">Browse, filter, and analyze available exchange-traded funds</div>
      <div class="card">
        <div class="card-body">
          <div class="form-row" style="gap:12px;align-items:flex-end">
            <div class="form-group" style="margin-bottom:0"><label class="form-label">Asset Class</label>
              <div class="select2-custom"><select><option>All Classes</option><option>Equity</option><option>Fixed Income</option><option>Commodity</option><option>Real Estate</option></select></div>
            </div>
            <div class="form-group" style="margin-bottom:0"><label class="form-label">Region</label>
              <div class="select2-custom"><select><option>Global</option><option>North America</option><option>Europe</option><option>Asia Pacific</option><option>Emerging Markets</option></select></div>
            </div>
            <div class="form-group" style="margin-bottom:0"><label class="form-label">TER Range</label>
              <div class="select2-custom"><select><option>Any</option><option>&lt; 0.10%</option><option>0.10% – 0.50%</option><option>&gt; 0.50%</option></select></div>
            </div>
            <div class="form-group" style="margin-bottom:0"><label class="form-label">Sort By</label>
              <div class="select2-custom"><select><option>AUM (High–Low)</option><option>Return 1Y</option><option>TER (Low–High)</option></select></div>
            </div>
            <button class="btn btn-blue" style="flex-shrink:0"><i class="fa-solid fa-filter"></i> Apply</button>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><span class="card-title">Fund List</span><span class="badge badge-blue">12 Funds</span></div>
        <div class="table-wrap">
          <table>
            <thead><tr>
              <th><input type="checkbox"></th>
              <th class="sort">Fund Name</th><th>Ticker</th>
              <th class="sort">NAV</th><th class="sort">1Y Return</th>
              <th class="sort">3Y CAGR</th><th class="sort">AUM</th>
              <th>TER</th><th>Rating</th><th></th>
            </tr></thead>
            <tbody id="fund-table-body"></tbody>
          </table>
        </div>
      </div>
    </div>

@endsection
