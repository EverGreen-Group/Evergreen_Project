
<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Tea Leaves Supplier</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="SupplyDashboard.html">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Confirmation Requests History</a>
                        </li>
                    </ul>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Confirmation Requests History</h3>
                        </div>
                        <div class="list">
                            <div class="item">
                                <p>Request for leaf collection confirmed</p>
                                <span class="time">01/09/2024 - 10:00 AM</span>
                            </div>
                            <div class="item">
                                <p>Request for leaf collection confirmed</p>
                                <span class="time">02/09/2024 - 11:30 AM</span>
                            </div>
                            <div class="item">
                                <p>Request for leaf collection confirmed</p>
                                <span class="time">03/09/2024 - 12:15 PM</span>
                            </div>
                            <div class="item">
                                <p>Request for leaf collection confirmed</p>
                                <span class="time">04/09/2024 - 02:00 PM</span>
                            </div>
                            <div class="item">
                                <p>Request for leaf collection confirmed</p>
                                <span class="time">05/09/2024 - 03:30 PM</span>
                            </div>
                        </div>
                        <a href="SupplyDashboard.php">
                            <button class="button">Back</button>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>
<script src="<?php echo URLROOT; ?>/css/script.js"></script>
</body>
</html>
