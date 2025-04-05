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
        <a href="<?php echo URLROOT; ?>" class="brand">
            <img src="<?php echo URLROOT; ?>/public/img/logo.svg" alt="Logo">
            <span class="text">EVERGREEN</span>
        </a>
        <ul class="side-menu top">
			<li>
				<a href="<?php echo URLROOT; ?>/supplier/index">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="<?php echo URLROOT; ?>/supplier/requestfertilizer">
					<i class='bx bxs-shopping-bag-alt'></i>
					<span class="text">Fertilizer Requests</span>
				</a>
			</li>
			<li>
				<a href="<?php echo URLROOT; ?>/supplier/viewAppointments">
					<i class='bx bxs-shopping-bag-alt'></i>
					<span class="text">Request Appointment</span>
				</a>
			</li>			
			<li>
					<a href="<?php echo URLROOT; ?>/supplier/payments">
					<i class='bx bxs-dollar-circle'></i>
					<span class="text">Payments</span>
				</a>
			</li>
			
			<li>
				<a href="<?php echo URLROOT; ?>/supplier/complaints">
					<i class='bx bxs-group'></i>
					<span class="text">Submit Complaint</span>
				</a>
			</li>
		</ul>
        <ul class="side-menu">
		<li>
				<a href="<?php echo URLROOT; ?>/supplier/profile">
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

	