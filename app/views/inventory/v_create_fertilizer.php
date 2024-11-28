<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/createfertilizer.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

</head>

<body>

    <!-- Top nav bar -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
    <!-- Side bar -->
    <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

    <main>
        <div class="fc-body">
            <div class="form-container">
                <div class="form-header">
                    <h2>Add New Fertilizer</h2>
                    <button class="close-btn">&times;</button>
                </div>
                <form action="<?php echo URLROOT; ?>/inventory/createfertilizer" method="POST">
                    <!-- Basic Information Section -->
                    <section class="form-section">
                        <h3>Basic information</h3>
                        <div class="form-group">
                            <div class="form-control">
                                <label for="fertilizer-name">Fertilizer name</label>
                                <input type="text" id="fertilizer-name" name="fertilizer_name" placeholder="Enter Fertilizer name" value="<?php echo isset($data['fertilizer_name']) ? $data['fertilizer_name'] : ''; ?>">
                            </div>
                            <div class="form-control">
                                <label for="company-name">Company name</label>
                                <select id="company-name" name="company_name">
                                    <option value="cic"<?php echo (isset($data['company_name']) && $data['company_name'] == 'cic') ? 'selected' : ''; ?> >CIC</option>
                                    <option value="baur"<?php echo (isset($data['company_name']) && $data['company_name'] == 'baur') ? 'selected' : ''; ?>>Baur</option>
                                    <option value="brown"<?php echo (isset($data['company_name']) && $data['company_name'] == 'brown') ? 'selected' : ''; ?>>Brown & Company</option>
                                    <option value="hayleys"<?php echo (isset($data['company_name']) && $data['company_name'] == 'hayleys') ? 'selected' : ''; ?>>Hayleys</option>
                                    <option value="lankem"<?php echo (isset($data['company_name']) && $data['company_name'] == 'lankem') ? 'selected' : ''; ?>>Lankem Ceylon</option>
                                    
            
                                
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-control">
                                <label for="details">Details</label>
                                <input type="text" id="details" name="details" placeholder="Enter details" value="<?php echo isset($data['details']) ? $data['details'] : ''; ?>">
                            </div>
                            <div class="form-control">
                                <label for="code">Code</label>
                                <input type="text" id="code" name="code" placeholder="Enter Code" value="<?php echo isset($data['code']) ? $data['code'] : ''; ?>">
                            </div>
                        </div>
                    </section>

                    <!-- Media Section -->
                    <section class="form-section">
                        <h3>Media <span>(Images, video or 3D models)</span></h3>
                        <div class="media-upload">
                            <div class="media-box">+</div>
                            <div class="media-box">+</div>
                        </div>
                    </section>

                    <!-- Sale Information Section -->
                    <section class="form-section">
                        <h3>Sale information</h3>
                        <div class="form-control">
                            <label for="price">Price</label>
                            <div class="price-input">
                                <span>$</span>
                                <input type="number" id="price" name="price" placeholder="Enter price" value="<?php echo isset($data['price']) ? $data['price'] : ''; ?>">
                            </div>
                        </div>
                    </section>

                    <!-- Inventory Section -->
                    <section class="form-section">
                        <h3>Inventory</h3>
                        <div class="form-group">
                            <div class="form-control">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" value="<?php echo isset($data['quantity']) ? $data['quantity'] : ''; ?>">
                            </div>
                            <div class="form-control">
                                <label for="unit">Unit</label>
                                <select id="unit" name="unit">
                                    <option value="item"<?php echo (isset($data['unit']) && $data['unit'] == 'item') ? 'selected' : ''; ?>>Item</option>
                                    <option value="kg"<?php echo (isset($data['unit']) && $data['unit'] == 'kg') ? 'selected' : ''; ?>>Kg</option>
                                    <option value="bag"<?php echo (isset($data['unit']) && $data['unit'] == 'bag') ? 'selected' : ''; ?>>Bag</option>
                                    <option value="ton"<?php echo (isset($data['unit']) && $data['unit'] == 'ton') ? 'selected' : ''; ?>>Ton</option>

                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        </div>
                    </section>

                    <!-- Save Button -->
                    <div class="form-footer">
                        <button type="submit" class="save-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require APPROOT . '/views/inc/components/footer.php' ?>