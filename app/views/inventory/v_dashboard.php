<?php require APPROOT . '/views/inc/components/header.php' ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>

<head>
	<style>
		/*inventory dashboard topselling ection*/

		.top-selling-section {
			margin: 40px auto;
			padding: 20px;
			background-color: #fff;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		}

		/* Section header */
		.section-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
		}

		.section-header h2 {
			font-size: 24px;
			color: #333;
		}

		.section-header .view-all {
			font-size: 16px;
			color: #27ae60;
			text-decoration: none;
		}

		.section-header .view-all:hover {
			text-decoration: underline;
		}

		/* Product grid */
		.product-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 20px;
		}

		.product-card {
			background-color: #fff;
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			padding: 20px;
			text-align: center;
			transition: box-shadow 0.3s ease;
		}

		.product-card:hover {
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
		}

		.product-card h3 {
			font-size: 18px;
			color: #333;
			margin-bottom: 10px;
		}

		.product-card .sold {
			font-size: 16px;
			color: #27ae60;
			font-weight: bold;
		}

		/* Pagination */
		.pagination {
			display: flex;
			justify-content: center;
			align-items: center;
			margin-top: 20px;
		}

		.pagination button {
			background-color: #fff;
			border: 1px solid #e0e0e0;
			padding: 8px 12px;
			font-size: 16px;
			color: #333;
			margin: 0 5px;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}

		.pagination button:hover {
			background-color: #27ae60;
			color: #fff;
		}

		.pagination button.active {
			background-color: #27ae60;
			color: #fff;
		}

		.pagination span {
			font-size: 16px;
			margin: 0 5px;
		}

		.order table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		.order th,
		.order td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: left;
		}

		.order th {
			background-color: #f2f2f2;
			font-weight: bold;
		}

		.order tr:nth-child(even) {
			background-color: #f9f9f9;
		}

		.order tr:hover {
			background-color: #f5f5f5;
		}

		.ap-button {
			padding: 8px 12px;
			background-color: #28a745;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		.rp-button {
			padding: 8px 12px;
			background-color: #2345;
			color: white;
			border: none;
			margin-left: 10px;
			border-radius: 4px;
			cursor: pointer;
		}


		/* Report Modal */
		.report-modal {
			display: none;
			position: fixed;
			z-index: 1000;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.6);
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.modal-content {
			background-color: #fff;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			width: 90%;
			max-width: 400px;
			position: relative;
			animation: modalPop 0.3s ease-out;
		}

		@keyframes modalPop {
			0% {
				transform: scale(0.7);
				opacity: 0;
			}

			100% {
				transform: scale(1);
				opacity: 1;
			}
		}

		.modal-content h2 {
			margin: 0;
			padding: 15px 20px;
			border-bottom: 1px solid #eee;
		}

		.modal-body {
			padding: 20px;
		}

		.modal-body textarea {
			width: 100%;
			min-height: 100px;
			padding: 10px;
			border: 1px solid #ddd;
			border-radius: 4px;
			resize: vertical;
		}

		.modal-footer {
			padding: 15px 20px;
			border-top: 1px solid #eee;
			text-align: right;
		}

		.modal-footer button {
			padding: 8px 16px;
			background-color: #007bff;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		.modal-footer button:hover {
			background-color: #0056b3;
		}

		.close {
			position: absolute;
			right: 10px;
			top: 10px;
			font-size: 24px;
			cursor: pointer;
			color: #666;
		}

		.close:hover {
			color: #333;
		}

		#content main .table-data {
			display: flex;
		}
	</style>
</head>

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
				<h3>Validate Stock</h3>
				<i class='bx bx-search'></i>
				<i class='bx bx-filter'></i>
			</div>

			<!-- Added table for validate stock -->
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
				<tbody>
				<?php foreach($data as $stock) : ?>
            <tr>
                <!-- Replace 'Michael Chen' with a dynamic driver name if available -->
                <td>Driver ID: <?= $stock->driver_id; ?></td>
                <!-- Collection ID -->
                <td style="text-align: center;"><?= $stock->collection_id; ?></td>
                <!-- Quantity -->
                <td><?= $stock->total_quantity; ?> units</td>
                <!-- Time -->
                <td><?= $stock->created_at; ?></td>
				<td><?= $stock->status; ?></td>
                <!-- Actions -->
                <td class="actions">
                    <button class="ap-button" onclick="approveAction(<?= $stock->collection_id; ?>)">Approve</button>
                    <button class="rp-button" onclick="reportModel(<?= $stock->collection_id; ?>)" style="">Reject</button>
                </td>
            </tr>
        <?php endforeach; ?>
							<!-- Add more rows as needed -->
				</tbody>
			</table>
		</div>

		<div id="reportModal" class="report-modal" style="display: none;">
			<div class="modal-content" id="reportModalContent">
				<span class="close" onclick="closeModal()">&times;</span>
				<h2>Report</h2>
				<div class="modal-body">
					<textarea type="text" placeholder="Enter your report"></textarea>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary">Submit</button>
				</div>
			</div>


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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		var ctx = document.getElementById('machineAllocationChart').getContext('2d');
		var machineAllocationChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ['Machine A', 'Machine B', 'Machine C', 'Machine D'],
				datasets: [{
					label: 'Working Hours',
					data: [8, 6, 7, 5],
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
				scales: {
					y: {
						beginAtZero: true,
						title: {
							display: true,
							text: 'Working Hours'
						},
						max: 12,
						ticks: {
							stepSize: 2
						}
					},
					x: {
						title: {
							display: true,
							text: 'Machines'
						}
					}
				},
				plugins: {
					legend: {
						display: false
					},
					title: {
						display: true,
						text: 'Daily Machine Working Hours'
					}
				}
			}
		});
	});


	function reportModel() {
		const modal = document.getElementById('reportModal');
		const model2 = document.getElementById('reportModalContent');
		modal.style.display = 'flex';
		modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
		modal.style.backdropFilter = 'blur(1px)';
		//model2.style.opacity = '1';

		// Add animation class
		const modalContent = modal.querySelector('.modal-content');
		modalContent.style.animation = 'modalPop 0.3s ease-out';
	}

	function closeModal() {
		const modal = document.getElementById('reportModal');
		modal.style.display = 'none';
	}

	// Improved click outside listener
	window.addEventListener('click', function (event) {
		const modal = document.getElementById('reportModal');
		if (event.target === modal) {
			closeModal();
		}
	});
</script>

<?php require APPROOT . '/views/inc/components/footer.php' ?>