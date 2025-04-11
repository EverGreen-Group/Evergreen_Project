<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php require APPROOT.'/views/inc/components/sidebar_inventory.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inventory_manager/collection/calendar.css">

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>

<main>
  <div class="head-title">
    <div class="left">
        <h1>Manage Collections</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Awaiting Inventory Addition</a></li>
        </ul>
    </div>
  </div>

  <!-- Section: Collections Awaiting Inventory Addition -->
  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Collections Awaiting Inventory Addition</h3>
        <!-- Optionally add a button to create a new collection if needed -->
        <a href="<?php echo URLROOT; ?>/inventory_manager/createCollection" class="btn btn-primary">
          <i class='bx bx-plus'></i>
          Add New Collection
        </a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Collection ID</th>
            <th>Schedule ID</th>
            <th>Status</th>
            <th>Total Quantity</th>
            <th>Bags</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($collections)): ?>
            <?php foreach ($collections as $collection): ?>
              <?php if($collection->status === 'Awaiting Inventory Addition'): ?>
                <tr>
                  <td><?php echo htmlspecialchars($collection->collection_id); ?></td>
                  <td><?php echo htmlspecialchars($collection->schedule_id); ?></td>
                  <td class="status-badge awaiting-inventory"><?php echo htmlspecialchars($collection->status); ?></td>
                  <td><?php echo htmlspecialchars($collection->total_quantity); ?></td>
                  <td><?php echo htmlspecialchars($collection->bags); ?></td>
                  <td><?php echo htmlspecialchars($collection->created_at); ?></td>
                  <td>
                    <a href="<?php echo URLROOT; ?>/inventory_manager/viewCollection/<?php echo htmlspecialchars($collection->collection_id); ?>" class="btn btn-primary">
                      <i class='bx bx-show'></i> View
                    </a>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">No collections awaiting inventory addition</td></tr>
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
    
    .pending {
        background-color: rgb(214, 159, 49);
        color: #FFA800;
    }

    .booked {
        background-color: #FFF4DE;
        color: #FFA800;
    }
    
    .approved {
        background-color: #E8FFF3;
        color: #1BC5BD;
    }
    
    .rejected, .auto-rejected {
        background-color: #FFE2E5;
        color: #F64E60;
    }

    .awaiting-inventory {
        background-color: #FFD700; /* Gold color for awaiting status */
        color: #000; /* Black text */
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
