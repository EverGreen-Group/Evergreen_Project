<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">

<script>
  const URLROOT = '<?php echo URLROOT; ?>';
  const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<main>
  <div class="head-title">
    <div class="left">
      <h1>Raw Leaf Input History</h1>
      <ul class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li><i class='bx bx-chevron-right'></i></li>
        <li><a class="active" href="#">Input History</a></li>
      </ul>
    </div>

  </div>



  <ul class="dashboard-stats">
    <li class="stat-card">
      <div class="stat-content">
        <i class='bx bx-time'></i>
        <div class="stat-info">
          <h3><?php
          $totalWeek = array_sum(array_map(function ($record) {
            return $record->total_quantity;
          }, $data['leafQuantities']));
          echo number_format($totalWeek, 2);
          ?> kg</h3>
          <p>Total Input This Week</p>
        </div>
      </div>
    </li>

    <li class="stat-card">
      <div class="stat-content">
        <i class='bx bx-leaf'></i>
        <div class="stat-info">
          <h3><?php
          $uniqueDays = count(array_unique(array_map(function ($record) {
            return $record->date;
          }, $data['leafQuantities'])));
          echo number_format($totalWeek / ($uniqueDays ?: 1), 2);
          ?> kg</h3>
          <p>Average Daily Input</p>
        </div>
      </div>
    </li>
  </ul>

  <!-- Raw Leaf Input History Table -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Daily Raw Leaf Inputs</h3>
      </div>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Normal Leaf (kg)</th>
            <th>Super Leaf (kg)</th>
            <th>Total Quantity (kg)</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data['leafQuantities'])): ?>
            <?php
            $currentDate = null;
            $normalLeaf = 0;
            $superLeaf = 0;

            foreach ($data['leafQuantities'] as $record):
              if ($currentDate !== $record->date) {
                // Output previous date's data if exists
                if ($currentDate !== null): ?>
                  <tr>
                    <td><?php echo date('Y-m-d (D)', strtotime($currentDate)); ?></td>
                    <td><?php echo number_format($normalLeaf, 2); ?></td>
                    <td><?php echo number_format($superLeaf, 2); ?></td>
                    <td><?php echo number_format($normalLeaf + $superLeaf, 2); ?></td>
                  </tr>
                  <?php
                endif;

                // Reset for new date
                $currentDate = $record->date;
                $normalLeaf = 0;
                $superLeaf = 0;
              }

              // Add to appropriate leaf type
              if ($record->leaf_type_id == 1) {
                $normalLeaf = $record->total_quantity;
              } else {
                $superLeaf = $record->total_quantity;
              }
            endforeach;

            // Output last date's data
            if ($currentDate !== null): ?>
              <tr>
                <td><?php echo date('Y-m-d (D)', strtotime($currentDate)); ?></td>
                <td><?php echo number_format($normalLeaf, 2); ?></td>
                <td><?php echo number_format($superLeaf, 2); ?></td>
                <td><?php echo number_format($normalLeaf + $superLeaf, 2); ?></td>
              </tr>
            <?php endif; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" style="text-align:center;">No input history available</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</main>

<style>
  .summary-stats {
    display: flex;
    gap: 2rem;
    padding: 1rem;
  }

  .stat-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    flex: 1;
  }

  .stat-item h4 {
    color: #555;
    margin-bottom: 0.5rem;
  }

  .stat-item p {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
  }

  table td {
    padding: 0.75rem;
  }

  table th {
    background: #f1f1f1;
    padding: 0.75rem;
  }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>