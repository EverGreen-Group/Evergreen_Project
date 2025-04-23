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
      <div class="card">
        <form method="GET" class="filter-form">
          <div class="filter-group">
            <label for="month">Filter by Month:</label>
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
        <div class="search-container">
          <i class='bx bx-search'></i>
          <input type="text" id="searchInput" placeholder="Search payments...">
        </div>
      </div>
    </div>

    <?php if (!empty($data['earnings'])): ?>
      <!-- Summary Section -->
      <div class="summary-container">
        <div class="summary-card">
          <div class="summary-title">
            <i class='bx bx-chart'></i>
            <span>Summary for <?php 
              if (isset($_GET['month']) && $_GET['month'] != 'all') {
                echo $months[$_GET['month']] . ' ';
              } else {
                echo 'All Months ';
              }
              echo isset($_GET['year']) ? $_GET['year'] : date('Y');
            ?></span>
          </div>
          <div class="summary-grid">
            <div class="summary-item">
              <span class="summary-label"><i class='bx bx-leaf'></i> Total Normal:</span>
              <span class="summary-value"><?php echo number_format($data['totals']->total_normal_kg, 2); ?> kg</span>
            </div>
            <div class="summary-item">
              <span class="summary-label"><i class='bx bx-star'></i> Total Super:</span>
              <span class="summary-value"><?php echo number_format($data['totals']->total_super_kg, 2); ?> kg</span>
            </div>
            <div class="summary-item">
              <span class="summary-label"><i class='bx bx-minus-circle'></i> Total Deductions:</span>
              <span class="summary-value"><?php echo number_format($data['totals']->total_deduction_kg, 2); ?> kg</span>
            </div>
            <div class="summary-item">
              <span class="summary-label"><i class='bx bx-package'></i> Total Weight:</span>
              <span class="summary-value"><?php echo number_format($data['totals']->total_kg, 2); ?> kg</span>
            </div>
            <div class="summary-item">
              <span class="summary-label"><i class='bx bx-money'></i> Base Payment:</span>
              <span class="summary-value">Rs. <?php echo number_format($data['totals']->total_base_payment, 2); ?></span>
            </div>
            <div class="summary-item">
              <span class="summary-label"><i class='bx bx-car'></i> Transport Charges:</span>
              <span class="summary-value">Rs. <?php echo number_format($data['totals']->total_transport_charge, 2); ?></span>
            </div>
            <div class="summary-item">
              <span class="summary-label"><i class='bx bx-wallet-alt'></i> Deduction Amount:</span>
              <span class="summary-value">Rs. <?php echo number_format($data['totals']->total_deduction_amount, 2); ?></span>
            </div>
            <div class="summary-item total">
              <span class="summary-label"><i class='bx bx-coin-stack'></i> Total Payment:</span>
              <span class="summary-value">Rs. <?php echo number_format($data['totals']->total_payment, 2); ?></span>
            </div>
          </div>
        </div>
      </div>

      <div class="payments-container">
        <div class="table-wrapper">
          <table id="paymentsTable" class="payments-table">
            <thead>
              <tr>
                <th data-sort="date">Date <i class="bx bx-sort"></i></th>
                <th data-sort="normal">Normal (kg) <i class="bx bx-sort"></i></th>
                <th data-sort="super">Super (kg) <i class="bx bx-sort"></i></th>
                <th data-sort="deductions">Deductions (kg) <i class="bx bx-sort"></i></th>
                <th data-sort="total">Total (kg) <i class="bx bx-sort"></i></th>
                <th data-sort="base">Base Payment <i class="bx bx-sort"></i></th>
                <th data-sort="transport">Transport <i class="bx bx-sort"></i></th>
                <th data-sort="deductamt">Deductions <i class="bx bx-sort"></i></th>
                <th data-sort="payment">Total Payment <i class="bx bx-sort"></i></th>
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
                <!-- Expandable details row -->
                <tr class="expanded-details" id="details-<?php echo $earning->id; ?>" style="display: none;">
                  <td colspan="9">
                    <div class="details-content">
                      <div class="details-header">
                        <h4>Payment Details</h4>
                        <span class="details-date"><?php echo date('F d, Y', strtotime($earning->collection_date)); ?></span>
                      </div>
                      <div class="details-grid">
                        <div class="details-column">
                          <h5><i class='bx bx-leaf'></i> Tea Collection</h5>
                          <div class="details-item">
                            <span class="details-label">Normal Grade:</span>
                            <span class="details-value"><?php echo number_format($earning->normal_kg, 2); ?> kg</span>
                          </div>
                          <div class="details-item">
                            <span class="details-label">Super Grade:</span>
                            <span class="details-value"><?php echo number_format($earning->super_kg, 2); ?> kg</span>
                          </div>
                          <div class="details-item">
                            <span class="details-label">Deductions:</span>
                            <span class="details-value"><?php echo number_format($earning->total_deduction_kg, 2); ?> kg</span>
                          </div>
                          <div class="details-item">
                            <span class="details-label">Net Total:</span>
                            <span class="details-value"><?php echo number_format($earning->total_kg, 2); ?> kg</span>
                          </div>
                        </div>
                        <div class="details-column">
                          <h5><i class='bx bx-money'></i> Payment Breakdown</h5>
                          <div class="details-item">
                            <span class="details-label">Base Payment:</span>
                            <span class="details-value">Rs. <?php echo number_format($earning->base_payment, 2); ?></span>
                          </div>
                          <div class="details-item">
                            <span class="details-label">Transport Charge:</span>
                            <span class="details-value">Rs. <?php echo number_format($earning->transport_charge, 2); ?></span>
                          </div>
                          <div class="details-item">
                            <span class="details-label">Deduction Amount:</span>
                            <span class="details-value">Rs. <?php echo number_format($earning->total_deduction_amount, 2); ?></span>
                          </div>
                          <div class="details-item">
                            <span class="details-label">Total Payment:</span>
                            <span class="details-value">Rs. <?php echo number_format($earning->total_payment, 2); ?></span>
                          </div>
                        </div>
                      </div>
                      <div class="details-footer">
                        <p><i class='bx bx-time'></i> <strong>Created:</strong> <?php echo date('F d, Y H:i:s', strtotime($earning->created_at)); ?></p>
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
                <i class='bx bx-calendar'></i>
                <span class="value"><?php echo date('M d, Y', strtotime($earning->collection_date)); ?></span>
              </div>
              <div class="card-amount">
                <span>Rs. <?php echo number_format($earning->total_payment, 2); ?></span>
              </div>
              <div class="card-toggle">
                <i class='bx bx-chevron-down'></i>
              </div>
            </div>
            <div class="card-body">
              <div class="card-section">
                <h5>Tea Collection</h5>
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
              </div>
              
              <div class="card-section">
                <h5>Payment Details</h5>
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
              </div>
              
              <!-- Expandable details section -->
              <div class="card-details" style="display: none;">
                <div class="details-divider"></div>
                <div class="card-section">
                  <h5>Additional Details</h5>
                  <p><i class='bx bx-time'></i> <strong>Created:</strong> <?php echo date('F d, Y H:i:s', strtotime($earning->created_at)); ?></p>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-payments">
        <div class="empty-state">
          <i class='bx bx-receipt bx-lg'></i>
          <h3>No Payment Records Found</h3>
          <p>There are no payment records available for the selected time period.</p>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Search functionality with delay for better performance
      const searchInput = document.getElementById('searchInput');
      let searchTimeout;
      
      searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
          const searchText = searchInput.value.toLowerCase();
          performSearch(searchText);
        }, 300); // 300ms delay for typing
      });
      
      function performSearch(searchText) {
        // Filter table rows
        const rows = document.querySelectorAll('.payments-table tbody tr.detail-row');
        const cards = document.querySelectorAll('.payment-card');
        let visibleCount = 0;
        
        // Table view
        rows.forEach(row => {
          const shouldShow = row.textContent.toLowerCase().includes(searchText);
          row.style.display = shouldShow ? '' : 'none';
          if (shouldShow) visibleCount++;
          
          // Also hide the details row if hiding the main row
          const detailsId = row.getAttribute('data-id');
          const detailsRow = document.getElementById('details-' + detailsId);
          if (detailsRow) {
            detailsRow.style.display = 'none';
          }
        });
        
        // Mobile cards view
        cards.forEach(card => {
          card.style.display = card.textContent.toLowerCase().includes(searchText) ? '' : 'none';
        });
        
        // Show no results message if needed
        const noResultsMsg = document.querySelector('.no-search-results');
        if (visibleCount === 0 && rows.length > 0) {
          if (!noResultsMsg) {
            const tableBody = document.querySelector('.payments-table tbody');
            const noResults = document.createElement('tr');
            noResults.className = 'no-search-results';
            noResults.innerHTML = '<td colspan="9">No payment records match your search</td>';
            tableBody.appendChild(noResults);
          }
        } else if (noResultsMsg) {
          noResultsMsg.remove();
        }
      }
      
      // Table Row click to show details (desktop)
      document.querySelectorAll('.detail-row').forEach(row => {
        row.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const detailsRow = document.getElementById('details-' + id);
          
          // Toggle active class on clicked row
          this.classList.toggle('active-row');
          
          // Hide all other details rows first
          document.querySelectorAll('.expanded-details').forEach(el => {
            if (el.id !== 'details-' + id) {
              el.style.display = 'none';
              // Remove active class from other rows
              const rowId = el.id.replace('details-', '');
              const relatedRow = document.querySelector(`.detail-row[data-id="${rowId}"]`);
              if (relatedRow) relatedRow.classList.remove('active-row');
            }
          });
          
          // Toggle this details row
          if (detailsRow) {
            detailsRow.style.display = detailsRow.style.display === 'none' ? 'table-row' : 'none';
            // If hiding, also remove active class
            if (detailsRow.style.display === 'none') {
              this.classList.remove('active-row');
            }
          }
        });
      });
      
      // Card toggle for mobile view
      document.querySelectorAll('.card-toggle, .card-header').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
          // Prevent default if it's an anchor tag
          e.preventDefault();
          
          const card = this.closest('.payment-card');
          const cardBody = card.querySelector('.card-body');
          const details = card.querySelector('.card-details');
          const icon = card.querySelector('.card-toggle .bx');
          
          // Toggle expanded class on card
          card.classList.toggle('expanded');
          
          if (details) {
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
          }
          
          if (icon) {
            icon.classList.toggle('bx-chevron-down');
            icon.classList.toggle('bx-chevron-up');
          }
        });
      });
      
      // Table sorting functionality
      document.querySelectorAll('th[data-sort]').forEach(header => {
        header.addEventListener('click', function() {
          const sortBy = this.getAttribute('data-sort');
          const table = document.getElementById('paymentsTable');
          const tbody = table.querySelector('tbody');
          const rows = Array.from(tbody.querySelectorAll('tr.detail-row'));
          
          // Toggle sort direction
          const currentDir = this.getAttribute('data-dir') || 'asc';
          const newDir = currentDir === 'asc' ? 'desc' : 'asc';
          
          // Reset all headers
          document.querySelectorAll('th[data-sort]').forEach(th => {
            th.removeAttribute('data-dir');
            th.classList.remove('sorted-asc', 'sorted-desc');
          });
          
          // Set current header sort direction
          this.setAttribute('data-dir', newDir);
          this.classList.add(newDir === 'asc' ? 'sorted-asc' : 'sorted-desc');
          
          // Sort rows
          rows.sort((a, b) => {
            let valueA, valueB;
            
            if (sortBy === 'date') {
              valueA = new Date(a.querySelector(`td[data-label="Date"]`).textContent);
              valueB = new Date(b.querySelector(`td[data-label="Date"]`).textContent);
            } else {
              // For numeric values, extract numbers from text
              const textA = a.querySelector(`td:nth-child(${Array.from(this.parentNode.children).indexOf(this) + 1})`).textContent;
              const textB = b.querySelector(`td:nth-child(${Array.from(this.parentNode.children).indexOf(this) + 1})`).textContent;
              
              valueA = parseFloat(textA.replace(/[^0-9.-]+/g, ""));
              valueB = parseFloat(textB.replace(/[^0-9.-]+/g, ""));
              
              if (isNaN(valueA)) valueA = textA;
              if (isNaN(valueB)) valueB = textB;
            }
            
            if (valueA < valueB) return newDir === 'asc' ? -1 : 1;
            if (valueA > valueB) return newDir === 'asc' ? 1 : -1;
            return 0;
          });
          
          // Reorder rows
          rows.forEach(row => {
            const rowId = row.getAttribute('data-id');
            const detailsRow = document.getElementById('details-' + rowId);
            
            // Move the row
            tbody.appendChild(row);
            
            // Move its details row too
            if (detailsRow) {
              tbody.appendChild(detailsRow);
            }
          });
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
    --primary-color: var(--main);
    --primary-light:rgb(250, 255, 230);
    --secondary-color: #2ECC71;
    --text-primary: #2c3e50;
    --text-secondary: #6c757d;
    --background-light: #f8f9fa;
    --background-card: #ffffff;
    --border-color: #e0e0e0;
    --border-light: #f0f0f0;
    --completed-color: #2ecc71;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --table-header-bg: #f5f7ff;
    --hover-light: #f9fafe;
    --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --border-radius-sm: 4px;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
    --transition-speed: 0.3s;
  }

  /* Layout & Common Styles */
  main {
    padding: var(--spacing-lg);
    max-width: 1400px;
    margin: 0 auto;
  }

  .card {
    background-color: var(--background-card);
    border-radius: var(--border-radius-md);
    box-shadow: var(--card-shadow);
    padding: var(--spacing-lg);
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
    font-weight: 600;
  }

  .breadcrumb {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color var(--transition-speed);
  }

  .breadcrumb a:hover {
    color: var(--primary-color);
  }

  .breadcrumb i {
    color: var(--primary-color);
  }

  .btn-download {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    transition: background-color var(--transition-speed);
  }

  .btn-download:hover {
    background-color: #3445d2;
  }

  /* Section Header */
  .section-header {
    margin-bottom: var(--spacing-lg);
  }

  /* Filter Form */
  .filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    align-items: center;
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
    white-space: nowrap;
  }

  .filter-group select {
    padding: var(--spacing-sm) var(--spacing-md);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    background-color: white;
    min-width: 150px;
    font-size: 0.95rem;
  }

  .filter-btn {
    padding: var(--spacing-sm) var(--spacing-md);
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    transition: background-color var(--transition-speed);
  }

  .filter-btn:hover {
    background-color: #3445d2;
  }

  /* Search Input */
  .search-container {
    position: relative;
    margin-top: var(--spacing-md);
  }

  .search-container i {
    position: absolute;
    left: var(--spacing-md);
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
  }

  #searchInput {
    padding: var(--spacing-md) var(--spacing-md) var(--spacing-md) calc(var(--spacing-md) * 2.5);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    width: 100%;
    font-size: 0.95rem;
  }

  #searchInput:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(65, 84, 241, 0.15);
  }

  /* Summary Section */
  .summary-container {
    margin-bottom: var(--spacing-lg);
  }

  .summary-card {
    background-color: var(--background-card);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
  }

  .summary-title {
    padding: var(--spacing-md);
    background-color: var(--primary-light);
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .summary-title i {
    color: var(--primary-color);
  }

  .summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-md);
    padding: var(--spacing-lg);
  }

  .summary-item {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-sm);
    background-color: #f8f9fa;
    transition: transform var(--transition-speed), box-shadow var(--transition-speed);
  }

  .summary-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  }

  .summary-item.total {
    grid-column: 1 / -1;
    background-color: var(--primary-light);
    border-top: 1px solid var(--border-color);
    padding: var(--spacing-md);
    margin-top: var(--spacing-sm);
  }

  .summary-label {
    font-weight: 500;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
  }

  .summary-label i {
    color: var(--primary-color);
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
    background-color: var(--background-card);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
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
    border-bottom: 1px solid var(--border-light);
  }

  .payments-table th {
    background-color: var(--table-header-bg);
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 10;
    cursor: pointer;
    transition: background-color var(--transition-speed);
  }

  .payments-table th:hover {
    background-color: #e6e9ff;
  }

  .payments-table th i {
    font-size: 0.8rem;
    margin-left: var(--spacing-xs);
    opacity: 0.4;
  }

  .payments-table th.sorted-asc i,
  .payments-table th.sorted-desc i {
    opacity: 1;
  }

  .payments-table th.sorted-asc i::before {
    content: "\ea77"; /* bx-up-arrow-alt */
  }

  .payments-table th.sorted-desc i::before {
    content: "\ea4a"; /* bx-down-arrow-alt */
  }

  .payments-table tr:last-child td {
    border-bottom: none;
  }

  /* Clickable rows */
  .detail-row {
    cursor: pointer;
    transition: background-color var(--transition-speed);
  }

  .detail-row:hover {
    background-color: var(--hover-light);
  }

  .detail-row.active-row {
    background-color: var(--primary-light);
  }

  /* Expanded details */
  .expanded-details td {
    background-color: #f8f9fa;
    padding: 0;
  }

  .details-content {
    padding: var(--spacing-lg);
  }

  .details-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
  }

  .details-header h4 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.1rem;
  }

  .details-date {
    color: var(--text-secondary);
    font-style: italic;
  }

  .details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
  }

  .details-column h5 {
    margin-top: 0;
    margin-bottom: var(--spacing-md);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
  }

  .details-column h5 i {
    color: var(--primary-color);
  }

  .details-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
    padding: var(--spacing-sm) 0;
    border-bottom: 1px dashed var(--border-light);
  }

  .details-item:last-child {
    border-bottom: none;
  }

  .details-label {
    color: var(--text-secondary);
  }

  .details-value {
    font-weight: 500;
    color: var(--text-primary);
  }

  .details-footer {
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 0.9rem;
  }

  .no-search-results {
    text-align: center;
    padding: var(--spacing-lg);
    color: var(--text-secondary);
    font-style: italic;
  }

  .no-payments {
    padding: var(--spacing-lg);
  }

  .empty-state {
    padding: var(--spacing-xl) var(--spacing-lg);
    text-align: center;
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    color: var(--text-secondary);
  }

  .empty-state i {
    font-size: 3rem;
    color: var(--text-secondary);
    opacity: 0.7;
    margin-bottom: var(--spacing-md);
  }

  .empty-state h3 {
    margin-bottom: var(--spacing-sm);
    color: var(--text-primary);
  }

  /* Mobile Cards View */
  .payments-cards {
    display: none;
  }

  .payment-card {
    background-color: var(--background-card);
    border-radius: var(--border-radius-md);
    box-shadow: var(--card-shadow);
    margin-bottom: var(--spacing-md);
    overflow: hidden;
    transition: box-shadow var(--transition-speed);
  }

  .payment-card:hover {
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
  }

  .payment-card.expanded {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    background-color: var(--primary-light);
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
  }

  .card-id {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--text-primary);
    font-weight: 500;
  }

  .card-id i {
    color: var(--primary-color);
  }

  .card-amount {
    font-weight: 600;
    color: var(--primary-color);
  }

  .card-toggle {
    color: var(--text-secondary);
    transform: translateX(var(--spacing-xs));
  }

  .card-body {
    padding: var(--spacing-md);
  }

  .card-section {
    margin-bottom: var(--spacing-md);
  }

  .card-section h5 {
    font-size: 1rem;
    margin-top: 0;
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-xs);
    border-bottom: 1px solid var(--border-light);
    color: var(--text-primary);
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

  .card-row .value {
    color: var(--text-primary);
  }

  .card-row.total .value {
    color: var(--primary-color);
    font-weight: 600;
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
      gap: var(--spacing-md);
    }

    .summary-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .details-grid {
      grid-template-columns: 1fr;
      gap: var(--spacing-lg);
    }
    
    .filter-form {
      flex-direction: column;
      align-items: flex-start;
      width: 100%;
    }
    
    .filter-group {
      width: 100%;
    }
    
    .filter-group select {
      flex-grow: 1;
    }
    
    .filter-btn {
      width: 100%;
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

    .summary-grid {
      grid-template-columns: 1fr;
      padding: var(--spacing-md);
    }
    
    .search-container {
      margin-top: var(--spacing-md);
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
    
    .card {
      padding: var(--spacing-md);
    }

    .card-header {
      flex-wrap: wrap;
      gap: var(--spacing-xs);
    }
    
    .card-amount {
      order: 1;
      width: 100%;
      margin-top: var(--spacing-xs);
    }
    
    .card-toggle {
      position: absolute;
      right: var(--spacing-md);
      top: var(--spacing-md);
    }
    
    .summary-title {
      font-size: 1rem;
    }
    
    .summary-item {
      padding: var(--spacing-sm);
    }
  }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>