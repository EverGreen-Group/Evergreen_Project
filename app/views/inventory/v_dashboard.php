<?php require APPROOT . '/views/inc/components/header.php' ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory_manager/dashboard.css">
<script src="<?php echo URLROOT; ?>/public/js/inventory_manager/dashboard.js"></script>
<script>
	const URLROOT = '<?php echo URLROOT; ?>';
	const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script> const chartData = <?php echo $jsonData; ?>;</script>
<script></script>
<!-- MAIN -->
<main>
	<div class="head-title">
		<div class="left">
			<h1>Dashboard</h1>

		</div>

	</div>

	<ul class="box-info">
		<li>
			<i class='bx bxs-calendar-check'></i>
			<span class="text">
				<h3>
					<?= isset($data['totalstock']->total_sum)
						? $data['totalstock']->total_sum
						: "0"; ?>
				</h3>

				<p>Today Stock</p>
			</span>
		</li>
		<li>
			<i class='bx bxs-group'></i>
			<span class="text">
				<h3>2834</h3>
				<p>Export Stock</p>

			</span>
		</li>
		<li>
			<i class='bx bxs-dollar-circle'></i>
			<span class="text">
				<h3>2543</h3>
				<p>Sales Orders</p>
			</span>
		</li>
		<li>
			<i class='bx bxs-dollar-circle'></i>
			<span class="text">
				<h3>2543</h3>
				<p>Fertilizer Orders</p>
			</span>
		</li>
	</ul>


	<div class="table-data">
		<div class="order">
			<div class="head">
				<h3>Collection Approval</h3>

				<i class='bx bx-filter'></i>
				<select name="" id="statusFilter" onchange="filterStocks()">
					<option value="All">All</option>
					<option value="Approved">Approved</option>
				</select>
			</div>

			<table>
				<thead>
					<tr>
						<th>Driver ID</th>
						<th>Collection No</th>
						<th>Quantity</th>
						<th>Time</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody id="collectionApprovalTable">
					<!-- Data will be populated via JavaScript -->
				</tbody>
			</table>
		</div>




	</div>

</main>
<!-- MAIN -->
</section>
<!-- CONTENT -->



<?php require APPROOT . '/views/inc/components/footer.php' ?>