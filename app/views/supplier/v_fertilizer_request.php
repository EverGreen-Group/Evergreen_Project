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

  <section class="fertilizer-section">

        <div class="product-grid" id="fertilizerGrid">
            <?php if (isset($fertilizer_types) && !empty($fertilizer_types)): ?>
                <?php foreach ($fertilizer_types as $fertilizer): ?>
                    <div class="product-card" data-fertilizer='<?php echo htmlspecialchars(json_encode($fertilizer)); ?>'>
                        <div class="product-image">
                            <img src="<?php echo URLROOT; ?>/uploads/fertilizers/<?php echo $fertilizer->image_path?>" alt="<?php echo $fertilizer->fertilizer_name; ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo $fertilizer->fertilizer_name; ?></h3>
                            <p>Company: <?php echo $fertilizer->company_name; ?></p>
                            <p>Details: <?php echo $fertilizer->details; ?></p>
                            <p>Price: Rs.<?php echo $fertilizer->price; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No fertilizers available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>

  <!-- New Fertilizer Request Form Section -->
  <div class="request-form-section">
    <div class="section-header">
      <h3><i class='bx bx-cart-add'></i> New Fertilizer Request</h3>
    </div>
    <div class="form-card">
      <form id="fertilizer-request-form" action="<?php echo URLROOT; ?>/Supplier/createFertilizerOrder" method="post">
        <!-- Add hidden supplier_id field -->
        <input type="hidden" name="supplier_id" value="<?php echo $_SESSION['supplier_id']; ?>">
        
        <div class="form-grid">
          <div class="form-group">
            <label for="fertilizer_id">Fertilizer Type:</label>
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
        </div>

        <div class="form-action">
          <button type="submit" class="submit-btn">
            <i class='bx bx-cart-add'></i> Submit Request
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Fertilizer Request History Section -->
  <div class="request-history-section">
    <div class="section-header">
      <h3><i class='bx bx-history'></i> Fertilizer Request History</h3>
    </div>
    <?php if (!empty($data['orders'])): ?>
      <div class="orders-container">
        <?php foreach($data['orders'] as $order): ?>
          <div class="order-card searchable-item" data-status="<?php echo strtolower(isset($order->status) ? $order->status : 'pending'); ?>" data-date="<?php echo date('Y-m-d', strtotime($order->order_date)); ?>">
            <div class="card-content">
              <div class="card-header">
                <div class="order-id">
                  <i class='bx bx-hash'></i>
                  <span>Order #<?php echo $order->order_id; ?></span>
                </div>
                <div class="status-badge <?php echo strtolower(isset($order->status) ? $order->status : 'pending'); ?>">
                  <?php echo isset($order->status) ? $order->status : 'Pending'; ?>
                </div>
              </div>
              <div class="card-body">
                <div class="order-details">
                  <div class="info-item">
                    <i class='bx bx-box'></i>
                    <span><?php echo $order->fertilizer_name; ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-calendar'></i>
                    <span><?php echo date('M d, Y', strtotime($order->order_date)); ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-time'></i>
                    <span><?php echo $order->order_time; ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-package'></i>
                    <span>Quantity: <?php echo $order->quantity; ?></span>
                  </div>
                  <div class="info-item">
                    <i class='bx bx-dollar'></i>
                    <span>රු<?php echo number_format($order->total_amount, 2); ?></span>
                  </div>
                </div>
                
                <!-- Action buttons -->
                <div class="order-actions">
                  <?php if (isset($order->status) && strtolower($order->status) === 'pending'): ?>
                    <button class="edit-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $order->order_id; ?>'">
                      <i class='bx bx-edit'></i> Edit
                    </button>
                    <button class="delete-btn" onclick="confirmDelete(<?php echo $order->order_id; ?>)">
                      <i class='bx bx-trash'></i> Delete
                    </button>
                  <?php else: ?>
                    <button class="edit-btn disabled" disabled title="Cannot edit non-pending orders">
                      <i class='bx bx-edit'></i> Edit
                    </button>
                    <button class="delete-btn disabled" disabled title="Cannot delete non-pending orders">
                      <i class="bx bx-trash"></i> Delete
                    </button>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-orders">
        <i class='bx bx-package empty-icon'></i>
        <p>No fertilizer requests found.</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<script>
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

  // Search and Filter functionality
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const searchableItems = document.querySelectorAll('.searchable-item');

    // Search function
    function performSearch() {
      const searchTerm = searchInput.value.toLowerCase();
      const statusValue = statusFilter.value;
      const dateValue = dateFilter.value;

      searchableItems.forEach(item => {
        let shouldShow = true;
        
        // Text search
        if (searchTerm) {
          const itemText = item.textContent.toLowerCase();
          shouldShow = itemText.includes(searchTerm);
        }
        
        // Status filter
        if (shouldShow && statusValue !== 'all' && item.hasAttribute('data-status')) {
          const itemStatus = item.getAttribute('data-status');
          shouldShow = itemStatus === statusValue;
        }
        
        // Date filter
        if (shouldShow && dateValue !== 'all' && item.hasAttribute('data-date')) {
          const itemDate = new Date(item.getAttribute('data-date'));
          const today = new Date();
          today.setHours(0, 0, 0, 0);
          
          if (dateValue === 'today') {
            const todayStr = today.toISOString().split('T')[0];
            const itemDateStr = itemDate.toISOString().split('T')[0];
            shouldShow = todayStr === itemDateStr;
          } else if (dateValue === 'this-week') {
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() - today.getDay());
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);
            shouldShow = itemDate >= weekStart && itemDate <= weekEnd;
          } else if (dateValue === 'this-month') {
            shouldShow = itemDate.getMonth() === today.getMonth() && 
                         itemDate.getFullYear() === today.getFullYear();
          }
        }
        
        // Show or hide item
        item.style.display = shouldShow ? '' : 'none';
      });
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    statusFilter.addEventListener('change', performSearch);
    dateFilter.addEventListener('change', performSearch);
  });
</script>

<style>
  /* Root Variables */
  :root {
    --primary-color: var(--mainn);
    --secondary-color: #2ecc71;
    --text-primary: #2c3e50;
    --text-secondary: #7f8c8d;
    --background-light: #f8f9fa;
    --background-card: #ffffff;
    --border-color: #e0e0e0;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --pending-color: #4895ef;
    --approved-color: #2a9d8f;
    --delivered-color: #27ae60;
    --cancelled-color: #e76f51;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --border-radius-sm: 6px;
    --border-radius-md: 10px;
    --border-radius-lg: 14px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  /* Layout & Common Styles */
  main {
    padding: var(--spacing-sm); /* Reduced left margin */
    max-width:100%;
    margin: 0 auto;
  }


  /* Header Styles */
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
    color: var(--text-secondary);
  }

  .breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .breadcrumb a:hover {
    color: var(--primary-color);
  }

  .breadcrumb i {
    color: var(--primary-color);
    font-size: 1.1rem;
  }


/*cards*/
  .fertilizer-main {
      background: none;
      min-height: 100vh;
      padding: 0 5%;
      color: #000;
      margin-top: 100px;
  }

  .fertilizer-section {
      background: #fff;
      padding: 45px 0 10px;
      border-radius: 20px;
      margin-top: 20px;
      margin-bottom: 30px;
  }

  .fertilizer-section h2 {
      color: #333;
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 3rem;
  }

  .product-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1200px;
      margin: 20px auto;
      padding: 0 2rem;
      gap: 20px;
  }

  .search-input {
      padding: 10px;
      width: 100%;
      max-width: 300px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
  }

  .sort-select {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
  }

  .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      max-width: 1200px;
      padding: 2rem;
  }

  .product-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.2s;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .product-card:hover {
      transform: scale(1.05);
  }

  .product-image img {
      width: 100%;
      height: 200px;
      object-fit: cover;
  }

  .product-info {
      padding: 15px;
      text-align: center;
  }

  .product-info h3 {
      margin: 0;
      font-size: 1.2em;
      color: #333;
  }

  .product-info p {
      margin: 5px 0;
      color: #555;
  }

  /* Modal Styles */
  .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
  }

  .modal-content {
      background: white;
      padding: 20px;
      border-radius: 8px;
      max-width: 500px;
      text-align: center;
  }

  .modal-content img {
      max-width: 100%;
      height: auto;
      margin: 10px 0;
  }

  .modal-content button {
      background-color: #22a45d;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
  }

  .modal-content button:hover {
      background-color: #1b8e4a;
  }

  @media (max-width: 768px) {
      .product-grid {
          grid-template-columns: 1fr;
      }

      .product-controls {
          flex-direction: column;
          align-items: stretch;
      }

      .search-input, .sort-select {
          max-width: 100%;
      }

      .fertilizer-section h2 {
          font-size: 1.5rem;
      }
  }


  /* Request Form Section */
  .request-form-section {
    margin-bottom: var(--spacing-xl);
  }

  /* Section Headers */
  .section-header {
    margin-bottom: var(--spacing-md);
    display: flex;
    align-items: center;
  }

  .section-header h3 {
    font-size: 1.25rem;
    color: var(--text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .section-header h3 i {
    color: var(--primary-color);
    font-size: 1.3rem;
  }

  /* Section Containers */
  .available-fertilizers-section,
  .request-form-section,
  .request-history-section {
    margin-bottom: var(--spacing-xl);
    background-color: var(--background-card);
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--box-shadow);
  }

  /* Table Styles */
  .table-responsive {
    overflow-x: auto;
  }

  .fertilizer-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: var(--spacing-md);
  }

  .fertilizer-table th,
  .fertilizer-table td {
    padding: var(--spacing-md);
    text-align: left;
    border-bottom: 1px solid var(--border-color);
  }

  .fertilizer-table th {
    background-color: var(--background-light);
    color: var(--text-primary);
    font-weight: 600;
  }

  .fertilizer-table tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
  }

  /* Form Styles */
  .form-card {
    background-color: var(--background-light);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
  }

  .form-group label {
    color: var(--text-primary);
    font-weight: 500;
  }

  .form-group select,
  .form-group input {
    padding: var(--spacing-md);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    background-color: white;
    color: var(--text-primary);
    outline: none;
  }

  .read-only-group input {
    background-color: var(--background-light);
    cursor: not-allowed;
  }

  .form-action {
    display: flex;
    justify-content: flex-end;
  }

  .submit-btn {
    padding: var(--spacing-md) var(--spacing-lg);
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    transition: background-color 0.3s ease;
  }

  .submit-btn:hover {
    background-color: var(--secondary-color);
  }

  /* Order Cards */
  .orders-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: var(--spacing-lg);
  }

  .order-card {
    background-color: white;
    border-radius: var(--border-radius-md);
    overflow: hidden;
    box-shadow: var(--box-shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  }

  .card-content {
    display: flex;
    flex-direction: column;
  }

  .card-header {
    padding: var(--spacing-md);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .order-id {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-weight: 600;
    color: var(--text-primary);
  }

  .status-badge {
    padding: var(--spacing-xs) var(--spacing-md);
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    color: white;
  }

  .status-badge.pending {
    background-color: var(--pending-color);
  }

  .status-badge.approved {
    background-color: var(--approved-color);
  }

  .status-badge.delivered {
    background-color: var(--delivered-color);
  }

  .status-badge.cancelled {
    background-color: var(--cancelled-color);
  }

  .card-body {
    padding: var(--spacing-md);
  }

  .order-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
  }

  .info-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .info-item i {
    color: var(--primary-color);
    font-size: 1.1rem;
  }

  .order-actions {
    display: flex;
    justify-content: flex-end;
    gap: var(--spacing-md);
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--border-color);
  }

  .edit-btn,
  .delete-btn {
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--border-radius-sm);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-weight: 500;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .edit-btn {
    background-color: var(--primary-color);
    color: white;
  }

  .edit-btn:hover {
    background-color: var(--secondary-color);
    transform: translateY(-2px);
  }

  .delete-btn {
    background-color: transparent;
    border: 1px solid var(--danger-color);
    color: var(--danger-color);
  }

  .delete-btn:hover {
    background-color: var(--danger-color);
    color: white;
    transform: translateY(-2px);
  }

  .disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .disabled:hover {
    transform: none;
  }

  /* Empty States */
  .no-orders {
    padding: var(--spacing-xl);
    text-align: center;
    background-color: var(--background-light);
    border-radius: var(--border-radius-md);
    color: var(--text-secondary);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
  }

  .empty-icon {
    font-size: 3rem;
    color: var(--text-secondary);
    opacity: 0.6;
  }

  /* Responsive Design */
  @media (max-width: 992px) {
    .form-grid {
      grid-template-columns: 1fr;
    }
    
    .orders-container {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 768px) {
    .search-filter-container {
      flex-direction: column;
      align-items: stretch;
    }
    
    .search-box {
      max-width: none;
    }
    
    .filter-options {
      flex-wrap: wrap;
    }
    
    .filter-options select {
      flex-grow: 1;
    }
    
    .order-details {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 480px) {
    main {
      padding: var(--spacing-md);
    }
    
    .available-fertilizers-section,
    .request-form-section,
    .request-history-section {
      padding: var(--spacing-md);
    }
    
    .form-grid {
      gap: var(--spacing-md);
    }
  }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>