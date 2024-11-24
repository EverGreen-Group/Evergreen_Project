<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo SITENAME; ?></title>
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

  <!-- Top nav bar -->
  <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
  <!-- Side bar -->
  <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

  <style>
        .calendar {
            font-family: Arial, sans-serif;
            width: 300px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 20px;
            padding: 20px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-header button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .calendar-grid div {
            text-align: center;
            padding: 5px;
        }

        .calendar-grid .day-name {
            font-weight: bold;
            color: #00a99d;
        }

        .calendar-grid .date {
            cursor: pointer;
            border-radius: 50%;
        }

        .calendar-grid .date:hover {
            background-color: #00a99d;
            color: white;
        }

        .calendar-grid .current {
            background-color: #00a99d;
            color: white;
        }
    </style>
</head>
<body>
   

    <div class="calendar">
        <div class="calendar-header">
            <button id="prevMonth">&lt;</button>
            <h2 id="monthDisplay"></h2>
            <button id="nextMonth">&gt;</button>
        </div>
        <div class="calendar-grid" id="calendarDays">
        </div>
    </div>

    <script>
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];

        function generateCalendar(month, year) {
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startingDay = firstDay.getDay();
            const monthLength = lastDay.getDate();

            const calendarDays = document.getElementById('calendarDays');
            const monthDisplay = document.getElementById('monthDisplay');

            monthDisplay.textContent = `${monthNames[month]} ${year}`;
            calendarDays.innerHTML = '';

            // Add day names
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayNames.forEach(day => {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'day-name';
                dayDiv.textContent = day;
                calendarDays.appendChild(dayDiv);
            });

            // Add blank spaces for days before start of month
            for (let i = 0; i < startingDay; i++) {
                const blankDiv = document.createElement('div');
                calendarDays.appendChild(blankDiv);
            }

            // Add days of the month
            for (let i = 1; i <= monthLength; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'date';
                dayDiv.textContent = i;
                if (i === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear()) {
                    dayDiv.classList.add('current');
                }
                calendarDays.appendChild(dayDiv);
            }
        }

        document.getElementById('prevMonth').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
        });

        // Initial calendar generation
        generateCalendar(currentMonth, currentYear);
    </script>
</body>
</html>
