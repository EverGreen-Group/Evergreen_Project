<?php require APPROOT . '/views/inc/components/header.php' ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory_manager/dashboard/dashboard.css">
<script src="<?php echo URLROOT; ?>/public/js/inventory_manager/dashboard/dashboard.js"></script>
<script>
	const URLROOT = '<?php echo URLROOT; ?>';
	const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
				<p>Fertilizer Orders</p>
			</span>
		</li>
	</ul>


	<div class="table-data">
		<div class="order">
			<div class="head">
				<h3>Raw Tea Leaves Supply</h3>
				<a href="<?php echo URLROOT; ?>/inventory/rawLeafHistory" class="btn btn-primary">
					<i class='bx bx-show'></i>
					View Raw Leaf History
				</a>

				<a href="<?php echo URLROOT; ?>/inventory/payments" class="btn btn-primary">
					<i class='bx bx-cog'></i>
					Manage Rates
				</a>

			</div>
			<div class="chart-container-wrapper" style="position:relative; width:100%; height:300px; padding:20px;">
				<canvas id="reportTypesChart"></canvas>
			</div>
		</div>


		<!-- machine chart -->


		<div class="todo">
			<div class="head">
				<h3>Machine Allocation Statics</h3>
				<i class='bx bx-plus'></i>
				<i class='bx bx-filter'></i>
			</div>
			<?php
			$machines = $data['machines'];
			// Example PHP array
			// $machines = [
			// 	(object) ["id" => 2, "machine_name" => "Machine A", "total_working_hours" => "200 items"],
			// 	(object) ["id" => 3, "machine_name" => "Machine B", "total_working_hours" => "300 items"],
			// 	(object) ["id" => 4, "machine_name" => "Machine C", "total_working_hours" => "150 hours"],
			// 	(object) ["id" => 5, "machine_name" => "Machine D", "total_working_hours" => "500 hours"],
			// ];
			
			// Extract machine names and working hours
			$chartdata = [
				'labels' => array_map(fn($machine) => $machine->machine_name, $machines),
				'hours' => array_map(fn($machine) => (int) preg_replace('/\D/', '', $machine->total_working_hours), $machines),
			];

			// Encode data to JSON
			$jsonData = json_encode($chartdata);
			?>

			<!-- Machine Allocation Chart -->
			<canvas id="machineAllocationChart"></canvas>
		</div>
	</div>

	<!-- Stock Validation -->
	<div class="table-data">
		<div class="order">
			<div class="head">
				<h3>Validate Stock</h3>
				<!-- <a href="<?php echo URLROOT; ?>/manager/createVehicle" class="btn btn-primary">
					<i class='bx bx-show'></i>
					View Stock Validation History
				</a> -->
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
										<a href="<?php echo URLROOT; ?>/inventory/viewAwaitingInventory/<?php echo $req->collection_id; ?>"
											class="btn btn-tertiary"
											style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
											title="View Awaiting Inventory Details">
											<i class='bx bx-add-to-queue' style="font-size: 24px; color:var(--main);"></i>
										</a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="5" style="text-align:center;">No incoming requests</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>




</main>
<!-- MAIN -->
</section>
<!-- CONTENT -->

<script> const chartData = <?php echo $jsonData; ?>;</script>
<script>
	// Chart.js
	document.addEventListener("DOMContentLoaded", function () {

		const ctx = document
			.getElementById("machineAllocationChart")
			.getContext("2d");

		console.log("chartData");
		// Use chartData from PHP
		const machineAllocationChart = new Chart(ctx, {
			type: "bar",
			data: {
				labels: chartData.labels,
				datasets: [
					{
						label: "Working Hours",
						data: chartData.hours,
						backgroundColor: [
							"rgba(255, 99, 132, 0.8)",
							"rgba(54, 162, 235, 0.8)",
							"rgba(255, 206, 86, 0.8)",
							"rgba(75, 192, 192, 0.8)",
						],
						borderColor: [
							"rgba(255, 99, 132, 1)",
							"rgba(54, 162, 235, 1)",
							"rgba(255, 206, 86, 1)",
							"rgba(75, 192, 192, 1)",
						],
						borderWidth: 1,
					},
				],
			},
			options: {
				responsive: true,
				scales: {
					y: {
						beginAtZero: true,
						title: {
							display: true,
							text: "Working Hours",
						},
						ticks: {
							stepSize: 50,
						},
					},
					x: {
						title: {
							display: true,
							text: "Machines",
						},
					},
				},
				plugins: {
					legend: {
						display: false,
					},
					title: {
						display: true,
						text: "Machine Working Hours",
					},
				},
			},
		});
	});

	//report Type chart
	document.addEventListener("DOMContentLoaded", function () {
		const reportCtx = document.getElementById("reportTypesChart");
		if (reportCtx) {
			const normalLeafData = <?php echo json_encode($data['normalLeafData']); ?>;
			const superLeafData = <?php echo json_encode($data['superLeafData']); ?>;
			const labels = <?php echo json_encode(array_map(function ($date) {
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

	// Show collection bag details
	document.addEventListener('DOMContentLoaded', function () {
		loadStockData();
		// Refresh every 30 seconds
		setInterval(loadStockData, 30000);
	});

	function loadStockData() {
		fetch(`${URLROOT}/inventory/getStockValidations`)
			.then(response => response.json())
			.then(data => {
				const tableBody = document.getElementById('stockTable');
				let html = '';


				// Then add actual data if any exists
				data.forEach(stock => {
					html += `
					<tr>
						<td>${stock.full_name}</td>
						<td style="text-align: center;">${stock.collection_id}</td>
						<td>${stock.total_quantity} units</td>
						<td>${stock.created_at}</td>
						<td class="status completed">${stock.status}</td>
						<td class="actions">
							<button class="btn btn-primary" onclick="showCollectionBagDetails(${stock.collection_id})"><i class="bx bx-show"></i></button>
						</td>
					</tr>
				`;
				});

				tableBody.innerHTML = html;
			})
			.catch(error => {
				console.error('Error loading stock data:', error);
				// If error occurs, at least show the dummy data
				const tableBody = document.getElementById('stockTable');
				tableBody.innerHTML = `
				<tr>
					<td>John Doe</td>
					<td style="text-align: center;">COL001</td>
					<td>150 units</td>
					<td>2024-03-20 09:30:00</td>
					<td class="status-cell">Not_Validate</td>
					<td class="actions">
						<button class="btn btn-primary" onclick="showCollectionBagDetails('1')"><i class="bx bx-show"></i></button>
						
					</td>
				</tr>
			`;
			});
	}

	function filterStocks() {
		const status = document.getElementById('statusFilter').value;
		fetch(`${URLROOT}/inventory/getStockValidations?status=${status}`)
			.then(response => response.json())
			.then(data => {
				const tableBody = document.getElementById('stockTable');
				let html = '';


				// Add actual filtered data
				data.forEach(stock => {
					html += `
					<tr>
						<td>${stock.full_name}</td>
						<td style="text-align: center;">${stock.collection_id}</td>
						<td>${stock.total_quantity} units</td>
						<td>${stock.created_at}</td>
						<td class="status-cell">${stock.status}</td>
						<td class="actions">
							<button class="btn btn-primary" onclick="showCollectionBagDetails(${stock.collection_id})"><i class="bx bx-show"></i></button>
						</td>
					</tr>
				`;
				});

				tableBody.innerHTML = html;
			})
			.catch(error => {
				console.error('Error filtering stock data:', error);
				// Show dummy data on error
				const tableBody = document.getElementById('stockTable');
				if (status === 'All' || status === 'Not_Validate') {
					tableBody.innerHTML = `
					<tr>
						<td>John Doe</td>
						<td style="text-align: center;">COL001</td>
						<td>150 units</td>
						<td>2024-03-20 09:30:00</td>
						<td class="status-cell">Not_Validate</td>
						<td class="actions">
							<button class="ap-button" onclick="showCollectionBagDetails('1')">Approve</button>
							<button class="rp-button" onclick="reportModel('1')">Reject</button>
						</td>
					</tr>
				`;
				} else {
					tableBody.innerHTML = ''; // Clear table if filtering for other statuses
				}
			});
	}
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
		height: 300px;
		/* Adjust as needed */
	}
</style>

<?php require APPROOT . '/views/inc/components/footer.php' ?>