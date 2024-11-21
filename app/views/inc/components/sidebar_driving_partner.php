<section id="sidebar">
  <a href="<?php echo URLROOT; ?>" class="brand">
    <img src="../img/logo.svg" alt="Logo" />
    <span class="text">EVERGREEN</span>
  </a>
  <ul class="side-menu top">
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/drivingpartner/index">
        <i class="bx bxs-dashboard"></i>
        <span class="text">Dashboard</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'leave') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/drivingpartner/leave">
        <i class='bx bxs-calendar'></i>
        <span class="text">Leave</span>
      </a>
    </li>
  <ul class="side-menu">
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'settings') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/drivingpartner/settings">
        <i class="bx bxs-cog"></i>
        <span class="text">Settings</span>
      </a>
    </li>
    <li>
      <a href="<?php echo URLROOT; ?>/auth/logout" class="logout">
        <i class="bx bxs-log-out-circle"></i>
        <span class="text">Logout</span>
      </a>
    </li>
  </ul>
</section>
