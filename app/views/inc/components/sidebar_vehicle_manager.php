<section id="sidebar">
  <a href="<?php echo URLROOT; ?>" class="brand">
    <img src="<?php echo URLROOT; ?>/img/logo.svg" alt="Logo" />
    <span class="text">EVERGREEN</span>
  </a>
  <ul class="side-menu top">

        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/manager/">
                <i class='bx bxs-envelope'></i>
                <span class="text">Applications</span>
            </a>
        </li>

      <li>
            <a href="<?php echo URLROOT; ?>/manager/announcements">
          <i class='bx bxs-megaphone'></i>
                <span class="text">Announcement</span>
            </a>
        </li>

      <li>
            <a href="<?php echo URLROOT; ?>/manager/chat">
                <i class='bx bxs-message-dots' ></i>
                <span class="text">Chat</span>
                <?php if(isset($_SESSION['manager_unread_messages']) && $_SESSION['manager_unread_messages'] > 0): ?>
                    <span class="notification-badge"><?php echo $_SESSION['manager_unread_messages']; ?></span>
                <?php endif; ?>
            </a>
        </li>

      <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/manager/appointments">
        <i class='bx bxs-calendar-check' ></i>
                <span class="text">Appointments</span>
            </a>
        </li>
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'suppliers') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/manager/supplier">
                <i class='bx bxs-network-chart'></i>
                <span class="text">Suppliers</span>
            </a>
        </li>       
        <li>
            <a href="<?php echo URLROOT; ?>/manager/complaints">
                <i class='bx bxs-alarm-exclamation' ></i>
                <span class="text">Complaints</span>
            </a>
        </li>

    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/manager/collection">
        <i class="bx bxs-dashboard"></i>
        <span class="text">Collection</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'schedule') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/manager/schedule">
        <i class="bx bxs-calendar"></i>
        <span class="text">Schedule</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'route') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/route">
        <i class="bx bx-trip"></i>
        <span class="text">Route</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'vehicle') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/manager/vehicle">
        <i class="bx bxs-car"></i>
        <span class="text">Vehicle</span>
      </a>
    </li>
    <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'team') ? 'active' : ''; ?>">
      <a href="<?php echo URLROOT; ?>/manager/driver">
        <i class="bx bxs-group"></i>
        <span class="text">Driver</span>
      </a>
    </li>

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



<style>

.side-menu li a {
  position: relative;
}

.notification-badge {
  position: absolute;
  top: 8px;
  right: 15px;
  background-color: #ff4d4d;
  color: white;
  border-radius: 50%;
  width: 22px;
  height: 22px;
  font-size: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  margin-top: 4px;
}


.notification-indicator {
  position: absolute;
  top: 8px;
  right: 15px;
  background-color: #ff4d4d;
  border-radius: 50%;
  width: 10px;
  height: 10px;
}

</style>