<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier/fertilizer_requests/styles.css">

<main>
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

  <div class="request-form-section">
    <div class="section-header">
      <h3>New Fertilizer Request</h3>
    </div>
    <div class="request-form-card">
      <form id="fertilizer-request-form" action="<?php echo URLROOT; ?>/Supplier/createFertilizerOrder" method="post">
        <div class="form-group">
          <label for="fertilizer">Fertilizer Type:</label>
          <select id="fertilizer" name="fertilizer" required onchange="updateTotalAmount()">
            <option value="">-- Select Fertilizer --</option>
            <?php foreach($data['fertilizer_types'] as $type): ?>
              <option value="<?php echo $type->id; ?>"
                data-price="<?php echo $type->price; ?>"
                data-quantity="<?php echo $type->quantity; ?>"
                data-unit="<?php echo $type->unit; ?>">
                <?php echo $type->name; ?> (<?php echo $type->unit; ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="quantity">Quantity:</label>
          <input type="number" id="quantity" name="quantity" min="1" required oninput="updateTotalAmount()">
        </div>
        <div class="form-group read-only-group">
          <label for="total_amount">Total Amount (Rs.):</label>
          <input type="text" id="total_amount" name="total_amount" readonly>
        </div>
        <div class="form-group">
          <button type="submit" class="submit-btn">Submit Request</button>
        </div>
      </form>
    </div>
  </div>

  <div class="request-history-section">
    <div class="section-header">
      <h3>Fertilizer Request History</h3>
    </div>
    <?php if (!empty($data['orders'])): ?>
      <?php foreach($data['orders'] as $order): ?>
        <div class="schedule-card">
          <div class="card-content">
            <div class="card-header">
              <div class="status-badge">
                Order #<?php echo $order->order_id; ?>
              </div>
            </div>
            <div class="card-body">
              <div class="schedule-info">
                <div class="info-item">
                  <i class='bx bx-box'></i>
                  <span>Fertilizer: <?php echo $order->fertilizer_name; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-calendar'></i>
                  <span>Date: <?php echo date('m/d/Y', strtotime($order->order_date)); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-time-five'></i>
                  <span>Time: <?php echo $order->order_time; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-calculator'></i>
                  <span>Quantity: <?php echo $order->quantity; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-dollar'></i>
                  <span>Total Amount: <?php echo 'රු.' . number_format($order->total_amount, 2); ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-check-circle'></i>
                  <span>Status: <?php echo isset($order->status) ? $order->status : 'Pending'; ?></span>
                </div>
                <div class="info-item">
                  <i class='bx bx-check-circle'></i>
                  <span>Payment Status: <?php echo isset($order->payment_status) ? $order->payment_status : 'pending'; ?></span>
                </div>
                <?php if ($order->delivery_date): ?>
                  <div class="info-item">
                    <i class='bx bx-calendar'></i>
                    <span>Delivery Date: <?php echo date('m/d/Y', strtotime($order->delivery_date)); ?></span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="schedule-action">
                <?php if ($order->status === 'Pending'): ?>
                  <button class="update-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $order->order_id; ?>'">
                    <i class='bx bx-edit'></i>
                  </button>
                  <button class="cancel-btn" onclick="deleteOrder(<?php echo $order->order_id; ?>)">
                    <i class='bx bx-trash'></i>
                  </button>
                <?php endif; ?>
                <?php if ($order->status === 'Accepted'): ?>
                  <button class="finalize-btn" onclick="location.href='<?php echo URLROOT; ?>/Supplier/finalizeFertilizerOrder/<?php echo $order->order_id; ?>'">
                    <i class='bx bx-check'></i> Finalize
                  </button>
                <?php endif; ?>
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

<script>
function updateTotalAmount() {
  const fertilizerSelect = document.getElementById('fertilizer');
  const quantityInput = document.getElementById('quantity');
  const totalAmountInput = document.getElementById('total_amount');

  const selectedOption = fertilizerSelect.options[fertilizerSelect.selectedIndex];
  if (!selectedOption || selectedOption.value === "") {
    totalAmountInput.value = '';
    return;
  }

  const price = parseFloat(selectedOption.getAttribute('data-price'));
  const quantity = parseFloat(quantityInput.value);

  const availableQuantity = parseFloat(selectedOption.getAttribute('data-quantity'));
  const unit = selectedOption.getAttribute('data-unit');
  if (!isNaN(quantity) && quantity > availableQuantity) {
    alert(`Requested quantity exceeds available stock. Only ${availableQuantity} ${unit} available.`);
    quantityInput.value = availableQuantity;
  }

  if (!isNaN(quantity) && !isNaN(price)) {
    totalAmountInput.value = (quantity * price).toFixed(2);
  } else {
    totalAmountInput.value = '';
  }
}

function deleteOrder(orderId) {
  fetch(`<?php echo URLROOT; ?>/Supplier/checkFertilizerOrderStatus/${orderId}`)
    .then(response => response.json())
    .then(data => {
      if (data.canDelete) {
        if (confirm('Are you sure you want to delete this order?')) {
          fetch(`<?php echo URLROOT; ?>/Supplier/deleteFertilizerRequest/${orderId}`, {
            method: 'POST'
          })
            .then(response => response.json())
            .then(result => {
              if (result.success) {
                alert(result.message);
                location.reload();
              } else {
                alert(result.message);
              }
            })
            .catch(error => {
              alert('An error occurred while deleting the order.');
              console.error(error);
            });
        }
      } else {
        alert(data.message);
      }
    })
    .catch(error => {
      alert('An error occurred while checking the order status.');
      console.error(error);
    });
}
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>