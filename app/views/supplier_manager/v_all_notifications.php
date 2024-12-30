<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_suppliermanager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
            <main><div class="head-title">
                <div class="left">
                    <h1>Supplement Manager</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="SupplementDashboard.html">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
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
                        <div class="table-data">
                            <div class="order">
                                <div>
                                    <table class="complaint-type">
                                        <tr>
                                            <th>Driver Team</th>
                                            <th>Supplier ID</th>
                                            <th>Notification</th>
                                            <th>Date and Time</th>
                                        </tr>
                                        <tr>
                                            <td>21</td>
                                            <td>00398</td>
                                            <td>driver arrival late message</td>
                                            <td>2024/11/10 06:36pm</td>
                                        </tr>
                                        <tr>
                                            <td>45</td>
                                            <td>02098</td>
                                            <td>collection successful</td>
                                            <td>2024/11/10 03:15pm</td>
                                        </tr>
                                        <tr>
                                            <td>45</td>
                                            <td>02498</td>
                                            <td>supplier pickup cancellation confirmed</td>
                                            <td>2024/11/10 03:15pm</td>
                                        </tr>
                                        <tr>
                                            <td>20</td>
                                            <td>03456</td>
                                            <td>supplier pickup cancellation request</td>
                                            <td>2024/11/10 02:45pm</td>
                                        </tr>
                                        <tr>
                                            <td>31</td>
                                            <td>04053</td>
                                            <td>collection confirmed</td>
                                            <td>2024/11/10 02:30pm</td>
                                        </tr>
                                        <tr>
                                            <td>48</td>
                                            <td>00315</td>
                                            <td>collection confirmed</td>
                                            <td>2024/11/10 01:33pm</td>
                                        </tr>
                                        <tr>
                                            <td>46</td>
                                            <td>02458</td>
                                            <td>driver collection cancellation request</td>
                                            <td>2024/11/10 01:11pm</td>
                                        </tr>
                                    </table>
                                </div>
                                <a href="<?php echo URLROOT; ?>/Supplier/" >
                                    <button class="button">Home</button>
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
    