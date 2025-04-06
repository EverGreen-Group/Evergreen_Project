<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<main>
  <div class="head-title">
    <div class="left">
        <h1>Collection Bag Verification</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Bag Verification</a></li>
        </ul>
    </div>
  </div>

  <!-- Section 1: Bags Awaiting Verification -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Bags Awaiting Verification</h3>
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
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($collectionBags) && !empty($collectionBags)): ?>
            <?php foreach ($collectionBags as $bag): ?>
              <?php if ($bag->is_finalized != 1): ?>
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
                  <td>
                    <div style="display: flex; gap: 5px;">
                        <a 
                            href="<?php echo URLROOT; ?>/inventory/approveBag/<?php echo $bag->history_id; ?>/<?php echo $bag->collection_id; ?>" 
                            class="btn btn-success" 
                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                            title="Approve Bag"
                            onclick="return confirm('Are you sure you want to approve this bag?');"
                            >
                            <i class='bx bx-check-circle' style="font-size: 24px; color:green;"></i>
                        </a>

                        <a 
                            href="<?php echo URLROOT; ?>/inventory/updateBag/<?php echo $bag->history_id; ?>" 
                            class="btn btn-primary" 
                            style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                            title="Update Bag Details"
                        >
                            <i class='bx bx-edit' style="font-size: 24px; color:var(--main);"></i>
                        </a>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" style="text-align:center;">No bags found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<!-- Section 2: Finalized Bags -->
<div class="table-data">
  <div class="order">
    <div class="head">
      <h3>Verified Bags</h3>
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
          <th>Payment Amount</th>
          <th>Finalized At</th>
        </tr>
      </thead>
      <tbody>
        <?php if (isset($collectionBags) && !empty($collectionBags)): ?>
          <?php foreach ($collectionBags as $bag): ?>
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
                <td><?php echo htmlspecialchars($bag->payment_amount ? 'Rs. ' . number_format($bag->payment_amount, 2) : 'Not set'); ?></td>
                <td><?php echo htmlspecialchars($bag->finalized_at ?? 'Not set'); ?></td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8" style="text-align:center;">No verified bags found</td></tr>
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
        background-color: #4CAF50; /* Green for Young */
        color: #FFFFFF; /* White text */
    }
    .leaf-medium {
        background-color: #FF9800; /* Orange for Medium */
        color: #FFFFFF; /* White text */
    }
    .leaf-mature {
        background-color: #8B4513; /* Brown for Mature */
        color: #FFFFFF; /* White text */
    }
    .moisture-wet {
        background-color: #2196F3; /* Blue for Wet */
        color: #FFFFFF; /* White text */
    }
    .moisture-semi-wet {
        background-color: #FFEB3B; /* Yellow for Semi Wet */
        color: #000000; /* Black text */
    }
    .moisture-dry {
        background-color: #F44336; /* Red for Dry */
        color: #FFFFFF; /* White text */
    }

    .normal {
        background-color: #4CAF50; /* Green */
        color: #FFFFFF; /* White text */
    }
    .super {
        background-color: #FF9800; /* Orange */
        color: #FFFFFF; /* White text */
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>