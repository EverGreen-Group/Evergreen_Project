<?php
// Dummy data for demonstration if not already provided by your controller.
if (empty($data) || empty($data['bag'])) {
  $data['bag'] = (object)[
    'bag_id'            => 123,
    'collection_id'     => 101,
    'actual_weight_kg'  => 15.5,
    'leaf_age'          => '2 years',
    'moisture_level'    => '12%',
    'deduction_notes'   => 'Slight damage on the bag, some discoloration observed on the edges.',
    'action'            => 'Approved',  // Possible values: Approved, Pending
    'timestamp'         => '2025-02-01 09:30:00',
    'leaf_type'         => 'Super Leaf'
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
      <h1>Bag Details</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Bag Details</span>
        </li>
      </ul>
    </div>
  </div>

  <!-- Bag Details Card (Enhanced for a single bag view) -->
  <div class="bag-card-container">
    <div class="bag-card">
      <!-- Card Header with Bag ID and Status -->
      <div class="bag-card-header">
        <h2>Bag #<?php echo $data['bag']->bag_id; ?></h2>
        <span class="bag-status <?php echo strtolower($data['bag']->action); ?>">
          <?php echo $data['bag']->action; ?>
        </span>
      </div>
      <!-- Card Body with Detailed Information -->
      <div class="bag-card-body">
        <div class="bag-details-grid">
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-collection'></i> Collection ID:
            </span>
            <span class="value"><?php echo $data['bag']->collection_id; ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-weight'></i> Weight:
            </span>
            <span class="value"><?php echo $data['bag']->actual_weight_kg; ?> kg</span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-time'></i> Leaf Age:
            </span>
            <span class="value"><?php echo $data['bag']->leaf_age; ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-droplet'></i> Moisture:
            </span>
            <span class="value"><?php echo $data['bag']->moisture_level; ?></span>
          </div>
          <div class="detail-item full-width">
            <span class="label">
              <i class='bx bx-note'></i> Deduction Notes:
            </span>
            <span class="value"><?php echo $data['bag']->deduction_notes; ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-calendar'></i> Timestamp:
            </span>
            <span class="value"><?php echo date('m/d/Y h:i A', strtotime($data['bag']->timestamp)); ?></span>
          </div>
          <div class="detail-item">
            <span class="label">
              <i class='bx bx-leaf'></i> Leaf Type:
            </span>
            <span class="value"><?php echo $data['bag']->leaf_type; ?></span>
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

  /* Bag Card Container */
  .bag-card-container {
    display: flex;
    justify-content: center;
    margin-bottom: var(--spacing-xl);
  }

  /* Enhanced Bag Card */
  .bag-card {
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    max-width: 1200px;
    width: 100%;
  }
  .bag-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
  }
  .bag-card-header h2 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.5rem;
  }
  .bag-status {
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    font-size: 0.9rem;
    text-transform: uppercase;
  }
  .bag-status.approved {
    background-color: var(--success-color);
    color: white;
  }
  .bag-status.pending {
    background-color: var(--warning-color);
    color: white;
  }
  .bag-card-body {
    padding-top: var(--spacing-lg);
  }
  /* Adjusted Details Grid with More Spacing */
  .bag-details-grid {
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
    .bag-details-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
