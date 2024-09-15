<?php require APPROOT . '/views/inc/components/header.php' ?>

<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar.php' ?>

<div class="product-page">
    <!-- Breadcrumbs -->
    <nav class="breadcrumb">
        <a href="#">Product</a> > <a href="#">Product Detail</a>
    </nav>

    <!-- Product Title -->
    <div class="product-header">
        <h1>GREEN TEA</h1>
        <span class="product-code">Code: SWE</span>
    </div>

    <!-- Product Detail Section -->
    <div class="product-detail">
        <!-- Image and Gallery Section -->
        <div class="product-image-gallery">
            <div class="main-image"></div>
            <div class="gallery-navigation">
                <button>&lt;</button>
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <button>&gt;</button>
            </div>
        </div>

        <!-- Product Information -->
        <div class="product-info">
            <div class="stock-info">
                <div class="stock">
                    <p>On hand</p>
                    <h2>200</h2>
                </div>
                <div class="to-be-delivered">
                    <p>To be delivered</p>
                    <h2>50</h2>
                </div>
                <div class="to-be-ordered">
                    <p>To be ordered</p>
                    <h2>50</h2>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="basic-info">
                <h3>Basic Information</h3>
                <p><strong>Product name:</strong> Green Sweater</p>
                <p><strong>Vendor:</strong> AS.laz Store</p>
                <p><strong>Location:</strong> Warehouse A</p>
                <p><strong>Code:</strong> SWE</p>
                <p><strong>Barcode:</strong> 40181700082</p>
            </div>

            <!-- Sale Information -->
            <div class="sale-info">
                <h3>Sale Information</h3>
                <p><strong>Price:</strong> $100.00</p>
                <p><strong>Profit:</strong> $50 <span class="margin">Margin 50%</span></p>
            </div>

            <!-- Inventory Section -->
            <div class="inventory-info">
                <h3>Inventory</h3>
                <p><strong>Quantity:</strong> 200</p>
                <p><strong>Unit:</strong> Item</p>
                <a href="#" class="update-history">Update Quantity History</a>
            </div>

            <!-- Buttons -->
            <div class="action-buttons">
                <button class="btn create-plan">Create Inventory Plan</button>
                <button class="btn edit">Edit</button>
                <button class="btn update-quantity">Update Quantity</button>
            </div>
        </div>
    </div>

    <!-- Barcode Section -->
    <div class="barcode-section">
        <h3>Barcode</h3>
        <!-- Barcode image will go here -->
    </div>
</div>

<?php require APPROOT . '/views/inc/components/footer.php' ?>