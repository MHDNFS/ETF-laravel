@extends('layouts.layout')
@section('title','Profile')
@section('content')

<div class="content page-animate">

      <div class="page-title">My Profile</div>
      <div class="page-sub">Manage your account, preferences, and activity</div>
      <div class="card" style="margin-bottom:20px;overflow:visible">
        <div class="profile-banner"></div>
        <div class="profile-avatar-wrap"><div class="avatar-lg">AA</div></div>
        <div class="profile-info-row">
          <div>
            <div class="profile-name">Alex Analyst</div>
            <div class="profile-role">Senior Portfolio Manager · QuantEdge Capital</div>
          </div>
          <div style="display:flex;gap:10px">
            <button class="btn btn-outline btn-sm"><i class="fa-solid fa-envelope"></i> Message</button>
            <button class="btn btn-blue btn-sm" onclick="showModal('edit-profile-modal')"><i class="fa-solid fa-pen"></i> Edit Profile</button>
          </div>
        </div>
        <div class="profile-stats-row">
          <div class="profile-stat"><div class="val">$48.3M</div><div class="lbl">Assets Managed</div></div>
          <div class="profile-stat"><div class="val">12</div><div class="lbl">Active Funds</div></div>
          <div class="profile-stat"><div class="val">+12.7%</div><div class="lbl">Avg Return (YTD)</div></div>
          <div class="profile-stat"><div class="val">4.8★</div><div class="lbl">Performance Score</div></div>
        </div>
      </div>
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><span class="card-title">Account Details</span></div>
          <div class="card-body">
            <div class="metric-row"><span class="metric-lbl">Full Name</span><span class="metric-val">Alex Analyst</span></div>
            <div class="metric-row"><span class="metric-lbl">Email</span><span class="metric-val">analyst@quantedge.io</span></div>
            <div class="metric-row"><span class="metric-lbl">Role</span><span class="metric-val">Senior Portfolio Manager</span></div>
            <div class="metric-row"><span class="metric-lbl">Department</span><span class="metric-val">Investment Strategy</span></div>
            <div class="metric-row"><span class="metric-lbl">Location</span><span class="metric-val">Kandy, Sri Lanka</span></div>
            <div class="metric-row"><span class="metric-lbl">Member Since</span><span class="metric-val">Jan 2024</span></div>
            <div class="metric-row"><span class="metric-lbl">Last Login</span><span class="metric-val">Today, 08:42 AM</span></div>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Recent Activity</span></div>
          <div class="card-body">
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(16,185,129,0.12);color:var(--success)"><i class="fa-solid fa-check"></i></div>
              <div><div class="notif-text">Settled <strong>VOO × 120</strong> — ETF trade</div><div class="notif-time">2 May 2026 · $61,488</div></div>
            </div>
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(79,140,255,0.12);color:var(--accent)"><i class="fa-solid fa-calculator"></i></div>
              <div><div class="notif-text">ETF Calculation — QQQ scenario</div><div class="notif-time">1 May 2026</div></div>
            </div>
            <div class="notif-item">
              <div class="notif-icon" style="background:rgba(245,158,11,0.12);color:var(--accent4)"><i class="fa-solid fa-file-export"></i></div>
              <div><div class="notif-text">Exported <strong>Q1 2026</strong> PDF Report</div><div class="notif-time">1 May 2026</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- <footer class="footer">
      <div>© 2026 QuantEdge Capital · ETF &amp; PTF Dashboard v3.2.1</div>
      <div class="footer-links">
        <a href="#">Privacy Policy</a><a href="#">Terms of Service</a>
        <a href="#">API Docs</a><a href="#">Support</a>
      </div>
    </footer> --}}


@endsection
