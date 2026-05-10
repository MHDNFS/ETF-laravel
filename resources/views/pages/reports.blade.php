@extends('layouts.layout')
@section('title','Reports')
@section('content')

    <div class="content page-animate">

      <div class="page-title">Reports &amp; Export</div>
      <div class="page-sub">Generate, download, and schedule fund performance reports</div>
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><span class="card-title">Generate Report</span></div>
          <div class="card-body">
            <div class="form-group"><label class="form-label">Report Type</label>
              <div class="select2-custom"><select><option>ETF Performance Summary</option><option>PTF Portfolio Statement</option><option>Tax Report (Annual)</option><option>Compliance Audit</option></select></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">From Date</label><input type="date" class="form-control" value="2026-01-01"></div>
              <div class="form-group"><label class="form-label">To Date</label><input type="date" class="form-control" value="2026-05-03"></div>
            </div>
            <div class="form-group"><label class="form-label">Export Format</label>
              <div class="select2-custom"><select><option>PDF</option><option>Excel (.xlsx)</option><option>CSV</option><option>JSON</option></select></div>
            </div>
            <div class="form-group"><label class="form-label">Notes / Comments</label>
              <textarea class="form-control" placeholder="Optional report notes…"></textarea>
            </div>
            <button class="btn btn-blue" style="width:100%" onclick="showToast('success','Report Generated','Your report is ready for download.')">
              <i class="fa-solid fa-file-export"></i> Generate &amp; Download
            </button>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Recent Reports</span></div>
          <div class="card-body">
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(79,140,255,0.12);color:var(--accent)"><i class="fa-solid fa-file-pdf"></i></div>
              <div><div class="notif-text"><strong>ETF Q1 2026 Summary</strong></div><div class="notif-time">Generated 3 May 2026 · PDF · 2.4 MB</div></div>
              <button class="btn btn-outline btn-sm" style="margin-left:auto"><i class="fa-solid fa-download"></i></button>
            </div>
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(0,212,170,0.12);color:var(--accent3)"><i class="fa-solid fa-file-excel"></i></div>
              <div><div class="notif-text"><strong>PTF Annual Performance</strong></div><div class="notif-time">Generated 1 May 2026 · XLSX · 1.1 MB</div></div>
              <button class="btn btn-outline btn-sm" style="margin-left:auto"><i class="fa-solid fa-download"></i></button>
            </div>
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(245,158,11,0.12);color:var(--accent4)"><i class="fa-solid fa-file-lines"></i></div>
              <div><div class="notif-text"><strong>Tax Report FY2025</strong></div><div class="notif-time">Generated 15 Apr 2026 · PDF · 3.8 MB</div></div>
              <button class="btn btn-outline btn-sm" style="margin-left:auto"><i class="fa-solid fa-download"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection

