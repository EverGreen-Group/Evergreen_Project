<?php require APPROOT . '/views/inc/components/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<!-- MAIN -->
<main>
  <!-- Supplier Applications Section -->
  <div class="head-title">
      <div class="left">
          <h1>Supplier Applications</h1>
          <ul class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
              <li><i class='bx bx-chevron-right'></i></li>
              <li><a class="active" href="#">Applications</a></li>
          </ul>
      </div>
  </div>

  <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-file-plus'></i>
                <div class="stat-info">
                    <h3><?php echo isset($totalApplications) ? $totalApplications : 0; ?></h3>
                    <p>Total Applications</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-time'></i>
                <div class="stat-info">
                    <h3><?php echo isset($pendingApplications) ? $pendingApplications : 0; ?></h3>
                    <p>Pending Review</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-check-circle'></i>
                <div class="stat-info">
                    <h3><?php echo isset($approvedApplications) ? $approvedApplications : 0; ?></h3>
                    <p>Approved</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-x-circle'></i>
                <div class="stat-info">
                    <h3><?php echo isset($rejectedApplications) ? $rejectedApplications : 0; ?></h3>
                    <p>Rejected</p>
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
            <form action="<?php echo URLROOT; ?>/manager/applications" method="GET">
                <div class="filter-group">
                    <label for="application-id">Application ID:</label>
                    <input type="text" id="application-id" name="application_id" 
                     placeholder="Enter application ID" value="<?php echo isset($_GET['application_id']) ? htmlspecialchars($_GET['application_id']) : ''; ?>">
                </div>
                <div class="filter-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo (isset ($_GET['status']) && $_GET['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo (isset($_GET['status'])) && $_GET['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        <option value="auto-rejected" <?php echo (isset($_GET['status']) && $_GET['status'] == 'auto-rejected') ? 'selected' : ''; ?>>Auto-Rejected</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date-from">Date From:</label>
                    <input type="date" id="date-from" name="date-from" value="<?php echo isset($_GET['date-from']) ? htmlspecialchars($_GET['date-from']) : ''; ?>">
                </div>
                <div class="filter-group">
                    <label for="date-to">Date To:</label>
                    <input type="date" id="date-to" name="date-to" value="<?php echo isset ($_GET['date-to']) ? htmlspecialchars($_GET['date-to']) : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</div>



<!-- Applications Table -->
<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Supplier Applications</h3>
            <a href="<?php echo URLROOT; ?>/manager/createVehicle" class="btn btn-primary">
                <i class='bx bx-cog'></i>
                Manage Application Constraints
            </a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>User ID</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th>Reviewed By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($applications) && !empty($applications)): ?>
                    <?php foreach ($applications as $application): ?>
                        <tr class="application-row" data-application-id="<?php echo htmlspecialchars($application->application_id); ?>">
                            <td><?php echo htmlspecialchars($application->application_id); ?></td>
                            <td><?php echo htmlspecialchars($application->user_id); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($application->created_at))); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($application->status); ?>">
                                    <?php echo htmlspecialchars(ucfirst($application->status)); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($application->reviewed_by)): ?>
                                    <a href="<?php echo URLROOT; ?>/managers/view/<?php echo htmlspecialchars($application->reviewed_by); ?>" class="manager-link">
                                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($application->manager_image); ?>" alt="Manager Photo" class="manager-photo">
                                        <?php echo htmlspecialchars($application->manager_name); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="status-badge pending">
                                    Unassigned
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <!-- View button with icon -->
                                    <a 
                                        href="<?php echo URLROOT; ?>/manager/viewApplication/<?php echo $application->application_id; ?>" 
                                        class="btn btn-tertiary" 
                                        style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                        title="View Application Details"
                                    >
                                        <i class='bx bx-show' style="font-size: 24px; color:blue;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No applications found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        

        <div class="table-pagination">
            <div class="pagination">
                <?php if ($totalPages > 1): ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a 
                        href="<?php echo URLROOT; ?>/manager/applications?page=<?php echo $i; ?>" 
                        <?php if ($currentPage == $i) { echo 'class="active"'; } ?>>
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle constraints form
    document.getElementById('edit-constraints-btn').addEventListener('click', function() {
        document.getElementById('constraints-form').style.display = 'block';
        document.getElementById('constraints-display').style.display = 'none';
    });
    
    document.getElementById('cancel-constraints-btn').addEventListener('click', function() {
        document.getElementById('constraints-form').style.display = 'none';
        document.getElementById('constraints-display').style.display = 'block';
    });
</script>

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
        background-color: #FFF4DE; /* Light background */
        color: #A65E00; /* Darker text for better contrast */
    }

    .under_review {
        background-color: rgb(253, 204, 106); /* Light yellow */
        color: rgb(100, 50, 50); /* Darker red for better contrast */
    }
    
    .approved {
        background-color:var(--mainn); /* Light green */
        color:rgb(255, 255, 255); /* Darker green for better contrast */
    }
    
    .rejected, .auto_rejected {
        background-color: var(--red); /* Light red */
        color:rgb(255, 255, 255); /* Darker red for better contrast */
    }
    
    .constraint-group {
        margin-bottom: 20px;
    }
    
    .constraint-group h4 {
        margin-bottom: 10px;
        color: #333;
    }
    
    .constraint-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .constraint-item label {
        width: 250px;
        font-weight: 500;
    }
    
    .constraint-item input {
        width: 150px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .constraint-display-item {
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
        border-radius: 4px;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 10px;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .manager-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit; /* Inherit text color */
    }

    .manager-photo {
        width: 30px; /* Set the desired width */
        height: 30px; /* Set the desired height */
        border-radius: 50%; /* Make it circular */
        margin-right: 8px; /* Space between image and name */
        object-fit: cover; /* Ensure the image covers the area */
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

