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
        
        
        
        
        {{-- Profile stats row: data here → <x-profile-stat /> (see components/profile-stat.blade.php) --}}
        
        
        @php
          $profileStats = [
              ['value' => '$48.3M', 'label' => 'Assets Managed'],
              ['value' => '12', 'label' => 'Active Funds'],
              ['value' => '+12.7%', 'label' => 'Avg Return (YTD)'],
              ['value' => '4.8★', 'label' => 'Performance Score'],
          ];
        @endphp
        <div class="profile-stats-row">
          @foreach ($profileStats as $stat)
            <x-profile-stat :value="$stat['value']" :label="$stat['label']" />
          @endforeach
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
            {{-- Recent activity rows → <x-profile-activity-item /> (components/profile-activity-item.blade.php) --}}
            
            
            
            @php
              $recentActivity = [
                  [
                      'icon' => 'fa-check',
                      'iconStyle' => 'background:rgba(16,185,129,0.12);color:var(--success)',
                      'time' => '2 May 2026 · $61,488',
                      'text' => 'Settled <strong>VOO x 120</strong> — ETF trade',
                  ],
                  [
                      'icon' => 'fa-calculator',
                      'iconStyle' => 'background:rgba(79,140,255,0.12);color:var(--accent)',
                      'time' => '1 May 2026',
                      'text' => 'ETF Calculation — QQQ scenario',
                  ],
                  [
                      'icon' => 'fa-file-export',
                      'iconStyle' => 'background:rgba(245,158,11,0.12);color:var(--accent4)',
                      'time' => '1 May 2026',
                      'text' => 'Exported <strong>Q1 2026</strong> PDF Report',
                  ],
              ];
            @endphp
            @foreach ($recentActivity as $activity)
              <x-profile-activity-item
                :icon="$activity['icon']"
                :icon-style="$activity['iconStyle']"
                :time="$activity['time']"
                :text="$activity['text']"
              />
            @endforeach
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
