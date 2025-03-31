<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/collection_1.css">

<main>
    <!-- Page Header -->
    <div class="head-title">
        <div class="left">
            <h1>Update Bag</h1>
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
                    <a href="#">Update Bag</a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Update Bag Section -->
    <div id="updateBagSection" class="panel">
        <div class="panel-header">
            <h3>Update Bag</h3>
            <div class="panel-badge">Bag #<?php echo $data['bag']->bag_id; ?></div>
        </div>
        <div class="panel-body">
            <form id="updateBagForm" method="POST" action="<?php echo URLROOT; ?>/vehicledriver/updateBagSubmit">
                <input type="hidden" name="history_id" value="<?php echo $data['bag']->history_id; ?>">
                <input type="hidden" name="bag_id" value="<?php echo $data['bag']->bag_id; ?>">
                <input type="hidden" name="collection_id" value="<?php echo $data['collection']->collection_id; ?>">
                <input type="hidden" name="supplier_id" value="<?php echo $data['supplier']['id']; ?>">
                
                <div class="form-group">
                    <label>Bag Capacity: <span><?php echo $data['bag']->capacity_kg; ?></span>kg</label>
                </div>
                <div class="form-group">
                    <label for="actualWeight">Actual Weight (kg)</label>
                    <input type="number" id="actualWeight" name="actual_weight" class="form-control" step="0.01" value="<?php echo $data['bag']->actual_weight_kg; ?>" required>
                </div>
                <div class="form-group">
                    <label>Leaf Quality Details</label>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="leafType">Leaf Type</label>
                            <select id="leafType" name="leaf_type" class="form-control" required>
                                <?php foreach ($data['leafTypes'] as $leafType): ?>
                                    <option value="<?= $leafType->leaf_type_id; ?>" <?php if($leafType->leaf_type_id == $data['bag']->leaf_type_id) echo 'selected'; ?>><?= $leafType->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="leafAge">Leaf Age</label>
                            <select id="leafAge" name="leaf_age" class="form-control" required>
                                <option value="Young" <?php if($data['bag']->leaf_age == 'Young') echo 'selected'; ?>>Young</option>
                                <option value="Medium" <?php if($data['bag']->leaf_age == 'Medium') echo 'selected'; ?>>Medium</option>
                                <option value="Mature" <?php if($data['bag']->leaf_age == 'Mature') echo 'selected'; ?>>Mature</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="moistureLevel">Moisture Level</label>
                            <select id="moistureLevel" name="moisture_level" class="form-control" required>
                                <option value="Wet" <?php if($data['bag']->moisture_level == 'Wet') echo 'selected'; ?>>Wet</option>
                                <option value="Semi Wet" <?php if($data['bag']->moisture_level == 'Semi Wet') echo 'selected'; ?>>Semi Wet</option>
                                <option value="Dry" <?php if($data['bag']->moisture_level == 'Dry') echo 'selected'; ?>>Dry</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="deductionNotes">Notes</label>
                    <textarea id="deductionNotes" name="notes" class="form-control" placeholder="Add any deduction notes or remarks"><?php echo $data['bag']->deduction_notes; ?></textarea>
                </div>
                <div class="actions-container">
                    <button type="submit" class="action-btn primary">
                        <i class='bx bx-check'></i>
                        Update Bag
                    </button>
                    <a href="<?php echo URLROOT; ?>/vehicledriver/collectionBags/<?php echo $data['collection']->collection_id; ?>/<?php echo $data['supplier']['id']; ?>" class="action-btn">
                        <i class='bx bx-x'></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</main> 