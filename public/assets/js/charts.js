/* ============================================================
   QuantEdge — ETF & PTF Dashboard
   assets/js/charts.js  —  Chart Initialisation (Chart.js)
   ============================================================ */

/* ─── HELPER: safe canvas get ─────────────────────────────── */
function getCtx(id) {
  const el = document.getElementById(id);
  return el ? el : null;
}

/* ─── PERFORMANCE CHART (dashboard.html) ─────────────────── */
function initPerfChart() {
  const ctx = getCtx('perf-chart');
  if (!ctx) return;
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
      datasets: [
        { label: 'ETF',       data: [100,104,108,106,112,116,114,120,124,122,128,134], borderColor: '#4f8cff', backgroundColor: 'rgba(79,140,255,0.06)', fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: '#4f8cff' },
        { label: 'PTF',       data: [100,102,106,105,109,113,112,117,121,120,125,130], borderColor: '#00d4aa', backgroundColor: 'rgba(0,212,170,0.04)',   fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: '#00d4aa' },
        { label: 'Benchmark', data: [100,103,105,104,108,111,110,114,118,117,121,127], borderColor: '#7b5ea7', borderDash: [5,4], tension: 0.4, pointRadius: 0 }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { labels: { color: '#8fa3c8', font: { size: 11 } } } },
      scales: {
        x: { ticks: { color: '#5a7299', font: { size: 11 } }, grid: { color: 'rgba(99,142,255,0.06)' } },
        y: { ticks: { color: '#5a7299', font: { size: 11 } }, grid: { color: 'rgba(99,142,255,0.06)' } }
      }
    }
  });
}

/* ─── ALLOCATION DONUT (dashboard.html) ──────────────────── */
function initAllocChart() {
  const ctx = getCtx('alloc-chart');
  if (!ctx) return;
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['US Equities','Bonds','Intl Markets','Commodities'],
      datasets: [{ data: [42,26,19,13], backgroundColor: ['#4f8cff','#00d4aa','#7b5ea7','#f59e0b'], borderWidth: 0, hoverOffset: 4 }]
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { display: false } } }
  });
}

/* ─── ETF PROJECTION BAR (etf-calculator.html) ───────────── */
function initETFProjChart(amount, rate) {
  const ctx = getCtx('etf-proj-chart');
  if (!ctx) return;
  amount = amount || 50000;
  rate   = rate   || 0.1247;
  const data = [];
  for (let y = 0; y <= 5; y++) data.push(Math.round(amount * Math.pow(1 + rate, y)));
  if (window._etfProjChartInstance) window._etfProjChartInstance.destroy();
  window._etfProjChartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Y0','Y1','Y2','Y3','Y4','Y5'],
      datasets: [{
        label: 'Portfolio Value', data,
        backgroundColor: [
          'rgba(79,140,255,0.40)', 'rgba(79,140,255,0.50)',
          'rgba(79,140,255,0.60)', 'rgba(79,140,255,0.70)',
          'rgba(79,140,255,0.85)', 'rgba(79,140,255,1.00)'
        ],
        borderRadius: 6, borderSkipped: false
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: '#5a7299', font: { size: 11 } }, grid: { display: false } },
        y: { ticks: { color: '#5a7299', font: { size: 11 }, callback: function(v){ return '$' + (v/1000).toFixed(0) + 'k'; } }, grid: { color: 'rgba(99,142,255,0.06)' } }
      }
    }
  });
}

/* ─── PTF GROWTH LINE (ptf-calculator.html) ──────────────── */
function initPTFChart(base, netRate, grossRate) {
  const ctx = getCtx('ptf-chart');
  if (!ctx) return;
  base      = base      || 250000;
  netRate   = netRate   || 0.09;
  grossRate = grossRate || 0.105;
  const net   = [];
  const gross = [];
  for (let y = 0; y <= 10; y++) {
    net.push(Math.round(base * Math.pow(1 + netRate,   y)));
    gross.push(Math.round(base * Math.pow(1 + grossRate, y)));
  }
  const labels = Array.from({ length: 11 }, function(_, i){ return 'Y' + i; });
  new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [
        { label: 'Net Value',    data: net,   borderColor: '#00d4aa', backgroundColor: 'rgba(0,212,170,0.06)', fill: true, tension: 0.4, pointRadius: 2 },
        { label: 'Without Fees', data: gross, borderColor: '#4f8cff', borderDash: [4,4], tension: 0.4, pointRadius: 0 }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { labels: { color: '#8fa3c8', font: { size: 11 } } } },
      scales: {
        x: { ticks: { color: '#5a7299', font: { size: 11 } }, grid: { color: 'rgba(99,142,255,0.06)' } },
        y: { ticks: { color: '#5a7299', font: { size: 11 }, callback: function(v){ return '$' + (v/1000).toFixed(0) + 'k'; } }, grid: { color: 'rgba(99,142,255,0.06)' } }
      }
    }
  });
}
