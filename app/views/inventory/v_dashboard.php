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
				<h3>Raw Tea Leaves Supply</h3>
				<i class='bx bx-shopping-bag'></i>
			</div>
			<div class="chart-container-wrapper" style="position:relative; width:100%; height:300px; padding:20px;">
				<canvas id="reportTypesChart"></canvas>
			</div>
		</div>



		<div id="reportModal" class="report-modal" style="display: none;">
			<div class="modal-content" id="reportModalContent">
				<span class="close" onclick="closeModal('reportModal')">&times;</span>
				<h2>Report</h2>
				<form action="<?php echo URLROOT; ?>/Inventory/" method="POST">
					<div class="modal-body">
						<textarea type="text" name="report" placeholder="Enter your report">

				</textarea>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>

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

				<i class='bx bx-filter'></i>
				<select name="" id="statusFilter" onchange="filterStocks()">
					<option value="All">All</option>
					<option value="Approved">Approved</option>
					<option value="Rejected">Rejected</option>
					<option value="Not_Validate">Not Checked</option>
				</select>
			</div>

			<table>
				<thead>
					<tr>
						<th>Driver Name</th>
						<th>Collection No</th>
						<th>Quantity</th>
						<th>Time</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody id="stockTable">
					<!-- Data will be populated via JavaScript -->
				</tbody>
			</table>
		</div>
		<div id="collectionBagDetailsModal" class="modal">
			<div class="modal-content">
				<span class="close" onclick="closeModal('collectionBagDetailsModal')">&times;</span>
				<h2>Colllection Details</h2>
				<div id="collectionBagDetailsContent">
					<!-- Bag details will be populated here -->
				</div>
			</div>
		</div>
	</div>

	<div class="top-selling-section">
		<div class="section-header">
			<h2>Top Selling Products</h2>
			<a href="<?php echo URLROOT; ?>/inventory/product" class="view-all">View all &gt;</a>
		</div>

		<div class="product-grid">
			<div class="product-card">
				<img src="<?php echo URLROOT; ?>/public/img//green tea1.webp" alt="Green Tea" class="product-image"
					style="height:300px; width: 300px;">
				<h3>Green Tea</h3>
				<p class="sold">120 items sold</p>
			</div>

			<div class="product-card">
				<img src="<?php echo URLROOT; ?>/public/img/black tea.jpg" alt="Black Tea" class="product-image"
					style="height:300px; width: 300px;">
				<h3>Black Tea</h3>
				<p class="sold">100 items sold</p>
			</div>

			<div class="product-card">
				<img src="<?php echo URLROOT; ?>/public/img/white tea.webp" alt="White Tea" class="product-image"
					style="height:300px; width: 300px;">
				<h3>White Tea</h3>
				<p class="sold">90 items sold</p>
			</div>

			<div class="product-card">
				<img src="<?php echo URLROOT; ?>/public/img/oolong tea.webp" alt="oolong Tea" class="product-image"
					style="height:300px; width: 300px;">
				<h3>Oolong Tea</h3>
				<p class="sold">80 items sold</p>
			</div>
		</div>

		<!-- Pagination -->
		<div class="pagination">
			<button class="prev">&lt;</button>
			<button class="page-number active">1</button>
			<button class="page-number">2</button>
			<button class="page-number">3</button>
			<button class="next">&gt;</button>
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

		console.log("reportCtx");
		if (reportCtx) {

			// Hardcoded chaotic data for Normal Leaf and Super Leaf for the past week
			const labels = [
				"Monday",
				"Tuesday",
				"Wednesday",
				"Thursday",
				"Friday",
				"Saturday",
				"Sunday",
			];
			const normalLeafData = [12, 18, 25, 30, 22, 35, 40]; // Chaotic values for Normal Leaf
			const superLeafData = [8, 15, 20, 25, 18, 30, 28]; // Chaotic values for Super Leaf

			// Create the line chart with the chaotic data
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
							labels: {
								padding: 20,
								font: {
									size: 12,
								},
							},
						},
						title: {
							display: false,
							text: "Weekly Collection of Raw Tea Leaves",
						},
					},
					scales: {
						y: {
							beginAtZero: true,
							grid: {
								color: "rgba(0, 0, 0, 0.1)",
							},
							ticks: {
								callback: function (value) {
									return value + " kg";
								},
							},
							title: {
								display: true,
								text: "Stock (kg)",
							},
						},
						x: {
							grid: {
								display: false,
							},
							title: {
								display: true,
								text: "Days of the Week",
							},
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

<?php require APPROOT . '/views/inc/components/footer.php' ?>