@extends('layouts.layout')
@section('title','PTF PORTFOLIO')
@section('header_title', 'Portfolio Manager')
@section('content')


    <div class="content page-animate">

      <x-page-header title="Portfolio Manager" subtitle="Manage holdings, rebalance, and monitor PTF positions" />
      <div class="stats-grid">
        <div class="stat-card blue"><div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div><div class="stat-label">Total Portfolio Value</div><div class="stat-value">$603K</div><div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> +2.3% today</div></div>
        <div class="stat-card green"><div class="stat-icon green"><i class="fa-solid fa-arrow-trend-up"></i></div><div class="stat-label">Unrealised P&amp;L</div><div class="stat-value">+$41.2K</div><div class="stat-change up"><i class="fa-solid fa-arrow-up"></i> +7.3%</div></div>
        <div class="stat-card amber"><div class="stat-icon amber"><i class="fa-solid fa-rotate"></i></div><div class="stat-label">Rebalance Drift</div><div class="stat-value">3.8%</div><div class="stat-change down"><i class="fa-solid fa-triangle-exclamation"></i> Action needed</div></div>
      </div>
      <div class="card">
        <div class="card-header">
          <span class="card-title">Holdings</span>
          <div style="display:flex;gap:8px">
            <button class="btn btn-outline btn-sm" onclick="showModal('rebalance-modal')"><i class="fa-solid fa-rotate"></i> Rebalance</button>
            <button class="btn btn-blue btn-sm" onclick="showModal('add-trade-modal')"><i class="fa-solid fa-plus"></i> Add Position</button>
          </div>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Holding</th><th>Type</th><th>Units</th><th>Avg Cost</th><th>Current Price</th><th>Market Value</th><th>P&amp;L</th><th>Weight</th><th>Action</th></tr></thead>
            <tbody>
              <tr>
                <td><div class="fund-name">Vanguard S&amp;P 500</div><div class="fund-ticker">VOO</div></td>
                <td><span class="tag tag-etf">ETF</span></td>
                <td class="td-mono">120</td><td class="td-mono">$488.20</td><td class="td-mono">$512.40</td>
                <td class="td-mono" style="font-weight:500">$61,488</td>
                <td class="td-up">+$2,904</td>
                <td><div style="display:flex;align-items:center;gap:8px"><span class="td-mono">32%</span><div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:64%;background:var(--accent)"></div></div></div></td>
                <td><div style="display:flex;gap:4px"><button class="btn btn-outline btn-sm">Buy</button><button class="btn btn-danger btn-sm">Sell</button></div></td>
              </tr>
              <tr>
                <td><div class="fund-name">Custom Growth PTF</div><div class="fund-ticker">CGF-A</div></td>
                <td><span class="tag tag-ptf">PTF</span></td>
                <td class="td-mono">200</td><td class="td-mono">$82.50</td><td class="td-mono">$88.75</td>
                <td class="td-mono" style="font-weight:500">$17,750</td>
                <td class="td-up">+$1,250</td>
                <td><div style="display:flex;align-items:center;gap:8px"><span class="td-mono">18%</span><div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:36%;background:var(--accent3)"></div></div></div></td>
                <td><div style="display:flex;gap:4px"><button class="btn btn-outline btn-sm">Buy</button><button class="btn btn-danger btn-sm">Sell</button></div></td>
              </tr>
              <tr>
                <td><div class="fund-name">Tactical Bond PTF</div><div class="fund-ticker">TBP-2</div></td>
                <td><span class="tag tag-ptf">PTF</span></td>
                <td class="td-mono">500</td><td class="td-mono">$105.10</td><td class="td-mono">$102.30</td>
                <td class="td-mono" style="font-weight:500">$51,150</td>
                <td class="td-down">-$1,400</td>
                <td><div style="display:flex;align-items:center;gap:8px"><span class="td-mono">26%</span><div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:52%;background:var(--accent2)"></div></div></div></td>
                <td><div style="display:flex;gap:4px"><button class="btn btn-outline btn-sm">Buy</button><button class="btn btn-danger btn-sm">Sell</button></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>




@endsection
