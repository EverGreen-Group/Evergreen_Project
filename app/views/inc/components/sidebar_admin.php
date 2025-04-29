<section id="sidebar">
<a href="<?php echo URLROOT; ?>" class="brand">
    <img src="<?php echo URLROOT; ?>/img/logo.svg" alt="Logo" />
    <span class="text">EVERGREEN</span>
  </a>
  <ul class="side-menu top">

			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/admin/index">
					<i class='bx bxs-envelope'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
      		<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'userLogs') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/admin/userLogs">
        				<i class='bx bxs-calendar-check' ></i>
					<span class="text">User logs</span>
				</a>
			</li>
      		<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'payments') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/admin/payments">
					<i class='bx bxs-wallet' ></i>
					<span class="text">Payment Reports</span>
				</a>
			</li>
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'admin') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/admin/config">
					<i class='bx bxs-cog'></i>
					<span class="text">Factory Configs</span>
				</a>
			</li>	
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'manager') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/manager/">
					<i class='bx bxs-network-chart'></i>
					<span class="text">Supplier Relations</span>
				</a>
			</li>		
			<li>
				<a href="<?php echo URLROOT; ?>/inventory/">
					<i class='bx bxs-alarm-exclamation' ></i>
					<span class="text">Inventory Management</span>
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
