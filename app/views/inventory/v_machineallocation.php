<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/machineallo.css" />
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
            <h1>MACHINE ALLOCATION</h1>
            <table>
                <thead>
                    <tr>
                        <th>Check</th>
                        <th>Machine</th>
                        <th>Status</th>
                        <th>Button</th>
                        <th>Button</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>
                            <div class="machine-info">
                                <span class="machine-icon">A</span>
                                <span>Machine A</span>
                            </div>
                        </td>
                        <td><span class="status-ready">Ready</span></td>
                        <td><button class="btn allocate">Allocate</button></td>
                        <td><button class="btn deallocate">Deallocate</button></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>
                            <div class="machine-info">
                                <span class="machine-icon">A</span>
                                <span>Machine B</span>
                            </div>
                        </td>
                        <td><span class="status-ready">Ready</span></td>
                        <td><button class="btn allocate">Allocate</button></td>
                        <td><button class="btn deallocate">Deallocate</button></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>
                            <div class="machine-info">
                                <span class="machine-icon">A</span>
                                <span>Machine C</span>
                            </div>
                        </td>
                        <td><span class="status-ready">Ready</span></td>
                        <td><button class="btn allocate">Allocate</button></td>
                        <td><button class="btn deallocate">Deallocate</button></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>
                            <div class="machine-info">
                                <span class="machine-icon">A</span>
                                <span>Machine D</span>
                            </div>
                        </td>
                        <td><span class="status-ready">Ready</span></td>
                        <td><button class="btn allocate">Allocate</button></td>
                        <td><button class="btn deallocate">Deallocate</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <?php require APPROOT . '/views/inc/components/footer.php' ?>