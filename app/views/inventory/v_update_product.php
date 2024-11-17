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
                <form class="create-product-form" action="<?php echo URLROOT; ?>/inventory/updateproduct/<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-header">
                        <h2>Update Product</h2>
                    </div>

                    <div class="form-section">
                        <h3>Basic information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="product-name">Product name</label>
                                <input type="text" id="product-name" name="product-name"
                                    value="<?php echo $data['product']->product_name ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <select id="location" name="location">
                                    <option value="warehouse-a" <?php echo ($data['product']->location == 'warehouse-a') ? 'selected' : ''; ?>>Warehouse A</option>
                                    <option value="warehouse-b" <?php echo ($data['product']->location == 'warehouse-b') ? 'selected' : ''; ?>>Warehouse B</option>
                                    <option value="warehouse-c" <?php echo ($data['product']->location == 'warehouse-c') ? 'selected' : ''; ?>>Warehouse C</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="details">Details</label>
                                <input type="text" id="details" name="details"
                                    value="<?php echo $data['product']->details ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" id="code" name="code" placeholder="Enter Code" value="<?php echo $data['product']->code ?? ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Media (Images)</h3>
                        <div class="media-upload-area">
                            <input type="file" id="product_image" name="product_image" accept="image/*"
                                onchange="previewImage(this)">
                            <div class="image-preview" id="imagePreview">
                                <?php if (!empty($data['product']->image_path)): ?>
                                    <img src="<?php echo URLROOT . '/uploads/products/' . $data['product']->image_path; ?>" 
                                         alt="Product Image" id="preview">
                                <?php else: ?>
                                    <div class="upload-placeholder" id="placeholder">
                                        <span>+</span>
                                    </div>
                                <?php endif; ?>
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
                                        value="<?php echo $data['product']->price ?? ''; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profit">Profit</label>
                                <input type="number" id="profit" name="profit"
                                    value="<?php echo $data['product']->profit ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="margin">Margin</label>
                                <input type="number" id="margin" name="margin"
                                    value="<?php echo $data['product']->margin ?? ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Inventory</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity"
                                    value="<?php echo $data['product']->quantity ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="unit">Unit</label>
                                <select id="unit" name="unit">
                                    <option value="item" <?php echo ($data['product']->unit == 'item') ? 'selected' : ''; ?>>Item</option>
                                    <option value="kg" <?php echo ($data['product']->unit == 'kg') ? 'selected' : ''; ?>>Kg</option>
                                    <option value="box" <?php echo ($data['product']->unit == 'box') ? 'selected' : ''; ?>>Box</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">

                        <button type="submit" class="button-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php require APPROOT . '/views/inc/components/footer.php' ?>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>