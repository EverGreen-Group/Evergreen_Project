<?php

require APPROOT . '/views/inc/components/header.php';
require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php';
require APPROOT . '/views/inc/components/topnavbar.php';
?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Driver Partner Dashboard</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li>Overview</li>
            </ul>
        </div>
    </div>

    <!-- Quick Stats -->
    <ul class="route-box-info">
        <li>
            <i class='bx bxs-timer'></i>
            <span class="text">
                <p>Next Collection</p>
                <h3>2:30 PM</h3>
                <span>Hatton Central</span>
            </span>
        </li>
        <li>
            <i class='bx bxs-calendar'></i>
            <span class="text">
                <p>Today's Progress</p>
                <h3>4/6</h3>
                <span>Collections</span>
            </span>
        </li>
        <li>
            <i class='bx bxs-map'></i>
            <span class="text">
                <p>Distance Today</p>
                <h3>45 km</h3>
                <span>of 75 km planned</span>
            </span>
        </li>
    </ul>

    <!-- Earnings Overview -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Monthly Earnings - July 2024</h3>
                <div class="total-earnings">Rs. 45,750</div>
            </div>
            <div class="earnings-breakdown">
                <div class="earnings-chart">
                    <canvas id="earningsChart"></canvas>
                </div>
                <div class="earnings-details">
                    <div class="breakdown-item">
                        <span class="label">Base Salary</span>
                        <span class="amount">Rs. 30,000</span>
                    </div>
                    <div class="breakdown-item">
                        <span class="label">Distance Bonus</span>
                        <span class="amount">Rs. 8,250</span>
                    </div>
                    <div class="breakdown-item">
                        <span class="label">On-time Bonus</span>
                        <span class="amount">Rs. 4,500</span>
                    </div>
                    <div class="breakdown-item">
                        <span class="label">Overtime</span>
                        <span class="amount">Rs. 3,000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection History -->
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Recent Collections</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Distance</th>
                        <th>Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jul 15, 2024</td>
                        <td>Hatton Central</td>
                        <td>2:30 PM</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>15 km</td>
                        <td>Rs. 1,500</td>
                    </tr>
                    <tr>
                        <td>Jul 15, 2024</td>
                        <td>Nuwara Eliya Hub</td>
                        <td>11:45 AM</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>12 km</td>
                        <td>Rs. 1,200</td>
                    </tr>
                    <tr>
                        <td>Jul 14, 2024</td>
                        <td>Talawakelle Point</td>
                        <td>3:15 PM</td>
                        <td><span class="status pending">Pending</span></td>
                        <td>18 km</td>
                        <td>Rs. 1,800</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
.route-box-info {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    margin-top: 36px;
    list-style: none;
    padding: 0;
}

.route-box-info li {
    flex: 1;
    background: var(--light);
    border-radius: 20px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 24px;
}

.route-box-info li i {
    font-size: 36px;
    color: var(--main);
    background: var(--light-main);
    border-radius: 10%;
    padding: 16px;
}

.route-box-info li .text h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

.route-box-info li .text p {
    font-size: 14px;
    color: var(--dark-grey);
    margin: 0;
}

/* Earnings Section */
.earnings-breakdown {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-top: 20px;
}

.earnings-chart {
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
    height: 300px;
}

.earnings-details {
    background: var(--light);
    padding: 20px;
    border-radius: 10px;
}

.breakdown-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
}

.breakdown-item:last-child {
    border-bottom: none;
}

.breakdown-item .label {
    color: var(--dark-grey);
}

.breakdown-item .amount {
    font-weight: 600;
    color: var(--dark);
}

.total-earnings {
    font-size: 24px;
    font-weight: 600;
    color: var(--main);
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

th {
    font-weight: 600;
    color: var(--dark-grey);
}

.status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
}

.status.completed {
    background: var(--light-success);
    color: var(--success);
}

.status.pending {
    background: var(--light-warning);
    color: var(--warning);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Earnings Chart
const ctx = document.getElementById('earningsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
            label: 'Weekly Earnings',
            data: [12500, 11800, 10900, 10550],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rs. ' + value;
                    }
                }
            }
        }
    }
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>