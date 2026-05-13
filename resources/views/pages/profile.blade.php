@extends('layouts.layout')
@section('title','Profile')
@section('header_title', 'My Profile')
@section('content')

<div class="content page-animate">

      <x-page-header title="My Profile" subtitle="Manage your account, preferences, and activity" />
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
          <x-profile-stat value="$48.3M" label="Assets Managed" />
          <x-profile-stat value="12" label="Active Funds" />
          <x-profile-stat value="+12.7%" label="Avg Return (YTD)" />
          <x-profile-stat value="4.8★" label="Performance Score" />
        </div>
      </div>



      <div class="grid-2">
        <div class="card">
          <div class="card-header"><span class="card-title">Account Details</span></div>
          <div class="card-body">

            <x-profile-detail-row label="Full Name" value="Alex Analyst" />
            <x-profile-detail-row label="Email" value="analyst@quantedge.io" />
            <x-profile-detail-row label="Role" value="Senior Portfolio Manager" />
            <x-profile-detail-row label="Department" value="Investment Strategy" />
            <x-profile-detail-row label="Location" value="Kandy, Sri Lanka" />
            <x-profile-detail-row label="Member Since" value="Jan 2024" />
            <x-profile-detail-row label="Last Login" value="Today, 08:42 AM" />




          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Recent Activity</span></div>
          <div class="card-body">
            <x-profile-activity-item
              icon="fa-check"
              icon-style="background:rgba(16,185,129,0.12);color:var(--success)"
              time="2 May 2026 · $61,488"
              :text="'Settled <strong>VOO x 120</strong> — ETF trade'"
            />
            <x-profile-activity-item
              icon="fa-calculator"
              icon-style="background:rgba(79,140,255,0.12);color:var(--accent)"
              time="1 May 2026"
              text="ETF Calculation — QQQ scenario"
            />
            <x-profile-activity-item
              icon="fa-file-export"
              icon-style="background:rgba(245,158,11,0.12);color:var(--accent4)"
              time="1 May 2026"
              :text="'Exported <strong>Q1 2026</strong> PDF Report'"
            />
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
