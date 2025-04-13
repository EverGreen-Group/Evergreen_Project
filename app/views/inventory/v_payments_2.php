<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_inventory.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/vehicle.js"></script>

<!-- MAIN -->
<main>
  <div class="head-title">
      <div class="left">
          <h1>Payment Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>


      </div>
  </div>

  <div class="action-buttons">
        <!-- <a href="<?php echo URLROOT; ?>/manager/viewInactiveSuppliers" class="btn btn-primary">
            <i class='bx bx-show'></i>
            View Inactive Suppliers
        </a> -->
    </div>

    <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Create Factory Payment Report</h3>
        </div>
        <div class="filter-options">
            <form action="<?php echo URLROOT; ?>/inventory/createPaymentReport" method="POST">
                <div class="filter-group">
                    <label for="select-year">Select Year:</label>
                    <select id="select-year" name="year" required>
                        <option value="">-- Select Year --</option>
                        <?php
                        $currentYear = date("Y");
                        for ($y = $currentYear; $y >= $currentYear - 5; $y--): ?>
                            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="select-month">Select Month:</label>
                    <select id="select-month" name="month" required>
                        <option value="">-- Select Month --</option>
                        <?php
                        for ($m = 1; $m <= 12; $m++): 
                            $monthName = date("F", mktime(0, 0, 0, $m, 10));
                        ?>
                            <option value="<?php echo $m; ?>"><?php echo $monthName; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="normal-leaf-rate">Normal Leaf Rate:</label>
                    <input type="number" id="normal-leaf-rate" name="normal_leaf_rate" required>
                </div>

                <div class="filter-group">
                    <label for="super-leaf-rate">Super Leaf Rate:</label>
                    <input type="number" id="super-leaf-rate" name="super_leaf_rate" required>
                </div>

                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
        </div>
    </div>
</div>






<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Factory Payments Report</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>No. of Suppliers</th>
                    <th>Total Kg Supplied</th>
                    <th>Total Payment</th>
                    <th>Normal Leaf Rate</th>
                    <th>Super Leaf Rate</th>  
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payment_summary as $payment): ?>
                    <tr class="payment-row" data-payment-id="<?php echo htmlspecialchars($payment->id); ?>">
                        <td><?php echo htmlspecialchars($payment->year); ?></td>
                        <td><?php echo htmlspecialchars($payment->month_name); ?></td>
                        <td><?php echo htmlspecialchars($payment->total_suppliers); ?></td>
                        <td><?php echo htmlspecialchars($payment->total_kg); ?> kg</td>
                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->total_payment, 2)); ?></td>
                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->normal_leaf_rate, 2)); ?></td> <!-- Display normal leaf rate -->
                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->super_leaf_rate, 2)); ?></td>  <!-- Display super leaf rate -->
                        <td>
                            <a 
                                href="<?php echo URLROOT; ?>/inventory/viewPaymentReport/<?php echo $payment->id; ?>" 
                                class="btn btn-primary"
                            >
                                <i class='bx bx-show'></i>
                                View
                            </a>
                            <a 
                                href="<?php echo URLROOT; ?>/inventory/deletePaymentReport/<?php echo $payment->id; ?>" 
                                class="btn btn-danger delete-payment"
                                onclick="return confirm('Are you sure you want to delete this payment report? This will also delete all associated payment details.');"
                            >
                                <i class='bx bx-trash'></i>
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>