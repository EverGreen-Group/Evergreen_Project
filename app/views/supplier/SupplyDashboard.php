
<section id="content">

	<div class="content-wrapper">
        <?php include '../components/navbar.php'; ?>
        <?php include '../components/sidebar.php'; ?>

		<main>
			<div class="head-title">
				<div class="left">
					<h1>Tea Leaves Supplier</h1>
					<ul class="breadcrumb">
						<li>
							<a href="SupplyDashboard.php">Home</a>
						</li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="#">Dashboard</a>
						</li>
					</ul>
				</div>
				<a href="#" class="button">
					<i class='bx bxs-cloud-download'></i>
					<span class="text">Download Stats</span>
				</a>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check'></i>
					<span class="text">
						<h3>200</h3>
						<p>Tea Leaves Suppliers</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group'></i>
					<span class="text">
						<h3>45</h3>
						<p>Drivers</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle'></i>
					<span class="text">
						<h3>455kg</h3>
						<p>Total Leaves Quantity</p>
					</span>
				</li>
			</ul>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Pending Confirmation Requests</h3>
					</div>
					<div>No pending requests</div>
					<a href="ConfirmationHistory.php">
						<button class="button">View History</button>
					</a>
				</div>
				<div class="todo">
					<div class="head">
						<h3>Scheduled Collection Dates</h3>
					</div>
					<ul class="scheduled-dates">
						<li>Tomorrow <span class="time"> 05:00pm</span></li>
						<li>13/08/2024 <span class="time"> 03:00pm</span></li>
						<li>01/08/2024 <span class="time"> 06:00pm</span></li>
					</ul>
					<a href="CancelPickup.php">
						<button class="button">Cancel Pickup</button>
					</a>
				</div>
			</div>

		</main>
		</div>
	</section>

	<script src="../public/script.js"></script>
</body>
</html>
