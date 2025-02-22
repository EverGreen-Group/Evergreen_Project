<?php
// Dummy data for demonstration if not already provided by your controller.
if (empty($data) || empty($data['collectionRecords']) || empty($data['fertilizerPurchases'])) {
  $data['collectionsCount'] = 1;
  $data['fertilizerCount']  = 1;
  $data['totalIncome']      = 5000.00;
  $data['collectionRecords'] = [
    (object)[
      'collection_id'         => 101,
      'collection_date'       => '2025-02-01',
      'bag_usage_history_id'  => 202,
      'factory_payment'       => 3000.00,
      'delivery_charges'      => 200.00
    ]
  ];
  $data['fertilizerPurchases'] = [
    (object)[
      'order_id'      => 501,
      'purchase_date' => '2025-02-03',
      'price'         => 1500.00
    ]
  ];
  $data['totalTeaLeaves']   = 120;      // in kg
  $data['totalDeductions']  = 200.00;
}

// Dummy months for the dropdown navigation.
// In a real scenario these might be generated dynamically.
$months = [
  '2025-02' => 'February 2025',
  '2025-01' => 'January 2025',
  '2024-12' => 'December 2024'
];
$currentMonth = '2025-02'; // assume current month is February 2025
?>

<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/supplier_payment/styles.css">
<main>
  <!-- Page Header -->
  <div class="head-title">
    <div class="left">
      <h1>Supplier Payments</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Payments</span>
        </li>
      </ul>
    </div>
    <!-- Month Selector Dropdown -->
    <div class="month-selector">
      <label for="month">Select Month:</label>
      <select id="month" onchange="location = this.value;">
        <?php foreach($months as $monthValue => $monthName) : ?>
          <option value="<?php echo URLROOT; ?>/Supplier/payments/<?php echo $monthValue; ?>" <?php echo ($monthValue == $currentMonth) ? 'selected' : ''; ?>>
            <?php echo $monthName; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Quick Stats Container -->
  <div class="stats-container">
    <div class="stat-item">
      <div class="stat-header">
        <i class='bx bx-collection'></i>
        <span>Collections</span>
      </div>
      <div class="stat-value">
        <?php echo isset($data['collectionsCount']) ? $data['collectionsCount'] : '0'; ?>
        <small>this month</small>
      </div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat-item">
      <div class="stat-header">
        <i class='bx bx-purchase-tag'></i>
        <span>Fertilizer Orders</span>
      </div>
      <div class="stat-value">
        <?php echo isset($data['fertilizerCount']) ? $data['fertilizerCount'] : '0'; ?>
        <small>this month</small>
      </div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat-item">
      <div class="stat-header">
        <i>රු.</i>
        <span>Total Income</span>
      </div>
      <div class="stat-value">
        <?php 
          echo isset($data['totalIncome']) 
            ? 'රු.' . number_format($data['totalIncome'], 2) 
            : 'රු.0.00'; 
        ?>
        <small>this month</small>
      </div>
    </div>
  </div>

  <!-- Tea Leaves Collection Records Section -->
  <div class="schedule-section">
    <div class="section-header">
      <h3>Tea Leaves Collection Records</h3>
    </div>

    <?php if (!empty($data['collectionRecords'])): ?>
      <?php foreach($data['collectionRecords'] as $record): ?>
        <div class="schedule-card">
          <div class="card-content">
            <div class="card-header">
              <div class="status-badge">
                Collection #<?php echo $record->collection_id; ?>
              </div>
            </div>
            <div class="card-body">
              <div class="schedule-info">
                <div class="info-item">
                  <i class='bx bx-calendar'></i>
                  <span><?php echo date('m/d/Y', strtotime($record->collection_date)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-box'></i>
                  <span>
                    Bag Usage: 
                    <a href="<?php echo URLROOT; ?>/Supplier/bag/<?php echo $record->bag_usage_history_id; ?>">
                      <?php echo $record->bag_usage_history_id; ?>
                    </a>
                  </span>
                </div>
                <div class="info-item">
                  <i class='bx bx-money'></i>
                  <span>Factory Payment: රු.<?php echo number_format($record->factory_payment, 2); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-truck'></i>
                  <span>Delivery Charges: රු.<?php echo number_format($record->delivery_charges, 2); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-schedule">
        <p>No collection records for this month.</p>
      </div>
    <?php endif; ?>
  </div>

  <div class="section-divider"></div>

  <!-- Fertilizer Purchases Section -->
  <div class="schedule-section">
    <div class="section-header">
      <h3>Fertilizer Purchases</h3>
    </div>

    <?php if (!empty($data['fertilizerPurchases'])): ?>
      <?php foreach($data['fertilizerPurchases'] as $purchase): ?>
        <div class="schedule-card">
          <div class="card-content">
            <div class="card-header">
              <div class="status-badge">
                Purchase #<?php echo $purchase->order_id; ?>
              </div>
            </div>
            <div class="card-body">
              <div class="schedule-info">
                <div class="info-item">
                  <i class='bx bx-calendar'></i>
                  <span><?php echo date('m/d/Y', strtotime($purchase->purchase_date)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-receipt'></i>
                  <span>
                    Order: 
                    <a href="<?php echo URLROOT; ?>/Supplier/fertilizer/<?php echo $purchase->order_id; ?>">
                      <?php echo $purchase->order_id; ?>
                    </a>
                  </span>
                </div>
                <div class="info-item">
                  <i class='bx bx-money'></i>
                  <span>Price: රු.<?php echo number_format($purchase->price, 2); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-schedule">
        <p>No fertilizer purchases for this month.</p>
      </div>
    <?php endif; ?>
  </div>

  <div class="section-divider"></div>

  <!-- Monthly Summary Section -->
  <div class="schedule-section">
    <div class="section-header">
      <h3>Monthly Summary</h3>
    </div>
    <div class="schedule-card">
      <div class="card-content">
        <div class="schedule-info">
          <div class="info-item">
            <i class='bx bx-leaf'></i>
            <span>Total Tea Leaves Supplied: 
              <strong><?php echo isset($data['totalTeaLeaves']) ? $data['totalTeaLeaves'] . ' kg' : '0 kg'; ?></strong>
            </span>
          </div>
          <div class="info-item">
            <i class='bx bx-dollar'></i>
            <span>Total Income: 
              <strong><?php echo isset($data['totalIncome']) ? 'රු.' . number_format($data['totalIncome'], 2) : 'රු.0.00'; ?></strong>
            </span>
          </div>
          <div class="info-item">
            <i class='bx bx-minus-circle'></i>
            <span>Total Deductions: 
              <strong><?php echo isset($data['totalDeductions']) ? 'රු.' . number_format($data['totalDeductions'], 2) : 'රු.0.00'; ?></strong>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>

<!-- Scripts -->
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
