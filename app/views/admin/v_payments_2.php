<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_admin.php'; ?>
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
            <form action="<?php echo URLROOT; ?>/admin/createPaymentReport" method="POST">
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
                    <th>Normal Leaf Rate</th>
                    <th>Super Leaf Rate</th>  
                    <th>Total Payment</th>
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

                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->normal_leaf_rate, 2)); ?></td> 
                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->super_leaf_rate, 2)); ?></td>
                        <td>Rs. <?php echo htmlspecialchars(number_format($payment->total_payment, 2)); ?></td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a 
                                    href="<?php echo URLROOT; ?>/admin/viewPaymentReport/<?php echo $payment->id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-show' style="font-size: 24px; color:blue;"></i> 
                                </a>
                                <a 
                                    href="<?php echo URLROOT; ?>/admin/publishPaymentReport/<?php echo $payment->id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bxs-file-export' style="font-size: 24px; color:orange;"></i> 
                                </a>

                                <a 
                                    href="<?php echo URLROOT; ?>/admin/deletePaymentReport/<?php echo $payment->id; ?>" 
                                    class="btn btn-tertiary" 
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                >
                                    <i class='bx bx-trash' style="font-size: 24px; color:red;"></i> 
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>