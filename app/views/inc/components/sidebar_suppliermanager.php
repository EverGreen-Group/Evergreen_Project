<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tea Leaves Supplier Manager</title>
</head>
<body>
<section id="sidebar">
        <a href="<?php echo URLROOT; ?>" class="brand">
            <img src="<?php echo URLROOT; ?>/public/img/logo.svg" alt="Logo">
            <span class="text">EVERGREEN</span>
        </a>
        <ul class="side-menu top">
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/manager/applications">
					<i class='bx bxs-envelope'></i>
					<span class="text">Applications</span>
				</a>
			</li>
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/manager/appointments">
					<i class='bx bxs-calander'></i>
					<span class="text">Appointments</span>
				</a>
			</li>
			<li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'suppliers') ? 'active' : ''; ?>">
				<a href="<?php echo URLROOT; ?>/manager/suppliers">
					<i class='bx bxs-network-chart'></i>
					<span class="text">Suppliers</span>
				</a>
			</li>		

			<li>
				<a href="<?php echo URLROOT; ?>/Suppliermanager/chat">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Chat</span>
				</a>
			</li>
			<li>
				<a href="<?php echo URLROOT; ?>/Suppliermanager/complaints">
					<i class='bx bxs-alarm-exclamation' ></i>
					<span class="text">Complaints</span>
				</a>
			</li>

		</ul>
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/Suppliermanager/settings">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
			<a href="<?php echo URLROOT; ?>/auth/logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

	