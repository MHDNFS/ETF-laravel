<header class="header">
      <button class="hamburger" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
      <x-layout.header-breadcrumb :title="$headerTitle ?? 'Dashboard'" />
      <div class="header-search">
        <i class="fa-solid fa-magnifying-glass si"></i>
        <input type="text" placeholder="Search funds, tickers…">
      </div>
      <div class="header-actions">
        <x-layout.theme-toggle />
        <div class="hbtn" onclick="showModal('notifications-modal')" title="Notifications">
          <i class="fa-regular fa-bell"></i><span class="badge">3</span>
        </div>
        <div class="hbtn hide-mobile-sm" title="Messages"><i class="fa-regular fa-comment-dots"></i><span class="badge">1</span></div>
        <div class="hbtn hide-mobile-sm" title="Help"><i class="fa-regular fa-circle-question"></i></div>
        <div style="position:relative">
          <div class="header-avatar" id="auth-user-initials-menu" data-auth-initials onclick="toggleAvatarMenu()">--</div>
          <div class="header-avatar-menu" id="avatar-menu">
            <div class="avatar-menu-header">
              <div class="avatar" style="width:36px;height:36px;font-size:13px" data-auth-initials>--</div>
              <div>
                <div style="font-size:13px;font-weight:500" id="auth-user-name-menu" data-auth-name>--</div>
                <div style="font-size:11px;color:var(--text3)" id="auth-user-email-menu">--</div>
              </div>
            </div>
            <a class="avatar-menu-item" href="/profile"><i class="fa-solid fa-user"></i> My Profile</a>
            <a class="avatar-menu-item" href="/settings"><i class="fa-solid fa-gear"></i> Settings</a>
            <div class="avatar-menu-divider"></div>
            <button type="button" class="avatar-menu-item danger avatar-menu-item--button" data-auth-logout>
              <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
            </button>
          </div>
        </div>
      </div>
    </header>

