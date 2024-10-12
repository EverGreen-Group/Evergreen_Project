<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/product.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

</head>

<body>
    <main>

        <!-- Top nav bar -->
        <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
        <!-- Side bar -->
        <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

        <!-- Header Section -->
        <header>
            <div class="head-title">
                <div class="left">
                    <h1>Product</h1>

                </div>

            </div>
            <div class="header-actions">
                <input type="text" placeholder="Search">
                <button class="filter-btn">Filter</button>
                <div class="grouped-by">
                    <label for="grouped-by">Grouped by:</label>
                    <select id="grouped-by">
                        <option value="warehouse">Warehouse</option>
                        <option value="category">Category</option>
                    </select>
                </div>
                <button  class="create-product">+ Create New Product</button>
            </div>
        </header>

        <!-- Warehouse Section -->
        <div class="warehouse-section">
            <h2><img src="warehouse-icon.png" alt="Warehouse Icon"> Warehouse A</h2>
            <span class="product-info">50 products | 1,000 items</span>
            <div class="product-grid">
                <div class="product-card">
                    <div class="product-info">
                        <span class="product-code">Code: SWE-1</span>
                        <h3>Green Tea</h3>
                        <p>On hand: 200 items</p>
                    </div>
                </div>
                <div class="product-card low-stock">
                    <div class="product-info">
                        <span class="product-code">Code: BTS</span>
                        <h3>Blue Tea</h3>
                        <p>On hand: 200 items</p>
                        <span class="low-stock-alert">Low-Stock Alerts</span>
                    </div>
                </div>

                <div class="product-card low-stock">
                    <div class="product-info">
                        <span class="product-code">Code: BTS</span>
                        <h3>Blue Tea</h3>
                        <p>On hand: 200 items</p>
                        <span class="low-stock-alert">Low-Stock Alerts</span>
                    </div>
                </div>

                <div class="product-card low-stock">
                    <div class="product-info">
                        <span class="product-code">Code: BTS</span>
                        <h3>Blue Tea</h3>
                        <p>On hand: 200 items</p>
                        <span class="low-stock-alert">Low-Stock Alerts</span>
                    </div>
                </div>

                <div class="product-card low-stock">
                    <div class="product-info">
                        <span class="product-code">Code: BTS</span>
                        <h3>Blue Tea</h3>
                        <p>On hand: 200 items</p>
                        <span class="low-stock-alert">Low-Stock Alerts</span>
                    </div>
                </div>
                <!-- Additional product cards go here -->
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="prev">&lt;</button>
            <button class="page-number active">1</button>
            <button class="page-number">2</button>
            <button class="page-number">3</button>
            <button class="next">&gt;</button>
        </div>
        </div>

    </main>
    <?php require APPROOT . '/views/inc/components/footer.php' ?>