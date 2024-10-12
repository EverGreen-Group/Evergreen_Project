<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/item.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

</head>

<body>
    <main>


        <!-- Top nav bar -->
        <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
        <!-- Side bar -->
        <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

        <div class="item-page">
            <div class="product-header">
                <h1>GREEN TEA</h1>
                <span class="product-code">Code: SWE</span>
            </div>

            <div class="product-details">
                <div class="product-image-carousel">
                    <!-- Carousel placeholder -->
                    <div class="carousel">
                        <div class="carousel-images">
                            <!-- Placeholder for images -->
                        </div>
                        <div class="carousel-dots">
                            <!-- Dots for navigating -->
                            <span class="dot active"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                    </div>
                </div>

                <div class="product-info">
                    <div class="product-quantity">
                        <div class="info-block">
                            <h3>On hand</h3>
                            <span>200</span>
                        </div>
                        <div class="info-block">
                            <h3>To be delivered</h3>
                            <span>50</span>
                        </div>
                        <div class="info-block">
                            <h3>To be ordered</h3>
                            <span>50</span>
                        </div>
                    </div>

                    <div class="product-basic-info">
                        <h2>Basic information</h2>
                        <p><strong>Product name:</strong> Green Sweater</p>
                        <p><strong>Vendor:</strong> AS.laz Store</p>
                        <p><strong>Location:</strong> Warehouse A</p>
                        <p><strong>Code:</strong> SWE</p>
                    </div>

                    <div class="product-sale-info">
                        <h2>Sale information</h2>
                        <p><strong>Price:</strong> $100.00</p>
                        <p><strong>Profit:</strong> $50</p>
                    </div>

                    <div class="product-inventory">
                        <h2>Inventory</h2>
                        <p><strong>Quantity:</strong> 200</p>
                    </div>

                    <div class="product-actions">
                        <button class="btn edit">Edit</button>
                        <button class="btn update">Update Quantity</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require APPROOT . '/views/inc/components/footer.php' ?>