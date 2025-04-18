<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT.'/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<main>
  <div class="head-title">
    <div class="left">
        <h1>Manage Complaints</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Complaints</a></li>
        </ul>
    </div>
  </div>

  <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-file-plus'></i>
                <div class="stat-info">
                    <h3><?php echo isset($totalComplaints) ? $totalComplaints : 0; ?></h3>
                    <p>Total Complaints</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-check-circle'></i>
                <div class="stat-info">
                    <h3><?php echo isset($resolvedComplaints) ? $resolvedComplaints : 0; ?></h3>
                    <p>Resolved</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-x-circle'></i>
                <div class="stat-info">
                    <h3><?php echo isset($pendingComplaints) ? $pendingComplaints : 0; ?></h3>
                    <p>Pending</p>
                </div>
            </div>
        </li>
    </ul>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Search Filters</h3>
                <i class='bx bx-search'></i>
            </div>
            <div class="filter-options">
            <form action="<?php echo URLROOT; ?>/manager/complaints" method="GET">
                    <div class="filter-group">
                        <label for="complaint-id">Complaint ID:</label>
                        <input type="text" id="complaint-id" name="complaint_id" placeholder="Enter complaint ID">
                    </div>
                    <div class="filter-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="date-from">Date From:</label>
                        <input type="date" id="date-from" name="date_from">
                    </div>
                    <div class="filter-group">
                        <label for="date-to">Date To:</label>
                        <input type="date" id="date-to" name="date_to">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>All Complaints</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Complaint ID</th>
                        <th>Supplier</th>
                        <th>Subject</th>
                        <th>Date Filed</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['complaints'])): ?>
                        <?php foreach ($data['complaints'] as $complaint): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($complaint->complaint_id); ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/manager/viewSupplier/<?php echo htmlspecialchars($complaint->supplier_id); ?>" class="manager-link">
                                    <img src="<?php echo URLROOT . '/' . htmlspecialchars($complaint->image_path); ?>" alt="Supplier Photo" class="manager-photo">
                                    <?php echo htmlspecialchars($complaint->supplier_name); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                            <td><?php echo htmlspecialchars($complaint->created_at); ?></td>
                            <td><span class="priority-badge <?php echo strtolower($complaint->priority); ?>"><?php echo ucfirst($complaint->priority); ?></span></td>
                            <td><span class="status-badge <?php echo strtolower($complaint->status); ?>"><?php echo ucfirst($complaint->status); ?></span></td>
                            <td><a href="<?php echo URLROOT; ?>/manager/viewComplaint/<?php echo $complaint->complaint_id; ?>" class="btn btn-primary" title="View Details">
                                    <i class='bx bx-detail'></i>
                                </a></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;">No complaints found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="table-pagination">
                <div class="pagination">
                    <?php if ($data['totalPages'] > 1): ?>
                        <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                            <a 
                            href="<?php echo URLROOT; ?>/manager/complaints?page=<?php echo $i; ?>&complaint_id=<?php echo urlencode($complaint_id); ?>&status=<?php echo urlencode($status); ?>&date_from=<?php echo urlencode($date_from); ?>&date_to=<?php echo urlencode($date_to); ?>" 
                            <?php if ($data['currentPage'] == $i) { echo 'class="active"'; } ?>>
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .status-badge,
    .priority-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    /* Status badges */
    .new {
        background-color: #FFF4DE;
        color: #FFA800;
    }
    
    .in-progress {
        background-color: #EEE5FF;
        color: #8950FC;
    }
    
    .resolved {
        background-color: #E8FFF3;
        color: #1BC5BD;
    }

    .high {
        background-color: #F64E60;
        color: white;
    }

    .medium {
        background-color: #FFA800;
        color: white;
    }

    .low {
        background-color: #3699FF;
        color: white;
    }

    .manager-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
    }

    .manager-photo {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 8px;
        object-fit: cover;
    }
    
</style>
<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>