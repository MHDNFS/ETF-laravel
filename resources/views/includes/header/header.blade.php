<header class="header">
      <button class="hamburger" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
      <div class="header-breadcrumb">Dashboard</div>
      <div class="header-search">
        <i class="fa-solid fa-magnifying-glass si"></i>
        <input type="text" placeholder="Search funds, tickers…">
      </div>
      <div class="header-actions">
        <div class="hbtn" onclick="showModal('notifications-modal')" title="Notifications">
          <i class="fa-regular fa-bell"></i><span class="badge">3</span>
        </div>
        <div class="hbtn" title="Messages"><i class="fa-regular fa-comment-dots"></i><span class="badge">1</span></div>
        <div class="hbtn" title="Help"><i class="fa-regular fa-circle-question"></i></div>
        <div style="position:relative">
          <div class="header-avatar" onclick="toggleAvatarMenu()">AA</div>
          <div class="header-avatar-menu" id="avatar-menu">
            <div class="avatar-menu-header">
              <div class="avatar" style="width:36px;height:36px;font-size:13px">AA</div>
              <div><div style="font-size:13px;font-weight:500">Alex Analyst</div>
              <div style="font-size:11px;color:var(--text3)">analyst@quantedge.io</div></div>
            </div>
            <a class="avatar-menu-item" href="profile.html"><i class="fa-solid fa-user"></i> My Profile</a>
            <a class="avatar-menu-item" href="settings.html"><i class="fa-solid fa-gear"></i> Settings</a>
            <div class="avatar-menu-divider"></div>
            <a class="avatar-menu-item danger" href="index.html"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out</a>
          </div>
        </div>
      </div>
    </header>
