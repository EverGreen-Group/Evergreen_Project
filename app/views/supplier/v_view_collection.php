<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/collections/styles.css">

<main>
  <div class="head-title">
    <div class="left">
      <h1>My Collections</h1>
      <ul class="breadcrumb">
        <li>
          <i class='bx bx-home'></i>
          <a href="<?php echo URLROOT; ?>/Supplier/dashboard/">Dashboard</a>
        </li>
        <li>
          <span>Collections</span>
        </li>
      </ul>
    </div>
  </div>

  <div class="collections-section">
    <div class="section-header">
      <div class="filter-container">
        <input type="text" id="searchInput" placeholder="Search collections...">
        <div class="filter-controls">
          <select id="statusFilter">
            <option value="">All Statuses</option>
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
          </select>
          <select id="dateFilter">
            <option value="">All Dates</option>
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
            <option value="90">Last 90 days</option>
          </select>
          <button id="resetFilters" class="reset-btn">
            <i class='bx bx-reset'></i> Reset
          </button>
        </div>
      </div>
    </div>
    <?php if (!empty($data['collections'])): ?>
      <div class="collections-container">
        <div class="table-wrapper">
          <table class="collections-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Driver</th>
                <th>Vehicle</th>
                <th>Quantity</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data['collections'] as $collection): ?>
                <tr data-date="<?php echo strtotime($collection->created_at); ?>" data-status="<?php echo $collection->status; ?>">
                  <td data-label="Date"><?php echo date('M d, Y', strtotime($collection->created_at)); ?></td>
                  <td data-label="Status">
                    <span class="status-badge <?php echo $collection->status === 'Pending' ? 'pending' : 'completed'; ?>">
                      <?php echo $collection->status; ?>
                    </span>
                  </td>
                  <td data-label="Driver ID"><?php echo $collection->driver_name; ?></td>
                  <td data-label="Vehicle ID"><?php echo $collection->license_plate; ?></td>
                  <td data-label="Quantity"><?php echo $collection->quantity; ?> kg</td>
                  <td data-label="Action">
                    <a href="<?php echo URLROOT; ?>/supplier/collectionBags/<?php echo $collection->collection_id; ?>" class="view-btn">
                      <i class='bx bx-show'></i> <span class="btn-text">View Details</span>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Mobile Cards View (Only shown on small screens) -->
      <div class="collections-cards">
        <?php foreach($data['collections'] as $collection): ?>
          <div class="collection-card" data-date="<?php echo strtotime($collection->created_at); ?>" data-status="<?php echo $collection->status; ?>">
            <div class="card-header">
              <div class="card-id">
                <span class="label">Collection ID:</span>
                <span class="value"><?php echo $collection->collection_id; ?></span>
              </div>
              <div class="card-status">
                <span class="status-badge <?php echo $collection->status === 'Pending' ? 'pending' : 'completed'; ?>">
                  <?php echo $collection->status; ?>
                </span>
              </div>
            </div>
            <div class="card-body">
              <div class="card-row">
                <span class="label">Date:</span>
                <span class="value"><?php echo date('M d, Y', strtotime($collection->created_at)); ?></span>
              </div>
              <div class="card-row">
                <span class="label">Driver ID:</span>
                <span class="value"><?php echo $collection->driver_id; ?></span>
              </div>
              <div class="card-row">
                <span class="label">Vehicle ID:</span>
                <span class="value"><?php echo $collection->vehicle_id; ?></span>
              </div>
              <div class="card-row">
                <span class="label">Quantity:</span>
                <span class="value"><?php echo $collection->quantity; ?> kg</span>
              </div>
            </div>
            <div class="card-footer">
              <a href="<?php echo URLROOT; ?>/supplier/collectionBags/<?php echo $collection->collection_id; ?>" class="view-btn">
                <i class='bx bx-show'></i> View Details
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-collections">
        <p>You don't have any completed collections yet.</p>
      </div>
    <?php endif; ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('searchInput');
      const statusFilter = document.getElementById('statusFilter');
      const dateFilter = document.getElementById('dateFilter');
      const resetFilters = document.getElementById('resetFilters');
      const tableRows = document.querySelectorAll('.collections-table tbody tr');
      const cards = document.querySelectorAll('.collection-card');
      

      function applyFilters() {
        const searchText = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const dateValue = dateFilter.value ? parseInt(dateFilter.value) : 0;
        const currentDate = Math.floor(Date.now() / 1000); 
        

        tableRows.forEach(row => {
          const rowText = row.textContent.toLowerCase();
          const rowStatus = row.getAttribute('data-status');
          const rowDate = parseInt(row.getAttribute('data-date'));
          
          const matchesSearch = searchText === '' || rowText.includes(searchText);
          const matchesStatus = statusValue === '' || rowStatus === statusValue;
          const matchesDate = dateValue === 0 || (currentDate - rowDate) < (dateValue * 24 * 60 * 60);
          
          row.style.display = (matchesSearch && matchesStatus && matchesDate) ? '' : 'none';
        });
        

        cards.forEach(card => {
          const cardText = card.textContent.toLowerCase();
          const cardStatus = card.getAttribute('data-status');
          const cardDate = parseInt(card.getAttribute('data-date'));
          
          const matchesSearch = searchText === '' || cardText.includes(searchText);
          const matchesStatus = statusValue === '' || cardStatus === statusValue;
          const matchesDate = dateValue === 0 || (currentDate - cardDate) < (dateValue * 24 * 60 * 60);
          
          card.style.display = (matchesSearch && matchesStatus && matchesDate) ? '' : 'none';
        });
      }
      

      searchInput.addEventListener('input', applyFilters);
      statusFilter.addEventListener('change', applyFilters);
      dateFilter.addEventListener('change', applyFilters);
      

      resetFilters.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        dateFilter.value = '';
        applyFilters();
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

  /* Filter Styles */
  .filter-container {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    align-items: center;
  }

  .filter-controls {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
  }

  #searchInput, 
  #statusFilter, 
  #dateFilter {
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    font-size: 0.9rem;
  }

  #searchInput {
    flex: 1;
    min-width: 200px;
  }

  #statusFilter, 
  #dateFilter {
    min-width: 120px;
  }

  .reset-btn {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm);
    background-color: var(--text-secondary);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .reset-btn:hover {
    background-color: var(--text-primary);
  }

  /* Collections Section */
  .collections-section {
    margin-bottom: var(--spacing-xl);
  }

  .collections-container {
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  .table-wrapper {
    width: 100%;
    overflow-x: auto;
  }

  .collections-table {
    width: 100%;
    border-collapse: collapse;
  }

  .collections-table th,
  .collections-table td {
    padding: var(--spacing-md);
    text-align: left;
    border-bottom: 1px solid var(--border-color);
  }

  .collections-table th {
    background-color: var(--table-header-bg);
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
  }

  .collections-table tr:last-child td {
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
    background-color: #f39c12;
  }

  .view-btn {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs) var(--spacing-sm);
    background-color: var(--primary-color);
    color: white;
    border-radius: var(--border-radius-sm);
    text-decoration: none;
    font-size: 0.875rem;
    transition: background-color 0.3s ease;
    white-space: nowrap;
  }

  .view-btn:hover {
    background-color: var(--secondary-color);
  }

  .no-collections {
    padding: var(--spacing-lg);
    text-align: center;
    background-color: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    color: var(--text-secondary);
  }

  /* Mobile Cards View */
  .collections-cards {
    display: none;
  }

  .collection-card {
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

  .card-footer {
    padding: var(--spacing-md);
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
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
  }

  @media (max-width: 768px) {
    .collections-container {
      display: none;
    }

    .collections-cards {
      display: block;
    }

    .head-title h1 {
      font-size: 1.5rem;
    }

    .filter-container {
      flex-direction: column;
      align-items: stretch;
    }

    #searchInput {
      width: 100%;
    }

    .filter-controls {
      width: 100%;
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

    .card-footer {
      justify-content: center;
    }

    .view-btn {
      width: 100%;
      justify-content: center;
    }

    #statusFilter, 
    #dateFilter {
      width: 100%;
    }

    .reset-btn {
      width: 100%;
      justify-content: center;
    }
  }
</style>