<?php require APPROOT . '/views/inc/components/header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/machineallo.css" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/style.css" />
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
        <div class="head-title">
            <div class="left">
                <h1>Machine Allocation</h1>

            </div>
        </div>

        <div style="display: flex;">
            <div class="table-data">

                <div class="">
                    <table>
                        <thead>
                            <tr>
                                <th>Machine</th>
                                <th>Status</th>
                                <th>Button</th>
                                <th>Button</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <pre><?= print_r($data) ?></pre> -->
                            <?php foreach ($data['machines'] as $machine): ?>
                                <tr>

                                    <td>
                                        <div class="machine-info">
                                            <span class="machine-icon"></span>
                                            <span><?php echo $machine->machine_name; ?></span>
                                        </div>
                                    </td>
                                    <td><span class="status completed"><?php echo $machine->status; ?></span></td>
                                    <td>
                                        <form method="POST"
                                            action="<?php echo URLROOT; ?>/Inventory/machine?id=<?php echo $machine->id; ?>">
                                            <button type="submit" name="status_allocate"
                                                class="btn btn-primary">Allocate</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST"
                                            action="<?php echo URLROOT; ?>/Inventory/machine?id=<?php echo $machine->id; ?>">
                                            <button type="submit" name="status_deallocate"
                                                class="btn btn-tertiary">Deallocate</button>
                                        </form>
                                    </td>
                                    <td><button class="btn detail" onclick="">details</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>


                <div class="table-data">
                    <h2>Weekly Machine Allocation Statistics</h2>
                    <canvas id="machineAllocationChart"></canvas>
                </div>

                </div>
            </div>
            <div class="chart-container"
                style="margin: 20px; padding: 20px; background: white; border-radius: 10px; width: 98%;">
                <h1>Add New Machine Form</h1>
                <form name="abcd" action="<?php echo URLROOT ?>/Inventory/machine" method="POST">
                    <div class="form-group">
                        <label for="machine-name">Machine Name</label>
                        <input type="text" id="machine-name" name="machine_name" placeholder="Enter Machine Name"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" id="brand" name="brand" placeholder="Enter Brand" required>
                    </div>
                    <div class="form-group">
                        <label for="started-date">Started Date</label>
                        <input type="date" id="started-date" name="started_date" required>
                    </div>
                    <div class="form-group">
                        <label for="last-maintenance">Last Maintenance (in days)</label>
                        <input type="number" id="last-maintenance" name="last_maintenance"
                            placeholder="Enter days since last maintenance" required>
                    </div>
                    <div class="form-group">
                        <label for="next-maintenance">Next Maintenance Details</label>
                        <input type="text" id="next-maintenance" name="next_maintenance"
                            placeholder="Enter next maintenance details" required>
                    </div>
                    <div class="form-group">
                        <label for="total-working-hours">Total Working Hours</label>
                        <input type="text" id="total-working-hours" name="total_working_hours"
                            placeholder="Enter total working hours" required>
                    </div>
                    <div class="form-group">
                        <label for="specialnote">Special Notes</label>
                        <textarea id="specialnotes" name="specialnotes" style="height: 100px; width: 100%;"
                            placeholder="Enter Any Special Details" required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Submit</button>
                </form>
            </div>
        
    </main>
</body>

<script>
    const ctx = document.getElementById('machineAllocationChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [
                {
                    label: 'Machine A',
                    data: [0, 7, 6, 8, 7, 4, 3],
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Machine B',
                    data: [6, 5, 4, 5, 4, 2, 1],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Machine C',
                    data: [4, 3, 5, 4, 3, 2, 1],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Machine D',
                    data: [3, 4, 5, 3, 4, 2, 1],
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Working Hours'
                    },
                    stacked: true
                },
                x: {
                    title: {
                        display: true,
                        text: 'Days of the Week'
                    },
                    stacked: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Daily Machine Working Hours'
                },
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.dataset.label}: ${context.raw} hours`;
                        }
                    }
                }
            }
        }
    });
</script>

<?php require APPROOT . '/views/inc/components/footer.php' ?>

<!-- Add this modal/popup HTML before the closing </main> tag -->
<div id="machineModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-header">
            <img src="<?php echo URLROOT; ?>/img/machine-report.png" alt="Machine Report">
            <h2 id="machineName">Machine A</h2>
        </div>
        <h3>Machine A Report</h3>
        <div class="report-container">
            <div class="report-row">
                <span>Brand</span>
                <span>usha</span>
            </div>
            <div class="report-row">
                <span>Started Date</span>
                <span>Nov 23, 2023</span>
            </div>
            <div class="report-row">
                <span>Last maintance Day</span>
                <span>10 days</span>
            </div>
            <div class="report-row">
                <span>Next Maintance Day</span>
                <span>All (50 products)</span>
            </div>
            <div class="report-row">
                <span>Total Working Hours</span>
                <span>200 items</span>
            </div>
            <div class="report-row">
                <span>3 discrepancies found</span>
                <span>+5 unit of Product A C</span>
            </div>
        </div>
        <button class="btn remove-machine">Remove machine</button>
    </div>
</div>

<!-- Add this CSS in your machineallo.css file -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 6000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border-radius: 10px;
        width: 80%;
        max-width: 500px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .modal-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .modal-header img {
        width: 100px;
        margin-bottom: 10px;
    }

    .report-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .report-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .remove-machine {
        background-color: #00b300;
        color: white;
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<!-- Add this JavaScript before the closing </body> tag -->
<script>
    const modal = document.getElementById("machineModal");
    const detailButtons = document.querySelectorAll(".btn.detail");
    const closeBtn = document.querySelector(".close");

    detailButtons.forEach(button => {
        button.addEventListener("click", function () {
            const machineName = this.closest("tr").querySelector(".machine-info span:last-child").textContent;
            document.getElementById("machineName").textContent = machineName;
            modal.style.display = "block";
        });
    });

    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });


</html >