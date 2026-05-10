@extends('layouts.layout')
@section('title','Settings')
@section('content')




    <div class="content page-animate">

      <div class="page-title">Settings</div>
      <div class="page-sub">Configure system preferences, notifications, and integrations</div>
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><span class="card-title">Preferences</span></div>
          <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:20px">
              <div style="display:flex;justify-content:space-between;align-items:center">
                <div><div style="font-size:13px;font-weight:500">Email Notifications</div><div style="font-size:12px;color:var(--text3)">Receive trade alerts via email</div></div>
                <label class="switch"><input type="checkbox" checked><span class="switch-slider"></span></label>
              </div>
              <div style="display:flex;justify-content:space-between;align-items:center">
                <div><div style="font-size:13px;font-weight:500">Rebalance Alerts</div><div style="font-size:12px;color:var(--text3)">Alert when drift exceeds 5%</div></div>
                <label class="switch"><input type="checkbox" checked><span class="switch-slider"></span></label>
              </div>
              <div style="display:flex;justify-content:space-between;align-items:center">
                <div><div style="font-size:13px;font-weight:500">Two-Factor Auth</div><div style="font-size:12px;color:var(--text3)">TOTP authenticator app</div></div>
                <label class="switch"><input type="checkbox"><span class="switch-slider"></span></label>
              </div>
              <div style="display:flex;justify-content:space-between;align-items:center">
                <div><div style="font-size:13px;font-weight:500">Dark Mode</div><div style="font-size:12px;color:var(--text3)">Always enabled</div></div>
                <label class="switch"><input type="checkbox" checked disabled><span class="switch-slider"></span></label>
              </div>
            </div>
            <div class="form-group"><label class="form-label">Default Currency</label>
              <div class="select2-custom"><select><option>USD ($)</option><option>EUR (€)</option><option>GBP (£)</option><option>LKR (₨)</option></select></div>
            </div>
            <div class="form-group"><label class="form-label">Date Format</label>
              <div class="select2-custom"><select><option>YYYY-MM-DD</option><option>DD/MM/YYYY</option><option>MM/DD/YYYY</option></select></div>
            </div>
            <button class="btn btn-blue" onclick="showToast('success','Settings Saved','Your preferences have been updated.')">Save Preferences</button>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">API &amp; Integrations</span></div>
          <div class="card-body">
            <div class="form-group"><label class="form-label">Market Data Provider</label>
              <div class="select2-custom"><select><option>Alpha Vantage</option><option>Bloomberg</option><option>Refinitiv</option></select></div>
            </div>
            <div class="form-group"><label class="form-label">API Key</label>
              <div class="input-icon-wrap"><i class="fa-solid fa-key icon"></i>
              <input type="password" class="form-control" value="sk-live-xxxxxxxxxxxxxxxx" style="padding-left:36px"></div>
            </div>
            <div class="form-group"><label class="form-label">Webhook URL</label>
              <input type="url" class="form-control" placeholder="https://your-server.com/webhook">
            </div>
            <button class="btn btn-outline" onclick="showToast('info','Test Sent','Webhook test ping sent successfully.')">Test Connection</button>
          </div>
        </div>
      </div>
    </div>

@endsection

