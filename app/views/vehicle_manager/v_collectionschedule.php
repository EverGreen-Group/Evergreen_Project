<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/dashboard_stats.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/calendar.css">
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script> -->
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>
<script defer src="<?php echo URLROOT; ?>/public/js/vehicle_manager/collection.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_manager/collection_request_populate.js"></script>



<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Schedule Management</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ul>
        </div>


    </div>

    <div class="action-buttons">
        <a href="<?php echo URLROOT; ?>/manager/createSchedule" class="btn btn-primary">
            <i class='bx bx-plus'></i>
            Create a Schedule
        </a>
    </div>

    <ul class="dashboard-stats">
        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bxs-calendar'></i>
                <div class="stat-info">
                    <h3><?php echo $totalSchedules; ?></h3>
                    <p>Total Schedules</p>
                </div>
            </div>
        </li>

        <li class="stat-card">
            <div class="stat-content">
                <i class='bx bx-time'></i>
                <div class="stat-info">
                    <h3><?php echo $availableSchedules; ?></h3>
                    <p>Currently Ongoing</p>
                </div>
            </div>
        </li>
    </ul>



    



    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Collection Schedules</h3>
                <div class="view-toggle">
                    <button class="view-btn active" data-view="day">Day View</button>
                    <button class="view-btn" data-view="table">Table View</button>
                </div>
            </div>
            
            <!-- Day View -->
            <div id="day-view" class="schedule-view active">
                <div class="days-container">
                    <?php
                    $hasSchedules = false;
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    foreach ($days as $day) :
                        $daySchedules = array_filter($data['schedules'] ?? [], function($schedule) use ($day) {
                            return $schedule->day === $day;
                        });
                        
                        // Skip days with no schedules
                        if (empty($daySchedules)) continue;
                        $hasSchedules = true;
                    ?>
                    <div class="day-card">
                        <div class="day-header">
                            <h4><?php echo $day; ?></h4>
                            <span class="schedule-count"><?php echo count($daySchedules); ?> schedules</span>
                        </div>
                        <div class="day-schedules">
                            <?php foreach ($daySchedules as $schedule) : 
                                // Add a class if supplier count is 0
                                $noSuppliersClass = ($schedule->supplier_count == 0) ? 'no-suppliers' : '';
                            ?>
                                <div class="schedule-card <?php echo $noSuppliersClass; ?>" data-id="<?php echo $schedule->schedule_id; ?>">
                                    <div class="schedule-info">
                                        <div class="route-name">
                                            <a href="<?php echo URLROOT; ?>/route/manageRoute/<?php echo htmlspecialchars($schedule->route_id); ?>">
                                                <?php echo htmlspecialchars($schedule->route_name); ?>
                                            </a>
                                        </div>
                                        <div class="schedule-details">
                                            <span class="shift-time">(<?php echo $schedule->start_time; ?> - <?php echo $schedule->end_time; ?>)</span>
                                            <span class="driver-name"><i class='bx bxs-user'></i> <?php echo $schedule->driver_name; ?></span>
                                            <span class="vehicle-info"><i class='bx bxs-car'></i> <?php echo $schedule->license_plate; ?></span>
                                            <span class="supplier-count <?php echo ($schedule->supplier_count == 0) ? 'warning' : ''; ?>">
                                                <i class='bx bxs-store'></i> <?php echo $schedule->supplier_count; ?> suppliers
                                            </span>
                                        </div>
                                    </div>
                                    <div class="schedule-actions">
                                        <a href="<?php echo URLROOT; ?>/manager/updateSchedule/<?php echo $schedule->schedule_id; ?>" class="action-btn edit">
                                            <i class='bx bx-cog'></i>
                                        </a>
                                        <form action="<?php echo URLROOT; ?>/manager/deleteSchedule" method="POST" style="display: inline;" 
                                            onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                            <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                            <button type="submit" class="action-btn delete">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (!$hasSchedules): ?>
                        <div class="no-schedules-message">
                            <i class='bx bx-calendar-x'></i>
                            <p>No schedules have been created yet.</p>
                            <a href="<?php echo URLROOT; ?>/manager/createSchedule" class="btn btn-primary">Create A Collection Schedule</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Table View (original) - hidden by default -->
            <div id="table-view" class="schedule-view" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Schedule ID</th>
                            <th>Route</th>
                            <th>Driver</th>
                            <th>Vehicle</th>
                            <th>Shift</th>
                            <th>Day</th>
                            <th>Suppliers</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($data['schedules']) && !empty($data['schedules'])): ?>
                            <?php foreach($data['schedules'] as $schedule): ?>
                                <tr>
                                    <td>CS<?php echo str_pad($schedule->schedule_id, 3, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo $schedule->route_name; ?></td>
                                    <td><?php echo $schedule->driver_name; ?></td>
                                    <td><?php echo $schedule->license_plate; ?></td>
                                    <td>(<?php echo $schedule->start_time; ?> - <?php echo $schedule->end_time; ?>)</td>
                                    <td><?php echo $schedule->day; ?></td>
                                    <td><?php echo $schedule->supplier_count; ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($schedule->created_at)); ?></td>
                                    <td>
                                        <form action="<?php echo URLROOT; ?>/collectionschedules/toggleActive" method="POST" style="display: inline;">
                                            <button type="submit" class="status-btn <?php echo $schedule->is_active ? 'active' : 'inactive'; ?>" style="background-color: var(--main)"> 
                                                <?php echo $schedule->is_active ? 'Active' : 'Inactive'; ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <!-- Update button with icon only -->
                                            <a 
                                                href="<?php echo URLROOT; ?>/manager/updateSchedule/<?php echo $schedule->schedule_id; ?>" 
                                                class="btn btn-tertiary" 
                                                style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                            >
                                                <i class='bx bx-cog' style="font-size: 24px; color:green;"></i>
                                            </a>
                                            
                                            <!-- Delete button with icon only -->
                                            <form action="<?php echo URLROOT; ?>/manager/deleteSchedule" method="POST" style="margin: 0;" 
                                                onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                                <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                                <button type="submit" class="btn btn-tertiary" 
                                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;">
                                                    <i class='bx bx-trash' style="font-size: 24px; color:red;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">No schedules found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Schedule Details Modal -->
            <div id="scheduleDetailsModal" class="modal">
                <div class="modal-content" onclick="event.stopPropagation();">
                    <span class="close" onclick="closeModal('scheduleDetailsModal')">&times;</span>
                    <h2>Schedule Details</h2>
                    <div id="schedule-details-content">
                        <!-- Schedule details will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>




<script>
    const schedules = <?php echo json_encode($data['schedules'] ?? []); ?>;
    
    document.addEventListener('DOMContentLoaded', function() {
        // View toggle functionality
        const viewBtns = document.querySelectorAll('.view-btn');
        const scheduleViews = document.querySelectorAll('.schedule-view');
        
        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const viewType = this.dataset.view;
                
                // Update active button
                viewBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Show selected view
                scheduleViews.forEach(view => {
                    if (view.id === viewType + '-view') {
                        view.style.display = 'block';
                    } else {
                        view.style.display = 'none';
                    }
                });
            });
        });
        
        // Schedule card click to show details (optional)
        const scheduleCards = document.querySelectorAll('.schedule-card');
        scheduleCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Prevent click when clicking on action buttons
                if (e.target.closest('.schedule-actions')) {
                    return;
                }
                
                const scheduleId = this.dataset.id;
                // You could implement a modal or expand the card to show more details
                console.log('Schedule clicked:', scheduleId);
            });
        });
    });
    
</script>


<style>
    /* Status styles */
.status.pending {
    color: orange; /* Color for Pending status */
}

.status.approved {
    color: green; /* Color for Approved status */
}

.status.rejected {
    color: red; /* Color for Rejected status */
}

.status.awaiting-inventory-addition  {
    color: green; /* Color for Rejected status */
}

.status.completed {
    color: blue; /* Color for Completed status */
}

/* FOR SCHEDULE CALEND */

.next-schedule-alert {
    display: flex;
    align-items: center;
    gap: 15px;
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    border-left: 4px solid var(--main);
}

.next-schedule-alert i {
    font-size: 2rem;
    color: var(--main);
}

.schedule-info h4 {
    color: var(--dark);
    margin-bottom: 5px;
}

.schedule-info p {
    color: #555;
}

/* Day View Styles */
.view-toggle {
    display: flex;
    gap: 10px;
}

.view-btn {
    padding: 5px 10px;
    border: 1px solid var(--main);
    background: transparent;
    color: var(--main);
    border-radius: 5px;
    cursor: pointer;
}

.view-btn.active {
    background: var(--main);
    color: white;
}

.days-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.day-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.day-header {
    background: var(--main);
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.day-header h4 {
    margin: 0;
    font-size: 1.1rem;
}

.schedule-count {
    background: rgba(255,255,255,0.2);
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 0.8rem;
}

.day-schedules {
    padding: 15px;
    max-height: 400px;
    overflow-y: auto;
}

.schedule-card {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.route-name {
    font-weight: bold;
    margin-bottom: 5px;
    color: var(--dark);
}

.schedule-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    font-size: 0.85rem;
    color: #555;
}

.schedule-actions {
    display: flex;
    gap: 5px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}

.action-btn.edit {
    background: rgba(39, 174, 96, 0.1);
    color: #27ae60;
}

.action-btn.edit:hover {
    background: rgba(39, 174, 96, 0.2);
}

.action-btn.delete {
    background: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
}

.action-btn.delete:hover {
    background: rgba(231, 76, 60, 0.2);
}

.no-schedules {
    text-align: center;
    padding: 20px;
    color: #888;
    font-style: italic;
}

.no-schedules-message {
    grid-column: 1 / -1;
    text-align: center;
    padding: 50px 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.no-schedules-message i {
    font-size: 3rem;
    color: var(--main);
    margin-bottom: 15px;
}

.no-schedules-message p {
    font-size: 1.2rem;
    color: #555;
    margin-bottom: 20px;
}

/* Highlight schedules with 0 suppliers */
.schedule-card.no-suppliers {
    background-color: rgba(231, 76, 60, 0.1);
    /* border-left: 3px solid #e74c3c; */
}


</style>


</main>




<?php require APPROOT . '/views/inc/components/footer.php'; ?>
