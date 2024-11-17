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
                        <li><a href="SupplementDashboard.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Confirmation Form</a></li>
                    </ul>
                </div>
            </div>
            <!-- BAR GRAPH-->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <div class="graph-container">
                            <h4>Tea Leaves Collection Confirmations By Month</h4>
                            <canvas id="teaLeavesConfirmationGraph" width="600" height="400"></canvas>
                        </div>
                    </div>
                </div>
                <div class="head">
                    <div class="graph-container">
                            <canvas id="teaLeavesConfirmationChart" width="400"></canvas>
                        </div>
                    </div>
                </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Tea Leaves Collection Confirmation Form</h3>
                    </div>
                    <p>Request the supplier to confirm the amount of tea leaves collected.</p>
                <div class="form-container">
                <form action="submitConfirmation.php" method="post" class="complaint-form">
                    <div class="form-group">
                        <label for="supplier-id">Supplier ID</label>
                        <select id="supplier-id" name="supplier-id">
                            <option value="000231"></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date">
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" readonly>
                    </div>

                    <div class="form-group">
                        <label for="route">Route</label>
                        <input type="text" id="route" name="route" >
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" >
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" readonly>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="text" id="quantity" name="quantity" >
                    </div>

                    <div class="form-group">
                        <label for="team-id">Team ID</label>
                        <input type="text" id="team-id" name="team-id" >
                    </div>

                    <div class="form-group">
                        <label for="supplier-status">Supplier Status</label>
                        <select id="supplier-status" name="supplier-status">
                            <option value="unconfirmed">Unconfirmed</option>
                            <option value="confirmed">Confirmed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="driver-status">Driver Status</label>
                        <select id="driver-status" name="driver-status">
                            <option value="unconfirmed">Unconfirmed</option>
                            <option value="confirmed">Confirmed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="button">Send Confirmation Request</button>
                    </div>
                </form>
                <a href="<?php echo URLROOT; ?>/Suppliermanager/" >
                    <button class="button">Home</button>
                </a>
            </div>
            </div>
            </div>>
        </main>
    </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    