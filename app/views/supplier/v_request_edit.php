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
          <span class="label">Fertilizer Type:</span>
          <span class="value"><?php echo $data['order']->fertilizer_name; ?></span>
        </div>
        <div class="detail-item">
          <span class="label">Total Amount:</span>
          <span class="value"><?php echo $data['order']->total_amount; ?></span>
        </div>
        <div class="detail-item">
          <span class="label">Unit:</span>
          <span class="value"><?php echo $data['order']->unit; ?></span>
        </div>
        <div class="detail-item">
          <span class="label">Price Per Unit:</span>
          <span class="value"><?php echo $data['order']->price_per_unit; ?></span>
        </div>
        <div class="detail-item">
          <span class="label">Total Price:</span>
          <span class="value"><?php echo $data['order']->total_price; ?></span>
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
      <form id="fertilizerForm" method="POST" action="<?php echo URLROOT . '/supplier/editFertilizerRequest/' . $data['order']->order_id; ?>">
        <div class="form-group">
          <label for="type_id">Fertilizer Type:</label>
          <select id="type_id" name="type_id" required>
            <option value="">Select Fertilizer</option>
            <?php foreach($data['fertilizer_types'] as $type): ?>
              <option value="<?php echo $type->type_id; ?>" 
                      <?php echo ($data['order']->type_id == $type->type_id) ? 'selected' : ''; ?>
                      data-unit-price-kg="<?php echo $type->unit_price_kg; ?>"
                      data-pack-price="<?php echo $type->unit_price_packs; ?>"
                      data-box-price="<?php echo $type->unit_price_box; ?>">
                <?php echo $type->name; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="unit">Unit:</label>
          <select id="unit" name="unit" required>
            <option value="">Select Unit</option>
            <option value="kg" <?php echo ($data['order']->unit == 'kg') ? 'selected' : ''; ?>>Kilograms (kg)</option>
            <option value="packs" <?php echo ($data['order']->unit == 'packs') ? 'selected' : ''; ?>>Packs</option>
            <option value="box" <?php echo ($data['order']->unit == 'box') ? 'selected' : ''; ?>>Box</option>
          </select>
        </div>

        <div class="form-group">
          <label for="total_amount">Total Amount:</label>
          <input type="number" id="total_amount" name="total_amount" max="50" min="1" value="<?php echo $data['order']->total_amount; ?>" required>
        </div>

        <input type="hidden" id="price_per_unit" name="price_per_unit">
        <input type="hidden" id="total_price" name="total_price">

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
  // (Optional) JavaScript functions for price calculation can be added here
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

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .edit-form-card form {
      grid-template-columns: 1fr;
    }
  }
</style>
