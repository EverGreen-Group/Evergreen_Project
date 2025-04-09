<?php require APPROOT . '/views/inc/components/header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/f_dashboard.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .fertilizer-constraints {
            margin: 20px 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .constraints-order {
            width: 100%;
        }

        .constraints-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .constraints-head h3 {
            color: #2B2D42;
            font-size: 1.2rem;
        }

        .btn-save {
            background-color: #36A2EB;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: #2d8ac7;
        }

        .constraints-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .constraint-group {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
        }

        .constraint-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .constraint-label {
            font-weight: 500;
            color: #2B2D42;
        }

        .constraint-input {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .constraint-input input,
        .constraint-input select {
            width: 100px;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .constraint-input select {
            width: 120px;
        }

        .unit {
            color: #666;
        }

        .constraint-info {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 0.9rem;
        }

        .constraint-info i {
            color: #36A2EB;
        }
    </style>
</head>

<body>
    <!-- Top nav bar -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
    <!-- Side bar -->
    <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

    <main>


        <div class="head-title">
            <div class="left">
                <h1>Fertilizer</h1>
                <ul class="breadcrumb">
                </ul>
            </div>
            <div class="action-buttons">
                <a href="<?php echo URLROOT; ?>/inventory/createfertilizer">
                    <button class="btn btn-primary">
                        <i class='bx bx-plus'></i>
                        New fertilizer
                    </button>
                </a>
            </div>
        </div>




        <ul class="box-info">
            <li>
                <i class='bx bxs-shopping-bag'></i>
                <span class="text">
                    <p>Accept Orders</p>
                    <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
                </span>
            </li>
            <li>
                <i class='bx bxs-user'></i>
                <span class="text">
                    <p>Reject Orders</p>
                    <h3><?php echo isset($data['a']) ? $data['a'] : '0'; ?></h3>
                </span>
            </li>
            <li>
                <i class='bx bxs-user'></i>
                <span class="text">
                    <p>Available Orders</p>
                    <h3><?php echo isset($data['b']) ? $data['b'] : '0'; ?></h3>
                </span>
            </li>
        </ul>


        <!-- Fertilizer Constraints Section -->
        <!-- <div class="table-data fertilizer-constraints">
                <div class="constraints-order">
                    <div class="constraints-head">
                        <h3>Fertilizer Request Constraints</h3>
                        <button class="btn-save" onclick="saveConstraints()">
                            <i class='bx bx-save'></i>
                            Save Changes
                        </button>
                    </div>
                    <div class="constraints-container">
                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Maximum Order Per Supplier</span>
                                <div class="constraint-input">
                                    <input type="number" id="maxOrder" value="20" min="0">
                                    <span class="unit">kg</span>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Maximum amount a supplier can request per order</span>
                            </div>
                        </div>

                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Minimum Stock Level</span>
                                <div class="constraint-input">
                                    <input type="number" id="minStock" value="500" min="0">
                                    <span class="unit">kg</span>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Alert threshold for low stock warning</span>
                            </div>
                        </div>

                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Order Frequency Limit</span>
                                <div class="constraint-input">
                                    <select id="orderFrequency">
                                        <option value="7">Weekly</option>
                                        <option value="14">Bi-weekly</option>
                                        <option value="30" selected>Monthly</option>
                                        <option value="90">Quarterly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>How often a supplier can place new orders</span>
                            </div>
                        </div>

                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Quantity per Acre</span>
                                <div class="constraint-input">
                                    <input type="number" id="qtyPerAcre" value="2.5" step="0.5" min="0">
                                    <span class="unit">kg</span>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Recommended fertilizer amount per acre of land</span>
                            </div>
                        </div>

                        <div class="constraint-group">
                            <div class="constraint-item">
                                <span class="constraint-label">Previous Supply Window</span>
                                <div class="constraint-input">
                                    <input type="number" id="supplyWindow" value="30" min="0">
                                    <span class="unit">days</span>
                                </div>
                            </div>
                            <div class="constraint-info">
                                <i class='bx bx-info-circle'></i>
                                <span>Check previous supply within this time period</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        <div class="table-data">
            <div class="order">

                <section
                    style="display: flex; justify-content: center; align-items: center; padding: 20px; margin: 20px;">
                    <div style="width: 80%; text-align: center;">
                        <h2>Monthly Fertilizer Usage</h2>
                        <canvas id="fertilizerChart"></canvas>
                    </div>
                </section>

                <section class="fertilizer-stock">
                    <h2>Fertilizer Stock</h2>
                    <p>This month (3)</p>
                    <a href="#" class="details-link">View detail ></a>

                    <table>
                        <thead>
                            <td>Fertilizer name</td>
                            <td>code</td>
                            <td>Quantity</td>
                            <td>update</td>
                            <td>delete</td>
                        </thead>
                        <tbody>
                            <?php foreach ($data['fertilizer'] as $fertilizer): ?>
                                <tr>
                                    <td><?php echo $fertilizer->fertilizer_name; ?></td>
                                    <td><?php echo $fertilizer->code; ?></td>
                                    <td><?php echo $fertilizer->quantity; ?></td>
                                    <td><a
                                            href="<?php echo URLROOT; ?>/inventory/updatefertilizer/<?php echo $fertilizer->id; ?>"><button
                                                class="update-btn">Update</button></a></td>
                                    <td><a
                                            href="<?php echo URLROOT; ?>/inventory/deletefertilizer/<?php echo $fertilizer->id; ?>"><button
                                                class="delete-btn">Delete</button></a></td>
                                </tr>
                            <?php endforeach; ?>
                            <!-- <tr>

                            <td>B 589</td>
                            <td>50kg</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                        <tr>

                            <td>C 450</td>
                            <td>50kg</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                        <tr>

                            <td>C 345</td>
                            <td>Content</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                        <tr>

                            <td>B 110</td>
                            <td>123Kg</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr> -->
                        </tbody>
                    </table>


                </section>

            </div>
        </div>
    </main>



    <?php require APPROOT . '/views/inc/components/footer.php' ?>

    <script>
        // Get the chart canvas
        const ctx = document.getElementById('fertilizerChart').getContext('2d');

        // Sample data - replace with actual data from your database
        const fertilizerData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Fertilizer Usage (kg)',
                data: [1200, 1900, 800, 1600, 2000, 1500],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // Create the bar chart
        new Chart(ctx, {
            type: 'bar',
            data: fertilizerData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity (kg)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Fertilizer Usage'
                    }
                }
            }
        });
    </script>
</body>

</html>