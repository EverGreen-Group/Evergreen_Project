<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
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

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Confirmation Requests</h3>
                        </div>
                        <div>view all the confirmation requests sent to Tea Leaves Suppliers and the status of each request</div>
                        <a href="<?php echo URLROOT; ?>/Supplier/requeststatus/" >
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
                        <a href="<?php echo URLROOT; ?>/Supplier/routeschedule/" >
                            <button class="button">View Routes</button>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    
