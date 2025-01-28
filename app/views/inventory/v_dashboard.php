<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection_bags/styles.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory_manager/stocks.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/inventory_manager/dashboard.js"></script>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Collection Approval</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn btn-primary" onclick="openAddStockModal()">
            <i class='bx bx-plus'></i>
            Add Stocks
        </button>
    </div>

    <ul class="box-info">
    <li>
        <i class='bx bxs-shopping-bag'></i>
        <span class="text">
            <p>Total Stocks</p>
            <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-user'></i>
        <span class="text">
            <p>Total Received Last Month</p>
            <h3><?php echo isset($data['availableVehicles']) ? $data['availableVehicles'] : '0'; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bxs-shopping-bag'></i>
        <span class="text">
            <p>Total In Processing</p>
            <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
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


	<div id="viewCollectionModal" class="modal">
		<div class="modal-content">
			<span class="close" onclick="closeModal('viewCollectionModal')">&times;</span>
			<h2>Collection Details</h2>
			<div class="collection-modal-content">
				<div class="collection-modal-details">
					<form id="collectionDetailsForm">
						<div class="detail-group">
							<h3>Collection Information</h3>
							<p id="collectionInfo"> <!-- Collection info will be populated here --> </p>
						</div>
						<div class="detail-group">
							<h3>Suppliers</h3>
							<table>
								<thead>
									<tr>
										<th>Supplier Name</th>
										<th>Quantity</th>
										<th>Notes</th>
										<th>Total</th>
										<th>Approved</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="supplierDetailsTable">
									<!-- Dynamic rows will be populated here -->
								</tbody>
							</table>
						</div>
						<div class="detail-group">
							<button type="button" id="finalizeButton" class="btn btn-primary" style="margin-top:20px; height:40px;">Finalize Collection</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="viewBagModal" class="modal" style="display: none;">
		<div class="modal-content">
			<span class="close" onclick="closeModal('viewBagModal')">&times;</span>
			<h2>Bag Details</h2>
			<div class="bag-modal-content collection-modal-content">
				<div class="bag-modal-details collection-modal-details">
					<form id="bagDetailsForm">
						<div class="detail-group">
							<h3>Bags for Supplier: <span id="supplierName"></span></h3>
							<table>
								<thead>
									<tr>
										<th>Bag ID</th>
										<th>Capacity (kg)</th>
										<th>Actual Weight (kg)</th>
										<th>Leaf Age</th>
										<th>Leaf Type</th>
										<th>Moisture Level</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="bagDetailsTable">
									<!-- Dynamic rows will be populated here -->
								</tbody>
							</table>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="inspectBagModal" class="modal">
		<div class="modal-content">
			<span class="close" onclick="closeModal('inspectBagModal')">&times;</span>
			<h2>Bag Details</h2>
			<div class="collection-modal-content" style="display: flex;">
				<div class="qr-image-container" style="flex: 1; padding: 10px;">
					<img id="inspectQrImage" src="/Evergreen_Project/uploads/qr_codes/73.png" alt="QR Code" style="width: 370px; height: 370px;" />
				</div>
				<div class="collection-modal-details" style="flex: 2; padding: 10px;">
					<form id="inspectBagForm">
						<div class="detail-group">
							<h3>Bag Information</h3>
							<div class="detail-row">
							<div class="detail-row">
								<span class="label">Collection ID:</span>
								<span class="value" id="bagCollectionId"></span>
							</div>
								<span class="label">Bag ID:</span>
								<span class="value" id="inspectBagId"></span>
							</div>
							<div class="detail-row">
								<span class="label">Capacity:</span>
								<span class="value" id="inspectCapacity"></span>
							</div>
							<div class="detail-row">
								<span class="label">Bag Weight:</span>
								<span class="value" id="inspectBagWeight"></span>
							</div>
							<div class="detail-row">
								<span class="label">Status:</span>
								<span class="value" id="inspectStatus"></span>
							</div>
							<div class="detail-row">
								<span class="label">Supplier Assigned:</span>
								<span class="value" id="inspectSupplier"></span>
							</div>
							<div class="detail-row">
								<span class="label">Moisture Level:</span>
								<span class="value" id="inspectMoisture"></span>
							</div>
							<div class="detail-row">
								<span class="label">Leaf Age:</span>
								<span class="value" id="inspectLeafAge"></span>
							</div>
							<div class="detail-row">
								<span class="label">Deduction Notes:</span>
								<span class="value" id="inspectDeductionNotes"></span>
							</div>
						</div>
						<!-- Buttons for Approve and Update -->

					</form>

				</div>

			</div>
			<div class="button-group" style="margin-top: 20px; display: flex; gap: 20px;">
				<button type="button" class="btn btn-primary" style="width: 100%; height: 40px;" onclick="approveBag()">Approve</button>
				<button type="button" class="btn btn-secondary" style="width: 100%; height: 40px;" onclick="updateBag()">Update</button>
			</div>
		</div>
	</div>

</main>




<?php require APPROOT . '/views/inc/components/footer.php'; ?>

