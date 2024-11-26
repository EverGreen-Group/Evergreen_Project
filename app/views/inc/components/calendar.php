<div class="simple-calendar">
    <div class="calendar-header">
        <button onclick="previousMonth()">&lt;</button>
        <span id="monthDisplay"></span>
        <button onclick="nextMonth()">&gt;</button>
    </div>
    <div class="weekdays">
        <div>Sun</div>
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
    </div>
    <div id="calendar"></div>
</div>

<style>
.simple-calendar {
    width: 100%;
    border: 1px solid #ddd;
    font-family: Arial, sans-serif;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background: #f0f0f0;
}

.weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    background: #f8f8f8;
    padding: 5px;
}

#calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.calendar-day {
    min-height: 80px;
    padding: 5px;
    border: 1px solid #eee;
    position: relative;
}

.calendar-day.inactive {
    background: #f5f5f5;
    color: #999;
}

.shift-reminder {
    font-size: 11px;
    background: #007664;
    color: white;
    padding: 2px 4px;
    margin: 2px 0;
    border-radius: 2px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: flex;
    flex-direction: column;
}

.shift-time {
    font-weight: bold;
}

.shift-location {
    font-size: 10px;
    opacity: 0.9;
}

.shift-reminder:hover {
    background: #005a4d;
}
</style>

<script>
let currentDate = new Date();
let shifts = <?php echo json_encode($data['shifts'] ?? []); ?>;

function loadCalendar() {
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    
    document.getElementById('monthDisplay').textContent = 
        `${currentDate.toLocaleString('default', { month: 'long' })} ${currentDate.getFullYear()}`;
    
    const calendar = document.getElementById('calendar');
    calendar.innerHTML = '';

    // Add empty cells for days before first day of month
    for(let i = 0; i < firstDay.getDay(); i++) {
        const dayElement = document.createElement('div');
        dayElement.classList.add('calendar-day', 'inactive');
        calendar.appendChild(dayElement);
    }

    // Add days of current month
    for(let day = 1; day <= lastDay.getDate(); day++) {
        const dayElement = document.createElement('div');
        dayElement.classList.add('calendar-day');
        
        // Add date number
        const dateNumber = document.createElement('div');
        dateNumber.textContent = day;
        dayElement.appendChild(dateNumber);

        // Check for shifts on this day
        const currentDateString = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        if(shifts[currentDateString]) {
            shifts[currentDateString].forEach(shift => {
                const reminder = document.createElement('div');
                reminder.classList.add('shift-reminder');
                
                const timeSpan = document.createElement('span');
                timeSpan.classList.add('shift-time');
                timeSpan.textContent = `${shift.start_time} - ${shift.end_time}`;
                
                const locationSpan = document.createElement('span');
                locationSpan.classList.add('shift-location');
                locationSpan.textContent = shift.location;
                
                reminder.appendChild(timeSpan);
                reminder.appendChild(locationSpan);
                dayElement.appendChild(reminder);
            });
        }

        calendar.appendChild(dayElement);
    }
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    loadCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    loadCalendar();
}

// Initial load
loadCalendar();
</script> 