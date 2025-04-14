<?php require APPROOT . '/views/inc/components/header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/f_available.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />

</head>



<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

<main>

    <div class="head-title">
        <div class="left">
            <h1>Fertilizer Available</h1>

        </div>
        <a href="<?php echo URLROOT; ?>/inventory/fertilizerdashboard">
            <button class="btn btn-primary">Fertilizer Dashboard</button>
        </a>
    </div>

    </header>

    <div class="container">
        <section class="orders">
            <div class="head">
                <h3>Available Orders </h3>
            </div>

            <table>
                <thead>
                    <tr>

                        <th>Name</th>
                        <th>Location</th>
                        <th>Inventory Date</th>
                        <th>Status</th>
                        <th>Approved</th>
                        <th>Discard</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($fertilizers)): ?>
                        <?php foreach ($fertilizers as $fertilizer): ?>
                            <tr>
                                <td><?php echo $fertilizer->first_name; ?></td>
                                <td><?php echo $fertilizer->address; ?></td>
                                <td><?php echo $fertilizer->order_date; ?></td>
                                <td><span class="status"><?php echo $fertilizer->status; ?></span></td>
                                <td><button class="btn btn-primary">Approve</button></td>
                                <td><button class="action-btn reject-btn">Reject</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No fertilizers available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>


            </table>
        </section>


    </div>

</main>

<?php require APPROOT . '/views/inc/components/footer.php' ?>