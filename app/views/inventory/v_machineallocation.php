<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/machineallo.css" />
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
        <h1 style="margin-top:20px; margin-left:30px;">MACHINE ALLOCATION</h1>
        
        <div class="container" style="margin-left:20px; border-radius: 20px;">

            <table>
                <thead>
                    <tr>
                        <th>Check</th>
                        <th>Machine</th>
                        <th>Status</th>
                        <th>Button</th>
                        <th>Button</th>
                        <th>Details</th>
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
                        <td><span class="status-ready">Allocated</span></td>
                        <td><button class="btn allocate">Allocate</button></td>
                        <td><button class="btn deallocate">Deallocate</button></td>
                        <td><button class="btn detail">details</button></td>
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
                        <td><button class="btn detail">details</button></td>
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
                        <td><button class="btn detail">details</button></td>
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
                        <td><button class="btn detail">details</button></td>
                    </tr>
                </tbody>
            </table>
            <button class="btn allocate" style="margin: 10px;">+ New Machine</button>

            <div class="chart-container" style="margin: 20px; padding: 20px; background: white; border-radius: 10px; width: 90%;">
                <h2>Weekly Machine Allocation Statistics</h2>
                <canvas id="machineAllocationChart"></canvas>
            </div>
        </div>
    </main>

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
                            label: function(context) {
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
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
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
        button.addEventListener("click", function() {
            const machineName = this.closest("tr").querySelector(".machine-info span:last-child").textContent;
            document.getElementById("machineName").textContent = machineName;
            modal.style.display = "block";
        });
    });

    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
    </script>
</body>

</html>