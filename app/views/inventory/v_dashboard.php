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
			z-index: 6000;
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

		/*get simaak models */
		.modal {
			display: none;
			/* Hidden by default */
			position: fixed;
			/* Stay in place */
			z-index: 6000;
			/* Sit on top */
			left: 0;
			top: 0;
			width: 100%;
			/* Full width */
			height: 100%;
			/* Full height */
			overflow: auto;
			/* Enable scroll if needed */
			/*background-color: rgb(0, 0, 0);*/
			/* Fallback color */
			background-color: rgba(0, 0, 0, 0.6);
			/* Black w/ opacity */
			align-items: center;
			justify-content: center;
		}

		.modal-content {
			background-color: #fefefe;
			margin: 5% auto;
			/* Reduced top margin to 5% */
			padding: 20px;
			border: 1px solid #888;
			width: 85%;
			/* Increased width to 85% of the viewport */
			max-width: 1000px;
			/* Set a maximum width for larger screens */
			border-radius: 8px;
			/* Optional: round the corners */
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
			/* Optional: add shadow for depth */
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

		/*simak modal part */
		.vehicle-modal-content {
    padding: 20px;
}

.vehicle-modal-image {
    width: 100%;
    height: 250px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f5f5f5;
}

.vehicle-modal-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
}

.vehicle-modal-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.detail-group {
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}

.detail-group h3 {
    color: var(--main);
    margin-bottom: 10px;
    font-size: 1.2em;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.detail-row .label {
    color: #666;
    font-weight: 500;
    flex: 1;
}

.detail-row .value {
    flex: 2;
    color: #333;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.9em;
    display: inline-block;
    flex-grow: 0;
}

.status-badge.available {
    background: #e8f5e9;
    color: #2e7d32;
    max-width: 200px;
}

.status-badge.in-use {
    background: #e3f2fd;
    color: #1565c0;
}

.status-badge.maintenance {
    background: #fff3e0;
    color: #ef6c00;
}

/* Responsive adjustments */
@media screen and (max-width: 768px) {
    .vehicle-modal-details {
        gap: 15px;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .detail-row .label,
    .detail-row .value {
        flex: none;
    }
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
					<?php foreach ($data as $stock): ?>
						<tr>
							<!-- Replace 'Michael Chen' with a dynamic driver name if available -->
							<td><?= $stock->full_name; ?></td>
							<!-- Collection ID -->
							<td style="text-align: center;"><?= $stock->collection_id; ?></td>
							<!-- Quantity -->
							<td><?= $stock->total_quantity; ?> units</td>
							<!-- Time -->
							<td><?= $stock->created_at; ?></td>
							<td><?= $stock->status; ?></td>
							<!-- Actions -->
							<td class="actions">
								<button class="ap-button" onclick="showCollectionBagDetails()">Approve</button>
								<button class="rp-button" onclick="reportModel(<?= $stock->collection_id; ?>)"
									style="">Reject</button>
							</td>
						</tr>
					<?php endforeach; ?>
					<!-- Add more rows as needed -->
				</tbody>
			</table>
		</div>

		<div id="collectionBagDetailsModal" class="modal" >
			<div class="modal-content">
				<span class="close" onclick="closeModal('collectionBagDetailsModal')">&times;</span>
				<h2>Colllection Details</h2>
				<div id="collectionBagDetailsContent">
					<!-- Bag details will be populated here -->
				</div>
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

	function closeModal(modalId) {
		const modal = document.getElementById(modalId);
		modal.style.display = 'none';
	}

	// // Improved click outside listener
	// window.addEventListener('click', function (event) {
	// 	const modal = document.getElementById('reportModal');
	// 	if (event.target === modal) {
	// 		closeModal();
	// 	}
	// });

	function showCollectionBagDetails() {
		const content = document.getElementById("collectionBagDetailsContent");
		const modal =document.getElementById('collectionBagDetailsModal')
		modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
		modal.style.backdropFilter = 'blur(1px)';


		// Hardcoded values for demonstration
		const collectionBag = {
			collection_id: "COL001",
			route: "Route A",
			driver: "Driver 1",
			suppliers: [
				{
					name: "Supplier A",
					bags: [
						{
							name: "Bag 1",
							capacity: 50,
							filledAmount: 30,
							detailsUrl: "bag_details.php?id=1",
						},
						{
							name: "Bag 2",
							capacity: 70,
							filledAmount: 50,
							detailsUrl: "bag_details.php?id=2",
						},
					],
				},
				{
					name: "Supplier B",
					bags: [
						{
							name: "Bag 3",
							capacity: 60,
							filledAmount: 20,
							detailsUrl: "bag_details.php?id=3",
						},
					],
				},
			],
			unassignedSuppliers: ["Supplier C", "Supplier D"],
			unassignedBags: [
				{ name: "Bag 4", capacity: 40, detailsUrl: "bag_details.php?id=4" },
				{ name: "Bag 5", capacity: 30, detailsUrl: "bag_details.php?id=5" },
			],
		};

		// Create tags for unassigned bags
		const unassignedBagTags = collectionBag.unassignedBags
			.map(
				(bag) => `
		<button class="tag-button" onclick="window.location.href='${bag.detailsUrl}'">
			${bag.name} (Capacity: ${bag.capacity} kg)
		</button>
	`
			)
			.join(" ");

		// Create table rows for assigned suppliers and their bags
		const supplierRows = collectionBag.suppliers
			.map(
				(supplier) => `
		<tr>
			<td>${supplier.name}</td>
			<td>${supplier.bags
						.map(
							(bag) => `
				<button class="tag-button" onclick="window.location.href='${bag.detailsUrl}'">
					${bag.name} (Capacity: ${bag.capacity} kg, Filled: ${bag.filledAmount} kg)
				</button>
			`
						)
						.join(" ")}</td>
		</tr>
	`
			)
			.join("");

		content.innerHTML = `
		  <div class="vehicle-modal-content">
			  <div class="vehicle-modal-details">
				  <div class="detail-group">
					  <h3>Collection Information</h3>
					  <div class="detail-row">
						  <span class="label">Collection ID:</span>
						  <span class="value">${collectionBag.collection_id
			}</span>
					  </div>
					  <div class="detail-row">
						  <span class="label">Route:</span>
						  <span class="value">${collectionBag.route}</span>
					  </div>
					  <div class="detail-row">
						  <span class="label">Driver:</span>
						  <span class="value">${collectionBag.driver}</span>
					  </div>
					  <div class="detail-row">
						  <span class="label">Number of Suppliers:</span>
						  <span class="value">${collectionBag.suppliers.length
			}</span>
					  </div>
				  </div>

				  <div class="detail-group">
					  <h3>Unassigned Suppliers</h3>
					  <div class="detail-row">
						  <span class="label">Suppliers:</span>
						  <span class="value">${collectionBag.unassignedSuppliers.join(
				", "
			)}</span>
					  </div>
				  </div>

				  <div class="detail-group">
					  <h3>Unassigned Bags</h3>
					  <div class="detail-row">
						  <span class="label">Bags:</span>
						  <span class="value">${unassignedBagTags}</span>
					  </div>
				  </div>

				  <div class="detail-group">
					  <h3>Assigned Suppliers and Their Bags</h3>
					  <table>
						  <thead>
							  <tr>
								  <th>Supplier</th>
								  <th>Assigned Bags</th>
							  </tr>
						  </thead>
						  <tbody>
							  ${supplierRows}
						  </tbody>
					  </table>
				  </div>
			  </div>
			  <button type="submit" class="btn btn-primary">confirm</button>
		  </div>
	`;
		document.getElementById("collectionBagDetailsModal").style.display = "block";
	}
</script>

<?php require APPROOT . '/views/inc/components/footer.php' ?>