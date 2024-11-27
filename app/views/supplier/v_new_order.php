<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tea Leaves Supplier</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="SupplyDashboard.html">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">New Order</a>
                </li>
            </ul>
        </div>
        <!-- <div>
                <div class="table-data">
                    <h3>Tea Order History Chart</h3>
                    <i class='bx bx-plus'></i>
                    <i class='bx bx-filter'></i>
                    <div class="head">
                        <canvas id="teaOrderHistoryChart" width="400" height="200"></canvas>
                    </div>
                </div>
                </div> -->


        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Latest Tea Packet Orders</h3>
                </div>
                <div class="table-container">
                    <table class="table-body">
                        <thead>
                            <tr>
                                <th>Supplier ID</th>
                                <th>Quantity</th>
                                <th>Order Date</th>
                                <th>Order Time</th>
                                <th>Accepted/Rejected</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>001<br>
                                <td>2Kg</td>
                                <td>2024/11/10</td>
                                <td>12:59pm<br>
                                <td>
                                    <button class="pending-btn">pending</button>
                                </td>
                            </tr>
                            <tr>
                                <td>002<br>
                                <td>1Kg</td>
                                <td>2024/11/1</td>
                                <td>01:23pm<br>
                                <td>
                                    <button class="accept-btn">Accepted</button>
                                </td>
                            </tr>
                            <tr>
                                <td>003<br>
                                <td>3Kg</td>
                                <td>2024/11/1</td>
                                <td>10:15am<br>
                                <td>
                                    <button class="reject-btn">Rejected</button>
                                </td>
                            </tr>
                            <tr>
                                <td>001<br>
                                <td>5Kg</td>
                                <td>2024/10/12</td>
                                <td>04:40pm<br>
                                <td>
                                    <button class="accept-btn">Accepted</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <!-- <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>New Order Form</h3>
                            <a href="<?php echo URLROOT; ?>/Supplier/" >
                                <button class="button">Home</button>
                            </a>
                        </div>
                        <form action="submit_complaint.php" method="post" class="complaint-form">
                            <div class="form-group">

                            <div class="form-group">
                                <label for="description">Full Name:</label>
                                <input type="fullname" id="fullname" name="fullname" required>

                            <div class="form-group">
                                <label for="total_amount">Packet Amount:</label>
                                <input type="number" id="total_amount" name="total_amount" min="1" max="50" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Address:</label>
                                <input type="address" id="address" name="address" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number:</label>
                                <input type="text" id="phone" name="phone">
                            </div>

                            <button type="submit" class="button" onclick="submitmessage()">Submit Request</button>
                            <button type="submit" class="button" onclick="refreshPage()">Cancel</button>
                        </form>
                    </div>
                </div> -->

</main>
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- TEA ORDER CHART -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('teaOrderHistoryChart').getContext('2d');
        var teaOrderHistoryChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'], // Example months
                datasets: [{
                    label: 'Tea Orders',
                    data: [120, 150, 180, 220, 250, 210], // Example data (tea orders per month)
                    fill: false,
                    backgroundColor: 'rgba(210, 0, 0)',
                    borderColor: 'rgba(147, 0, 0, 0.5)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Tea Order History (Monthly)'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>
</body>

</html>