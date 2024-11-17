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
                            <a class="active" href="#">Payments</a>
                        </li>
                    </ul>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Supplier Salary Payments</h3>
                        </div>
                    <div class="table-container">
                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Supplier ID</th>
                                <th>Due Date</th>
                                <th>Payment Mode</th>
                                <th>Amount (Rs)</th>
                                <th>Done</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>000345</td>
                                <td>21/08/24</td>
                                <td>Cash</td>
                                <td class="status pending">Pending</td>
                                <td><button class="pending-btn">Pending</button></td>
                            </tr>
                            <tr>
                                <td>000847</td>
                                <td>21/08/24</td>
                                <td>Bank</td>
                                <td class="status done">12,000.00</td>
                                <td><button class="pending-btn">Pending</button></td>
                            </tr>
                            <tr>
                                <td>000264</td>
                                <td>21/07/24</td>
                                <td>Bank</td>
                                <td class="status done">12,800.00</td>
                                <td><button class="pending-btn">Pending</button></td>
                            </tr>
                            <tr>
                                <td>000029</td>
                                <td>21/06/24</td>
                                <td>Cash</td>
                                <td class="status done">12,550.00</td>
                                <td><button class="pending-btn">Pending</button></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                </div>
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Supplier Order Payments</h3>
                        </div>
                    <div class="table-container">
                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Supplier ID</th>
                                <th>Order</th>
                                <th>Pay Date</th>
                                <th>Amount (Rs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>000345</td>
                                <td>Fertilizer</td>
                                <td>21/08/24</td>
                                <td>3 600.00</td>
                            <tr>
                                <td>000345</td>
                                <td>Tea Packets</td>
                                <td>21/08/24</td>
                                <td>21 000.00</td>
                            </tr>
                            <tr>
                                <td>000345</td>
                                <td>Fertilizer</td>
                                <td>21/08/24</td>
                                <td>20 500.00</td>
                            </tr>
                            <tr>
                                <td>000345</td>
                                <td>Tea Packets</td>
                                <td>21/08/24</td>
                                <td>2 030.00</td>
                            </tr>
                            <tr>
                                <td>000345</td>
                                <td>Tea Packets</td>
                                <td>21/08/24</td>
                                <td>5 000.00</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="<?php echo URLROOT; ?>/Suppliermanager/" >
                        <button class="button">Home</button>
                    </a>
                    <a href="<?php echo URLROOT; ?>/Suppliermanager/profile" >
                        <button class="button">View Income</button>
                    </a>
                    </div>
                    </div>
                    </div>
                </div>
        </main>
    </div>
    </section>
    <script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
    </body>
</html>
    