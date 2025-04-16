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

  <!-- Section 1: Your Time Slots -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Available Fertilizer Types</h3>
      </div>
      <table>
        <thead>
          <tr>
            <th>Fertilizer ID</th>
            <th>Fertilizer Name</th>
            <th>Company Name</th>
            <th>Code</th>
            <th>Description</th>
            <th>Price</th>
            <th>Available Quantity</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($fertilizer_types) && !empty($fertilizer_types)): ?>
            <?php foreach ($fertilizer_types as $fertilizer): ?>
              <tr>
                <td><?php echo $fertilizer->fertilizer_id; ?></td>
                <td><?php echo $fertilizer->fertilizer_name; ?></td>
                <td><?php echo $fertilizer->company_name; ?></td>
                <td><?php echo $fertilizer->code; ?></td>
                <td><?php echo $fertilizer->details; ?></td>
                <td><?php echo $fertilizer->price; ?></td>
                <td><?php echo $fertilizer->quantity; ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No fertilizer stocks available.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- New Fertilizer Request Form Section -->
  <div class="request-form-section">
    <div class="section-header">
      <h3>New Fertilizer Request</h3>
    </div>
    <div class="request-form-card">

      <form id="fertilizer-request-form" action="<?php echo URLROOT; ?>/Supplier/createFertilizerOrder" method="post">

        <!-- Add hidden supplier_id field -->
        <input type="hidden" name="supplier_id" value="<?php echo $_SESSION['supplier_id']; ?>">
        
          <div class="form-group">
              <label for="fertilizer_id">Fertilizer ID:</label>
              <select id="fertilizer_id" name="fertilizer_id" required onchange="updateTotalPrice()">
                  <option value="">-- Select Fertilizer --</option>
                  <?php foreach($data['fertilizer_types'] as $fertilizer): ?>
                      <option value="<?php echo $fertilizer->fertilizer_id; ?>" data-price="<?php echo $fertilizer->price; ?>">
                          <?php echo $fertilizer->fertilizer_name; ?> (<?php echo $fertilizer->company_name; ?>)
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="form-group">
              <label for="quantity">Quantity:</label>
              <input type="number" id="quantity" name="quantity" min="1" max="100" required oninput="updateTotalPrice()">
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
              <div class="schedule-info-grid">
                <!-- first row -->
                <div class="info-row">
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
                    <i class='bx bx-package'></i>
                    <span>Quantity: <?php echo $order->quantity; ?></span>
                  </div>
                </div>
                <!-- second row -->
                <div class="info-row">
                  <div class="info-item">
                    <i class='bx bx-dollar'></i>
                    <span>Price: <?php echo 'රු.' . number_format($order->total_amount, 2); ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-check-circle'></i>
                    <span>Status: <p class="btn"> <?php echo isset($order->status) ? $order->status : 'Pending'; ?></p></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-credit-card'></i>
                    <span>Payment: <p class="btn"> <?php echo isset($order->payment_status) ? $order->payment_status : 'Pending'; ?></p></span>
                  </div>
                  <div class="info-item">
                  </div>
                </div>
              </div>

              <!-- Action buttons -->
              <div class="schedule-action">
                <?php if (isset($order->status) && strtolower($order->status) ==='pending'): ?>
                  <button class="update-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $order->order_id; ?>'">
                    <i class='bx bx-edit'></i>
                  </button>
                  <button class="cancel-btn" onclick="confirmDelete(<?php echo $order->order_id; ?>)">
                    <i class='bx bx-trash'></i>
                  </button>
                <?php else: ?>
                  <button class="update-btn disabled" disabled title="Cannot edit not pending orders">
                    <i class='bx bx-edit'></i>
                  </button>
                  <button class="cancel-btn disabled" disabled title="Cannot delete not pending orders">
                    <i class="bx bx-trash"></i>
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
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<script>

    // Update Price Per Unit based on the selected fertilizer and unit
    /*function updatePricePerUnit() {
      const fertilizerSelect = document.getElementById('fertilizer_name');
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
    }*/

    // Update total price based on amount and price per unit
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
    --danger-color: #e74c3c;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-xxl: 3rem; /* Added larger spacing */
    --border-radius-sm: 4px;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
  }

  /* Layout & Common Styles */
  main {
    padding: var(--spacing-sm); /* Reduced left margin */
    max-width: 1200px;
    margin: 0 auto;
  }

  .head-title {
    margin-bottom: var(--spacing-lg);
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

  /* Table Styles */
  .table-data {
    margin-bottom: var(--spacing-xxl); /* Increased gap between fertilizer types and form */
  }

  .table-data .order {
    background-color: white;
    padding: var(--spacing-md);
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .table-data .head {
    display: flex;
    align-items: center;
    grid-gap: 16px;
    margin-bottom: 16px;
  }

  .table-data table {
    width: 100%;
    border-collapse: collapse;
  }

  .table-data table th {
    padding: var(--spacing-sm);
    text-align: left;
    background-color: var(--background-light);
    color: var(--text-primary);
  }

  .table-data table td {
    padding: var(--spacing-sm);
    border-bottom: 1px solid var(--border-color);
  }

  /* Request Form Section */
  .request-form-section {
    margin-bottom: var(--spacing-xl);
  }

  .section-header {
    margin-bottom: var(--spacing-md);
  }

  .section-header h3 {
    color: var(--text-primary);
    font-size: 1.25rem;
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

  /* Adjust width of form fields */
  .request-form-card .form-group {
    display: flex;
    flex-direction: column;
    width: 100%;
  }

  /* Make fertilizer_id and price_per_unit match width with quantity and total_price */
  #fertilizer_id, #price_per_unit, #quantity, #total_price {
    width: 100%;
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
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
  }

  /* Move price to second row */
  .schedule-info .info-item:nth-child(4) {
    grid-row: 2;
    grid-column: 1;
  }

  .info-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .info-item i {
    color: var(--primary-color);
  }

  /* Order Status Styles */
  .status-indicator {
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    font-weight: 500;
    display: inline-block;
  }

  .status-pending {
    background-color: rgba(243, 156, 18, 0.2);
    color: var(--warning-color);
  }

  .status-approved {
    background-color: rgba(46, 204, 113, 0.2);
    color: var(--secondary-color);
  }

  .status-delivered {
    background-color: rgba(39, 174, 96, 0.2);
    color: var(--success-color);
  }

  .status-cancelled {
    background-color: rgba(231, 76, 60, 0.2);
    color: var(--danger-color);
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

  .schedule-info-grid {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 100%;
  }

  .info-row {
    display: flex;
    justify-content: space-between;
    width: 100%;
  }

  .info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
  }

  .info-item i {
    font-size: 18px;
    color: var(--primary-color); /* Using your theme color */
  }

  /* For responsive design on smaller screens */
  @media (max-width: 768px) {
    .info-row {
      flex-direction: column;
      gap: 10px;
    }
  }

  .disabled {
    cursor: not-allowed;
  }

  .btn {
    background-color:rgba(35, 111, 81, 0.09); /*rgb(253, 207, 26)*/
    font-size: small;
    height: fit-content;
    width: fit-content;
    color: rgb(80, 80, 80);
  }

</style>