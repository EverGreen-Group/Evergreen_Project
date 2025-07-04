<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/collections/styles.css">

<main>
  <!-- Page Header -->
  <div class="head-title">
    <div class="left">
      <h1>Collection Bags</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <a href="<?php echo URLROOT; ?>/Supplier/collections/">Collections</a>
        </li>
        <li>
          <span>Collection #<?php echo $data['collection_id']; ?> Bags</span>
        </li>
      </ul>
    </div>
    <div class="last-updated">
      <i class='bx bx-refresh'></i>
      Updates as of <?php echo date('M d, Y H:i:s'); ?>
    </div>
  </div>

  <!-- Bags Section -->
  <div class="bags-section">
    <div class="section-header">
      <h3>Bag Details</h3>
    </div>

    <?php if (!empty($data['bags'])): ?>
      <div class="bags-container">
        <div class="table-wrapper">
          <table class="bags-table">
            <thead>
              <tr>
                <th>Bag ID</th>
                <th>Leaf Type</th>
                <th>Weight (kg)</th>
                <th>Leaf Age</th>
                <th>Moisture Level</th>
                <th>Deduction Notes</th>
                <th>Timestamp</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data['bags'] as $bag): ?>
                <tr>
                  <td data-label="Bag ID"><?php echo $bag->bag_id; ?></td>
                  <td data-label="Leaf Type">
                    <?php 
                      echo ($bag->leaf_type_id == 1) ? 'Normal Leaf' : 'Super Leaf'; 
                    ?>
                  </td>
                  <td data-label="Weight"><?php echo $bag->actual_weight_kg; ?></td>
                  <td data-label="Leaf Age"><?php echo $bag->leaf_age; ?></td>
                  <td data-label="Moisture Level"><?php echo $bag->moisture_level; ?></td>
                  <td data-label="Deduction Notes"><?php echo empty($bag->deduction_notes) ? 'None' : $bag->deduction_notes; ?></td>
                  <td data-label="Timestamp"><?php echo date('M d, Y H:i', strtotime($bag->timestamp)); ?></td>
                  <td data-label="Status">
                    <span class="status-badge <?php echo ($bag->is_finalized) ? 'finalized' : 'pending'; ?>">
                      <?php echo ($bag->is_finalized) ? 'Finalized' : 'Pending'; ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Mobile Cards View (Only shown on small screens) -->
      <div class="bags-cards">
        <?php foreach($data['bags'] as $bag): ?>
          <div class="bag-card">
            <div class="card-header">
              <div class="card-id">
                <span class="label">Bag ID:</span>
                <span class="value"><?php echo $bag->bag_id; ?></span>
              </div>
              <div class="card-status">
                <span class="status-badge <?php echo ($bag->is_finalized) ? 'finalized' : 'pending'; ?>">
                  <?php echo ($bag->is_finalized) ? 'Finalized' : 'Pending'; ?>
                </span>
              </div>
            </div>
            <div class="card-body">
              <div class="card-row">
                <span class="label">Leaf Type:</span>
                <span class="value">
                  <?php echo ($bag->leaf_type_id == 1) ? 'Normal Leaf' : 'Super Leaf'; ?>
                </span>
              </div>
              <div class="card-row">
                <span class="label">Weight:</span>
                <span class="value"><?php echo $bag->actual_weight_kg; ?> kg</span>
              </div>
              <div class="card-row">
                <span class="label">Leaf Age:</span>
                <span class="value"><?php echo $bag->leaf_age; ?></span>
              </div>
              <div class="card-row">
                <span class="label">Moisture:</span>
                <span class="value"><?php echo $bag->moisture_level; ?></span>
              </div>
              <div class="card-row">
                <span class="label">Notes:</span>
                <span class="value"><?php echo empty($bag->deduction_notes) ? 'None' : $bag->deduction_notes; ?></span>
              </div>
              <div class="card-row">
                <span class="label">Timestamp:</span>
                <span class="value"><?php echo date('M d, Y H:i', strtotime($bag->timestamp)); ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-bags">
        <p>No bags found for this collection.</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>

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
    --completed-color: #2ecc71;
    --pending-color: #f39c12;
    --finalized-color: #3498db;
    --table-header-bg: #f5f5f5;
    --card-background: white;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --border-radius-sm: 4px;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
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

  .section-header {
    margin-bottom: var(--spacing-md);
  }

  .section-header h3 {
    font-size: 1.25rem;
    color: var(--text-primary);
  }

  /* Flash Messages */
  .alert {
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    border-radius: var(--border-radius-md);
    background-color: var(--secondary-color);
    color: white;
  }

  .alert-error {
    background-color: #e74c3c;
  }

  /* Bags Section */
  .bags-section {
    margin-bottom: var(--spacing-xl);
  }

  .bags-container {
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  .table-wrapper {
    width: 100%;
    overflow-x: auto;
  }

  .bags-table {
    width: 100%;
    border-collapse: collapse;
  }

  .bags-table th,
  .bags-table td {
    padding: var(--spacing-md);
    text-align: left;
    border-bottom: 1px solid var(--border-color);
  }

  .bags-table th {
    background-color: var(--table-header-bg);
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
  }

  .bags-table tr:last-child td {
    border-bottom: none;
  }

  .status-badge {
    display: inline-block;
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    color: white;
    font-size: 0.875rem;
    white-space: nowrap;
  }

  .status-badge.completed {
    background-color: var(--completed-color);
  }

  .status-badge.pending {
    background-color: var(--pending-color);
  }

  .status-badge.finalized {
    background-color: var(--mainn);
  }

  .no-bags {
    padding: var(--spacing-lg);
    text-align: center;
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    color: var(--text-secondary);
  }

  /* Mobile Cards View */
  .bags-cards {
    display: none;
  }

  .bag-card {
    background-color: var(--card-background);
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-sm);
    margin-bottom: var(--spacing-md);
    overflow: hidden;
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    background-color: var(--table-header-bg);
    border-bottom: 1px solid var(--border-color);
  }

  .card-id .label {
    font-weight: bold;
    color: var(--text-primary);
    margin-right: var(--spacing-xs);
  }

  .card-body {
    padding: var(--spacing-md);
  }

  .card-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
  }

  .card-row:last-child {
    margin-bottom: 0;
  }

  .card-row .label {
    font-weight: 500;
    color: var(--text-secondary);
  }

  /* Responsive Design */
  @media (max-width: 992px) {
    main {
      padding: var(--spacing-md);
    }
    
    .head-title {
      flex-direction: column;
      align-items: flex-start;
    }

    .last-updated {
        align-self: flex-start;
    }
  }

  @media (max-width: 768px) {
    .bags-container {
      display: none;
    }

    .bags-cards {
      display: block;
    }

    .head-title h1 {
      font-size: 1.5rem;
    }

    .section-header h3 {
      font-size: 1.125rem;
    }
  }

  /* Extra small devices */
  @media (max-width: 480px) {
    main {
      padding: var(--spacing-sm);
    }

    .head-title {
      margin-bottom: var(--spacing-md);
    }

    .card-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .card-status {
      margin-top: var(--spacing-xs);
    }

    .card-row {
      flex-direction: column;
      margin-bottom: var(--spacing-md);
    }

    .card-row .label {
      margin-bottom: var(--spacing-xs);
    }
  }

  /* Add this to the existing style section */
  .last-updated {
    color: var(--text-secondary);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    margin-top: var(--spacing-sm);
  }

  .last-updated i {
    font-size: 1.1rem;
    color: var(--primary-color);
  }
</style>