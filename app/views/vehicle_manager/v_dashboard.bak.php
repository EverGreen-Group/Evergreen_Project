<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>

<div class="head-title">
    <div class="left">
        <h1>Collection Management</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
        </ul>
    </div>
</div>

<ul class="box-info">
    <li>
        <i class='bx bxs-car'></i>
        <span class="text">
            <h3>20</h3>
            <p>Vehicles</p>
            <small>10 Available</small>
        </span>
    </li>
    <li>
        <i class='bx bxs-user'></i>
        <span class="text">
            <h3>15</h3>
            <p>Drivers</p>
            <small>4 Available</small>
        </span>
    </li>
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3>15</h3>
            <p>Driving Partners</p>
            <small>4 Available</small>
        </span>
    </li>
</ul>

<!-- Second Row: Bar Chart for Weekly Tea Leaves Collection -->
<div class="chart-section">
    <div class="chart-box">
        <h3>Weekly Tea Leaves Collection</h3>
        <canvas id="weeklyCollectionChart"></canvas>
    </div>
    <div class="chart-details">
        <div class="stat-item">
            <span class="stat-label">Total Collection:</span>
            <span class="stat-value" id="totalCollection"></span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Average Daily Collection:</span>
            <span class="stat-value" id="avgDailyCollection"></span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Highest Collection Day:</span>
            <span class="stat-value" id="highestCollectionDay"></span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Lowest Collection Day:</span>
            <span class="stat-value" id="lowestCollectionDay"></span>
        </div>
    </div>
</div>

<!-- Third Row: Pie Chart for Total Collection Statistics -->
<div class="chart-section">
    <div class="chart-box">
        <h3>Total Collection Statistics</h3>
        <canvas id="collectionPieChart"></canvas>
    </div>
</div>

<!-- Fourth Row: Tea Leaves Collection Table -->
<div class="table-section">
    <h3>Tea Leaves Collection Table</h3>
    <table id="teaLeavesCollectionTable">
        <thead>
            <tr>
                <th>Collection ID</th>
                <th>Total Suppliers</th>
                <th>Route</th>
                <th>Team ID</th>
                <th>Shift ID</th>
                <th>Estimated Amount (kg)</th>
                <th>Status</th>
                <th>Date Time</th>
                <th>Time Elapsed</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be dynamically populated here -->
        </tbody>
    </table>
</div>

</main>

<!-- JS & Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Bar chart for weekly tea leaves collection
  var ctx = document.getElementById('weeklyCollectionChart').getContext('2d');
  var collectionData = [500, 700, 600, 800, 900, 1000, 950]; // Replace with dynamic data
  var weeklyCollectionChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
      datasets: [{
        label: 'Tea Collection (kg)',
        data: collectionData,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Collection (kg)'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Day of Week'
          }
        }
      },
      plugins: {
        title: {
          display: true,
          text: 'Weekly Tea Leaves Collection',
          font: {
            size: 18
          }
        }
      }
    }
  });

  // Calculate and display additional details
  var totalCollection = collectionData.reduce((a, b) => a + b, 0);
  var avgDailyCollection = (totalCollection / 7).toFixed(2);
  var highestCollectionDay = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][collectionData.indexOf(Math.max(...collectionData))];
  var lowestCollectionDay = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][collectionData.indexOf(Math.min(...collectionData))];

  document.getElementById('totalCollection').textContent = totalCollection + ' kg';
  document.getElementById('avgDailyCollection').textContent = avgDailyCollection + ' kg';
  document.getElementById('highestCollectionDay').textContent = highestCollectionDay;
  document.getElementById('lowestCollectionDay').textContent = lowestCollectionDay;

  // Pie chart for total collections
  var ctx2 = document.getElementById('collectionPieChart').getContext('2d');
  var collectionPieChart = new Chart(ctx2, {
    type: 'pie',
    data: {
      labels: ['Total Collected', 'Total Delivered', 'Total Cancelled'],
      datasets: [{
        label: 'Collections',
        data: [5000, 3500, 300], // Replace with dynamic data
        backgroundColor: [
          'rgba(75, 192, 192, 0.6)',
          'rgba(153, 102, 255, 0.6)',
          'rgba(255, 99, 132, 0.6)'
        ],
        borderColor: [
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 99, 132, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: 'Total Collection Statistics',
          font: {
            size: 18
          }
        },
        legend: {
          position: 'bottom'
        }
      }
    }
  });

  // Populate Tea Leaves Collection Table
  function populateTeaLeavesCollectionTable() {
    const tableBody = document.querySelector('#teaLeavesCollectionTable tbody');
    const sampleData = [
      {id: 'COL001', suppliers: 5, route: 'Route A', teamId: 'T001', shiftId: 'S001', amount: 500, status: 'Completed', dateTime: '2024-09-12 08:00', elapsed: '2h 30m'},
      {id: 'COL002', suppliers: 3, route: 'Route B', teamId: 'T002', shiftId: 'S002', amount: 300, status: 'In Progress', dateTime: '2024-09-12 09:30', elapsed: '1h 00m'},
      {id: 'COL003', suppliers: 4, route: 'Route C', teamId: 'T001', shiftId: 'S001', amount: 450, status: 'Scheduled', dateTime: '2024-09-12 11:00', elapsed: '-'},
      // Add more sample data as needed
    ];

    sampleData.forEach(row => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${row.id}</td>
        <td>${row.suppliers}</td>
        <td>${row.route}</td>
        <td>${row.teamId}</td>
        <td>${row.shiftId}</td>
        <td>${row.amount}</td>
        <td>${row.status}</td>
        <td>${row.dateTime}</td>
        <td>${row.elapsed}</td>
      `;
      tableBody.appendChild(tr);
    });
  }

  // Call the function to populate the table
  populateTeaLeavesCollectionTable();
</script>

<!-- CONTENT END -->
<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<!-- CSS Section -->
<style>
/* General Styling */

.chart-section {
    margin: 40px 0;
}

.chart-box {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
    height: 400px;
    position: relative;
}

.chart-box canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.chart-details {
    margin-top: 20px;
    background: #f4f4f4;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
}

.chart-details .stat-item {
    flex: 1;
    text-align: center;
}

.chart-details .stat-label {
    font-size: 14px;
    color: #666;
    display: block;
    margin-bottom: 5px;
}

.chart-details .stat-value {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.table-section {
    margin-top: 40px;
    background-color: white;
}

#teaLeavesCollectionTable {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

#teaLeavesCollectionTable th,
#teaLeavesCollectionTable td {
  background-color: white;
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

#teaLeavesCollectionTable th {
    background-color: white;
    font-weight: bold;
}

#teaLeavesCollectionTable tr:nth-child(even) {
    background-color: #f9f9f9;
}

#teaLeavesCollectionTable tr:hover {
    background-color: #f5f5f5;
}
</style>