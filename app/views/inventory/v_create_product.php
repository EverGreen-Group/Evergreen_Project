<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/create_product.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

</head>

<body>
    <main>

        <!-- Top nav bar -->
        <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
        <!-- Side bar -->
        <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
        <div class="screen">
            <div class="form-container">
                <form class="create-product-form" action="<?php echo URLROOT; ?>/Inventory/createproduct" method="POST">
                    <div class="form-header">
                        <h2>Create New Product</h2>
                    </div>

                    <div class="form-section">
                        <h3>Basic information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="product-name">Product name</label>
                                <input type="text" id="product-name" name="product-name"
                                    value="<?php echo isset($data['product-name']) ? $data['product-name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <select id="location" name="location">
                                    <option value="warehouse-a" <?php echo (isset($data['location']) && $data['location'] == 'warehouse-a') ? 'selected' : ''; ?>>Warehouse A</option>
                                    <option value="warehouse-b" <?php echo (isset($data['location']) && $data['location'] == 'warehouse-b') ? 'selected' : ''; ?>>Warehouse B</option>
                                    <option value="warehouse-c" <?php echo (isset($data['location']) && $data['location'] == 'warehouse-c') ? 'selected' : ''; ?>>Warehouse C</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="details">Details</label>
                                <input type="text" id="details" name="details"
                                    value="<?php echo isset($data['details']) ? $data['details'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" id="code" name="code" placeholder="Enter Code">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Media (Images)</h3>
                        <div class="media-upload-area">
                            <div class="upload-placeholder">
                                <span>+</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Sale information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <div class="input-with-prefix">
                                    <span class="prefix">$</span>
                                    <input type="number" id="price" name="price"
                                        value="<?php echo isset($data['price']) ? $data['price'] : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profit">Profit</label>
                                <input type="number" id="profit" name="profit"
                                    value="<?php echo isset($data['profit']) ? $data['profit'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="margin">Margin</label>
                                <input type="number" id="margin" name="margin"
                                    value="<?php echo isset($data['margin']) ? $data['margin'] : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Inventory</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity"
                                    value="<?php echo isset($data['quantity']) ? $data['quantity'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="unit">Unit</label>
                                <select id="unit" name="unit">
                                    <option value="item" <?php echo (isset($data['unit']) && $data['unit'] == 'item') ? 'selected' : ''; ?>>Item</option>
                                    <option value="kg" <?php echo (isset($data['unit']) && $data['unit'] == 'kg') ? 'selected' : ''; ?>>Kg</option>
                                    <option value="box" <?php echo (isset($data['unit']) && $data['unit'] == 'box') ? 'selected' : ''; ?>>Box</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">

                        <button type="submit" class="button-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php require APPROOT . '/views/inc/components/footer.php' ?>