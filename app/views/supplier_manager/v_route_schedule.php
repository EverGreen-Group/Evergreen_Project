<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Route Schedule</h1>
                    <ul class="breadcrumb">
                        <li><a href="SupplementDashboard.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Route Schedule</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-container">
                <div class="table-container">
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
                    <a href="<?php echo URLROOT; ?>/Supplier/index/" >
                        <button class="button">Done</button>
                    </a>
                </div>
            </div>
        </main>
    </div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    