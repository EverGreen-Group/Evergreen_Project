<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Update Bag Details</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/inventory">Inventory</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="<?= URLROOT ?>/inventory/viewAwaitingInventory/<?= $data['bag']->collection_id ?>">Collection
                        Bags</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Update Bag</a></li>
            </ul>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>

    <form id="updateBagForm" method="POST"
        action="<?php echo URLROOT; ?>/inventory/updateBag/<?php echo $data['bag']->history_id; ?>">

        <!-- Bag Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Bag Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="bag_id">Bag ID:</label>
                        <span class="value"><?= 'B' . str_pad($data['bag']->bag_id, 3, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="info-row">
                        <label class="label" for="capacity_kg">Capacity (kg):</label>
                        <span class="value"><?= $data['bag']->capacity_kg ?></span>
                    </div>
                    <div class="info-row">
                        <label class="label" for="actual_weight_kg">Actual Weight (kg):</label>
                        <input type="number" step="0.01" id="actual_weight_kg" name="actual_weight_kg"
                            class="form-control" value="<?= $data['bag']->actual_weight_kg ?>" required min=0>
                    </div>
                    <div class="info-row">
                        <label class="label" for="leaf_age">Leaf Age:</label>
                        <select id="leaf_age" name="leaf_age" class="form-control" required>
                            <option value="">Select Leaf Age</option>
                            <option value="Young" <?= ($data['bag']->leaf_age == 'Young') ? 'selected' : ''; ?>>Young
                            </option>
                            <option value="Medium" <?= ($data['bag']->leaf_age == 'Medium') ? 'selected' : ''; ?>>Medium
                            </option>
                            <option value="Mature" <?= ($data['bag']->leaf_age == 'Mature') ? 'selected' : ''; ?>>Mature
                            </option>
                        </select>
                    </div>
                    <div class="info-row">
                        <label class="label" for="moisture_level">Moisture Level:</label>
                        <select id="moisture_level" name="moisture_level" class="form-control" required>
                            <option value="">Select Moisture Level</option>
                            <option value="Wet" <?= ($data['bag']->moisture_level == 'Wet') ? 'selected' : ''; ?>>Wet
                            </option>
                            <option value="Semi Wet" <?= ($data['bag']->moisture_level == 'Semi Wet') ? 'selected' : ''; ?>>Semi Wet</option>
                            <option value="Dry" <?= ($data['bag']->moisture_level == 'Dry') ? 'selected' : ''; ?>>Dry
                            </option>
                        </select>
                    </div>
                    <div class="info-row">
                        <label class="label" for="leaf_type_id">Leaf Type:</label>
                        <select id="leaf_type_id" name="leaf_type_id" class="form-control" required>
                            <option value="">Select Leaf Type</option>
                            <?php foreach ($data['leaf_types'] as $type): ?>
                                <option value="<?= $type->leaf_type_id ?>"
                                    <?= ($data['bag']->leaf_type_id == $type->leaf_type_id) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($type->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="info-row">
                        <label class="label" for="deduction_notes">Deduction Notes:</label>
                        <textarea id="deduction_notes" name="deduction_notes" class="form-control"
                            rows="4"><?= $data['bag']->deduction_notes ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bag Summary -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Bag Summary</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <span class="label">Bag ID:</span>
                        <span class="value"><?= 'B' . str_pad($data['bag']->bag_id, 3, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Collection ID:</span>
                        <span
                            class="value"><?= 'C' . str_pad($data['bag']->collection_id, 3, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Supplier:</span>
                        <span class="value">
                            <?php
                            if (!empty($data['bag']->supplier_id)) {
                                echo 'S' . str_pad($data['bag']->supplier_id, 3, '0', STR_PAD_LEFT);
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="label">Status:</span>
                        <span class="value"><?= ucfirst($data['bag']->action) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Timestamp:</span>
                        <span class="value"><?= date('Y-m-d h:i A', strtotime($data['bag']->timestamp)) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="button-group">
            <button type="submit" class="btn btn-primary">Update Bag</button>
            <a href="<?= URLROOT ?>/inventory/viewAwaitingInventory/<?= $data['bag']->collection_id ?>"
                class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    <style>
        /* Table Data Container */
        .table-data {
            margin-bottom: 24px;
        }

        .order {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Section Headers */
        .head {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .head h3 {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Content Sections */
        .section-content {
            padding: 8px 0;
        }

        /* Info Rows */
        .info-row {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            transition: background-color 0.2s;
        }

        .info-row:hover {
            background-color: #f8f9fa;
        }

        .info-row .label {
            flex: 0 0 200px;
            font-size: 14px;
            color: #6c757d;
        }

        .info-row .value {
            flex: 1;
            font-size: 14px;
            color: #2c3e50;
        }

        /* Form controls */
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Alert styling */
        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .breadcrumb a {
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #3b82f6;
        }

        .breadcrumb a.active {
            color: #2c3e50;
            pointer-events: none;
        }

        .breadcrumb i {
            color: #9ca3af;
            font-size: 14px;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 10px;
            margin: 0 0 20px 20px;
        }

        /* Button Styling */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: #10b981;
            color: white;
        }

        .btn-primary:hover {
            background-color: #059669;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>

    <?php require APPROOT . '/views/inc/components/footer.php'; ?>