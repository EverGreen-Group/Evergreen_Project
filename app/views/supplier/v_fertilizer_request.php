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
                                    <th>Order id</th>
                                    <th>Fertilizer Type</th>
                                    <th>Order Date</th>
                                    <th>Order Time</th>
                                    <th>Amount</th>
                                    <th>Price</th>
                                    <th>Payment Status</th>
                                    <th>Update order</th>
                                    <th>Cancel order</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    <?php foreach ($data['orders'] as $order): ?>
                                        <tr>
                                            <td><?php echo $order->order_id; ?></td> 
                                            <td><?php echo $order->fertilizer_name; ?></td>
                                            <td><?php echo $order->order_date; ?></td>
                                            <td><?php echo $order->order_time; ?></td>
                                            <td><?php echo $order->total_amount . ' ' . $order->unit; ?></td>
                                            <td><?php echo $order->total_price; ?></td>
                                            <td><?php echo isset($order->payment_status) ? $order->payment_status : 'Pending'; ?></td>
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

                            <form action="<?php echo URLROOT; ?>/supplier/createFertilizerOrder" method="POST" class="complaint-form" id="fertilizerForm">
                                <div class="form-group">
                                    
                                    <div class="form-group">
                                        <label for="type_id">Fertilizer Type:</label>
                                        <select id="type_id" name="type_id" required>
                                            <option value="">Select Fertilizer</option>
                                            <?php foreach($data['fertilizer_types'] as $type): ?>
                                                <option value="<?php echo $type->type_id; ?>" 
                                                        data-unit-price-kg="<?php echo $type->unit_price_kg; ?>"
                                                        data-pack-price="<?php echo $type->unit_price_packs; ?>"
                                                        data-box-price="<?php echo $type->unit_price_box; ?>">
                                                    <?php echo $type->name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="unit">Unit:</label>
                                        <select id="unit" name="unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="kg">Kilograms (kg)</option>
                                            <option value="packs">Packs</option>
                                            <option value="box">Box</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="total_amount">Total Amount:</label>
                                        <input type="number" id="total_amount" name="total_amount" min="1" max="50" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="price_per_unit">Price Per Unit:</label>
                                        <input type="number" id="price_per_unit" name="price_per_unit" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="total_price">Total Price:</label>
                                        <input type="number" id="total_price" name="total_price" readonly>
                                    </div>
                                    <button type="submit" class="button">Submit Request</button>
                                    <button type="button" class="button" onclick="document.getElementById('fertilizerForm').reset()">Cancel</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </main>
        </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.FERTILIZER_TYPES = <?php echo json_encode($data['fertilizer_types']); ?>;
    </script>
    <script>
        /* TEA ORDER CHART */
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
    <script>
        /* FERTILIZER REQUEST PIE CHART */
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
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
</html>
</body>
    