<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo" href="index.html"><img src="../assets/images/logo.svg" alt="logo" /></a>
    <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="../assets/images/logo-mini.svg"
        alt="logo" /></a>
  </div>
  <ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle " src="../assets/images/faces/face15.jpg" alt="">
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            <h5 class="mb-0 font-weight-normal"><?php echo $_SESSION['full_name']; ?></h5>
            <span><?php echo $_SESSION['user_type']; ?></span>
          </div>
        </div>
        <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list"
          aria-labelledby="profile-dropdown">
          <a href="settings.php" class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-settings text-success"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1 text-small">Settings</p>
            </div>
          </a>
          
        </div>
    </li>
    <li class="nav-item nav-category">
      <span class="nav-link">Navigation</span>
    </li>
    <li class="nav-item menu-items <?php if($currentPage =='dashboard'){echo 'active';}?>">
      <a class="nav-link" href="index.php">
        <span class="menu-icon">
          <i class="mdi mdi-speedometer"></i>
        </span>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item menu-items <?php if($currentPage =='new-user'){echo 'active';}?>">
      <a class="nav-link" href="new-user.php">
        <span class="menu-icon">
          <i class="mdi mdi-account-plus"></i>
        </span>
        <span class="menu-title">New User</span>
      </a>
    </li>

    <li class="nav-item menu-items <?php if($currentPage =='settings'){echo 'active';}?>">
      <a class="nav-link" href="settings.php">
        <span class="menu-icon">
          <i class="mdi mdi-settings"></i>
        </span>
        <span class="menu-title">Settings</span>
      </a>
    </li>
  </ul>
</nav>