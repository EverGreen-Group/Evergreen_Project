<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/collection_1.css">

<main>
    <div class="head-title">
        <div class="left">
            <h1>Add New Bag</h1>
        </div>
    </div>
    
    <?php if (isset($data['flash'])): ?>
        <div class="alert alert-warning">
            <?php echo htmlspecialchars($data['flash']); ?>
        </div>
    <?php endif; ?>


    <div id="bagDetailsSection" class="panel">
        <div class="panel-header">
            <h3>Bag Details</h3>
            <div class="panel-badge">Bag #<span id="selectedBagId"></span></div>
        </div>
        <div class="panel-body">
            <form id="addBagForm" method="POST" action="<?php echo URLROOT; ?>/vehicledriver/saveBag">
                <input type="hidden" name="collection_id" value="<?php echo $data['collection']->collection_id; ?>">
                <input type="hidden" name="supplier_id" value="<?php echo $data['supplier']['id']; ?>">

                <div class="form-group">
                    <label for="actualWeight">Bag ID</label>
                    <input type="number" id="bagIdInput" name="bag_id" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="actualWeight">Weight (kg)</label>
                    <input type="number" id="actualWeight" name="actual_weight" class="form-control" required>
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
    
    function cancelBagDetails() {
        window.location.href = `<?php echo URLROOT; ?>/vehicledriver/collectionBags/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['supplier']['id']; ?>`;
    }
</script>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>