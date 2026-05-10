/* ============================================================
   QuantEdge — ETF & PTF Dashboard
   assets/js/modals.js  —  All Modal HTML Templates
   ============================================================ */

var MODALS_HTML = `
<!-- Notifications Modal -->
<div class="modal-overlay" id="notifications-modal">
  <div class="modal" style="width:460px">
    <div class="modal-header">
      <span class="modal-title"><i class="fa-regular fa-bell" style="color:var(--accent);margin-right:8px"></i>Notifications</span>
      <button class="modal-close" onclick="closeModal('notifications-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="notif-item">
        <div class="notif-icon" style="background:rgba(239,68,68,0.12);color:var(--danger)"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div><div class="notif-text"><strong>Rebalance Required</strong> — Portfolio drift at 3.8%</div><div class="notif-time">10 minutes ago</div></div>
      </div>
      <div class="notif-item">
        <div class="notif-icon" style="background:rgba(16,185,129,0.12);color:var(--success)"><i class="fa-solid fa-check-circle"></i></div>
        <div><div class="notif-text"><strong>Trade Settled</strong> — VOO × 120 units settled</div><div class="notif-time">2 hours ago</div></div>
      </div>
      <div class="notif-item">
        <div class="notif-icon" style="background:rgba(79,140,255,0.12);color:var(--accent)"><i class="fa-solid fa-chart-line"></i></div>
        <div><div class="notif-text"><strong>Market Alert</strong> — S&P 500 up +1.2% today</div><div class="notif-time">4 hours ago</div></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('notifications-modal')">Mark All Read</button>
      <button class="btn btn-blue" onclick="closeModal('notifications-modal')">Done</button>
    </div>
  </div>
</div>

<!-- Add Trade Modal -->
<div class="modal-overlay" id="add-trade-modal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fa-solid fa-plus" style="color:var(--accent);margin-right:8px"></i>New Trade</span>
      <button class="modal-close" onclick="closeModal('add-trade-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Fund Type</label>
        <div class="select2-custom"><select><option>ETF</option><option>PTF</option></select></div>
      </div>
      <div class="form-group">
        <label class="form-label">Select Fund</label>
        <div class="select2-custom"><select>
          <option>Vanguard S&P 500 ETF (VOO)</option>
          <option>iShares MSCI Emerging (IEMG)</option>
          <option>Custom Growth Fund (CGF-A)</option>
          <option>SPDR Gold ETF (GLD)</option>
        </select></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Action</label>
          <div class="select2-custom"><select><option>Buy</option><option>Sell</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Units</label>
          <input type="number" class="form-control" placeholder="100">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Price ($)</label>
          <input type="number" class="form-control" placeholder="512.40">
        </div>
        <div class="form-group"><label class="form-label">Trade Date</label>
          <input type="date" class="form-control" value="2026-05-03">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Notes</label>
        <textarea class="form-control" placeholder="Optional trade notes…"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('add-trade-modal')">Cancel</button>
      <button class="btn btn-blue" onclick="closeModal('add-trade-modal');showToast('success','Trade Submitted','New trade queued for settlement.')">Submit Trade</button>
    </div>
  </div>
</div>

<!-- Trade Detail Modal -->
<div class="modal-overlay" id="trade-detail-modal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title">Trade Detail — VOO</span>
      <button class="modal-close" onclick="closeModal('trade-detail-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div style="margin-bottom:14px"><span class="tag tag-etf">ETF</span> &nbsp;<span class="badge badge-green">Settled</span></div>
      <div class="metric-row"><span class="metric-lbl">Fund</span><span class="metric-val">Vanguard S&P 500 ETF</span></div>
      <div class="metric-row"><span class="metric-lbl">Ticker</span><span class="metric-val" style="color:var(--accent)">VOO</span></div>
      <div class="metric-row"><span class="metric-lbl">Trade Type</span><span class="metric-val">Buy</span></div>
      <div class="metric-row"><span class="metric-lbl">Units</span><span class="metric-val">120</span></div>
      <div class="metric-row"><span class="metric-lbl">Execution Price</span><span class="metric-val">$512.40</span></div>
      <div class="metric-row"><span class="metric-lbl">Total Value</span><span class="metric-val">$61,488</span></div>
      <div class="metric-row"><span class="metric-lbl">Commission</span><span class="metric-val">$4.95</span></div>
      <div class="metric-row"><span class="metric-lbl">Net Cost</span><span class="metric-val" style="color:var(--success)">$61,492.95</span></div>
      <div class="metric-row"><span class="metric-lbl">Settlement Date</span><span class="metric-val">2026-05-04 (T+2)</span></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('trade-detail-modal')">Close</button>
      <button class="btn btn-danger" onclick="closeModal('trade-detail-modal');showToast('error','Trade Cancelled','Cancellation request submitted.')">Cancel Trade</button>
    </div>
  </div>
</div>

<!-- Rebalance Modal -->
<div class="modal-overlay" id="rebalance-modal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fa-solid fa-rotate" style="color:var(--accent);margin-right:8px"></i>Portfolio Rebalance</span>
      <button class="modal-close" onclick="closeModal('rebalance-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:8px;padding:12px 14px;margin-bottom:16px;font-size:13px;color:var(--accent4)">
        <i class="fa-solid fa-triangle-exclamation"></i>&nbsp; Portfolio has drifted <strong>3.8%</strong> from target. Rebalancing recommended.
      </div>
      <div class="metric-row"><span class="metric-lbl">US Equity (Target: 40%)</span><span class="metric-val">→ Sell $2,840</span></div>
      <div class="metric-row"><span class="metric-lbl">Fixed Income (Target: 30%)</span><span class="metric-val">→ Buy $3,200</span></div>
      <div class="metric-row"><span class="metric-lbl">Intl Equity (Target: 20%)</span><span class="metric-val">→ No change</span></div>
      <div class="metric-row"><span class="metric-lbl">Commodities (Target: 10%)</span><span class="metric-val">→ Buy $640</span></div>
      <div style="margin-top:14px;font-size:12px;color:var(--text3)">Estimated transaction cost: $14.80</div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('rebalance-modal')">Cancel</button>
      <button class="btn btn-blue" onclick="closeModal('rebalance-modal');showToast('success','Rebalance Initiated','Portfolio rebalance orders placed.')">Execute Rebalance</button>
    </div>
  </div>
</div>

<!-- Add Asset Modal -->
<div class="modal-overlay" id="add-asset-modal">
  <div class="modal" style="width:400px">
    <div class="modal-header">
      <span class="modal-title">Add Asset Class</span>
      <button class="modal-close" onclick="closeModal('add-asset-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-group"><label class="form-label">Asset Name</label><input type="text" class="form-control" placeholder="e.g. Real Estate"></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Weight (%)</label><input type="number" class="form-control" value="10"></div>
        <div class="form-group"><label class="form-label">Expected Return (%)</label><input type="number" class="form-control" value="8.0" step="0.1"></div>
      </div>
      <div class="form-group"><label class="form-label">Volatility (%)</label><input type="number" class="form-control" value="15.0" step="0.1"></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('add-asset-modal')">Cancel</button>
      <button class="btn btn-blue" onclick="closeModal('add-asset-modal');showToast('success','Asset Added','New asset class added.')">Add Asset</button>
    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal-overlay" id="edit-profile-modal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title">Edit Profile</span>
      <button class="modal-close" onclick="closeModal('edit-profile-modal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-row">
        <div class="form-group"><label class="form-label">First Name</label><input type="text" class="form-control" value="Alex"></div>
        <div class="form-group"><label class="form-label">Last Name</label><input type="text" class="form-control" value="Analyst"></div>
      </div>
      <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" value="analyst@quantedge.io"></div>
      <div class="form-group"><label class="form-label">Job Title</label><input type="text" class="form-control" value="Senior Portfolio Manager"></div>
      <div class="form-group"><label class="form-label">Department</label>
        <div class="select2-custom"><select>
          <option selected>Investment Strategy</option>
          <option>Risk Management</option>
          <option>Research &amp; Analysis</option>
        </select></div>
      </div>
      <div class="form-group"><label class="form-label">Bio</label>
        <textarea class="form-control" style="min-height:70px">Senior portfolio manager with 8+ years in ETF/PTF strategy.</textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('edit-profile-modal')">Cancel</button>
      <button class="btn btn-blue" onclick="closeModal('edit-profile-modal');showToast('success','Profile Updated','Your profile has been saved.')">Save Changes</button>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toast-container"></div>
`;

document.addEventListener('DOMContentLoaded', function () {
  const wrap = document.createElement('div');
  wrap.innerHTML = MODALS_HTML;
  document.body.appendChild(wrap);
});
