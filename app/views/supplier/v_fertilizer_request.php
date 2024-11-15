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
                                <a href="SupplyDashboard.html">Home > </a>
                            </li>
                            <li><i class='bx bx-chevron-right'></i></li>
                            <li>
                                <a class="active" href="#"> Fertilizer Requests</a>
                            </li>
                        </ul>
                    </div>
    
                    <div class="table-data">
                        <div class="todo">
                            <div class="head">
                                <h3>Fertilizer Request History</h3>
                                <i class='bx bx-plus'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                            <canvas id="fertilizerRequestChart" ></canvas>
                        </div>
                        <div class="tea-order-history">
                            <div class="head">
                                <i class='bx bx-plus'></i>
                                <i class='bx bx-filter'></i>
                            </div>
                            <canvas id="fertilizerChart" width="500" height="400"></canvas>
                        </div>
                    </div>

                
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Fertilizer Requests History</h3>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th id="table-head">Request id</th>
                                    <th id="table-head">Supplier id</th>
                                    <th id="table-head">Order Date and Time</th>
                                    <th id="table-head">Amount in kg</th>
                                    <th id="table-head">Payment Status</th>
                                    <th id="table-head">Update</th>
                                    <th id="table-head">Cancel order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['orders'] as $order): ?>
                                    <tr>
                                        <td><?php echo $order->order_id; ?></td> 
                                        <td><?php echo $order->supplier_id; ?></td>
                                        <td><?php echo $order->order_date; ?></td>
                                        <td><?php echo $order->total_amount; ?></td>
                                        <td><?php echo $order->payment_status; ?></td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/Supplier/editFertilizerRequest/<?php echo $order->order_id; ?>" class="btn-edit btn-primary">
                                                Edit
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/Supplier/deleteFertilizerRequest/<?php echo $order->order_id; ?>" class="btn-delete btn-primary">
                                                Delete
                                            </a>
                                        </td>



                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>

                    </div>


                    <div class="table-data">
                        <div class="order">
                            <div class="head">
                                <h3>Request Form</h3>
                                <a href="FertilizerPage.php">
                                <button class="button">Dashboard</button>
                                </a>
                            </div>

                            <form action="<?php echo URLROOT; ?>/supplier/requestFertilizer" method="POST" class="complaint-form">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="supplier_id">Supplier ID:</label>
                                        <input type="text" id="supplier_id" name="supplier_id" >
                                    </div>
                                    <div>
                                        <label for="complaint-type">Total Amount:</label>
                                            <select id="complaint-type" name="total_amount" required>
                                                <?php 
                                                    for ($i = 1; $i <= 50; $i++) {
                                                        echo "<option value='$i'>{$i}kg</option>";
                                                    }
                                                ?>
                                            </select>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <input type="address" id="address" name="address" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number:</label>
                                        <input type="text" id="phone" name="phone_number" >
                                    </div>-->

                                    <button type="submit" class="button" onclick="submitmessage()">Submit Request</button>
                                    <button type="submit" class="button" onclick="refreshPage()">Cancel</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </main>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- TEA ORDER CHART -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('fertilizerChart').getContext('2d');
        var fertilizerChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Requests',
                    data: [120, 10, 200, 180, 220, 80], // Example data 
                    fill: false,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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
                        text: 'Requests (Monthly)'
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
                            text: 'Number of Requests'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
<script src="../public/script.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('fertilizerRequestChart').getContext('2d');
            var fertilizerRequestChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['June', 'July', 'August', 'September', 'October', 'November'],
                    datasets: [{
                        data: [120, 10, 200, 180, 220, 80 ], // Example data for tea orders
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
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
                            text: 'Fertilizer Request History (Monthly Distribution)'
                        }
                    }
                }
            });
        });
    </script>


	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/script.css" />
    </body>
    </html>
    