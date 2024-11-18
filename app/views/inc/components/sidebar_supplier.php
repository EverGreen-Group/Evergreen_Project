<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/style.css" />
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
				<a href="<?php echo URLROOT; ?>/supplier/">
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
					<a href="<?php echo URLROOT; ?>/supplier/v_tea_orders">
					<i class='bx bxs-doughnut-chart'></i>
					<span class="text">Tea Orders</span>
				</a>
			</li>
			<li>
				<a href="<?php echo URLROOT; ?>/supplier/v_notifications">
					<i class='bx bxs-message-dots'></i>
					<span class="text">Notifications</span>
				</a>
			</li>
			<li>
					<a href="<?php echo URLROOT; ?>/supplier/v_payments">
					<i class='bx bxs-group'></i>
					<span class="text">Payments</span>
				</a>
			</li>
			<li>
				<a href="<?php echo URLROOT; ?>/supplier/v_profile">
					<i class='bx bxs-group'></i>
					<span class="text">Profile</span>
				</a>
			</li>
			<li>
				<a href="<?php echo URLROOT; ?>/supplier/v_complaints">
					<i class='bx bxs-group'></i>
					<span class="text">Submit Complaint</span>
				</a>
			</li>
		</ul>
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/supplier/v_settings">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="#" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

	