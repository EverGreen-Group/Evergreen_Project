<section id="content">
    <div class="content-wrapper">
        <?php include '../components/navbar.php'; ?>
        <?php include '../components/sidebar.php'; ?>

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
                            <a class="active" href="#">Tea Order History</a>
                        </li>
                    </ul>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Tea Order History</h3>
                        </div>
                        <div class="list">
                            <div class="item">
                                <p>Request for tea order confirmed</p>
                                <span class="time">01/09/2024 - 12:00 PM</span>
                            </div>
                            <div class="item">
                                <p>Request for tea order rejected</p>
                                <span class="time">03/09/2024 - 10:30 AM</span>
                            </div>
                            <div class="item">
                                <p>Request for tea order confirmed</p>
                                <span class="time">04/09/2024 - 12:43 PM</span>
                            </div>
                            <div class="item">
                                <p>Request for tea order confirmed</p>
                                <span class="time">09/09/2024 - 02:54 PM</span>
                            </div>
                            <div class="item">
                                <p>Request for tea order rejected</p>
                                <span class="time">12/09/2024 - 06:35 PM</span>
                            </div>
                        </div>
                        
                        <a href="OrderPage.php">
                            <button class="button">Back</button>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>

<script src="../public/script.js"></script>
</body>
</html>
