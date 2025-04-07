<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
                <div class="head-title">
                    <div class="left">
                        <h1>Supplement Manager Dashboard</h1>
                        <ul class="breadcrumb">
                            <li><a href="SupplyDashboard.php">Home</a></li>
                            <li><i class='bx bx-chevron-right'></i></li>
                            <li><a class="active" href="#">Dashboard</a></li>
                        </ul>
                    </div>
                    <a href="#" class="button">
                        <i class='bx bxs-cloud-download'></i>
                        <span class="text">Download Stats</span>
                    </a>
                </div>

                <!-- Bar Graph -->
                <div class="table-data">
                    <div class="order">
                    <div class="head">
                        <div class="graph-container">
                            <h3>Tea Leaves Collection by Month</h3>
                            <canvas id="teaLeavesGraph" width="800" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <ul class="box-info">
                    <li>
                        <i class='bx bxs-calendar-check'></i>
                        <span class="text">
                            <h3>200</h3>
                            <p>Tea Leaves Suppliers</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-group'></i>
                        <span class="text">
                            <h3>45</h3>
                            <p>Drivers</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bxs-dollar-circle'></i>
                        <span class="text">
                            <h3>3325kg</h3>
                            <p>Last Month Total</p>
                        </span>
                    </li>
                </ul>
                </div>
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Confirmation Requests</h3>
                        </div>
                        <div>view all the confirmation requests sent to Tea Leaves Suppliers and the status of each request</div>
                        <a href="RequestStatus.php">
                            <button class="button">View All</button>
                        </a>
                    </div>
                    <div class="todo">
                        <div class="head">
                            <h3>Scheduled Routes</h3>
                        </div>
                        <div>
                            View the scheduled routes and the drivers assigned to each route
                        </div>
                        <a href="RouteSchedule.php">
                            <button class="button">View Routes</button>
                        </a>
                    </div>
                </div>
                <div class="table-data">
                <div class="order">
                    <h2>Driver Teams Status</h2>
                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Route No</th>
                                <th>Date</th>
                                <th>Team No</th>
                                <th>Start Time</th>
                                <th>Finished</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>45</td>
                                <td>21/08/24</td>
                                <td>2</td>
                                <td>14:00</td>
                                <td><button class="pending-btn">Pending</button></td>
                            </tr>
                            <tr>
                                <td>47</td>
                                <td>21/08/24</td>
                                <td>43</td>
                                <td>12:00</td>
                                <td><button class="pending-btn">Pending</button></td>
                            </tr>
                            <tr>
                                <td>29</td>
                                <td>21/07/24</td>
                                <td>1</td>
                                <td>10:30</td>
                                <td><button class="pending-btn">Pending</button></td>
                            </tr>
                            <tr>
                                <td>29</td>
                                <td>21/06/24</td>
                                <td>9</td>
                                <td>13:00</td>
                                <td><button class="pending-btn">Pending</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="<?php echo URLROOT; ?>/Suppliermanager/index/" >
                        <button class="button">Done</button>
                    </a>
                </div>
            </div>
            </main>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    
