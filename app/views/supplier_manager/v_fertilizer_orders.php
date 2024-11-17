<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main class="fertilizer-requests-container">
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
                            <a class="active" href="#">Fertilizer Orders</a>
                        </li>
                    </ul>
                </div>

            <div class="table-data">
            <div class="table-data">
                <div class="order">
                <div class="head">
                    <h5>Tea Leaves</h5>
                    <i class='bx bx-plus'></i>
                    <i class='bx bx-filter'></i>
                </div>
                <canvas id="fertilizerOrdersChart"></canvas>
            </div>
            </div>
                <div class="order">
                    <div class="head">
                        <h3>Fertilizer Orders</h3>
                    </div>
                <div class="table-container">
                <table class="table-body">
                    <thead>
                        <tr>
                            <th>Supplier ID</th>
                            <th>Phone No</th>
                            <th>Quantity</th>
                            <th>Accept/Reject</th>
                            <th>Notification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Supplier 000231<br><small>3 Minutes ago</small></td>
                            <td>718360733</td>
                            <td>20Kg</td>
                            <td>
                                <button class="accept-btn">Accept</button>
                                <button class="reject-btn">Reject</button>
                            </td>
                            <td><button class="pending-btn">Send</button></td>
                        </tr>
                        <tr>
                            <td>Supplier 020331<br><small>13 days ago, 12:20 PM</small></td>
                            <td>718090733</td>
                            <td>20Kg</td>
                            <td>
                                <button class="accept-btn">Accept</button>
                                <button class="reject-btn">Reject</button>
                            </td>
                            <td><button class="pending-btn">Send</button></td>
                        </tr>
                        <tr>
                            <td>Supplier 028001<br><small>2 weeks ago, 09:15 PM</small></td>
                            <td>77607533</td>
                            <td>20Kg</td>
                            <td>
                                <button class="accept-btn">Accept</button>
                                <button class="reject-btn">Reject</button>
                            </td>
                            <td><button class="pending-btn">Send</button></td>
                        </tr>
                        <tr>
                            <td>Supplier 004473<br><small>2 weeks ago, 09:15 PM</small></td>
                            <td>718360762</td>
                            <td>20Kg</td>
                            <td>
                                <button class="accept-btn">Accept</button>
                                <button class="reject-btn">Reject</button>
                            </td>
                            <td><button class="pending-btn">Send</button></td>
                        </tr>
                        <tr>
                            <td>Supplier 000939<br><small>3 weeks ago, 09:13 PM</small></td>
                            <td>718319523</td>
                            <td>20Kg</td>
                            <td>
                                <button class="accept-btn">Accept</button>
                                <button class="reject-btn">Reject</button>
                            </td>
                            <td><button class="pending-btn">Send</button></td>
                        </tr>
                    </tbody>
                </table>
                <button class="button">Done</button>
                </div>
                </div>
            </div>
    </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>