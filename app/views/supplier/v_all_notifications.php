<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
            <main><div class="head-title">
                <div class="left">
                    <h1>Tea Leaves Supplier</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="SupplyDashboard.html">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Notifications</a>
                        </li>
                    </ul>
                </div>
    
                <div class="table-data">
                    <div class="order">
                        <div>
                            <table class="complaint-type">
                                <tr>
                                    <th>Notification</th>
                                    <th>Date and Time</th>
                                    <th>Done</th>
                                </tr>
                                <tr>
                                    <td>collection today at 10:00pm</td>
                                    <td>2024/11/10</td>
                                    <td class="btn-done">Done</td>
                                </tr>
                                <tr>
                                    <td>collection confirmed</td>
                                    <td>2024/11/04</td>
                                    <td class="btn-done">Done</td>
                                </tr>
                                <tr>
                                    <td>leaf collection today at 08:00am</td>
                                    <td>2024/10/27</td>
                                    <td class="btn-done">Done</td>
                                </tr>
                                <tr>
                                    <td>collection late message</td>
                                    <td>2024/10/20</td>
                                    <td class="btn-done">Done</td>
                                </tr>
                                <tr>
                                    <td>collection confirm request</td>
                                    <td>2024/10/11</td>
                                    <td class="btn-done">Done</td>
                                </tr>
                            </table>
                        </div>
                        <a href="Notifications.php">
                            <button class="button">View less</button>
                        </a>
                    </div>
                </div>
            </main>
            </div>
        </section>
    
        <script src="../public/script.js"></script>
    </body>
    </html>
    