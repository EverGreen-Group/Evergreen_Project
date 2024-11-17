<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
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
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
                            <a class="active" href="#">Notifications</a>
                        </li>
                    </ul>
                </div>
    
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Notifications</h3>
                        </div>
                        <div class="list">
                            <div class="item">
                                <p>Driver team 7 sent a collection late message</p>
                                <span class="time">5 minutes ago</span>
                            </div>
                            <div class="item">
                                <p>Tea leaves supplier Senarath sent a pickup cancellation request</p>
                                <span class="time">1 hour ago</span>
                            </div>
                            <div class="item">
                                <p>Driver Randil sent a collection cancellation message</p>
                                <span class="time">Yesterday</span>
                            </div>
                            <div class="item">
                                <p>Driver team 24 collection confirmed</p>
                                <span class="time">2 days ago</span>
                            </div>
                            <div class="item">
                                <p>Driver team 3 collection confirmed</p>
                                <span class="time">3 days ago</span>
                            </div>
                        </div>
                        <a href="<?php echo URLROOT; ?>/Supplier/allnotifications/" >
                            <button class="button">View All Notifications</button>
                        </a>
                    </div>
                </div>
            </main>
            </div>
        </section>
        <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    