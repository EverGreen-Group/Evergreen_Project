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
        <div class="form-container">
            <div class="form-header">
                <h2>Add New Fertilizer</h2>
                <button class="close-btn">&times;</button>
            </div>
            <form>
                <!-- Basic Information Section -->
                <section class="form-section">
                    <h3>Basic information</h3>
                    <div class="form-group">
                        <div class="form-control">
                            <label for="fertilizer-name">Fertilizer name</label>
                            <input type="text" id="fertilizer-name" placeholder="B750">
                        </div>
                        <div class="form-control">
                            <label for="company-name">Company name</label>
                            <select id="company-name">
                                <option>CIC</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">
                            <label for="details">Details</label>
                            <input type="text" id="details" placeholder="Enter details">
                        </div>
                        <div class="form-control">
                            <label for="code">Code</label>
                            <input type="text" id="code" placeholder="Enter Code">
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
                            <input type="number" id="price" placeholder="Enter price">
                        </div>
                    </div>
                </section>

                <!-- Inventory Section -->
                <section class="form-section">
                    <h3>Inventory</h3>
                    <div class="form-group">
                        <div class="form-control">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" placeholder="Enter quantity">
                        </div>
                        <div class="form-control">
                            <label for="unit">Unit</label>
                            <select id="unit">
                                <option>Item</option>
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

    </main>

    <?php require APPROOT . '/views/inc/components/footer.php' ?>