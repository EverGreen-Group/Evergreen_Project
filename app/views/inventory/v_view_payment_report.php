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
          <h1>Supplier Payment</h1>
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
            <h3>Factory Payments Report</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Supplier Name</th>
                    <th>Total Collections</th>
                    <th>Normal Kg</th>
                    <th>Super Kg</th>
                    <th>Total Kg</th>
                    <th>Quality Reduct Kg</th>
                    <th>Quality Reduct Rs.</th>
                    <th>True Kg</th>
                    <th>Transport Charge</th>
                    <th>Payment Amount</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($payment_details as $detail): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detail->supplier_id); ?></td>
                        <td><?php echo htmlspecialchars($detail->supplier_name); ?></td>
                        <td><?php echo htmlspecialchars($detail->total_collections); ?></td>
                        <td><?php echo htmlspecialchars($detail->normal_kg); ?> kg</td>
                        <td><?php echo htmlspecialchars($detail->super_kg); ?> kg</td>
                        <td><?php echo htmlspecialchars($detail->total_kg); ?> kg</td>
                        <td><?php echo htmlspecialchars($detail->total_deduction_kg); ?> kg</td>
                        <td>Rs. <?php echo htmlspecialchars($detail->total_deduction_amount); ?></td>
                        <td><?php echo htmlspecialchars($detail->total_kg - $detail->total_deduction_kg); ?> kg</td>

                        <td>Rs. <?php echo htmlspecialchars(number_format($detail->transport_charge, 2)); ?></td>
                        <td>Rs. <?php echo htmlspecialchars(number_format($detail->payment_amount, 2)); ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</main>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>