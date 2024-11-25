<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Weekly Schedule Overview</h3>
            <select id="week-selector">
                <option value="1">Week 1</option>
                <option value="2">Week 2</option>
            </select>
        </div>
        
        <div class="schedule-grid">
            <div class="time-slots">
                <!-- Empty corner cell -->
                <div class="corner-cell"></div>
                <!-- Days header -->
                <div class="day-header">Mon</div>
                <div class="day-header">Tue</div>
                <div class="day-header">Wed</div>
                <div class="day-header">Thu</div>
                <div class="day-header">Fri</div>
                <div class="day-header">Sat</div>
                <div class="day-header">Sun</div>
            </div>
            
            <div class="schedule-body">
                <?php foreach ($data['shifts'] as $shift): ?>
                    <div class="shift-row">
                        <div class="shift-time">
                            <?= $shift->shift_name ?><br>
                            <small><?= $shift->start_time ?> - <?= $shift->end_time ?></small>
                        </div>
                        <?php for ($day = 1; $day <= 7; $day++): ?>
                            <div class="schedule-cell" data-shift="<?= $shift->shift_id ?>" data-day="<?= $day ?>">
                                <!-- Schedules will be populated via JavaScript -->
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.schedule-grid {
    overflow-x: auto;
    margin-top: 20px;
    background: var(--light);
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.time-slots {
    display: grid;
    grid-template-columns: 150px repeat(7, 1fr);
    gap: 1px;
    background-color: var(--grey);
    border-radius: 8px 8px 0 0;
}

.corner-cell {
    background-color: var(--light);
    padding: 15px;
    font-weight: bold;
    color: var(--dark);
}

.day-header {
    background-color: var(--main);
    color: var(--light);
    padding: 15px;
    text-align: center;
    font-weight: bold;
}

.schedule-body {
    display: flex;
    flex-direction: column;
}

.shift-row {
    display: grid;
    grid-template-columns: 150px repeat(7, 1fr);
    gap: 1px;
    background-color: var(--grey);
}

.shift-time {
    background-color: var(--light);
    padding: 15px;
    font-weight: bold;
    color: var(--dark);
}

.schedule-cell {
    background-color: var(--light);
    padding: 15px;
    min-height: 80px;
    transition: all 0.3s ease;
}

.schedule-cell:hover {
    background-color: var(--grey);
}

.schedule-item {
    background-color: var(--light-main);
    border-radius: 6px;
    padding: 8px;
    margin-bottom: 5px;
    font-size: 0.9em;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid var(--main);
    color: var(--dark);
}

.schedule-item:hover {
    background-color: var(--main);
    color: var(--light);
    transform: translateY(-2px);
}

#week-selector {
    padding: 8px 15px;
    border-radius: 6px;
    border: 1px solid var(--grey);
    background-color: var(--light);
    color: var(--dark);
    cursor: pointer;
    transition: all 0.3s ease;
}

#week-selector:hover {
    border-color: var(--main);
}

.empty-routes {
    text-align: center;
    padding: 20px;
    color: var(--dark-grey);
    font-style: italic;
}
</style>

<script>
function populateScheduleGrid(weekNumber) {
    // Clear existing schedules
    document.querySelectorAll('.schedule-cell').forEach(cell => {
        cell.innerHTML = '';
    });
    
    // Get schedules data
    const schedules = <?php echo json_encode($data['schedules']); ?>;
    
    // Filter schedules for selected week
    const weekSchedules = schedules.filter(schedule => 
        schedule.week_number == weekNumber
    );
    
    // Map day names to numbers
    const dayMap = {
        'mon': 1, 'tue': 2, 'wed': 3, 'thu': 4,
        'fri': 5, 'sat': 6, 'sun': 7
    };
    
    // Populate cells with schedules
    weekSchedules.forEach(schedule => {
        const days = schedule.days_of_week.split(',');
        days.forEach(day => {
            const dayNum = dayMap[day.toLowerCase()];
            const cell = document.querySelector(
                `.schedule-cell[data-shift="${schedule.shift_id}"][data-day="${dayNum}"]`
            );
            
            if (cell) {
                cell.innerHTML += `
                    <div class="schedule-item" title="Route: ${schedule.route_name}\nTeam: ${schedule.team_name}">
                        ${schedule.route_name}<br>
                        <small>${schedule.team_name}</small>
                    </div>
                `;
            }
        });
    });
}

// Add event listener for week selector
document.getElementById('week-selector').addEventListener('change', function() {
    populateScheduleGrid(this.value);
});

// Initialize with Week 1
document.addEventListener('DOMContentLoaded', function() {
    populateScheduleGrid(1);
});
</script> 