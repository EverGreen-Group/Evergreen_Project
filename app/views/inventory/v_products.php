<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/driver/driver.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle_card.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory_manager/products.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/vehicle.js"></script>


<!-- MAIN -->
<main>


<div class="head-title">
      <div class="left">
          <h1>Product Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
              <li><i class='bx bx-chevron-right' ></i></li>
              <li><a class="active" href="#">Products</a></li>
          </ul>
      </div>
  </div>

  <div class="action-buttons">
      <button class="btn btn-primary" onclick="document.getElementById('addProductModal').style.display='block'">
          <i class='bx bx-plus'></i>
          Add New Product
      </button>
  </div>

  <ul class="box-info">
    <li>
        <i class='bx bx-package'></i>
        <span class="text">
          <p>Total Products</p>
          <h3><?php echo isset($data['totalProducts']) ? $data['totalProducts'] : 0; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bx-low-vision'></i>
        <span class="text">
          <p>Low Stock Items</p>
          <h3><?php echo isset($data['lowStockItems']) ? $data['lowStockItems'] : 0; ?></h3>
        </span>
    </li>
    <li>
        <i class='bx bx-trending-up'></i>
        <span class="text">
          <p>Total Inactive</p>
          <h3><?php echo isset($data['totalInactive']) ? $data['totalInactive'] : 0; ?></h3>
        </span>
    </li>
  </ul>

  <div class="table-data">

    <!-- <div class="order">
        <div class="head">
            <h3>Weekly Revenue Overview</h3>
            <i class='bx bx-line-chart'></i>
        </div>
        <div class="chart-container-wrapper">
            <div class="chart-header">
                <div class="chart-filters">
                    <select id="weekSelector" class="filter-select">
                        <option value="current">Current Week</option>
                        <option value="last">Last Week</option>
                    </select>
                </div>
            </div>
            <canvas id="weeklyRevenueChart"></canvas>
        </div>
    </div> -->


    <div class="order">
        <div class="head">
            <h3>Available Products</h3>
            <i class='bx bx-package'></i>
        </div>
        <div class="bags-grid">
            <!-- Black Tea - BOPF -->
            <div class="bag-card" onclick="showProductDetails('P001')">
                <div class="bag-icon">
                    <img src="http://localhost/Evergreen_Project/uploads/products/6749498b5dc06.png" alt="Black Tea" class="product-image">
                </div>
                <div class="bag-info">
                    <h4>Black Tea</h4>
                    <p class="product-grade">Grade: BOPF</p>
                    <div class="stock-info">
                        <span class="quantity">5,000 kg</span>
                        <span class="status completed">In Stock</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="order" style="max-width:510px;">
        <div class="head">
            <h3>Products Low On Stock</h3>
        </div>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Stock (kg)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="lowStockProducts">
                <!-- Low stock products will be populated here -->
            </tbody>
        </table>
    </div>
</div>









  <div id="productTable" class="table-data">
    <div class="order">
      <div class="head">
        <h3>Product Inventory</h3>
        <div class="filter-container">
            <select id="teaTypeFilter" class="filter-select">
                <option value="all">All Types</option>
                <option value="Black Tea">Black Tea</option>
                <option value="Green Tea">Green Tea</option>
                <option value="Herbal Tea">Herbal Tea</option>
                <option value="Oolong Tea">Oolong Tea</option>
            </select>
            <select id="gradingFilter" class="filter-select">
                <option value="all">All Grades</option>
                <option value="BOPF">BOPF</option>
                <option value="BOP">BOP</option>
                <option value="FBOP">FBOP</option>
                <option value="PF">PF</option>
                <option value="DUST">DUST</option>
            </select>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>Product ID</th>
            <th>Tea Type</th>
            <th>Grade</th>
            <th>Price</th>
            <th>Quantity (kg)</th>
            <th>Last Updated</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Product Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addProductModal')">&times;</span>
        <h2>Add New Product</h2>
        <div class="product-modal-content">
            <form id="createProductForm" onsubmit="submitProductForm(event)">
                <!-- Product Image Upload -->
                <div class="detail-group">
                    <h3>Product Image</h3>
                    <div class="image-upload-container">
                        <div class="image-preview" id="imagePreview">
                            <i class='bx bx-image-add'></i>
                            <span>Click to upload image</span>
                        </div>
                        <input type="file" id="productImage" name="product_image" accept="image/*" required 
                               onchange="previewImage(this)">
                    </div>
                </div>

                <!-- Basic Product Information -->
                <div class="detail-group">
                    <h3>Product Details</h3>
                    <div class="specifications-container">
                        <div class="specifications-left">
                        <div class="detail-row">
                                <span class="label">Tea Type:</span>
                                <span class="value">
                                    <select name="tea_type" class="form-select" required>
                                        <option value="">Select Tea Type</option>
                                        <?php foreach ($data['leafTypes'] as $type): ?>
                                            <option value="<?= $type->leaf_type_id ?>">
                                                <?= htmlspecialchars($type->name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Grade:</span>
                                <span class="value">
                                    <select name="grade" class="form-select" required>
                                        <option value="">Select Grade</option>
                                        <?php foreach ($data['leafGradings'] as $grade): ?>
                                            <option value="<?= $grade->grading_id ?>">
                                                <?= htmlspecialchars($grade->name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </span>
                            </div>
                            <!-- Add Product Name field -->
                            <div class="detail-row">
                                <span class="label">Product Name:</span>
                                <span class="value">
                                    <input type="text" 
                                           name="product_name" 
                                           class="form-control" 
                                           required 
                                           placeholder="Enter product name">
                                </span>
                            </div>
                        </div>
                        <div class="specifications-right">
                            <div class="detail-row">
                                <span class="label">Price per kg (Rs.):</span>
                                <span class="value">
                                    <input type="number" name="price_per_kg" class="form-control" 
                                           step="0.01" min="0" required>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Quantity (kg):</span>
                                <span class="value">
                                    <input type="number" name="initial_stock" class="form-control" 
                                           step="0.01" min="0" required>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="detail-group">
                    <h3>Product Description</h3>
                    <div class="detail-row">
                        <textarea name="description" class="form-control" rows="4" 
                                placeholder="Enter product description..." required></textarea>
                    </div>
                </div>


                <div class="form-actions">
                    <button type="submit" class="btn btn-primary full-width">ADD PRODUCT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="productDetailsModal" class="modal">
    <div class="modal-content product-details-content">
        <span class="close" onclick="closeModal('productDetailsModal')">&times;</span>
        <h2>Product Details</h2>
        <div class="product-modal-content">
            <div id="modalProductInfo" class="product-details-modal">
                <div class="product-image-container-modal">
                    <img id="modalProductImage" src="" alt="Product Image" class="product-image-modal">
                </div>
                <div class="product-info-modal">
                    <div class="editable-field">
                        <input type="text" id="modalProductNameEdit" class="edit-input product-name" style="display: none;">
                        <h3 id="modalProductName" class="product-name"></h3>
                        <i class='bx bx-edit edit-icon' onclick="toggleEdit('modalProductName')"></i>
                    </div>

                    <p id="modalProductGrade" class="product-grade"></p>

                    <!-- New field for product status display -->
                    <p id="modalProductStatus" class="product-grade">test</p>


                    <div class="editable-field">
                        <input type="number" id="modalProductPriceEdit" class="edit-input product-price" style="display: none;" step="0.01">
                        <p id="modalProductPrice" class="product-price"></p>
                        <i class='bx bx-edit edit-icon' onclick="toggleEdit('modalProductPrice')"></i>
                    </div>

                    <p id="modalProductStock" class="product-stock"></p>

                    <div class="editable-field">
                        <textarea id="modalProductDescriptionEdit" class="edit-input product-description" style="display: none;"></textarea>
                        <p id="modalProductDescription" class="product-description"></p>
                        <i class='bx bx-edit edit-icon' onclick="toggleEdit('modalProductDescription')"></i>
                    </div>


                    <div class="modal-actions" style="margin-top: 20px;">
                        <button id="saveChangesBtn" class="btn-action" onclick="saveProductChanges()" style="display: none;">
                            Save Changes
                        </button>
                        <button class="btn-action delete" onclick="deleteProduct()">
                            Delete Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo URLROOT; ?>/public/js/inventory_manager/products.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchBestSellingProducts(); // Fetch best selling products
        fetchLowStockProducts();     // Fetch low stock products
    });
</script>



</main>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>


