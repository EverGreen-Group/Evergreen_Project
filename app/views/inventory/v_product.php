<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/product.css" />
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">

<style>
  /* Existing styles... */
  
  .header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
  }

  .search-box {
    display: flex;
    align-items: center;
  }

  .search-box form {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .search-box input[type="text"] {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 200px;
  }

  .filter-btn {
    background: none;
    border: none;
    color: #555;
    cursor: pointer;
    padding: 0.5rem;
  }

  .filter-btn:hover {
    color: #333;
  }



  .head-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }
</style>

<main>

    <!-- Top nav bar -->


    <!-- Header Section -->
    <header>
    <div class="head-title">
      <div class="left">
          <h1>Inventory Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
              <li><i class='bx bx-chevron-right'></i></li>
              <li><a class="active" href="#">Inventory</a></li>
          </ul>
      </div>
  </div>
        <div class="header-actions">
            <div class="search-box">
                <form method="GET">
                    <input type="text" name="search" placeholder="Search by date...">
                    <button class="filter-btn">
                        <i class='bx bx-search-alt-2'></i>
                    </button>
                </form>
            </div>
            <a href="<?php echo URLROOT; ?>/inventory/createproduct">
                <button class="btn btn-primary">
                    <i class='bx bx-plus-circle'></i> Create New Product
                </button>
            </a>
        </div>
    </header>

    <ul class="box-info">
        <li>
            <i class='bx bx-package'></i>
            <span class="text">
                <p>Total Products</p>
                <h3><?php echo count($data['products']); ?></h3>
            </span>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-basket'></i>
                <div class="stat-info">
                    <h3><?php echo isset($data['totalInactive']) ? $data['totalInactive'] : 0;?></h3>
                    <p>Total Inactive</p>
                </div>
            </div>
        </li>

    </ul>

    <!-- Warehouse Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3><img src="<?php echo URLROOT; ?>/img/warehouse-svgrepo-com.svg" alt="Warehouse Icon"
                        style="width: 20px;"> Total Products</h3>
            </div>
            
            <div class="product-grid">
                <?php foreach ($data['products'] as $product): ?>
                    <div class="product-card"
                        onclick="openProductModal(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                        <div class="product-image">
                            <?php if (!empty($product->image_path)): ?>
                                <img src="<?php echo URLROOT; ?>/uploads/products/<?php echo $product->image_path; ?>"
                                    alt="<?php echo $product->product_name; ?>">
                            <?php else: ?>
                                <img src="<?php echo URLROOT; ?>/img/default-product.png" alt="Default Product Image">
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo $product->product_name; ?></h3>
                            <p>Quantity: <?php echo $product->quantity; ?>     <?php echo $product->unit; ?></p>
                            <p>Price: Rs.<?php echo $product->price; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>



    <!-- <div class="table-data">
            <div class="order">
                <div class="head">
                    <h2><img src="<?php echo URLROOT; ?>/img/warehouse-svgrepo-com.svg" alt="Warehouse Icon"
                    style="width: 24px; heignt:24px"> Total Products</h2>
                    <span class="product-info">(<?php echo count($data['products']); ?> products)</span>
                </div>
                <div class="product-grid">
                    <?php foreach ($data['products'] as $product): ?>
                        <div class="product-card"
                            onclick="openProductModal(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                            <div class="product-image">
                                <?php if (!empty($product->image_path)): ?>
                                    <img src="<?php echo URLROOT; ?>/uploads/products/<?php echo $product->image_path; ?>"
                                        alt="<?php echo $product->product_name; ?>">
                                <?php else: ?>
                                    <img src="<?php echo URLROOT; ?>/img/default-product.png" alt="Default Product Image">
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3><?php echo $product->product_name; ?></h3>
                                <p>Quantity: <?php echo $product->quantity; ?>     <?php echo $product->unit; ?></p>
                                <p>Price: Rs.<?php echo $product->price; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

 -->


    <!-- Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-header">
                <h2 id="modal-product-name"></h2>
            </div>
            <div class="modal-body">
                <div class="product-details-grid">
                    <div class="product-image-section">
                        <img id="modal-product-image" src="" alt="Product Image">
                    </div>
                    <div class="product-info-section">
                        <div class="info-group">
                            <label></label>
                            <span id="modal-product-code"></span>
                        </div>
                        <div class="info-group">
                            <label>Location:</label>
                            <span id="modal-product-location"></span>
                        </div>
                        <div class="info-group">
                            <label>Details:</label>
                            <p id="modal-product-details"></p>
                        </div>
                        <div class="info-group">
                            <label>Price:</label>
                            <span id="modal-product-price"></span>
                        </div>
                        <div class="info-group">
                            <label>Quantity:</label>
                            <div>
                                <span id="modal-product-quantity"></span> <span id="modal-product-unit"></span>
                            </div>
                        </div>
                        <div class="info-group">
                            <label>Unit:</label>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-update" onclick="updateProduct()" id="modal-update-btn">Update</button>
                <button class="btn-delete" onclick="deleteProduct()" id="modal-delete-btn">Delete</button>
            </div>
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

</main>
<?php require APPROOT . '/views/inc/components/footer.php' ?>

<script>
    let currentProductId = null;

    function openProductModal(product) {
        currentProductId = product.id;
        const modal = document.getElementById('productModal');

        // Update modal content
        document.getElementById('modal-product-name').textContent = product.product_name;
        document.getElementById('modal-product-code').textContent = product.code;
        document.getElementById('modal-product-location').textContent = product.location;
        document.getElementById('modal-product-details').textContent = product.details;
        document.getElementById('modal-product-price').textContent = 'Rs.' + product.price;
        document.getElementById('modal-product-quantity').textContent = product.quantity;
        document.getElementById('modal-product-unit').textContent = product.unit;

        // Set product image
        const imageUrl = product.image_path
            ? '<?php echo URLROOT; ?>/uploads/products/' + product.image_path
            : '<?php echo URLROOT; ?>/img/default-product.png';
        document.getElementById('modal-product-image').src = imageUrl;

        // Show modal
        modal.style.display = "block";
    }

    // Close modal when clicking the X
    document.querySelector('.close').onclick = function () {
        document.getElementById('productModal').style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('productModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function updateProduct() {
        if (currentProductId) {
            window.location.href = '<?php echo URLROOT; ?>/inventory/updateproduct/' + currentProductId;
        }
    }

    function deleteProduct() {
        if (currentProductId && confirm('Are you sure you want to delete this product?')) {
            window.location.href = '<?php echo URLROOT; ?>/inventory/deleteproduct/' + currentProductId;
        }
    }
</script>