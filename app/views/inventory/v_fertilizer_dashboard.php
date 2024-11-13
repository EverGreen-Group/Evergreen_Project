<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/f_dashboard.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

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

            <section class="chart-section">
                <div class="chart">
                    <span>Label 1</span>
                    <!-- Replace with actual chart if needed -->
                    <img src="chart-placeholder.png" alt="Chart Placeholder">
                </div>
            </section>

            <section class="fertilizer-stock">
                <h2>Fertilizer Stock</h2>
                <p>This month (3)</p>
                <a href="#" class="details-link">View detail ></a>

                <table>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>B 710</td>
                            <td>1000kg</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>B 589</td>
                            <td>50kg</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>C 450</td>
                            <td>50kg</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>C 345</td>
                            <td>Content</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Content</td>
                            <td>Content</td>
                            <td><button class="update-btn">Update</button></td>
                            <td><button class="delete-btn">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </section>

        </div>
    </main>

    <?php require APPROOT . '/views/inc/components/footer.php' ?>