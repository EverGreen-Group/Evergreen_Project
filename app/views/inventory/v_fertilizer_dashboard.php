<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/f_dashboard.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <!-- Top nav bar -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
    <!-- Side bar -->
    <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

    <main>
        <div class="container">
            <header>
                <h1>Fertilizer</h1>
                <a href="<?php echo URLROOT; ?>/inventory/createfertilizer">
                    <button class="filter-btn">+ New fertilizer</button>
                </a>
            </header>

            <section class="summary">
                <div class="summary-box completed-orders">
                    <h3>Completed Orders</h3>
                    <p class="count">1,345</p>
                    <a href="#" class="details-link">View detail ></a>
                </div>

                <div class="summary-box cancel-orders">
                    <h3>Cancel Orders</h3>
                    <p class="count">12</p>
                    <a href="#" class="details-link">View detail ></a>
                </div>

                <div class="summary-box available-orders">
                    <h3>Available Orders</h3>
                    <p class="count">200</p>
                    <a href="#" class="details-link">View detail ></a>
                </div>

                <div class="summary-box to-be-ordered">
                    <h3>To be ordered</h3>
                    <p class="count">120</p>
                    <a href="#" class="details-link">View detail ></a>
                </div>
            </section>

            <section style="display: flex; justify-content: center; align-items: center; padding: 20px; margin: 20px;">
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
                        <td>code</td>
                        <td>Quantity</td>
                        <td>update</td>
                        <td>delete</td>
                    </thead>
                    <tbody>

                        <tr>

                            <td>B 710</td>
                            <td>1000kg</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                        <tr>

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
                        </tr>
                    </tbody>
                </table>
            </section>

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