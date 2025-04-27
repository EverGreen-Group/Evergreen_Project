<section id="sidebar">
  <a href="<?php echo URLROOT; ?>" class="brand">
    <img src="<?php echo URLROOT; ?>/img/logo.svg" alt="Logo" />
    <span class="text">EVERGREEN</span>
  </a>
  <ul class="side-menu top">
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/inventory/index">
        <i class="bx bxs-dashboard"></i>
        <span class="text">Dashboard</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'product') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/inventory/product">
        <i class="bx bxs-car"></i>
        <span class="text">Product</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'fertilizer') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/inventory/fertilizer">
        <i class="bx bxs-group"></i>
        <span class="text">Fertilizer</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'collectionBags') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/inventory/collectionBags">
        <i class="bx bx-trip"></i>
        <span class="text">Bag</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'machine') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/inventory/machine">
        <i class="bx bxs-time-five"></i>
        <span class="text">Machine Allocation</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'release') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/export/release">
        <i class="bx bxs-group"></i>
        <span class="text">Export</span>
      </a>
    </li>

    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'payments') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/inventory/payments">
        <i class="bx bxs-group"></i>
        <span class="text">Payments</span>
      </a>
    </li>
    <!-- <li>
      <a href="../inventory/recodes">
        <i class="bx bxs-group"></i>
        <span class="text">Recodes</span>
      </a>
    </li> -->
    <!-- <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'settings') ? 'active' : ''; ?>">
      <a href="../inventory/settings">
        <i class="bx bxs-cog"></i>
        <span class="text">Payment</span>
      </a>
    </li> -->
  </ul>
  <ul class="side-menu">
    <li>
        <a href="<?php echo URLROOT; ?>/auth/logout" class="logout">
            <i class="bx bxs-log-out-circle"></i>
            <span class="text">Logout</span>
        </a>
    </li>
  </ul>
</section>