<?php
// Dummy data for demonstration if not already provided by your controller.
if (empty($data) || empty($data['fertilizer'])) {
  $data['fertilizer'] = (object)[
    'fertilizer_id'    => 456,
    'fertilizer_type'  => 'NPK Fertilizer',
    'quantity'         => 100,
    'unit'             => 'kg',
    'price_per_unit'   => 150.00,
    'total_price'      => 15000.00,
    'supplier'         => 'Acme Fertilizers',
    'status'           => 'Received',  // Possible values: Received, Pending
    'timestamp'        => '2025-02-01 10:00:00'
  ];
}
?>
<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
  <!-- Page Header -->
  <div class="head-title">
    <div class="left">
      <h1>Fertilizer Details</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Fertilizer Details</span>
        </li>
      </ul>
    </div>
  </div>

  <!-- Fertilizer Details Card -->
  <div class="fertilizer-card-container">
    <div class="fertilizer-card">
      <!-- Card Header with Fertilizer ID and Status -->
      <div class="fertilizer-card-header">
        <h2>Fertilizer #<?php echo $data['fertilizer']->fertilizer_id; ?></h2>
        <span class="fertilizer-status <?php echo strtolower($data['fertilizer']->status); ?>">
          <?php echo $data['fertilizer']->status; ?>
        </span>
      </div>
      <!-- Card Body with Detailed Information -->
      <div class="fertilizer-card-body">
        <div class="fertilizer-details-grid">
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-box'></i> Type:
            </span>
            <span class="value"><?php echo $data['fertilizer']->fertilizer_type; ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-purchase-tag'></i> Quantity:
            </span>
            <span class="value"><?php echo $data['fertilizer']->quantity; ?> <?php echo $data['fertilizer']->unit; ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-dollar'></i> Price per Unit:
            </span>
            <span class="value"><?php echo '$' . number_format($data['fertilizer']->price_per_unit, 2); ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-dollar'></i> Total Price:
            </span>
            <span class="value"><?php echo '$' . number_format($data['fertilizer']->total_price, 2); ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-user'></i> Supplier:
            </span>
            <span class="value"><?php echo $data['fertilizer']->supplier; ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-calendar'></i> Timestamp:
            </span>
            <span class="value"><?php echo date('m/d/Y h:i A', strtotime($data['fertilizer']->timestamp)); ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Scripts -->
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

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

  /* Head Title & Breadcrumbs */
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

  /* Fertilizer Card Container */
  .fertilizer-card-container {
    display: flex;
    justify-content: center;
    margin-bottom: var(--spacing-xl);
  }

  /* Enhanced Fertilizer Card */
  .fertilizer-card {
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    max-width: 1200px;
    width: 100%;
  }
  .fertilizer-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
  }
  .fertilizer-card-header h2 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.5rem;
  }
  .fertilizer-status {
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    font-size: 0.9rem;
    text-transform: uppercase;
  }
  .fertilizer-status.received {
    background-color: var(--success-color);
    color: white;
  }
  .fertilizer-status.pending {
    background-color: var(--warning-color);
    color: white;
  }
  .fertilizer-card-body {
    padding-top: var(--spacing-lg);
  }
  /* Details Grid */
  .fertilizer-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
  }
  .detail-item {
    display: flex;
    flex-direction: column;
  }
  .detail-item.full-width {
    grid-column: span 2;
  }
  .detail-item .label {
    font-weight: bold;
    color: var(--text-secondary);
    margin-bottom: var(--spacing-xs);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: 1rem;
  }
  .detail-item .value {
    color: var(--text-primary);
    font-size: 1.1rem;
    line-height: 1.5;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .fertilizer-details-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
