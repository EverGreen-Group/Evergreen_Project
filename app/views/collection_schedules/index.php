<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Collection Schedules</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URLROOT; ?>/vehiclemanager">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="#">Collection Schedules</a></li>
            </ul>
        </div>
    </div>

    <?php flash('schedule_error'); ?>
    <?php flash('schedule_success'); ?>

    <!-- Collection Schedules Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Collection Schedules</h3>
                <div class="filter-controls">
                    <select id="week-filter">
                        <option value="all">All Weeks</option>
                        <option value="1">Week 1</option>
                        <option value="2">Week 2</option>
                    </select>
                    <select id="day-filter">
                        <option value="all">All Days</option>
                        <option value="mon">Monday</option>
                        <option value="tue">Tuesday</option>
                        <option value="wed">Wednesday</option>
                        <option value="thu">Thursday</option>
                        <option value="fri">Friday</option>
                        <option value="sat">Saturday</option>
                        <option value="sun">Sunday</option>
                    </select>
                    <select id="shift-filter">
                        <option value="all">All Shifts</option>
                        <?php foreach ($data['shifts'] as $shift): ?>
                            <option value="<?= $shift->shift_id; ?>"><?= $shift->shift_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button onclick="resetFilters()" class="reset-btn">
                        <i class='bx bx-reset'></i> Reset Filters
                    </button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Schedule ID</th>
                        <th>Route</th>
                        <th>Team</th>
                        <th>Vehicle</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="schedule-tbody">
                    <?php if(isset($data['schedules']) && !empty($data['schedules'])): ?>
                        <?php foreach($data['schedules'] as $schedule): ?>
                            <tr class="schedule-row" 
                                data-week="<?php echo $schedule->week_number; ?>"
                                data-days="<?php echo $schedule->days_of_week; ?>"
                                data-shift="<?php echo $schedule->shift_id; ?>">
                                <td>CS<?php echo str_pad($schedule->schedule_id, 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $schedule->route_name; ?></td>
                                <td><?php echo $schedule->team_name; ?></td>
                                <td><?php echo $schedule->license_plate; ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($schedule->created_at)); ?></td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/collectionschedules/toggleActive" method="POST" style="display: inline;">
                                        <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                        <button type="submit" class="status-btn <?php echo $schedule->is_active ? 'active' : 'inactive'; ?>">
                                            <?php echo $schedule->is_active ? 'Active' : 'Inactive'; ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/collectionschedules/delete" method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                        <input type="hidden" name="schedule_id" value="<?php echo $schedule->schedule_id; ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No schedules found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php flash('schedule_create_error'); ?>
    <?php flash('schedule_create_success'); ?>

    <!-- Create New Schedule Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Create New Schedule</h3>
            </div>
            <form id="createScheduleForm" method="POST" action="<?php echo URLROOT; ?>/collectionschedules/create">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label for="route">Route:</label>
                        <select id="route" name="route_id" required onchange="updateVehicleId(this.value)">
                            <?php foreach ($data['routes'] as $route): ?>
                                <option value="<?= $route->route_id; ?>" data-vehicle-id="<?= $route->vehicle_id; ?>">
                                    <?= htmlspecialchars($route->route_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="vehicle_id" id="vehicle_id">
                    </div>

                    <div class="form-group">
                        <label for="team">Team:</label>
                        <select id="team" name="team_id" required>
                            <?php foreach ($data['teams'] as $team): ?>
                                <option value="<?= $team->team_id; ?>"><?= $team->team_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="shift">Shift:</label>
                        <select id="shift" name="shift_id" required>
                            <?php foreach ($data['shifts'] as $shift): ?>
                                <option value="<?= $shift->shift_id; ?>"><?= $shift->shift_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="week_number">Week:</label>
                        <select id="week_number" name="week_number" required>
                            <option value="1">Week 1</option>
                            <option value="2">Week 2</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Collection Days:</label>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
                        <label><input type="checkbox" name="days_of_week[]" value="mon"> Mon</label>
                        <label><input type="checkbox" name="days_of_week[]" value="tue"> Tue</label>
                        <label><input type="checkbox" name="days_of_week[]" value="wed"> Wed</label>
                        <label><input type="checkbox" name="days_of_week[]" value="thu"> Thu</label>
                        <label><input type="checkbox" name="days_of_week[]" value="fri"> Fri</label>
                        <label><input type="checkbox" name="days_of_week[]" value="sat"> Sat</label>
                        <label><input type="checkbox" name="days_of_week[]" value="sun"> Sun</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Create Schedule</button>
            </form>
        </div>
    </div>

    <!-- Edit Schedule Section -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Edit Schedule</h3>
            </div>
            <form id="editScheduleForm" method="POST" action="<?php echo URLROOT; ?>/collectionschedules/update">
                <div class="form-group">
                    <label for="schedule_id">Select Schedule:</label>
                    <select id="schedule_id" name="schedule_id" required onchange="loadScheduleData(this.value)">
                        <option value="">Select a schedule</option>
                        <?php foreach ($data['schedules'] as $schedule): ?>
                            <option value="<?= $schedule->schedule_id; ?>">
                                Schedule <?= str_pad($schedule->schedule_id, 3, '0', STR_PAD_LEFT); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label for="edit_route">Route:</label>
                        <select id="edit_route" name="route_id" required>
                            <?php foreach ($data['routes'] as $route): ?>
                                <option value="<?= $route->route_id; ?>"><?= htmlspecialchars($route->route_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_team">Team:</label>
                        <select id="edit_team" name="team_id" required>
                            <?php foreach ($data['teams'] as $team): ?>
                                <option value="<?= $team->team_id; ?>"><?= $team->team_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_shift">Shift:</label>
                        <select id="edit_shift" name="shift_id" required>
                            <?php foreach ($data['shifts'] as $shift): ?>
                                <option value="<?= $shift->shift_id; ?>"><?= $shift->shift_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_week_number">Week:</label>
                        <select id="edit_week_number" name="week_number" required>
                            <option value="1">Week 1</option>
                            <option value="2">Week 2</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Collection Days:</label>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
                        <label><input type="checkbox" name="days_of_week[]" value="mon"> Mon</label>
                        <label><input type="checkbox" name="days_of_week[]" value="tue"> Tue</label>
                        <label><input type="checkbox" name="days_of_week[]" value="wed"> Wed</label>
                        <label><input type="checkbox" name="days_of_week[]" value="thu"> Thu</label>
                        <label><input type="checkbox" name="days_of_week[]" value="fri"> Fri</label>
                        <label><input type="checkbox" name="days_of_week[]" value="sat"> Sat</label>
                        <label><input type="checkbox" name="days_of_week[]" value="sun"> Sun</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Update Schedule</button>
            </form>
        </div>
    </div>
</main>

<style>
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
}

.form-group select {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.btn-submit {
    padding: 0.5rem 1rem;
    background-color: var(--main);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: var(--main-dark);
}

.status-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-btn.active {
    background-color: #4CAF50;
    color: white;
}

.status-btn.inactive {
    background-color: #f44336;
    color: white;
}

.status-btn:hover {
    opacity: 0.8;
}

.delete-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    background-color: #f44336;
    color: white;
    cursor: pointer;
}

.delete-btn:hover {
    background-color: #da190b;
}

.filter-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.filter-controls select {
    padding: 5px 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.reset-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    background-color: #6c757d;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.reset-btn:hover {
    background-color: #5a6268;
}

.schedule-row.hidden {
    display: none;
}
</style>

<script>
function loadScheduleData(scheduleId) {
    <?php echo 'const schedules = ' . json_encode($data['schedules']) . ';'; ?>
    <?php echo 'const routes = ' . json_encode($data['routes']) . ';'; ?>
    
    const schedule = schedules.find(s => s.schedule_id === scheduleId);
    if (schedule) {
        document.getElementById('edit_route').value = schedule.route_id;
        document.getElementById('edit_team').value = schedule.team_id;
        document.getElementById('edit_shift').value = schedule.shift_id;
        document.getElementById('edit_week_number').value = schedule.week_number;
        
        const selectedRoute = routes.find(r => r.route_id === schedule.route_id);
        if (selectedRoute) {
            let vehicleInput = document.getElementById('hidden_vehicle_id');
            if (!vehicleInput) {
                vehicleInput = document.createElement('input');
                vehicleInput.type = 'hidden';
                vehicleInput.id = 'hidden_vehicle_id';
                vehicleInput.name = 'vehicle_id';
                document.getElementById('editScheduleForm').appendChild(vehicleInput);
            }
            vehicleInput.value = selectedRoute.vehicle_id;
        }
        
        const days = schedule.days_of_week.split(',');
        document.querySelectorAll('input[name="days_of_week[]"]').forEach(checkbox => {
            checkbox.checked = days.includes(checkbox.value);
        });
    }
}

function updateVehicleId(routeId) {
    const routeSelect = document.getElementById('route');
    const selectedOption = routeSelect.options[routeSelect.selectedIndex];
    const vehicleId = selectedOption.getAttribute('data-vehicle-id');
    document.getElementById('vehicle_id').value = vehicleId;
}

document.addEventListener('DOMContentLoaded', function() {
    updateVehicleId(document.getElementById('route').value);
});

function filterSchedules() {
    const weekFilter = document.getElementById('week-filter').value;
    const dayFilter = document.getElementById('day-filter').value;
    const shiftFilter = document.getElementById('shift-filter').value;
    const rows = document.querySelectorAll('.schedule-row');

    rows.forEach(row => {
        const weekMatch = weekFilter === 'all' || row.dataset.week === weekFilter;
        const dayMatch = dayFilter === 'all' || row.dataset.days.includes(dayFilter);
        const shiftMatch = shiftFilter === 'all' || row.dataset.shift === shiftFilter;
        
        if (weekMatch && dayMatch && shiftMatch) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}

function resetFilters() {
    document.getElementById('week-filter').value = 'all';
    document.getElementById('day-filter').value = 'all';
    document.getElementById('shift-filter').value = 'all';
    filterSchedules();
}

// Update event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('week-filter').addEventListener('change', filterSchedules);
    document.getElementById('day-filter').addEventListener('change', filterSchedules);
    document.getElementById('shift-filter').addEventListener('change', filterSchedules);
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>