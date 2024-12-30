<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->        <main class="fertilizer-requests-container">
            <div class="fertilizer-requests-header">
                <div class="left">
                    <h1>Confirmation Requests</h1>
                    <ul class="breadcrumb">
                        <li><a href="SupplementDashboard.php">Home</a>
                        <i class='bx bx-chevron-right'></i>
                        <a class="active" href="#">Confirmation Requests</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-container">
                <table class="table-body">
                    <thead>
                        <tr>
                            <th>Supplier ID</th>
                            <th>Driver ID</th>
                            <th>Quantity</th>
                            <th>Notifications</th>
                            <th>Driver Confirmation</th>
                            <th>Supplier Confirmation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Supplier 000231<br><small>3 Minutes ago</small></td>
                            <td>718360733</td>
                            <td>20Kg</td>
                            <td><button class="notification-send-btn">Send</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                        </tr>
                        <tr>
                            <td>Supplier 020331<br><small>13 days ago, 12:20 PM</small></td>
                            <td>718090733</td>
                            <td>20Kg</td>
                            <td><button class="notification-send-btn">Send</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                        </tr>
                        <tr>
                            <td>Supplier 028001<br><small>2 weeks ago, 09:15 PM</small></td>
                            <td>77607533</td>
                            <td>20Kg</td>
                            <td><button class="notification-send-btn">Send</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                        </tr>
                        <tr>
                            <td>Supplier 004473<br><small>2 weeks ago, 09:15 PM</small></td>
                            <td>718360762</td>
                            <td>20Kg</td>
                            <td><button class="notification-send-btn">Send</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                        </tr>
                        <tr>
                            <td>Supplier 000939<br><small>3 weeks ago, 09:13 PM</small></td>
                            <td>718319523</td>
                            <td>20Kg</td>
                            <td><button class="notification-send-btn">Send</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                            <td><button class="pending-btn">Pending</button></td>
                        </tr>
                    </tbody>
                </table>
                <a href="<?php echo URLROOT; ?>/Supplier/index/" >
                    <button class="button">Done</button>
                </a>
            </div>
        </main>
    </div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    