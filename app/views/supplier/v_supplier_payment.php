<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/payments/styles.css">

<main>
  <div class="head-title">
    <div class="left">
      <h1>My Payments</h1>
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
  </div>

  <div class="payments-section">
    <div class="section-header">
      <form method="GET" class="filter-form">
        <div class="filter-group">
          <label for="month">Month:</label>
          <select name="month" id="month">
            <option value="all" <?php echo (isset($_GET['month']) && $_GET['month'] == 'all') || !isset($_GET['month']) ? 'selected' : ''; ?>>All Months</option>
            <?php
              $months = [
                '1' => 'January', '2' => 'February', '3' => 'March',
                '4' => 'April', '5' => 'May', '6' => 'June',
                '7' => 'July', '8' => 'August', '9' => 'September',
                '10' => 'October', '11' => 'November', '12' => 'December'
              ];
              
              foreach ($months as $num => $name) {
                $selected = (isset($_GET['month']) && $_GET['month'] == $num) ? 'selected' : '';
                echo "<option value=\"$num\" $selected>$name</option>";
              }
            ?>
          </select>
        </div>
        <div class="filter-group">
          <label for="year">Year:</label>
          <select name="year" id="year">
            <?php
              $currentYear = date('Y');
              for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                $selected = (isset($_GET['year']) && $_GET['year'] == $year) || (!isset($_GET['year']) && $year == $currentYear) ? 'selected' : '';
                echo "<option value=\"$year\" $selected>$year</option>";
              }
            ?>
          </select>
        </div>
        <button type="submit" class="filter-btn">Apply Filter</button>
      </form>
      <input type="text" id="searchInput" placeholder="Search payments...">
    </div>

    <?php if (!empty($data['earnings'])): ?>
      <!-- Summary Section -->
      <div class="summary-container">
        <div class="summary-card">
          <div class="summary-title">Summary for <?php 
            if (isset($_GET['month']) && $_GET['month'] != 'all') {
              echo $months[$_GET['month']] . ' ';
            } else {
              echo 'All Months ';
            }
            echo isset($_GET['year']) ? $_GET['year'] : date('Y');
          ?></div>
          <div class="summary-grid">
            <div class="summary-item">
              <span class="summary-label">Total Normal (kg):</span>
              <span class="summary-value"><?php echo number_format($data['totals']->total_normal_kg, 2); ?> kg</span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Total Super (kg):</span>
              <span class="summary-value"><?php echo number_format($data['totals']->total_super_kg, 2); ?> kg</span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Total Deductions (kg):</span>
              <span class="summary-value"><?php echo number_format($data['totals']->total_deduction_kg, 2); ?> kg</span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Total Weight (kg):</span>
              <span class="summary-value"><?php echo number_format($data['totals']->total_kg, 2); ?> kg</span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Base Payment:</span>
              <span class="summary-value">Rs. <?php echo number_format($data['totals']->total_base_payment, 2); ?></span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Transport Charges:</span>
              <span class="summary-value">Rs. <?php echo number_format($data['totals']->total_transport_charge, 2); ?></span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Deduction Amount:</span>
              <span class="summary-value">Rs. <?php echo number_format($data['totals']->total_deduction_amount, 2); ?></span>
            </div>
            <div class="summary-item total">
              <span class="summary-label">Total Payment:</span>
              <span class="summary-value">Rs. <?php echo number_format($data['totals']->total_payment, 2); ?></span>
            </div>
          </div>
        </div>
      </div>

      <div class="payments-container">
        <div class="table-wrapper">
          <table class="payments-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Normal (kg)</th>
                <th>Super (kg)</th>
                <th>Deductions (kg)</th>
                <th>Total (kg)</th>
                <th>Base Payment</th>
                <th>Transport</th>
                <th>Deductions</th>
                <th>Total Payment</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data['earnings'] as $earning): ?>
                <tr class="detail-row" data-id="<?php echo $earning->id; ?>">
                  <td data-label="Date"><?php echo date('M d, Y', strtotime($earning->collection_date)); ?></td>
                  <td data-label="Normal (kg)"><?php echo number_format($earning->normal_kg, 2); ?> kg</td>
                  <td data-label="Super (kg)"><?php echo number_format($earning->super_kg, 2); ?> kg</td>
                  <td data-label="Deductions (kg)"><?php echo number_format($earning->total_deduction_kg, 2); ?> kg</td>
                  <td data-label="Total (kg)"><?php echo number_format($earning->total_kg, 2); ?> kg</td>
                  <td data-label="Base Payment">Rs. <?php echo number_format($earning->base_payment, 2); ?></td>
                  <td data-label="Transport">Rs. <?php echo number_format($earning->transport_charge, 2); ?></td>
                  <td data-label="Deductions">Rs. <?php echo number_format($earning->total_deduction_amount, 2); ?></td>
                  <td data-label="Total Payment">Rs. <?php echo number_format($earning->total_payment, 2); ?></td>
                </tr>
                <!-- Optional expandable details row -->
                <tr class="expanded-details" id="details-<?php echo $earning->id; ?>" style="display: none;">
                  <td colspan="9">
                    <div class="details-content">
                      <h4>Payment Details</h4>
                      <p><strong>Collection Date:</strong> <?php echo date('F d, Y', strtotime($earning->collection_date)); ?></p>
                      <p><strong>Created At:</strong> <?php echo date('F d, Y H:i:s', strtotime($earning->created_at)); ?></p>
                      <div class="details-grid">
                        <div>
                          <h5>Tea Collection</h5>
                          <p>Normal Grade: <?php echo number_format($earning->normal_kg, 2); ?> kg</p>
                          <p>Super Grade: <?php echo number_format($earning->super_kg, 2); ?> kg</p>
                          <p>Deductions: <?php echo number_format($earning->total_deduction_kg, 2); ?> kg</p>
                          <p>Net Total: <?php echo number_format($earning->total_kg, 2); ?> kg</p>
                        </div>
                        <div>
                          <h5>Payment Breakdown</h5>
                          <p>Base Payment: Rs. <?php echo number_format($earning->base_payment, 2); ?></p>
                          <p>Transport Charge: Rs. <?php echo number_format($earning->transport_charge, 2); ?></p>
                          <p>Deduction Amount: Rs. <?php echo number_format($earning->total_deduction_amount, 2); ?></p>
                          <p>Total Payment: Rs. <?php echo number_format($earning->total_payment, 2); ?></p>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Mobile Cards View (Only shown on small screens) -->
      <div class="payments-cards">
        <?php foreach($data['earnings'] as $earning): ?>
          <div class="payment-card">
            <div class="card-header">
              <div class="card-id">
                <span class="label">Date:</span>
                <span class="value"><?php echo date('M d, Y', strtotime($earning->collection_date)); ?></span>
              </div>
              <div class="card-toggle">
                <i class='bx bx-chevron-down'></i>
              </div>
            </div>
            <div class="card-body">
              <div class="card-row">
                <span class="label">Normal:</span>
                <span class="value"><?php echo number_format($earning->normal_kg, 2); ?> kg</span>
              </div>
              <div class="card-row">
                <span class="label">Super:</span>
                <span class="value"><?php echo number_format($earning->super_kg, 2); ?> kg</span>
              </div>
              <div class="card-row">
                <span class="label">Deductions:</span>
                <span class="value"><?php echo number_format($earning->total_deduction_kg, 2); ?> kg</span>
              </div>
              <div class="card-row">
                <span class="label">Total Weight:</span>
                <span class="value"><?php echo number_format($earning->total_kg, 2); ?> kg</span>
              </div>
              <div class="card-row">
                <span class="label">Base Payment:</span>
                <span class="value">Rs. <?php echo number_format($earning->base_payment, 2); ?></span>
              </div>
              <div class="card-row">
                <span class="label">Transport:</span>
                <span class="value">Rs. <?php echo number_format($earning->transport_charge, 2); ?></span>
              </div>
              <div class="card-row">
                <span class="label">Deduction Amount:</span>
                <span class="value">Rs. <?php echo number_format($earning->total_deduction_amount, 2); ?></span>
              </div>
              <div class="card-row total">
                <span class="label">Total Payment:</span>
                <span class="value">Rs. <?php echo number_format($earning->total_payment, 2); ?></span>
              </div>
              <!-- Expandable details section -->
              <div class="card-details" style="display: none;">
                <div class="details-divider"></div>
                <h5>Additional Details</h5>
                <p><strong>Created At:</strong> <?php echo date('F d, Y H:i:s', strtotime($earning->created_at)); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-payments">
        <p>No payment records found for the selected period.</p>
      </div>
    <?php endif; ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Search functionality
      document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('.payments-table tbody tr.detail-row');
        const cards = document.querySelectorAll('.payment-card');
        
        // Filter table rows
        rows.forEach(row => {
          const shouldShow = row.textContent.toLowerCase().includes(searchText);
          row.style.display = shouldShow ? '' : 'none';
          
          // Also hide the details row if hiding the main row
          const detailsId = row.getAttribute('data-id');
          const detailsRow = document.getElementById('details-' + detailsId);
          if (detailsRow) {
            detailsRow.style.display = 'none';
          }
        });
        
        // Filter cards
        cards.forEach(card => {
          card.style.display = card.textContent.toLowerCase().includes(searchText) ? '' : 'none';
        });
      });
      
      // Row click to show details (desktop)
      document.querySelectorAll('.detail-row').forEach(row => {
        row.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const detailsRow = document.getElementById('details-' + id);
          
          // Hide all other details rows first
          document.querySelectorAll('.expanded-details').forEach(el => {
            if (el.id !== 'details-' + id) {
              el.style.display = 'none';
            }
          });
          
          // Toggle this details row
          if (detailsRow) {
            detailsRow.style.display = detailsRow.style.display === 'none' ? 'table-row' : 'none';
          }
        });
      });
      
      // Card toggle for mobile view
      document.querySelectorAll('.card-toggle, .card-header').forEach(toggle => {
        toggle.addEventListener('click', function() {
          const card = this.closest('.payment-card');
          const details = card.querySelector('.card-details');
          const icon = card.querySelector('.bx');
          
          if (details) {
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
            icon.classList.toggle('bx-chevron-down');
            icon.classList.toggle('bx-chevron-up');
          }
        });
      });
    });
  </script>

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
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
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

  /* Filter Form */
  .filter-form {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: var(--spacing-lg);
  }

  .filter-group {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .filter-group label {
    font-weight: 500;
    color: var(--text-primary);
  }

  .filter-group select {
    padding: var(--spacing-xs) var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    background-color: white;
  }

  .filter-btn {
    padding: var(--spacing-xs) var(--spacing-md);
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .filter-btn:hover {
    background-color: var(--secondary-color);
  }

  /* Search Input */
  #searchInput {
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    width: 100%;
    margin-top: var(--spacing-md);
  }

  /* Summary Section */
  .summary-container {
    margin-bottom: var(--spacing-lg);
  }

  .summary-card {
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  .summary-title {
    padding: var(--spacing-md);
    background-color: var(--table-header-bg);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--text-primary);
  }

  .summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
    padding: var(--spacing-md);
  }

  .summary-item {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
  }

  .summary-item.total {
    grid-column: 1 / -1;
    border-top: 1px solid var(--border-color);
    padding-top: var(--spacing-md);
    margin-top: var(--spacing-sm);
  }

  .summary-label {
    font-weight: 500;
    color: var(--text-secondary);
  }

  .summary-value {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1.1rem;
  }

  .summary-item.total .summary-value {
    color: var(--primary-color);
    font-size: 1.2rem;
  }

  /* Payments Section */
  .payments-section {
    margin-bottom: var(--spacing-xl);
  }

  .payments-container {
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    margin-bottom: var(--spacing-lg);
  }

  .table-wrapper {
    width: 100%;
    overflow-x: auto;
  }

  .payments-table {
    width: 100%;
    border-collapse: collapse;
  }

  .payments-table th,
  .payments-table td {
    padding: var(--spacing-md);
    text-align: left;
    border-bottom: 1px solid var(--border-color);
  }

  .payments-table th {
    background-color: var(--table-header-bg);
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
  }

  .payments-table tr:last-child td {
    border-bottom: none;
  }

  /* Clickable rows */
  .detail-row {
    cursor: pointer;
    transition: background-color 0.2s ease;
  }

  .detail-row:hover {
    background-color: #f9f9f9;
  }

  /* Expanded details */
  .expanded-details td {
    background-color: #f9f9f9;
    padding: 0;
  }

  .details-content {
    padding: var(--spacing-lg);
  }

  .details-content h4 {
    margin-top: 0;
    margin-bottom: var(--spacing-md);
    color: var(--text-primary);
  }

  .details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
  }

  .details-grid h5 {
    margin-top: 0;
    margin-bottom: var(--spacing-md);
    color: var(--text-primary);
  }

  .no-payments {
    padding: var(--spacing-lg);
    text-align: center;
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    color: var(--text-secondary);
  }

  /* Mobile Cards View */
  .payments-cards {
    display: none;
  }

  .payment-card {
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
    cursor: pointer;
  }

  .card-toggle {
    color: var(--text-secondary);
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

  .card-row.total {
    border-top: 1px solid var(--border-color);
    padding-top: var(--spacing-md);
    margin-top: var(--spacing-md);
    font-weight: bold;
  }

  .card-row .label {
    font-weight: 500;
    color: var(--text-secondary);
  }

  .details-divider {
    height: 1px;
    background-color: var(--border-color);
    margin: var(--spacing-md) 0;
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

    .summary-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .details-grid {
      grid-template-columns: 1fr;
      gap: var(--spacing-lg);
    }
  }

  @media (max-width: 768px) {
    .payments-container {
      display: none;
    }

    .payments-cards {
      display: block;
    }

    .head-title h1 {
      font-size: 1.5rem;
    }

    .filter-form {
      flex-direction: column;
      align-items: flex-start;
    }

    .summary-grid {
      grid-template-columns: 1fr;
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

    .card-toggle {
      align-self: flex-end;
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
</style>