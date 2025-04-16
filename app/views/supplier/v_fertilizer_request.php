<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/fertilizer_requests/styles.css">

<main>
  <!-- Page Header -->
  <div class="head-title">
    <div class="left">
      <h1>Fertilizer Requests</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Fertilizer Requests</span>
        </li>
      </ul>
    </div>
  </div>

  <!-- Flash Message Section -->
  <?php if(isset($_SESSION['fertilizer_message'])): ?>
    <div class="alert-container">
      <div class="alert <?php echo isset($_SESSION['fertilizer_message_class']) ? $_SESSION['fertilizer_message_class'] : 'alert-success'; ?>">
        <div class="alert-icon">
          <i class='bx <?php echo (strpos($_SESSION['fertilizer_message_class'] ?? '', 'danger') !== false) ? 'bx-x-circle' : 'bx-check-circle'; ?>'></i>
        </div>
        <div class="alert-content">
          <?php echo $_SESSION['fertilizer_message']; ?>
        </div>
        <button class="alert-close" onclick="this.parentElement.style.display='none';">
          <i class='bx bx-x'></i>
        </button>
      </div>
    </div>
    <?php unset($_SESSION['fertilizer_message']); ?>
    <?php unset($_SESSION['fertilizer_message_class']); ?>
  <?php endif; ?>

  <!-- New Fertilizer Request Form Section -->
  <div class="request-form-section">
    <div class="section-header">
      <h3>New Fertilizer Request</h3>
    </div>
    <div class="request-form-card">
      <!-- Note: The form action has been updated to match the old version’s endpoint -->
      <form id="fertilizer-request-form" action="<?php echo URLROOT; ?>/Supplier/createFertilizerOrder" method="post">
        <!-- Add hidden supplier_id field -->
        <input type="hidden" name="supplier_id" value="<?php echo $_SESSION['supplier_id']; ?>">
        
        <div class="form-group">
            <label for="type_id">Fertilizer Type:</label>
            <select id="type_id" name="type_id" required onchange="updatePricePerUnit()">
                <option value="">-- Select Fertilizer --</option>
                <?php foreach($data['fertilizer_types'] as $type): ?>
                    <option value="<?php echo $type->type_id; ?>"
                        data-price-kg="<?php echo $type->unit_price_kg; ?>"
                        data-price-pack="<?php echo $type->unit_price_packs; ?>"
                        data-price-box="<?php echo $type->unit_price_box; ?>">
                        <?php echo $type->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="unit">Unit:</label>
            <select id="unit" name="unit" required onchange="updatePricePerUnit()">
                <option value="">-- Select Unit --</option>
                <option value="kg">Kilograms (kg)</option>
                <option value="packs">Packs</option>
                <option value="box">Box</option>
            </select>
        </div>
        <div class="form-group">
            <label for="total_amount">Amount:</label>
            <input type="number" id="total_amount" name="total_amount" min="1" required oninput="updateTotalPrice()">
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
            <button type="submit" class="submit-btn">Submit Request</button>
        </div>
    </form>
    </div>
  </div>

  <!-- Fertilizer Request History Section -->
  <div class="request-history-section">
    <div class="section-header">
      <h3>Fertilizer Request History</h3>
    </div>
    <?php if (!empty($data['orders'])): ?>
      <?php foreach($data['orders'] as $order): ?>
        <div class="schedule-card">
          <div class="card-content">
            <div class="card-header">
              <div class="status-badge">
                Order #<?php echo $order->order_id; ?>
              </div>
            </div>
            <div class="card-body">
              <div class="schedule-info">
                <div class="info-item">
                  <i class='bx bx-box'></i>
                  <span>Fertilizer: <?php echo $order->fertilizer_name; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-calendar'></i>
                  <span>Date: <?php echo date('m/d/Y', strtotime($order->order_date)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-time-five'></i>
                  <span>Time: <?php echo $order->order_time; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-calculator'></i>
                  <span>Amount: <?php echo number_format($order->quantity) . ' ' . ($order->unit ?? ''); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-dollar'></i>
                  <span>Price: <?php echo 'රු.' . number_format($order->total_amount, 2); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-check-circle'></i>
                  <span>Status: <?php echo isset($order->payment_status) ? $order->payment_status : 'Pending'; ?></span>
                </div>
              </div>
              <!-- Action buttons -->
              <div class="schedule-action">
                <button class="update-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $order->order_id; ?>'">
                  <i class='bx bx-edit'></i>
                </button>
                <button class="cancel-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/deleteFertilizerRequest/<?php echo $order->order_id; ?>'">
                  <i class='bx bx-trash'></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-schedule">
        <p>No fertilizer requests found.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Auto-hide success message and refresh page -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Look for alert elements with success class
    const successAlerts = document.querySelectorAll('.alert.alert-success');
    
    // Check if we found any success alerts
    if (successAlerts.length > 0) {
      console.log('Success alert found, setting timeout');
      
      // Set timeout to refresh after 3 seconds
      setTimeout(function() {
        console.log('Timeout triggered, refreshing page');
        window.location.reload();
      }, 3000);
    }
  });
</script>

</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<script>
  // Update Price Per Unit based on the selected fertilizer and unit
  function updatePricePerUnit() {
    const fertilizerSelect = document.getElementById('type_id');
    const unitSelect = document.getElementById('unit');
    const pricePerUnitInput = document.getElementById('price_per_unit');

    const selectedOption = fertilizerSelect.options[fertilizerSelect.selectedIndex];
    if (!selectedOption || selectedOption.value === "") {
      pricePerUnitInput.value = '';
      updateTotalPrice();
      return;
    }

    const unit = unitSelect.value;
    let price = 0;
    
    if (unit === "kg") {
      price = parseFloat(selectedOption.getAttribute('data-price-kg'));
    } else if (unit === "packs") {
      price = parseFloat(selectedOption.getAttribute('data-price-pack'));
    } else if (unit === "box") {
      price = parseFloat(selectedOption.getAttribute('data-price-box'));
    } else {
      price = 0;
    }
    
    if (!isNaN(price)) {
      pricePerUnitInput.value = price.toFixed(2);
    } else {
      pricePerUnitInput.value = '';
    }
    
    updateTotalPrice();
  }

  // Update total price based on amount and price per unit
  function updateTotalPrice() {
    const amount = parseFloat(document.getElementById('total_amount').value);
    const pricePerUnit = parseFloat(document.getElementById('price_per_unit').value);
    const totalPriceInput = document.getElementById('total_price');
    
    if (!isNaN(amount) && !isNaN(pricePerUnit)) {
      totalPriceInput.value = (amount * pricePerUnit).toFixed(2);
    } else {
      totalPriceInput.value = '';
    }
  }

</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>


<style>
/* Existing Styles */
:root {
    --primary-color: var(--mainn, #28a745);
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

main {
    padding: var(--spacing-lg);
    max-width: 1200px;
    margin: 0 auto;
    transition: margin-left 0.3s ease;
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

.section-divider {
    height: 1px;
    background-color: var(--border-color);
    margin: var(--spacing-xl) 0;
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background-color: #2c3e50;
    transition: transform 0.3s ease;
    z-index: 1000;
}

.sidebar.hidden {
    transform: translateX(-250px);
}

main.full-width {
    margin-left: 0 !important;
}

.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}

.toast {
    padding: 10px 20px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 250px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
}

.toast-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.toast-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.toast-message {
    flex-grow: 1;
}

.close-btn {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}

.close-btn-success {
    color: #155724;
}

.close-btn-error {
    color: #721c24;
}

.alert {
    padding: 10px 20px;
    border-radius: 5px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert .close-btn {
    background: none;
    border: none;
    color: inherit;
    font-size: 16px;
    cursor: pointer;
}

.message-box {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
}

.message-box-content {
    background: #2c2c2c;
    color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 90%;
}

.message-box-content h3 {
    margin: 0 0 1rem;
    font-size: 1.2rem;
    color: #ff6f61;
}

.message-box-content p {
    margin: 0 0 1.5rem;
    font-size: 1rem;
}

.message-box-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.message-box-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.message-box-ok {
    background-color: #ff6f61;
    color: white;
}

.message-box-ok:hover {
    background-color: #e65b50;
}

.message-box-cancel {
    background-color: #6c4b47;
    color: white;
}

.message-box-cancel:hover {
    background-color: #5a3e3b;
}

/* Fertilizer Products Section */
.products-section {
    margin-bottom: var(--spacing-xl);
}

.products-section .section-header {
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.products-section .section-header h3 {
    margin: 0;
    font-size: 1.5rem;
    color: var(--text-primary);
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: var(--spacing-lg);
}

.product-card {
    background-color: white;
    border-radius: var(--border-radius-md);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: var(--spacing-md);
    text-align: center;
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-card img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin-bottom: var(--spacing-sm);
}

.product-card h4 {
    margin: 0 0 var(--spacing-sm);
    font-size: 1.1rem;
    color: var(--text-primary);
}

.product-card p {
    margin: 0 0 var(--spacing-sm);
    color: var(--text-secondary);
}

.product-card .price {
    font-weight: bold;
    color: var(--primary-color);
}

.product-card .unit-options {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    justify-content: center;
    margin: var(--spacing-sm) 0;
}

.product-card .unit-options select {
    padding: 0.25rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
}

.product-card .add-to-cart-btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.product-card .add-to-cart-btn:hover {
    background-color: var(--secondary-color);
}

/* Updated Cart Section Styles */
.cart-section {
    margin-bottom: var(--spacing-xl);
}

.cart-section .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.cart-section .section-header h3 {
    margin: 0;
    font-size: 1.5rem;
    color: var(--text-primary);
}

.cart-section .clear-cart-btn {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: underline;
}

.cart-section .clear-cart-btn:hover {
    color: #c82333;
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.cart-item {
    display: flex;
    align-items: center;
    background-color: white;
    border-radius: var(--border-radius-md);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: var(--spacing-md);
}

.cart-item img {
    width: 60px;
    height: 80px;
    object-fit: cover;
    margin-right: var(--spacing-md);
}

.cart-item-details {
    flex: 1;
}

.cart-item-details h4 {
    margin: 0 0 0.25rem;
    font-size: 1.1rem;
    color: var(--text-primary);
}

.cart-item-details p {
    margin: 0 0 0.25rem;
    color: var(--text-secondary);
}

.cart-item-details .price {
    font-weight: bold;
    color: var(--primary-color);
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0 var(--spacing-md);
}

.cart-item-quantity button {
    background-color: #e0e0e0;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-item-quantity button:hover {
    background-color: #d0d0d0;
}

.cart-item-quantity span {
    font-size: 1rem;
    color: var(--text-primary);
}

.cart-item-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.cart-item-actions a {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9rem;
}

.cart-item-actions a:hover {
    text-decoration: underline;
}

.cart-item-actions .remove-btn {
    color: #dc3545;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
}

.cart-item-actions .remove-btn:hover {
    color: #c82333;
}

.cart-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    background-color: white;
    border-radius: var(--border-radius-md);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    margin-top: var(--spacing-md);
}

.cart-summary p {
    margin: 0;
    font-weight: bold;
    color: var(--text-primary);
}

.cart-summary .item-count {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.cart-summary .checkout-btn {
    background-color: #e6f0fa;
    color: #007bff;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius-md);
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.cart-summary .checkout-btn:hover {
    background-color: #d0e0f5;
}

.no-items {
    background-color: white;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.no-items p {
    margin: 0;
    color: #7f8c8d;
}

/* Request Form Section */
.request-form-section {
    margin-bottom: var(--spacing-xl);
}

.request-form-card {
    background-color: white;
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.request-form-card form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
}

.request-form-card .form-group {
    display: flex;
    flex-direction: column;
}

.request-form-card label {
    margin-bottom: var(--spacing-xs);
    color: var(--text-primary);
}

.request-form-card input[type="text"],
.request-form-card input[type="number"],
.request-form-card select {
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
}

.read-only-group input {
    background-color: var(--background-light);
    cursor: not-allowed;
}

.request-form-card .submit-btn {
    grid-column: span 2;
    padding: var(--spacing-md);
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    font-size: 1rem;
}

.request-form-card .submit-btn:hover {
    background-color: var(--secondary-color);
}

/* Request History Section */
.request-history-section {
    margin-bottom: var(--spacing-xl);
}

.schedule-card {
    background-color: white;
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: var(--spacing-md);
    display: flex;
    flex-direction: column;
}

.card-content {
    flex: 1;
}

.card-header {
    margin-bottom: var(--spacing-md);
}

.status-badge {
    display: inline-block;
    background-color: var(--primary-color);
    color: white;
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    font-size: 0.875rem;
}

.card-body {
    margin-top: var(--spacing-md);
}

.schedule-info {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-md);
}

.info-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    flex: 1;
    min-width: 200px;
}

.info-item i {
    color: var(--primary-color);
}

.schedule-action {
    display: flex;
    gap: var(--spacing-md);
}

.update-btn,
.cancel-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.5rem;
    color: var(--primary-color);
}

.update-btn:hover {
    color: var(--secondary-color);
}

.cancel-btn:hover {
    color: var(--warning-color);
}

/* Checkout Section Styles */
.checkout-section {
    margin-bottom: 2rem;
}

.checkout-section .section-header {
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.checkout-section .section-header h3 {
    margin: 0;
    font-size: 1.5rem;
    color: #2c3e50;
}

.checkout-section .request-form-card {
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.checkout-section form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.checkout-section .form-group {
    display: flex;
    flex-direction: column;
}

.checkout-section label {
    margin-bottom: 0.25rem;
    color: #2c3e50;
    font-weight: 500;
}

.checkout-section input[type="text"],
.checkout-section input[type="date"] {
    padding: 0.5rem;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 1rem;
}

.checkout-section input[type="date"] {
    color: #7f8c8d;
}

.checkout-section .submit-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 0.75rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
    grid-column: span 2;
}

.checkout-section .submit-btn:hover {
    background-color: #218838;
}

@media (max-width: 768px) {
    .request-form-card form,
    .checkout-section form {
        grid-template-columns: 1fr;
    }
    .schedule-info {
        flex-direction: column;
    }
  }

  /* Alert Styles */
  .alert-container {
    margin-bottom: var(--spacing-lg);
  }

  .alert {
    display: flex;
    align-items: center;
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    animation: slideDown 0.3s ease-out;
  }

  .alert-success {
    background-color: rgba(46, 204, 113, 0.15);
    border-left: 4px solid #2ecc71;
    color: #27ae60;
  }

  .alert-danger {
    background-color: rgba(231, 76, 60, 0.15);
    border-left: 4px solid #e74c3c;
    color: #c0392b;
  }

  .alert-warning {
    background-color: rgba(243, 156, 18, 0.15);
    border-left: 4px solid #f39c12;
    color: #d35400;
  }

  .alert-icon {
    font-size: 1.5rem;
    margin-right: var(--spacing-md);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .alert-content {
    flex: 1;
    font-size: 1rem;
    line-height: 1.4;
  }

  .alert-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    font-size: 1.25rem;
    opacity: 0.7;
    transition: opacity 0.2s;
  }

  .alert-close:hover {
    opacity: 1;
  }

  @keyframes slideDown {
    from {
      transform: translateY(-20px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
</style>

<main>
    <div class="head-title">
        <div class="left">
            <button id="toggleSidebar" class="toggle-sidebar-btn">
                <i class='bx bx-menu'></i>
            </button>
            <h1>Fertilizer Requests</h1>
            <ul class="breadcrumb">
                <li>
                    <i class='bx bx-home'></i>
                    <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
                </li>
                <li>
                    <span>Fertilizer Requests</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Message Box -->
    <div class="message-box" id="messageBox" style="display: none;">
        <div class="message-box-content">
            <h3 id="messageBoxTitle"></h3>
            <p id="messageBoxMessage"></p>
            <div class="message-box-actions">
                <button id="messageBoxOk" class="message-box-btn message-box-ok">OK</button>
                <button id="messageBoxCancel" class="message-box-btn message-box-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php flash('message'); ?>

    <!-- Fertilizer Products Section -->
    <div class="products-section">
        <div class="section-header">
            <h3>Available Fertilizers</h3>
        </div>
        <div class="products-grid">
            <?php foreach($data['fertilizer_types'] as $fertilizer): ?>
                <div class="product-card">
                    <img src="<?php echo URLROOT; ?>/public/img/fertilizer-placeholder.png" alt="<?php echo htmlspecialchars($fertilizer->name); ?>">
                    <h4><?php echo htmlspecialchars($fertilizer->name); ?></h4>
                    <div class="unit-options">
                        <select class="unit-select" data-fertilizer-id="<?php echo $fertilizer->type_id; ?>">
                            <option value="kg" data-price="<?php echo $fertilizer->unit_price_kg; ?>">kg (Rs. <?php echo number_format($fertilizer->unit_price_kg, 2); ?>)</option>
                            <option value="packs" data-price="<?php echo $fertilizer->unit_price_packs; ?>">Packs (Rs. <?php echo number_format($fertilizer->unit_price_packs, 2); ?>)</option>
                            <option value="box" data-price="<?php echo $fertilizer->unit_price_box; ?>">Box (Rs. <?php echo number_format($fertilizer->unit_price_box, 2); ?>)</option>
                        </select>
                    </div>
                    <button class="add-to-cart-btn" onclick="addToCart(<?php echo $fertilizer->type_id; ?>)">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Request Form Section -->
    <div class="request-form-section">
        <div class="section-header">
            <h3>New Fertilizer Request</h3>
        </div>
        <div class="request-form-card">
            <form id="fertilizer-request-form" action="<?php echo URLROOT; ?>/Supplier/requestFertilizer" method="post">
                <input type="hidden" name="action" value="create_order">
                <div class="form-group">
                    <label for="fertilizer">Fertilizer Type:</label>
                    <select id="fertilizer" name="fertilizer" required onchange="updateUnitOptions()">
                        <option value="">-- Select Fertilizer --</option>
                        <?php foreach($data['fertilizer_types'] as $type): ?>
                            <option value="<?php echo $type->type_id; ?>"
                                data-price-kg="<?php echo $type->unit_price_kg; ?>"
                                data-price-packs="<?php echo $type->unit_price_packs; ?>"
                                data-price-box="<?php echo $type->unit_price_box; ?>"
                                data-quantity="<?php echo $type->available_quantity; ?>">
                                <?php echo $type->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="unit_type">Unit Type:</label>
                    <select id="unit_type" name="unit_type" required onchange="updateTotalAmount()">
                        <option value="">-- Select Unit --</option>
                        <option value="kg">kg</option>
                        <option value="packs">Packs</option>
                        <option value="box">Box</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="1" required oninput="updateTotalAmount()">
                </div>
                <div class="form-group read-only-group">
                    <label for="total_amount">Total Amount (Rs.):</label>
                    <input type="text" id="total_amount" name="total_amount" readonly>
                </div>
                <div class="form-group">
                    <button type="submit" class="submit-btn">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Request History Section -->
    <div class="request-history-section">
        <div class="section-header">
            <h3>Fertilizer Request History</h3>
        </div>
        <?php if (!empty($data['orders'])): ?>
            <?php foreach($data['orders'] as $order): ?>
                <div class="schedule-card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="status-badge">
                                Order #<?php echo htmlspecialchars($order->order_id); ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="schedule-info">
                                <div class="info-item">
                                    <i class='bx bx-box'></i>
                                    <span>Fertilizer: <?php echo htmlspecialchars($order->name); ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-box'></i>
                                    <span>Unit Type: <?php echo htmlspecialchars($order->unit_type); ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-calendar'></i>
                                    <span>Date: <?php echo date('m/d/Y', strtotime($order->created_at)); ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-time-five'></i>
                                    <span>Time: <?php echo date('H:i:s', strtotime($order->created_at)); ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-calculator'></i>
                                    <span>Quantity: <?php echo htmlspecialchars($order->quantity); ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-dollar'></i>
                                    <span>Total Amount: Rs. <?php echo number_format($order->total_amount, 2); ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-check-circle'></i>
                                    <span>Status: <?php echo htmlspecialchars($order->status); ?></span>
                                </div>
                                <div class="info-item">
                                    <i class='bx bx-check-circle'></i>
                                    <span>Payment Status: <?php echo htmlspecialchars($order->payment_status); ?></span>
                                </div>
                                <?php if ($order->delivery_date): ?>
                                    <div class="info-item">
                                        <i class='bx bx-calendar'></i>
                                        <span>Delivery Date: <?php echo date('m/d/Y', strtotime($order->delivery_date)); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="schedule-action">
                                <?php if ($order->status === 'Pending'): ?>
                                    <button class="update-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $order->order_id; ?>'">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <button class="cancel-btn" onclick="showMessageBox('delete', 'Are you sure you want to delete this order?', () => deleteOrder(<?php echo $order->order_id; ?>))">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                <?php endif; ?>
                                <?php if ($order->status === 'Accepted'): ?>
                                    <button class="finalize-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/finalizeFertilizerOrder/<?php echo $order->order_id; ?>'">
                                        <i class='bx bx-check'></i> Finalize
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-schedule">
                <p>No fertilizer requests found.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Cart Section -->
    <div class="cart-section">
        <div class="section-header">
            <h3>Your Cart (Accepted Requests)</h3>
            <?php if (!empty($data['cart_items'])): ?>
                <button class="clear-cart-btn" onclick="showMessageBox('delete', 'Are you sure you want to clear your cart?', clearCart)">Remove all</button>
            <?php endif; ?>
        </div>
        <?php if (!empty($data['cart_items'])): ?>
            <div class="cart-items">
                <?php foreach($data['cart_items'] as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo URLROOT; ?>/img/fertilizer-placeholder.png" alt="<?php echo htmlspecialchars($item->name); ?>">
                        <div class="cart-item-details">
                            <h4><?php echo htmlspecialchars($item->name); ?></h4>
                            <p><?php echo htmlspecialchars($item->unit_type); ?></p>
                            <p class="price">Rs. <?php echo number_format($item->price * $item->quantity, 2); ?></p>
                        </div>
                        <div class="cart-item-quantity">
                            <button onclick="updateCartQuantity(<?php echo $item->cart_id; ?>, <?php echo $item->quantity - 1; ?>)">-</button>
                            <span><?php echo htmlspecialchars($item->quantity); ?></span>
                            <button onclick="updateCartQuantity(<?php echo $item->cart_id; ?>, <?php echo $item->quantity + 1; ?>)">+</button>
                        </div>
                        <div class="cart-item-actions">
                            <a href="#">Save for later</a>
                            <button class="remove-btn" onclick="showMessageBox('delete', 'Are you sure you want to remove this item from your cart?', () => removeFromCart(<?php echo $item->cart_id; ?>))">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-summary">
                <div>
                    <p>Sub-Total</p>
                    <p class="item-count"><?php echo count($data['cart_items']); ?> items</p>
                </div>
                <p>Rs. <?php echo number_format($cart_total + 500 + ($cart_total * 0.10), 2); ?></p>
                <button class="checkout-btn" onclick="document.getElementById('checkout-section').scrollIntoView({ behavior: 'smooth' })">Checkout</button>
            </div>
        <?php else: ?>
            <div class="no-items">
                <p>No accepted requests in your cart. Wait for the inventory manager to accept your requests.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Checkout Section -->
    <?php if (!empty($data['cart_items'])): ?>
        <div class="checkout-section" id="checkout-section">
            <div class="section-header">
                <h3>Checkout</h3>
            </div>
            <div class="request-form-card">
                <form action="<?php echo URLROOT; ?>/Supplier/requestFertilizer" method="post">
                    <input type="hidden" name="action" value="checkout">
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($data['supplier_application']->user_id == 37 ? 'Bruce Wayne' : ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" required value="">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required value="<?php echo htmlspecialchars($data['supplier_application']->address ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" required value="Colombo">
                    </div>
                    <div class="form-group">
                        <label for="postal_code">Postal Code:</label>
                        <input type="text" id="postal_code" name="postal_code" required value="">
                    </div>
                    <div class="form-group">
                        <label for="delivery_date">Delivery Date:</label>
                        <input type="date" id="delivery_date" name="delivery_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="submit-btn">Place Order</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
function showToast(message, type = 'error') {
    const toastContainer = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    const closeBtnClass = type === 'success' ? 'close-btn-success' : 'close-btn-error';
    toast.innerHTML = `
        <span class="toast-message">${message}</span>
        <button class="close-btn ${closeBtnClass}" onclick="this.parentElement.remove()">×</button>
    `;
    toastContainer.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

let messageBoxCallback = null;

function showMessageBox(title, message, callback) {
    const messageBox = document.getElementById('messageBox');
    const messageBoxTitle = document.getElementById('messageBoxTitle');
    const messageBoxMessage = document.getElementById('messageBoxMessage');
    const messageBoxOk = document.getElementById('messageBoxOk');
    const messageBoxCancel = document.getElementById('messageBoxCancel');

    messageBoxTitle.textContent = title.toUpperCase() + ' SAYS';
    messageBoxMessage.textContent = message;
    messageBoxCallback = callback;

    messageBox.style.display = 'flex';

    messageBoxOk.onclick = () => {
        if (messageBoxCallback) messageBoxCallback();
        messageBox.style.display = 'none';
        messageBoxCallback = null;
    };

    messageBoxCancel.onclick = () => {
        messageBox.style.display = 'none';
        messageBoxCallback = null;
    };
}

document.getElementById('toggleSidebar').addEventListener('click', function() {
    const sidebar = document.querySelector('.sidebar');
    const main = document.querySelector('main');
    sidebar.classList.toggle('hidden');
    main.classList.toggle('full-width');
});

function updateUnitOptions() {
    const fertilizerSelect = document.getElementById('fertilizer');
    const unitSelect = document.getElementById('unit_type');
    const selectedOption = fertilizerSelect.options[fertilizerSelect.selectedIndex];

    if (!selectedOption || selectedOption.value === "") {
        unitSelect.disabled = true;
        unitSelect.value = "";
        updateTotalAmount();
        return;
    }

    unitSelect.disabled = false;
    updateTotalAmount();
}

function updateTotalAmount() {
    const fertilizerSelect = document.getElementById('fertilizer');
    const unitSelect = document.getElementById('unit_type');
    const quantityInput = document.getElementById('quantity');
    const totalAmountInput = document.getElementById('total_amount');

    const selectedOption = fertilizerSelect.options[fertilizerSelect.selectedIndex];
    if (!selectedOption || selectedOption.value === "" || !unitSelect.value) {
        totalAmountInput.value = '';
        return;
    }

    const unitType = unitSelect.value;
    const price = parseFloat(selectedOption.getAttribute(`data-price-${unitType}`));
    const quantity = parseFloat(quantityInput.value);
    const availableQuantity = parseFloat(selectedOption.getAttribute('data-quantity'));

    if (!isNaN(quantity) && quantity > availableQuantity) {
        showToast(`Requested quantity exceeds available stock. Only ${availableQuantity} available.`, 'error');
        quantityInput.value = availableQuantity;
    }

    if (!isNaN(quantity) && !isNaN(price)) {
        totalAmountInput.value = (quantity * price).toFixed(2);
    } else {
        totalAmountInput.value = '';
    }
}

document.getElementById('fertilizer-request-form').addEventListener('submit', function(event) {
    const fertilizerSelect = document.getElementById('fertilizer');
    const unitSelect = document.getElementById('unit_type');
    if (!fertilizerSelect.value) {
        event.preventDefault();
        showToast('Please select a fertilizer type.', 'error');
    }
    if (!unitSelect.value) {
        event.preventDefault();
        showToast('Please select a unit type.', 'error');
    }
});

function deleteOrder(orderId) {
    fetch(`<?php echo URLROOT; ?>/Supplier/checkFertilizerOrderStatus/${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.canDelete) {
                fetch(`<?php echo URLROOT; ?>/Supplier/deleteFertilizerRequest/${orderId}`, {
                    method: 'POST'
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showToast(result.message, 'success');
                            location.reload();
                        } else {
                            showToast(result.message, 'error');
                        }
                    })
                    .catch(error => {
                        showToast('An error occurred while deleting the order.', 'error');
                        console.error(error);
                    });
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('An error occurred while checking the order status.', 'error');
            console.error(error);
        });
}

function addToCart(fertilizerId) {
    const unitSelect = document.querySelector(`.unit-select[data-fertilizer-id="${fertilizerId}"]`);
    const unitType = unitSelect.value;

    fetch(`<?php echo URLROOT; ?>/Supplier/addToCart/${fertilizerId}/${unitType}`, {
        method: 'POST'
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast(result.message, 'success');
                location.reload();
            } else {
                showToast(result.message, 'error');
            }
        })
        .catch(error => {
            showToast('An error occurred while adding to cart.', 'error');
            console.error(error);
        });
}

function removeFromCart(cartId) {
    fetch(`<?php echo URLROOT; ?>/Supplier/removeFromCart/${cartId}`, {
        method: 'POST'
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast(result.message, 'success');
                location.reload();
            } else {
                showToast(result.message, 'error');
            }
        })
        .catch(error => {
            showToast('An error occurred while removing the item.', 'error');
            console.error(error);
        });
}

function updateCartQuantity(cartId, quantity) {
    if (quantity < 1) {
        showMessageBox('delete', 'Quantity cannot be less than 1. Do you want to remove this item?', () => removeFromCart(cartId));
        return;
    }

    fetch(`<?php echo URLROOT; ?>/Supplier/updateCartItem`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `cart_id=${cartId}&quantity=${quantity}`
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast(result.message, 'success');
                location.reload();
            } else {
                showToast(result.message, 'error');
            }
        })
        .catch(error => {
            showToast('An error occurred while updating the quantity.', 'error');
            console.error(error);
        });
}

function clearCart() {
    fetch(`<?php echo URLROOT; ?>/Supplier/clearCart`, {
        method: 'POST'
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast(result.message, 'success');
                location.reload();
            } else {
                showToast(result.message, 'error');
            }
        })
        .catch(error => {
            showToast('An error occurred while clearing the cart.', 'error');
            console.error(error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.querySelector('.alert');
    if (flashMessage) {
        const message = flashMessage.textContent.trim().replace('×', '');
        const type = flashMessage.classList.contains('alert-success') ? 'success' : 'error';
        showToast(message, type);
        flashMessage.remove();
    }
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>