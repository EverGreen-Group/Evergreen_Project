<?php
// Dummy data for demonstration if not already provided by your controller.
if (empty($data)) {
  $data = [];
}

// Dummy fertilizer types for the request form.
if (empty($data['fertilizerTypes'])) {
  $data['fertilizerTypes'] = [
    (object)[
      'id'           => 1,
      'name'         => 'NPK Fertilizer',
      'pricePerUnit' => 100.00,
      'unit'         => 'kg'
    ],
    (object)[
      'id'           => 2,
      'name'         => 'Urea',
      'pricePerUnit' => 80.00,
      'unit'         => 'kg'
    ]
  ];
}

// Dummy data for fertilizer request history.
if (empty($data['fertilizerRequestHistory'])) {
  $data['fertilizerRequestHistory'] = [
    (object)[
      'order_id'        => 301,
      'fertilizer_type' => 'NPK Fertilizer',
      'order_date'      => '2025-02-05',
      'order_time'      => '10:30 AM',
      'amount'          => 50,
      'unit'            => 'kg',
      'price'           => 5000.00,
      'approval_status' => 'Pending'
    ]
  ];
}
?>
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
      <form id="fertilizer-request-form" action="<?php echo URLROOT; ?>/Supplier/submitFertilizerRequest" method="post">
        <div class="form-group">
          <label for="fertilizer">Fertilizer Type:</label>
          <select id="fertilizer" name="fertilizer" required onchange="updatePricePerUnit()">
            <option value="">-- Select Fertilizer --</option>
            <?php foreach($data['fertilizerTypes'] as $type): ?>
              <option value="<?php echo $type->id; ?>" data-price="<?php echo $type->pricePerUnit; ?>" data-unit="<?php echo $type->unit; ?>">
                <?php echo $type->name; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="unit">Unit:</label>
          <select id="unit" name="unit" required>
            <option value="">-- Select Unit --</option>
            <!-- Common units for demonstration -->
            <option value="kg">kg</option>
            <option value="bag">bag</option>
            <option value="liter">liter</option>
          </select>
        </div>
        <div class="form-group">
          <label for="amount">Amount:</label>
          <input type="number" id="amount" name="amount" min="1" required oninput="updateTotalPrice()">
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

  <div class="section-divider"></div>

  <!-- Fertilizer Request History Section -->
  <div class="request-history-section">
    <div class="section-header">
      <h3>Fertilizer Request History</h3>
    </div>
    <?php if (!empty($data['fertilizerRequestHistory'])): ?>
      <?php foreach($data['fertilizerRequestHistory'] as $request): ?>
        <div class="schedule-card">
          <div class="card-content">
            <div class="card-header">
              <div class="status-badge">
                Order #<?php echo $request->order_id; ?>
              </div>
            </div>
            <div class="card-body">
              <div class="schedule-info">
                <div class="info-item">
                  <i class='bx bx-box'></i>
                  <span>Fertilizer: <?php echo $request->fertilizer_type; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-calendar'></i>
                  <span>Date: <?php echo date('m/d/Y', strtotime($request->order_date)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-time-five'></i>
                  <span>Time: <?php echo $request->order_time; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-calculator'></i>
                  <span>Amount: <?php echo $request->amount . ' ' . $request->unit; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-dollar'></i>
                  <span>Price: <?php echo 'රු.' . number_format($request->price, 2); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-check-circle'></i>
                  <span>Status: <?php echo $request->approval_status; ?></span>
                </div>
              </div>
              <div class="schedule-action">
                <button class="update-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $request->order_id; ?>'">
                  <i class='bx bx-edit'></i>
                </button>
                <button class="cancel-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/cancelFertilizerRequest/<?php echo $request->order_id; ?>'">
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

<!-- Scripts -->
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<script>
  // Update price per unit based on the selected fertilizer.
  function updatePricePerUnit() {
    var fertilizerSelect = document.getElementById('fertilizer');
    var selectedOption = fertilizerSelect.options[fertilizerSelect.selectedIndex];
    var pricePerUnit = selectedOption.getAttribute('data-price');
    if (pricePerUnit) {
      document.getElementById('price_per_unit').value = 'රු.' + parseFloat(pricePerUnit).toFixed(2);
    } else {
      document.getElementById('price_per_unit').value = '';
    }
    updateTotalPrice();
  }

  // Calculate and update the total price based on amount and price per unit.
  function updateTotalPrice() {
    var fertilizerSelect = document.getElementById('fertilizer');
    var selectedOption = fertilizerSelect.options[fertilizerSelect.selectedIndex];
    var pricePerUnit = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    var amount = parseFloat(document.getElementById('amount').value) || 0;
    var total = pricePerUnit * amount;
    document.getElementById('total_price').value = 'රු.' + total.toFixed(2);
  }
</script>

