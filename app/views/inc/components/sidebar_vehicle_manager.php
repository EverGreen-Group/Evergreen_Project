<section id="sidebar">
  <a href="<?php echo URLROOT; ?>" class="brand">
    <img src="../img/logo.svg" alt="Logo" />
    <span class="text">EVERGREEN</span>
  </a>
  <ul class="side-menu top">
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/index">
        <i class="bx bxs-dashboard"></i>
        <span class="text">Dashboard</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'vehicle') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/vehicle">
        <i class="bx bxs-car"></i>
        <span class="text">Vehicle</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'team') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/driver">
        <i class="bx bxs-group"></i>
        <span class="text">Driver</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'route') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/route">
        <i class="bx bx-trip"></i>
        <span class="text">Route</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'shift') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/shift">
        <i class="bx bxs-time-five"></i>
        <span class="text">Shift</span>
      </a>
    </li>
  </ul>
  <ul class="side-menu">
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'settings') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/vehiclemanager/settings">
        <i class="bx bxs-cog"></i>
        <span class="text">Settings</span>
      </a>
    </li>
    <li>
      <a href="<?php echo URLROOT; ?>/vehiclemanager/personal-details" class="logout">
        <i class="bx bxs-user-detail"></i>
        <span class="text">Personal Details</span>
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
