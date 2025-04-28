<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
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
  <!-- Vehicle Management Section -->
  <div class="head-title">
      <div class="left">
          <h1>Collection Management</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
          </ul>
      </div>
  </div>

  <!-- <div class="action-buttons">
        <a href="<?php echo URLROOT; ?>/manager/createVehicle" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Add new vehicle
        </a>
    </div> -->


  <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-news'></i>
                <div class="stat-info">
                    <h3><?php echo $stats['collections']['total_today']; ?></h3>
                    <p>Collections Today</p>
                </div>
            </div>
        </li>

        <!-- <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-station'></i>
                <div class="stat-info">
                    <h3><?php echo $stats['collections']['total_ongoing']; ?></h3>
                    <p>Collections Ongoing</p>
                </div>
            </div>
        </li> -->

    </ul>

  <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Search Filters</h3>
            <i class='bx bx-search'></i>
        </div>
        <div class="filter-options">
            <form action="<?php echo URLROOT; ?>/manager/collection" method="GET">
                <div class="filter-group">
                    <label for="collection-id">Collection ID:</label>
                    <input type="number" id="collection-id" name="collection_id" placeholder="Enter collection ID" value="<?php echo isset($filters['collection_id']) ? $filters['collection_id'] : ''; ?>">
                </div>
                <div class="filter-group">
                    <label for="schedule-id">Schedule ID:</label>
                    <input type="number" id="schedule-id" name="schedule_id" placeholder="Enter schedule ID" value="<?php echo isset($filters['schedule_id']) ? $filters['schedule_id'] : ''; ?>">
                </div>
                <div class="filter-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="">Select Status</option>
                        <option value="Pending" <?php echo (isset($filters['status']) && $filters['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo (isset($filters['status']) && $filters['status'] === 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo (isset($filters['status']) && $filters['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="Awaiting Inventory Addition" <?php echo (isset($filters['status']) && $filters['status'] === 'Awaiting Inventory Addition') ? 'selected' : ''; ?>>Awaiting Inventory Addition</option>
                        <option value="Cancelled" <?php echo (isset($filters['status']) && $filters['status'] === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" name="start_date" value="<?php echo isset($filters['start_date']) ? $filters['start_date'] : ''; ?>">
                </div>
                <div class="filter-group">
                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date" name="end_date" value="<?php echo isset($filters['end_date']) ? $filters['end_date'] : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</div>



<!-- app/views/vehicle_manager/v_collection_0.php -->
<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Collections</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th># Collection ID</th>
                    <th># Schedule ID</th>
                    <th><i class='bx bxs-edit-alt'></i> Status</th>
                    <th><i class='bx bx-time' ></i> Started At</th>
                    <th><i class='bx bx-time' ></i> Ended At</th>
                    <th>Total Quantity (kg)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($all_collections)): ?>
                    <?php foreach ($all_collections as $collection): ?>
                        <tr class="collection-row" data-collection-id="<?php echo htmlspecialchars($collection->collection_id); ?>">
                            <td><?php echo htmlspecialchars($collection->collection_id); ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/manager/updateSchedule/<?php echo $collection->schedule_id; ?>">
                                    <?php echo htmlspecialchars($collection->schedule_id); ?>
                                </a>
                            </td>
                            <td>
                                <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $collection->status)); ?>">
                                    <?php echo htmlspecialchars($collection->status); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($collection->created_at)); ?></td>
                            <td><?php echo isset($collection->end_time) ? date('Y-m-d H:i', strtotime($collection->end_time)) : 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($collection->total_quantity); ?></td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a 
                                        href="<?php echo URLROOT; ?>/collection/details/<?php echo $collection->collection_id; ?>" 
                                        class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                    >
                                        <i class='bx bx-show' style="font-size: 24px; color:blue;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No collections found</td>
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
    
    .pending {
        background-color: #FFF4DE;
        color: #FFA800;
    }
    
    .in-progress {
        background-color: #E1F0FF;
        color: #3699FF;
    }
    
    .completed {
        background-color: #E8FFF3;
        color: #1BC5BD;
    }
    
    .awaiting-inventory-addition {
        background-color: #EEE5FF;
        color: #7337EE;
    }
    
    .cancelled {
        background-color: #FFE2E5;
        color: #F64E60;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

