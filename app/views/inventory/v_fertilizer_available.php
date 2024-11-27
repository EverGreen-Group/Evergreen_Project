<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/f_available.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />

</head>

<body>

    <!-- Top nav bar -->
    <?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
    <!-- Side bar -->
    <?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

    <main>

        <header style="margin: 20px;">
            <h1>Fertilizer</h1>
            <!-- <div class="search-filter">
                <input type="text" placeholder="Search">
                <button class="filter-btn">Filter</button>
            </div> -->
            <a href="<?php echo URLROOT; ?>/inventory/fertilizerdashboard">
                <button class="create-btn">Fertilizer Dashboard</button>
            </a>
        </header>

        <div class="container">
            <section class="orders">
                <h2>Available Orders <span class="inventory-count">10 Inventory Plans</span></h2>
                <table>
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Inventory Date</th>
                            <th>Status</th>
                            <th>Approved</th>
                            <th>Discard</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="profile-icon">A</div>
                            </td>
                            <td>ishan</td>
                            <td>galle</td>
                            <td>Nov 23, 2023</td>
                            <td><span class="status">Accept</span></td>
                            <td><button class="action-btn accept-btn">Approve</button></td>
                            <td><button class="action-btn reject-btn">Reject</button></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="profile-icon">A</div>
                            </td>
                            <td>Simaak</td>
                            <td>galle</td>
                            <td>Nov 23, 2023</td>
                            <td><span class="status">Accept</span></td>
                            <td><button class="action-btn accept-btn">Approve</button></td>
                            <td><button class="action-btn reject-btn">Reject</button></td>
                        </tr>
                        <!-- Additional rows go here -->
                    </tbody>
                </table>
            </section>

            <section class="completed-orders">
                <h3>Completed</h3>
                <p>3 Inventory Plans</p>
            </section>

            <section class="cancel-orders">
                <h3>Cancel</h3>
                <p>1 Inventory Plan</p>
            </section>
        </div>

    </main>

    <?php require APPROOT . '/views/inc/components/footer.php' ?>