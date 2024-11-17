<section id="sidebar">
  <a href="<?php echo URLROOT; ?>" class="brand">
    <img src="../img/logo.svg" alt="Logo" />
    <span class="text">EVERGREEN</span>
  </a>
  <ul class="side-menu top">
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/suppliermanager/applications">
        <i class="bx bxs-dashboard"></i>
        <span class="text">Review Applications</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'suppliers') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/suppliermanager/suppliers">
        <i class="bx bxs-dashboard"></i>
        <span class="text">Manage Suppliers</span>
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
