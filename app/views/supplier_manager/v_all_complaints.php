<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->    
            <main>
                <div class="head-title">
                <div class="left">
                    <h1>Supplement Manager</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="SupplementDashboard.html">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Complaints</a>
                        </li>
                    </ul>
                </div>
    
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Received Complaints</h3>
                        </div>
                        <div class="list">
                            <div class="item">
                                <p>Supplier 27429 sent a complain</p>
                                <span class="time">
                                    8 minutes ago    
                                    <a href="" class="view"> | view</a>
                            </span>
                            </div>
                            <div class="item">
                                <p>Supplier 67576 sent a complain</p>
                                <span class="time">
                                    1 hour ago
                                    <a href="" class="view"> | view</a>
                                </span>
                            </div>
                            <div class="item">
                                <p>Supplier 42552 sent a complain</p>
                                <span class="time">
                                    Yesterday
                                    <a href="" class="view"> | view</a>
                                </span>
                            </div>
                            <div class="item">
                                <p>Supplier 07686 sent a complain</p>
                                <span class="time">
                                    2 days ago
                                    <a href="" class="view"> | view</a>
                                </span>
                            </div>
                            <div class="item">
                                <p>Supplier 13343 sent a complain</p>
                                <span class="time">
                                    3 days ago
                                    <a href="" class="view"> | view</a>
                                </span>
                            </div>
                            <div class="item">
                                <p>Supplier 74539 sent a complain</p>
                                <span class="time">
                                    4days ago
                                    <a href="" class="view"> | view</a>
                                </span>
                            </div>
                            <div class="item">
                                <p>Supplier 83847 sent a complain</p>
                                <span class="time">
                                    1 week ago
                                    <a href="" class="view"> | view</a>
                                </span>
                            </div>
                            <div class="item">
                                <p>Supplier 74568 sent a complain</p>
                                <span class="time">
                                    2 weeks ago
                                    <a href="" class="view"> | view</a>
                                </span>
                            </div>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Supplier/complaints/" >
                            <button class="button">View less</button>
                        </a>
                    </div>
                </div>
            </main>
            </div>
        </section>
        <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>