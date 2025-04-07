<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">
<script>
	const URLROOT = '<?php echo URLROOT; ?>';
	const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- MAIN -->
<main>
<div class="head-title">
      <div class="left">
          <h1>Inventory Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
              <li><i class='bx bx-chevron-right'></i></li>
              <li><a class="active" href="#">Inventory</a></li>
          </ul>
      </div>
  </div>

	<div class="table-data">
		<div class="order">
			<div class="head">
				<h3>Raw Tea Leaves Supply</h3>
				<a href="<?php echo URLROOT; ?>/inventory/rawLeafHistory" class="btn btn-primary">
					<i class='bx bx-show'></i>
					View Raw Leaf History
				</a>
			</div>
			<div class="chart-container-wrapper" style="position:relative; width:100%; height:300px; padding:20px;">
				<canvas id="reportTypesChart"></canvas>
			</div>
		</div>

		<!-- Bag Usage Chart (replacing Machine Allocation Chart) -->
		<div class="todo">
			<div class="head">
				<h3>Current Bag Usage</h3>
				<a href="<?php echo URLROOT; ?>/inventory/collectionBags" class="btn btn-primary">
					<i class='bx bx-cog'></i>
					Manage Collection Bags
				</a>
			</div>
			<?php
			// Use fallback values to prevent errors
			$bagUsageCounts = isset($data['bagUsageCounts']) && is_array($data['bagUsageCounts']) 
				? $data['bagUsageCounts'] 
				: ['active' => 0, 'inactive' => 0];

			$bagChartData = [
				'labels' => array_keys($bagUsageCounts),
				'counts' => array_values($bagUsageCounts),
			];

			$jsonBagChartData = json_encode($bagChartData);
			?>
			<!-- Bag Usage Chart -->
			<canvas id="bagUsageChart"></canvas>
		</div>
	</div>

	<!-- Stock Validation -->
	<div class="table-data">
		<div class="order">
			<div class="head">
				<h3>Validate Stock</h3>
				<a href="<?php echo URLROOT; ?>/manager/createVehicle" class="btn btn-primary">
					<i class='bx bx-show'></i>
					View Stock Validation History
				</a>
			</div>

			<table>
				<thead>
					<tr>
						<th>Collection No</th>
						<th>Quantity</th>
						<th>Time</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php if (!empty($stockvalidate)): ?>
					<?php foreach ($stockvalidate as $req): ?>
					<tr>
						<td><?php echo htmlspecialchars($req->collection_id); ?></td>
						<td><?php echo htmlspecialchars($req->total_quantity); ?></td>
						<td><?php echo htmlspecialchars($req->end_time); ?></td>
						<td>
                            <span class="status-badge awaiting"><?php echo htmlspecialchars($req->status); ?></span>
                        </td>
						<td>
							<div style="display: flex; gap: 5px;">
								<a 
									href="<?php echo URLROOT; ?>/inventory/viewAwaitingInventory/<?php echo $req->collection_id; ?>" 
									class="btn btn-tertiary" 
									style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
									title="View Awaiting Inventory Details"
								>
									<i class='bx bx-add-to-queue' style="font-size: 24px; color:var(--main);"></i>
								</a>
							</div>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr><td colspan="5" style="text-align:center;">No incoming requests</td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</main>
<!-- MAIN -->
</section>
<!-- CONTENT -->

<script> 
	const bagChartData = <?php echo $jsonBagChartData; ?>;
	console.log("Labels: ", bagChartData.labels);
	console.log("Counts: ", bagChartData.counts);
</script>
<script>
	// Report Type Chart (unchanged)
	document.addEventListener("DOMContentLoaded", function () {
		const reportCtx = document.getElementById("reportTypesChart");
		if (reportCtx) {
			const normalLeafData = <?php echo json_encode($data['normalLeafData']); ?>;
			const superLeafData = <?php echo json_encode($data['superLeafData']); ?>;
			const labels = <?php echo json_encode(array_map(function($date) {
				return date('D', strtotime($date));
			}, $data['chartDates'])); ?>;

			new Chart(reportCtx, {
				type: "line",
				data: {
					labels: labels,
					datasets: [
						{
							label: "Normal Leaf",
							data: normalLeafData,
							backgroundColor: "rgba(54, 162, 235, 0.2)",
							borderColor: "#36A2EB",
							borderWidth: 2,
							fill: false,
							tension: 0.4,
						},
						{
							label: "Super Leaf",
							data: superLeafData,
							backgroundColor: "rgba(255, 159, 64, 0.2)",
							borderColor: "#FF9F40",
							borderWidth: 2,
							fill: false,
							tension: 0.4,
						},
					],
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							position: "top",
							labels: { padding: 20, font: { size: 12 } },
						},
						title: { display: false }
					},
					scales: {
						y: {
							beginAtZero: true,
							grid: { color: "rgba(0, 0, 0, 0.1)" },
							ticks: { callback: value => value + " kg" },
							title: { display: true, text: "Stock (kg)" },
						},
						x: {
							grid: { display: false },
							title: { display: true, text: "Days of the Week" },
						},
					},
				},
			});
		}
	});

	// Bag Usage Chart
	document.addEventListener("DOMContentLoaded", function () {
		const bagCtx = document.getElementById("bagUsageChart").getContext("2d");
		new Chart(bagCtx, {
			type: "bar",
			data: {
				labels: bagChartData.labels,
				datasets: [{
					label: "Bag Usage",
					data: bagChartData.counts,
					backgroundColor: [
						"rgba(75, 192, 192, 0.8)", // Active
						"rgba(255, 99, 132, 0.8)"   // Inactive
					],
					borderColor: [
						"rgba(75, 192, 192, 1)",
						"rgba(255, 99, 132, 1)"
					],
					borderWidth: 1,
					barThickness: 30,
				}],
			},
			options: {
				responsive: true,
				plugins: {
					legend: { position: "top" },
					title: { display: true, text: "Bag Usage Status" }
				},
				scales: {
					x: {
						ticks: {
							autoSkip: false
						}
					}
				}
			},
		});
	});
</script>

<style>
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .awaiting {
        background-color: rgba(18, 184, 98, 0.67);
        color: rgb(255, 255, 255);
    }
    #bagUsageChart {
        width: 100%;
        height: 300px; /* Adjust as needed */
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
