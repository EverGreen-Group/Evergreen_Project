<?php require APPROOT . '/views/inc/components/header.php' ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>

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
				<h3>1020</h3>
				<p>Today Stock</p>
			</span>
		</li>
		<li>
			<i class='bx bxs-group'></i>
			<span class="text">
				<h3>2834</h3>
				<p>Sales Order</p>
			</span>
		</li>
		<li>
			<i class='bx bxs-dollar-circle'></i>
			<span class="text">
				<h3>$2543</h3>
				<p>Export Stock</p>
			</span>
		</li>
		<li>
			<i class='bx bxs-dollar-circle'></i>
			<span class="text">
				<h3>$2543</h3>
				<p>To be Orders</p>
			</span>
		</li>
	</ul>


	<div class="table-data">
		<div class="order">
			<div class="head">
				<h3>Validate Stock</h3>
				<i class='bx bx-search'></i>
				<i class='bx bx-filter'></i>
			</div>

			<!-- Added table for validate stock -->
			<table>
				<thead>
					<tr>
						<th>Driver Name</th>
						<th>Quantity</th>
						<th>Time</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>John Doe</td>
						<td>500 units</td>
						<td>2024-03-20 09:30 AM</td>
						<td class="actions">
							<button style="padding: 8px 12px;
	background-color: #28a745;
	color: white;
	border: none;
	border-radius: 4px;
	cursor: pointer;">Approve</button>
							<button style="padding: 8px 12px;
	background-color: #2345;
	color: white;
	border: none;
	border-radius: 4px;
	cursor: pointer;">Reject</button>
						</td>
					</tr>
					<tr>
						<td>Jane Smith</td>
						<td>750 units</td>
						<td>2024-03-20 10:15 AM</td>
						<td class="actions">
							<button style="padding: 8px 12px;
	background-color: #28a745;
	color: white;
	border: none;
	border-radius: 4px;
	cursor: pointer;">Approve</button>
							<button style="padding: 8px 12px;
	background-color: #2345;
	color: white;
	border: none;
	border-radius: 4px;
	cursor: pointer;">Reject</button>
						</td>
					</tr>
					<!-- Add more rows as needed -->
				</tbody>
			</table>
		</div>
		<div class="todo">
			<div class="head">
				<h3>Machine Allocation Statics</h3>
				<i class='bx bx-plus'></i>
				<i class='bx bx-filter'></i>
			</div>
			<canvas id="machineAllocationChart"></canvas>
		</div>
	</div>
	<div class="top-selling-section">
		<div class="section-header">
			<h2>Top Selling Products</h2>
			<a href="<?php echo URLROOT; ?>/inventory/product" class="view-all">View all &gt;</a>
		</div>

		<div class="product-grid">
			<div class="product-card">
				<h3>Green Tea</h3>
				<p class="sold">120 items sold</p>
			</div>

			<div class="product-card">
				<h3>Black Tea</h3>
				<p class="sold">100 items sold</p>
			</div>

			<div class="product-card">
				<h3>White Tea</h3>
				<p class="sold">90 items sold</p>
			</div>

			<div class="product-card">
				<h3>Pink Tea</h3>
				<p class="sold">80 items sold</p>
			</div>
		</div>

		<!-- Pagination -->
		<div class="pagination">
			<button class="prev">&lt;</button>
			<button class="page-number active">1</button>
			<button class="page-number">2</button>
			<button class="page-number">3</button>
			<button class="page-number">4</button>
			<span>...</span>
			<button class="page-number">10</button>
			<button class="page-number">11</button>
			<button class="next">&gt;</button>
		</div>
	</div>
</main>
<!-- MAIN -->
</section>
<!-- CONTENT -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		var ctx = document.getElementById('machineAllocationChart').getContext('2d');
		var machineAllocationChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ['Machine A', 'Machine B', 'Machine C', 'Machine D'],
				datasets: [{
					data: [20, 25, 20, 25],
					backgroundColor: [
						'rgba(255, 99, 132, 0.8)',
						'rgba(54, 162, 235, 0.8)',
						'rgba(255, 206, 86, 0.8)',
						'rgba(75, 192, 192, 0.8)'
					],
					borderColor: [
						'rgba(255, 99, 132, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)'
					],
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: 'Daily Machine Allocation Statics'
					}
				}
			}
		});
	});
</script>

<?php require APPROOT . '/views/inc/components/footer.php' ?>