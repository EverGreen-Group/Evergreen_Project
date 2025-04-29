<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">

<main>
  <div class="head-title">
    <div class="left">
      <h1>Bag Usage History</h1>
      <ul class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li><i class='bx bx-chevron-right'></i></li>
        <li><a class="active" href="#">Bag Verification</a></li>
      </ul>
    </div>
  </div>



  <!-- Section 2: Finalized Bags -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Finalized Bags Usage</h3>
      </div>
      <table>
        <thead>
          <tr>
            <th>Bag ID</th>
            <th>Capacity (kg)</th>
            <th>Actual Weight (kg)</th>
            <th>Leaf Age</th>
            <th>Moisture Level</th>
            <th>Leaf Type</th>
            <th>Supplier ID</th>
            <th>Finalized At</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($bagHistory) && !empty($bagHistory)): ?>
            <?php foreach ($bagHistory as $bag): ?>
              <?php if ($bag->is_finalized == 1): ?>
                <tr>
                  <td><?php echo htmlspecialchars($bag->bag_id); ?></td>
                  <td><?php echo htmlspecialchars($bag->capacity_kg); ?></td>
                  <td><?php echo htmlspecialchars($bag->actual_weight_kg ?? 'Not set'); ?></td>
                  <td>
                    <?php if ($bag->leaf_age === 'Young'): ?>
                      <span class="status-badge leaf-young"><?php echo htmlspecialchars($bag->leaf_age); ?></span>
                    <?php elseif ($bag->leaf_age === 'Medium'): ?>
                      <span class="status-badge leaf-medium"><?php echo htmlspecialchars($bag->leaf_age); ?></span>
                    <?php else: ?>
                      <span class="status-badge leaf-mature"><?php echo htmlspecialchars($bag->leaf_age); ?></span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($bag->moisture_level === 'Wet'): ?>
                      <span class="status-badge moisture-wet"><?php echo htmlspecialchars($bag->moisture_level); ?></span>
                    <?php elseif ($bag->moisture_level === 'Semi Wet'): ?>
                      <span class="status-badge moisture-semi-wet"><?php echo htmlspecialchars($bag->moisture_level); ?></span>
                    <?php else: ?>
                      <span class="status-badge moisture-dry"><?php echo htmlspecialchars($bag->moisture_level); ?></span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if (strtolower($bag->name) === 'normal'): ?>
                      <span class="status-badge normal"><?php echo htmlspecialchars($bag->name); ?></span>
                    <?php else: ?>
                      <span class="status-badge super"><?php echo htmlspecialchars($bag->name); ?></span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($bag->supplier_id); ?></td>
                  <td><?php echo htmlspecialchars($bag->finalized_at ?? 'Not set'); ?></td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="text-align:center;">No verified bags found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>


<style>
  .status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
  }

  .leaf-young {
    background-color: #4CAF50;
    /* Green for Young */
    color: #FFFFFF;
    /* White text */
  }

  .leaf-medium {
    background-color: #FF9800;
    /* Orange for Medium */
    color: #FFFFFF;
    /* White text */
  }

  .leaf-mature {
    background-color: #8B4513;
    /* Brown for Mature */
    color: #FFFFFF;
    /* White text */
  }

  .moisture-wet {
    background-color: #2196F3;
    /* Blue for Wet */
    color: #FFFFFF;
    /* White text */
  }

  .moisture-semi-wet {
    background-color: #FFEB3B;
    /* Yellow for Semi Wet */
    color: #000000;
    /* Black text */
  }

  .moisture-dry {
    background-color: #F44336;
    /* Red for Dry */
    color: #FFFFFF;
    /* White text */
  }

  .normal {
    background-color: #4CAF50;
    /* Green */
    color: #FFFFFF;
    /* White text */
  }

  .super {
    background-color: #FF9800;
    /* Orange */
    color: #FFFFFF;
    /* White text */
  }
</style>


<style>
/* Add these styles to your existing <style> tag or CSS file */

.search-container {
  display: flex;
  align-items: center;
  margin-left: auto;
}

.search-input {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  width: 250px;
  transition: all 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: var(--blue);
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

/* Make the head flex to accommodate both title and search */
.order .head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>