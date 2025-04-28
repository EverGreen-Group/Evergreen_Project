<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Driver Dashboard</title>
</head>
<body>
<section id="sidebar">
    <a href="index.html" class="brand">
        <img src="<?php echo URLROOT; ?>/img/logo.svg" alt="Logo" />
        <span class="text">EVERGREEN</span>
    </a>
    <ul class="side-menu top">
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/vehicledriver/index">
                <i class="bx bxs-dashboard"></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
				<a href="<?php echo URLROOT; ?>/auth/profile">
					<i class='bx bxs-group'></i>
					<span class="text">Profile</span>
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
</body>
</html>
