<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo" href="index.php"><img src="../assets/images/logo.svg" alt="logo" /></a>
    <a class="sidebar-brand brand-logo-mini" href="index.php"><img src="../assets/images/logo-mini.svg"
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
    </li>

    <li class="nav-item nav-category">
      <span class="nav-link">Appointments</span>
    </li>

    <li class="nav-item menu-items <?php if ($currentPage == 'ongoing-appointments') {echo 'active';}?>">
      <a class="nav-link" href="index.php">
        <span class="menu-icon">
          <i class="mdi mdi-calendar-text"></i>
        </span>
        <span class="menu-title">Ongoing</span>
      </a>
    </li>

    <li class="nav-item menu-items <?php if ($currentPage == 'pending-appointments') {echo 'active';}?>">
      <a class="nav-link" href="pending-appointments.php">
        <span class="menu-icon">
          <i class="mdi mdi-calendar-clock"></i>
        </span>
        <span class="menu-title">Pending</span>
      </a>
    </li>

    <li class="nav-item menu-items <?php if ($currentPage == 'rejected-appointments') {echo 'active';}?>">
      <a class="nav-link" href="rejected-appointments.php">
        <span class="menu-icon">
          <i class="mdi mdi-calendar-remove"></i>
        </span>
        <span class="menu-title">Rejected</span>
      </a>
    </li>

    <li class="nav-item menu-items <?php if ($currentPage == 'completed-appointments') {echo 'active';}?>">
      <a class="nav-link" href="completed-appointments.php">
        <span class="menu-icon">
          <i class="mdi mdi-calendar-multiple-check"></i>
        </span>
        <span class="menu-title">Completed</span>
      </a>
    </li>

  </ul>
</nav>