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
                                <a class="active" href="#">Cancel Pickup</a>
                            </li>
                        </ul>
                    </div>

        <div class="table-data">
        <div class="order">
        
        <div class="head">
            <h1>Cancel Pickup Request</h1>
        </div>
            <form action="#" method="POST" class="complaint-form">
                <div class="form-group">
                    <label for="fullname">Full Name:</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-group">
                    <label for="route_no">Route No:</label>
                    <input type="text" id="route_no" name="route_no" required>
                </div>

                <div class="form-group">
                    <label for="date1">Supposed Pickup Date:</label>
                    <input type="date" id="date1" name="date1" required>
                </div>

                <div class="form-group">
                    <label for="time1">Supposed Pickup Time:</label>
                    <input type="time" id="time1" name="time1" required>
                </div>

                <div class="form-group">
                    <label for="date2">Chosen Pickup Date:</label>
                    <input type="date" id="date2" name="date2" required>
                </div>

                <div class="form-group">
                    <label for="time2">Chosen Pickup Time:</label>
                    <input type="time" id="time2" name="time2" required>
                </div>
                <button type="submit" class="button">Cancel Pickup</button>
            </form>
                <a href="SupplyDashboard.php">
                    <button class="button">Back</button>
                </a>
        </div>
    </section>

    <script src="../public/script.js"></script>
</body>
</html>
