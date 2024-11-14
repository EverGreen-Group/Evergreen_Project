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
                            <a class="active" href="#">Payment History</a>
                        </li>
                    </ul>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Payment History</h3>
                        </div>
                        <div class="list">
                            <div class="item">
                                <p>Payment of $100 for Tea Pockets</p>
                                <span class="time">Submitted at 02/09/2024 - 5:10 PM</span>
                            </div>
                            <div class="item">
                                <p>Payment of $200 for Fertilizer</p>
                                <span class="time">Submitted at 02/09/2024 - 6:30 PM</span>
                            </div>
                            <div class="item">
                                <p>Payment of $150 for Tea Pockets</p>
                                <span class="time">Submitted at 02/09/2024 - 5:15 PM</span>
                            </div>
                            <div class="item">
                                <p>Payment of $250 for Fertilizer</p>
                                <span class="time">Submitted at 02/09/2024 - 6:30 PM</span>
                            </div>
                        </div>
                        
                        <a href="Payments.php">
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
