<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
</head>

<body>
    <?php require APPROOT . '/views/inc/components/header.php'; ?>

    <!-- Side bar -->
    <?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
    <!-- Top nav bar -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
        const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
    </script>

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Update Product</h1>
                <ul class="breadcrumb">
                    <li><a href="<?= URLROOT ?>/Inventory">Products</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Update Product</a></li>
                </ul>
            </div>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($data['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>

        <form id="updateProductForm" method="POST" action="<?php echo URLROOT; ?>/inventory/updateproduct/<?php echo $data['id']; ?>" enctype="multipart/form-data">

            <!-- Basic Information -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Basic Information</h3>
                    </div>
                    <div class="section-content">
                        <div class="info-row">
                            <label class="label" for="product-name">Product Name:</label>
                            <input type="text" id="product-name" name="product-name" class="form-control" required value="<?php echo $data['product']->product_name ?? ''; ?>">
                        </div>
                        <div class="info-row">
                            <label class="label" for="location">Location:</label>
                            <select id="location" name="location" class="form-control" required>
                                <option value="warehouse-a" <?php echo ($data['product']->location == 'warehouse-a') ? 'selected' : ''; ?>>Warehouse A</option>
                                <option value="warehouse-b" <?php echo ($data['product']->location == 'warehouse-b') ? 'selected' : ''; ?>>Warehouse B</option>
                                <option value="warehouse-c" <?php echo ($data['product']->location == 'warehouse-c') ? 'selected' : ''; ?>>Warehouse C</option>
                            </select>
                        </div>
                        <div class="info-row">
                            <label class="label" for="details">Details:</label>
                            <input type="text" id="details" name="details" class="form-control" value="<?php echo $data['product']->details ?? ''; ?>">
                        </div>
                        <div class="info-row">
                            <label class="label" for="grade">Grade:</label>
                            <input type="text" id="grade" name="grade" class="form-control" placeholder="Enter Grade" required value="<?php echo $data['product']->grade ?? ''; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Section -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Media (Images)</h3>
                    </div>
                    <div class="section-content">
                        <div class="info-row">
                            <label class="label" for="product_image">Product Image:</label>
                            <input type="file" id="product_image" name="product_image" class="form-control" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <div class="info-row">
                            <div id="imagePreview" style="width:100%; text-align:center;">
                                <?php if (!empty($data['product']->image_path)): ?>
                                    <img src="<?php echo URLROOT . '/uploads/products/' . $data['product']->image_path; ?>" alt="Product Image" id="preview" style="max-width:300px;">
                                <?php else: ?>
                                    <img src="" alt="Image Preview" id="preview" style="max-width:300px; display:none;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sale Information -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Sale Information</h3>
                    </div>
                    <div class="section-content">
                        <div class="info-row">
                            <label class="label" for="price">Price:</label>
                            <div class="input-with-prefix" style="display:flex; align-items:center;">
                                <span class="prefix" style="margin-right:5px;">Rs.</span>
                                <input type="number" id="price" name="price" class="form-control" required value="<?php echo $data['product']->price ?? ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Information -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Inventory Information</h3>
                    </div>
                    <div class="section-content">
                        <div class="info-row">
                            <label class="label" for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" required value="<?php echo $data['product']->quantity ?? ''; ?>">
                        </div>
                        <div class="info-row">
                            <label class="label" for="unit">Unit:</label>
                            <select id="unit" name="unit" class="form-control" required>
                                <option value="kg" <?php echo ($data['product']->unit == 'kg') ? 'selected' : ''; ?>>Kg</option>
                                <option value="box" <?php echo ($data['product']->unit == 'box') ? 'selected' : ''; ?>>Box (100kg)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </main>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>    

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

        /* Breadcrumb Refinements */
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

        /* Form controls */
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
        }

        
    </style>

    <?php require APPROOT . '/views/inc/components/footer.php'; ?>
</body>
</html>