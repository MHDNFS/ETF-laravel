/* ============================================================
   QuantEdge — ETF & PTF Dashboard
   assets/js/calculators.js  —  ETF & PTF Calculation Logic
   ============================================================ */

/* ─── ETF CALCULATOR ──────────────────────────────────────── */
function calcETF() {
  const nav    = parseFloat(document.getElementById('etf-nav').value)    || 512.40;
  const units  = parseFloat(document.getElementById('etf-units').value)  || 100;
  const amountInput = parseFloat(document.getElementById('etf-amount').value);
  const amount = Number.isFinite(amountInput) ? amountInput : (units * nav);
  const price  = parseFloat(document.getElementById('etf-price').value)  || 513.10;
  const ter    = parseFloat(document.getElementById('etf-ter').value)    || 0.03;
  const bench  = parseFloat(document.getElementById('etf-bench').value)  || 12.5;
  const years  = parseFloat(document.getElementById('etf-years').value)  || 5;

  const prem      = ((price - nav) / nav * 100).toFixed(2);
  const netReturn = ((bench - ter) * years).toFixed(1);
  const proj      = amount * Math.pow(1 + (bench - ter) / 100, years);
  const gain      = proj - amount;
  const drag      = amount * (ter / 100) * years;
  const te        = (ter * 0.8).toFixed(2);

  function setEl(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
  }

  setEl('etf-r-prem', (prem >= 0 ? '+' : '') + prem + '%');
  setEl('etf-r-te',   te + '%');
  setEl('etf-r-ret',  '+' + netReturn + '%');
  setEl('etf-r-drag', '$' + drag.toFixed(0));
  setEl('etf-r-proj', '$' + fmt(proj.toFixed(0)));
  setEl('etf-r-gain', '+$' + fmt(gain.toFixed(0)));

  /* redraw projection chart */
  if (typeof initETFProjChart === 'function') {
    initETFProjChart(amount, (bench - ter) / 100);
  }

  showToast('success', 'ETF Calculated', 'Results updated successfully.');
}

/* ─── NUMBER FORMAT HELPER ────────────────────────────────── */
function fmt(n) {
  return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/* ─── FUND TABLE DATA ─────────────────────────────────────── */
var FUNDS = [
  { name: 'Vanguard S&P 500 ETF',   ticker: 'VOO',  nav: 512.40, ret1: 14.2, cagr: 12.8, aum: '42.1B', ter: 0.03, stars: 5 },
  { name: 'iShares MSCI Emerging',  ticker: 'IEMG', nav: 54.22,  ret1: 8.4,  cagr: 7.1,  aum: '18.3B', ter: 0.09, stars: 4 },
  { name: 'Invesco QQQ Trust',      ticker: 'QQQ',  nav: 448.10, ret1: 22.1, cagr: 18.4, aum: '24.8B', ter: 0.20, stars: 5 },
  { name: 'SPDR Gold Shares',       ticker: 'GLD',  nav: 224.10, ret1: 11.8, cagr: 6.2,  aum: '8.7B',  ter: 0.40, stars: 3 },
  { name: 'iShares Core US Agg',    ticker: 'AGG',  nav: 96.30,  ret1: 3.2,  cagr: 2.8,  aum: '32.4B', ter: 0.03, stars: 4 },
  { name: 'Vanguard Total World',   ticker: 'VT',   nav: 108.80, ret1: 12.4, cagr: 9.6,  aum: '15.2B', ter: 0.07, stars: 4 },
  { name: 'ARK Innovation ETF',     ticker: 'ARKK', nav: 48.30,  ret1: 31.4, cagr: 9.1,  aum: '6.9B',  ter: 0.75, stars: 3 },
  { name: 'iShares TIPS Bond',      ticker: 'TIP',  nav: 107.80, ret1: 4.1,  cagr: 3.4,  aum: '11.2B', ter: 0.19, stars: 4 },
  { name: 'Schwab US Dividend',     ticker: 'SCHD', nav: 28.60,  ret1: 9.8,  cagr: 11.2, aum: '20.1B', ter: 0.06, stars: 5 },
  { name: 'iShares Russell 2000',   ticker: 'IWM',  nav: 196.40, ret1: 7.2,  cagr: 8.6,  aum: '28.4B', ter: 0.19, stars: 3 },
  { name: 'SPDR S&P Dividend',      ticker: 'SDY',  nav: 132.50, ret1: 8.6,  cagr: 9.4,  aum: '9.7B',  ter: 0.35, stars: 4 },
  { name: 'Vanguard Real Estate',   ticker: 'VNQ',  nav: 86.20,  ret1: 5.8,  cagr: 6.8,  aum: '14.3B', ter: 0.12, stars: 3 }
];

function buildFundTable() {
  const tb = document.getElementById('fund-table-body');
  if (!tb) return;
  tb.innerHTML = FUNDS.map(function(f) {
    return '<tr>' +
      '<td><input type="checkbox"></td>' +
      '<td><div class="fund-name">' + f.name + '</div></td>' +
      '<td><span class="fund-ticker">' + f.ticker + '</span></td>' +
      '<td class="td-mono">$' + f.nav.toFixed(2) + '</td>' +
      '<td class="' + (f.ret1 >= 0 ? 'td-up' : 'td-down') + '">' + (f.ret1 >= 0 ? '+' : '') + f.ret1 + '%</td>' +
      '<td class="td-up">+' + f.cagr + '%</td>' +
      '<td class="td-mono">$' + f.aum + '</td>' +
      '<td class="td-mono">' + f.ter + '%</td>' +
      '<td><span style="color:#f59e0b">' + '★'.repeat(f.stars) + '</span><span style="color:var(--text3)">' + '★'.repeat(5 - f.stars) + '</span></td>' +
      '<td><button type="button" class="btn btn-blue btn-sm js-open-add-etf-fund" data-fund="' +
      encodeURIComponent(JSON.stringify({name:f.name,ticker:f.ticker,nav:f.nav,ret1:f.ret1,cagr:f.cagr,aum:f.aum,ter:f.ter,stars:f.stars})) +
      '">Add</button></td>' +
    '</tr>';
  }).join('');
}
