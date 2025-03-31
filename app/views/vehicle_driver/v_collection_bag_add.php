<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/collection_1.css">
<script src="https://unpkg.com/@zxing/library@latest"></script>

<main>
    <!-- Page Header -->
    <div class="head-title">
        <div class="left">
            <h1>Add New Bag</h1>
            <ul class="breadcrumb">
                <li>
                    <i class='bx bx-home'></i>
                    <a href="<?php echo URLROOT; ?>/vehicledriver/dashboard">Dashboard</a>
                </li>
                <li>
                    <i class='bx bx-chevron-right'></i>
                    <a href="<?php echo URLROOT; ?>/vehicledriver/collectionRoute/<?php echo $data['collection']->collection_id; ?>">Collection Route</a>
                </li>
                <li>
                    <i class='bx bx-chevron-right'></i>
                    <a href="<?php echo URLROOT; ?>/vehicledriver/collectionBags/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['supplier']['id']; ?>">Bags</a>
                </li>
                <li>
                    <i class='bx bx-chevron-right'></i>
                    <a href="#">Add Bag</a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Bag Scanning Section -->
    <div id="bagScanningSection" class="panel">
        <div class="panel-header">
            <h3>Scan Bag</h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="bagId">Bag ID</label>
                <div style="display: flex; gap: var(--spacing-sm);">
                    <input type="text" id="bagId" class="form-control" placeholder="Scan or enter bag ID">
                    <button onclick="checkBag()" class="action-btn primary">Verify</button>
                </div>
            </div>
            <div class="scanner-container">
                <div id="reader" style="width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Bag Details Section (Initially Hidden) -->
    <div id="bagDetailsSection" class="panel" style="display: none;">
        <div class="panel-header">
            <h3>Bag Details</h3>
            <div class="panel-badge">Bag #<span id="selectedBagId"></span></div>
        </div>
        <div class="panel-body">
            <form id="addBagForm" method="POST" action="<?php echo URLROOT; ?>/vehicledriver/saveBag">
                <input type="hidden" id="bagIdInput" name="bag_id">
                <input type="hidden" name="collection_id" value="<?php echo $data['collection']->collection_id; ?>">
                <input type="hidden" name="supplier_id" value="<?php echo $data['supplier']['id']; ?>">
                
                <div class="form-group">
                    <label for="actualWeight">Actual Weight (kg)</label>
                    <input type="number" id="actualWeight" name="actual_weight" class="form-control" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Leaf Quality Details</label>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="leafType">Leaf Type</label>
                            <select id="leafType" name="leaf_type" class="form-control" required>
                                <?php foreach ($data['leafTypes'] as $leafType): ?>
                                    <option value="<?= $leafType->leaf_type_id; ?>"><?= $leafType->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="leafAge">Leaf Age</label>
                            <select id="leafAge" name="leaf_age" class="form-control" required>
                                <option value="Young">Young</option>
                                <option value="Medium">Medium</option>
                                <option value="Mature">Mature</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="moistureLevel">Moisture Level</label>
                            <select id="moistureLevel" name="moisture_level" class="form-control" required>
                                <option value="Wet">Wet</option>
                                <option value="Semi Wet">Semi Wet</option>
                                <option value="Dry">Dry</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="deductionNotes">Notes</label>
                    <textarea id="deductionNotes" name="notes" class="form-control" placeholder="Add any deduction notes or remarks"></textarea>
                </div>
                <div class="actions-container">
                    <button type="submit" class="action-btn primary">
                        <i class='bx bx-plus-circle'></i>
                        Add Bag
                    </button>
                    <button type="button" onclick="cancelBagDetails()" class="action-btn">
                        <i class='bx bx-x'></i>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Initialize the QR scanner
    document.addEventListener('DOMContentLoaded', function() {
        initializeScanner();
    });
    
    // Initialize the QR scanner functionality
    function initializeScanner() {
        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            document.getElementById('bagId').value = decodedText;
            html5QrCode.stop();
            checkBag();
        };
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        // Start scanner with camera
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
    }
    
    // Verify bag code
    function checkBag() {
        const bagId = document.getElementById('bagId').value;
        if(bagId) {
            document.getElementById('selectedBagId').textContent = bagId;
            document.getElementById('bagIdInput').value = bagId;
            document.getElementById('bagScanningSection').style.display = 'none';
            document.getElementById('bagDetailsSection').style.display = 'block';
        } else {
            alert('Please enter a valid bag ID');
        }
    }
    
    // Cancel bag details entry
    function cancelBagDetails() {
        window.location.href = `<?php echo URLROOT; ?>/vehicledriver/collectionBags/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['supplier']['id']; ?>`;
    }
</script> 