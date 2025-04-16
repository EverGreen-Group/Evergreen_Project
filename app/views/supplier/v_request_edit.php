<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Sidebar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>

<!-- Top Navbar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
  <!-- Page Header -->
  <div class="head-title">
    <div class="left">
      <h1>Edit Fertilizer Request</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Home</a>
        </li>
        <li>
          <i class='bx bx-chevron-right'></i>
        </li>
        <li>
        <span>Fertilizer Requests</span>
        </li>        
        <li>
          <i class='bx bx-chevron-right'></i>
        </li>
        <li>
          <span>Edit Fertilizer Request</span>
        </li>
      </ul>
    </div>
  </div>

  <!-- Order Details Card -->
  <div class="order-card">
    <div class="card-header">
      <h2>Current Order Details</h2>
    </div>
    <div class="card-body">
        <div class="order-details-grid">
            <div class="detail-item">
              <span class="label">Order ID:</span>
              <span class="value"><?php echo $data['order']->order_id; ?></span>
            </div>
            <div class="detail-item">
              <span class="label">Fertilizer Name:</span>
              <span class="value"><?php echo $data['order']->fertilizer_name; ?></span>
            </div>
            <div class="detail-item">
              <span class="label">Quantity:</span>
              <span class="value"><?php echo $data['order']->quantity; ?></span>
            </div>
            <div class="detail-item">
              <span class="label">Total Price:</span>
              <span class="value"><?php echo $data['order']->total_amount; ?></span>
            </div>
        </div>
    </div>
  </div>

  <!-- Edit Fertilizer Request Form Card -->
  <div class="edit-form-card">
    <div class="card-header">
      <h2>Update Request</h2>
    </div>
    <div class="card-body">
      <form id="fertilizer-request-form" method="POST" action="<?php echo URLROOT . '/supplier/editFertilizerRequest/' . $data['order']->order_id; ?>">
        
      <div class="form-group">
          <label for="fertilizer_id">Fertilizer Name:</label>
          <input type="text" id="fertilizer_name" name="fertilizer_name" value="<?php echo $data['order']->fertilizer_name; ?>" readonly>          
        </div>

        <div class="form-group">
          <label for="total_amount">Quantity:</label>
          <input type="number" id="quantity" name="quantity" max="100" min="1" value="<?php echo $data['order']->quantity; ?>" required onchange="updateTotalPrice()">
        </div>

        <div class="form-group read-only-group">
            <label for="price_per_unit">Price Per Unit:</label>
            <input type="text" id="price_per_unit" name="price_per_unit" readonly>
        </div>
        <div class="form-group read-only-group">
            <label for="total_price">Total Price:</label>
            <input type="text" id="total_price" name="total_price" readonly>
        </div>

        <div class="form-group">
          <button type="submit" class="submit-btn">Update Request</button>
        </div>

      </form>
    </div>
  </div>
</main>

<div id="notification" class="notification" style="display: none;"></div>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>

<script>
  function updateTotalPrice() {
      const fertilizerSelect = document.getElementById('fertilizer_id');
      const quantityInput = document.getElementById('quantity');
      const pricePerUnitInput = document.getElementById('price_per_unit');
      const totalPriceInput = document.getElementById('total_price');

      // Get selected option
      const selectedOption = fertilizerSelect.options[fertilizerSelect.selectedIndex];

      // Check if a valid option is selected
      if (!selectedOption || selectedOption.value === "") {
        pricePerUnitInput.value = '';
        totalPriceInput.value = '';
        return;
      }

      // Get price
      const price = parseFloat(selectedOption.getAttribute('data-price'));
      const quantity = parseFloat(quantityInput.value);

      if (!isNaN(price)) {
        pricePerUnitInput.value = price.toFixed(2);

        if (!isNaN(quantity)) {
          totalPriceInput.value = (price * quantity).toFixed(2);
        } else {
          totalPriceInput.value = '';
        }
      } else {
        pricePerUnitInput.value = '';
        totalPriceInput.value = '';
      }
    }

    function confirmDelete(orderId) {
      if (confirm("Are you sure you want to delete order #" + orderId + "?")) {
        window.location.href = '<?php echo URLROOT; ?>/Supplier/deleteFertilizerRequest/' + orderId;
      }
    }

    document.getElementById('fertilizer-request-form').addEventListener('submit', function(e) {
      const fertilizerSelect = document.getElementById('fertilizer_id');
      const quantityInput = document.getElementById('quantity');
      const totalPriceInput = document.getElementById('total_price');
      
      if (fertilizerSelect.value === "") {
          e.preventDefault();
          alert("Please select a fertilizer type");
          return false;
      }
      
      const quantity = parseFloat(quantityInput.value);
      if (isNaN(quantity) || quantity <= 0 || quantity > 100) {
          e.preventDefault();
          alert("Please enter a valid quantity between 1 and 100");
          return false;
      }
      
      // Ensure price calculation was performed
      if (totalPriceInput.value === "") {
          e.preventDefault();
          updateTotalPrice(); // Recalculate price
          if (totalPriceInput.value === "") {
              alert("Unable to calculate total price. Please check your inputs.");
              return false;
          }
      }
      
      return true;
  });
</script>

<style>
  /* Root Variables */
  :root {
    --primary-color: var(--mainn);
    --secondary-color: #2ecc71;
    --text-primary: #2c3e50;
    --text-secondary: #7f8c8d;
    --background-light: #f8f9fa;
    --border-color: #e0e0e0;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --border-radius-sm: 4px;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
  }

  /* Main Layout */
  main {
    padding: var(--spacing-lg);
    max-width: 1200px;
    margin: 0 auto;
  }

  .head-title {
    margin-bottom: var(--spacing-xl);
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }
  .head-title h1 {
    color: var(--text-primary);
    font-size: 1.75rem;
    margin-bottom: var(--spacing-sm);
  }
  .breadcrumb {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    list-style: none;
    padding: 0;
  }
  .breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
  }
  .breadcrumb i {
    color: var(--primary-color);
  }

  /* Order Details Card */
  .order-card,
  .edit-form-card {
    background-color: white;
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-bottom: var(--spacing-xl);
  }
  .order-card .card-header,
  .edit-form-card .card-header {
    border-bottom: 1px solid var(--border-color);
    padding-bottom: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
  }
  .order-card .card-header h2,
  .edit-form-card .card-header h2 {
    margin: 0;
    font-size: 1.5rem;
    color: var(--text-primary);
  }
  .order-card .card-body,
  .edit-form-card .card-body {
    padding-top: var(--spacing-md);
  }

  .order-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
  }
  .order-details-grid .detail-item {
    display: flex;
    flex-direction: column;
  }
  .order-details-grid .detail-item .label {
    font-weight: bold;
    color: var(--text-secondary);
    margin-bottom: var(--spacing-xs);
  }
  .order-details-grid .detail-item .value {
    color: var(--text-primary);
    font-size: 1.1rem;
  }

  /* Edit Form Styles */
  .edit-form-card form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
  }
  .edit-form-card .form-group {
    display: flex;
    flex-direction: column;
  }
  .edit-form-card label {
    margin-bottom: var(--spacing-xs);
    color: var(--text-primary);
  }
  .edit-form-card input[type="number"],
  .edit-form-card select {
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
  }
  .edit-form-card .submit-btn {
    grid-column: span 2;
    padding: var(--spacing-md);
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
  }
  .edit-form-card .submit-btn:hover {
    background-color: var(--secondary-color);
  }

  @media (max-width: 768px) {
    .edit-form-card form {
      grid-template-columns: 1fr;
    }
  }

  .read-only-group input {
    background-color: var(--background-light);
    cursor: not-allowed;
  }

  .edit-form-card input[type="text"],
  .edit-form-card input[type="number"],
  .edit-form-card select {
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    font-size: 1rem;
    height: 40px; 
    width: 100%;
    box-sizing: border-box;
  }

  .edit-form-card input[readonly] {
    background-color: var(--background-light);
    cursor: not-allowed;
    color: var(--text-secondary);
  }

  .edit-form-card .button-group {
    grid-column: span 2;
  }

  .edit-form-card form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
  }

  @media (max-width: 768px) {
    .edit-form-card form {
      grid-template-columns: 1fr;
    }
    
    .edit-form-card .button-group {
      grid-column: 1;
    }
  }
</style>
