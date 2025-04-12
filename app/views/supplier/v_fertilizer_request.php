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
                  <span>Amount: <?php echo number_format($order->quantity) . ' ' . isset($unit) ? $unit : ''; ?></span>
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
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<script>
// Update Price Per Unit based on the selected fertilizer and unit
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

  /* Layout & Common Styles */
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

  .section-divider {
    height: 1px;
    background-color: var(--border-color);
    margin: var(--spacing-xl) 0;
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
    gap: var(--spacing-lg);
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

  /* Action Buttons Section */
  .schedule-action {
    display: flex;
    gap: var(--spacing-sm);
    justify-content: flex-end;
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-xs);
    border-top: 1px solid var(--border-color);
  }

  .update-btn,
  .cancel-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 1.75rem;
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .update-btn:hover {
    background-color: var(--secondary-color);
    color: white;
    transform: scale(1.05);
  }

  .cancel-btn:hover {
    background-color: var(--warning-color);
    color: white;
    transform: scale(1.05);
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .request-form-card form {
      grid-template-columns: 1fr;
    }
    .schedule-info {
      flex-direction: column;
    }
  }
</style>
