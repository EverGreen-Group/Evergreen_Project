<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css" />
    <title>Tea Leaves Supplier</title>
</head>
<body>
<section id="sidebar">
		<a href="" class="brand">
			<img src="<?php echo URLROOT; ?>/img/logo.svg" alt="Logo" />
			<span class="text">EVERGREEN</span>
		</a>
        <ul class="side-menu top">
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/supplier/index">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'announcements') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/supplier/announcements">
					<i class='bx bxs-megaphone'></i>
					<span class="text">View Announcements</span>
				</a>
			</li>

			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'chat') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/supplier/chat">
					<i class='bx bxs-message-dots'></i>
					<span class="text">Chat</span>
					<?php if(isset($_SESSION['unread_messages']) && $_SESSION['unread_messages'] > 0): ?>
						<span class="notification-badge"><?php echo $_SESSION['unread_messages']; ?></span>
					<?php endif; ?>
				</a>
			</li>

			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'collections') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/supplier/collections">
					<i class='bx bx-history'></i>
					<span class="text">History</span>
				</a>
			</li>
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'requestFertilizer') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/supplier/requestfertilizer">
					<i class='bx bxs-shopping-bag-alt'></i>
					<span class="text">Fertilizer Requests</span>
				</a>
			</li>
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'viewAppointments') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/supplier/viewAppointments">
					<i class='bx bxs-shopping-bag-alt'></i>
					<span class="text">Request Appointment</span>
				</a>
			</li>			
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'payments') ? 'active' : ''; ?>">
					<a href="<?php echo URLROOT; ?>/supplier/payments">
					<i class='bx bxs-dollar-circle'></i>
					<span class="text">Payments</span>
				</a>
			</li>



			
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'complaints') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/supplier/complaints">
					<i class='bx bxs-group'></i>
					<span class="text">Submit Complaint</span>
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